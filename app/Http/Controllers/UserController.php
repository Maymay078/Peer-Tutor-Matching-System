<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\BookingSession;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Handle the registration request.
     */

      public function sessionScheduling(Request $request)
    {
        // Get the tutor_id from the query string
        $tutorId = $request->query('tutor_id');

        // Fetch the tutor's details from the database
        $tutor = Tutor::with('user')->findOrFail($tutorId);

        // Parse payment methods from payment_details
        $paymentMethods = $this->parsePaymentMethods($tutor->payment_details);

        // Fix: Only decode if availability is a string
        $availabilityRaw = $tutor->availability;
        if (is_string($availabilityRaw)) {
            $availabilityArr = json_decode($availabilityRaw, true);
        } elseif (is_array($availabilityRaw)) {
            $availabilityArr = $availabilityRaw;
        } else {
            $availabilityArr = [];
        }

        // Check tutor availability
        if (empty($availabilityArr)) {
            return view('session-scheduling', ['tutor' => $tutor, 'paymentMethods' => $paymentMethods]);
        }

        // Pass the tutor details to the view
        return view('session-scheduling', ['tutor' => $tutor, 'paymentMethods' => $paymentMethods]);
    }

    /**
     * Parse payment methods from payment_details field
     * Handles JSON array, comma-separated values, or simple string
     */
    private function parsePaymentMethods($paymentDetails)
    {
        if (empty($paymentDetails)) {
            return [];
        }

        // Try to decode as JSON first
        $decoded = json_decode($paymentDetails, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_filter($decoded); // Remove empty values
        }

        // Try comma-separated values
        if (strpos($paymentDetails, ',') !== false) {
            $methods = array_map('trim', explode(',', $paymentDetails));
            return array_filter($methods); // Remove empty values
        }

        // Single payment method as string
        return [trim($paymentDetails)];
    }
    public function register(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'role'            => 'required|string|in:student,tutor',
        'full_name'       => 'required|string|max:255',
        'email'           => 'required|string|email|unique:users',
        'password'        => 'required|string|min:8|confirmed',
        'phone_number'    => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        'availability'    => 'required|array',
        'availability.*'  => 'array',  // Each availability entry is an array
    ]);

    // Custom validation: check that availability dates are unique
    $dates = [];
    foreach ($validated['availability'] as $key => $availabilityEntry) {
        if (isset($availabilityEntry['date'])) {
            $dateValue = $availabilityEntry['date'];
        } else {
            // For the current form structure, dates are stored as availability[date1], availability[date2], etc.
            // So keys like 'date1', 'date2' hold the date strings directly
            if (preg_match('/^date\d+$/', $key)) {
                $dateValue = $availabilityEntry;
            } else {
                continue;
            }
        }
        if (in_array($dateValue, $dates)) {
            return back()->withErrors(['availability' => 'Availability dates must be unique. Duplicate date found: ' . $dateValue])->withInput();
        }
        $dates[] = $dateValue;
    }

    // Create the user
    $user = User::create([
        'full_name'       => $validated['full_name'],
        'email'           => $validated['email'],
        'password'        => bcrypt($validated['password']),
        'phone_number'    => $validated['phone_number'],
        'role'            => $validated['role'],
    ]);

    // Handle student or tutor profile creation
        if ($validated['role'] == 'student') {
            $student = Student::create([
                'user_id'         => $user->id,
                'major'           => $request->input('major'),
                'year'            => $request->input('year'),
                'preferred_course'=> $request->input('student_subjects'), // updated to array input
                'availability'    => json_encode($validated['availability']), // Store availability as JSON
            ]);
        } elseif ($validated['role'] == 'tutor') {
        $tutor = Tutor::create([
            'user_id'         => $user->id,
            'expertise'       => $request->input('expertise'),
            'payment_details' => $request->input('payment_details'),
            'availability'    => json_encode($validated['availability']), // Store availability as JSON
        ]);
    }

    return view('users.index', ['users' => User::all()]);
}

    public function studentHome()
    {
        $user = auth()->user();
        $tutors = \App\Models\Tutor::with('user')->get();

        if ($user && $user->role === 'student') {
            $student = $user->student;
            if ($student) {
                $preferredCourses = $student->preferred_course;
                if (is_string($preferredCourses)) {
                    $preferredCourses = json_decode($preferredCourses, true) ?: [];
                }

                // Map tutors to include match count and filter availability
                $tutors = $tutors->map(function ($tutor) use ($preferredCourses, $user) {
                    $expertise = $tutor->expertise;
                    if (is_string($expertise)) {
                        $expertise = json_decode($expertise, true) ?: [];
                    }
                    $matchCount = 0;
                    foreach ($preferredCourses as $preferredCourse) {
                        foreach ($expertise as $subject) {
                            if (isset($subject['name']) && $subject['name'] === $preferredCourse) {
                                $matchCount++;
                            }
                        }
                    }
                    $tutor->match_count = $matchCount;
                    
                    // Filter availability to exclude already booked times
                    $tutor = $this->filterTutorAvailability($tutor, $user->student->id);
                    
                    return $tutor;
                });

                // Filter tutors with at least one match
                $tutors = $tutors->filter(function ($tutor) {
                    return $tutor->match_count > 0;
                });

                // Sort: first by match_count DESC, then by rating DESC
                $tutors = $tutors->sort(function ($a, $b) {
                    if ($a->match_count === $b->match_count) {
                        return ($b->rating ?? 0) <=> ($a->rating ?? 0);
                    }
                    return $b->match_count <=> $a->match_count;
                })->values();
            }
        }

        return view('home_student', compact('tutors'));
    }

    /**
     * API endpoint to get calendar events for the student real-time calendar.
     */
    public function getCalendarEvents()
    {
        $user = auth()->user();
        $events = [];

        if ($user && $user->role === 'tutor') {
            $tutor = $user->tutor;
            if ($tutor) {
                $bookingSessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)->get();

                foreach ($bookingSessions as $session) {
                    // Split session_time by comma to handle multiple time slots
                    $timeRanges = explode(',', $session->session_time);
                    $student = $session->student()->with('user')->first();
                    $studentName = $student ? $student->user->full_name : 'Student';

                    foreach ($timeRanges as $timeRange) {
                        $timeRange = trim($timeRange);
                        $timeParts = explode(' - ', $timeRange);
                        $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
                        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';

                        // Support both "HH:mm" and "HH:mm AM/PM" formats
                        $startDateTime = date('Y-m-d\TH:i:s', strtotime($session->session_date . ' ' . $startTime));
                        $endDateTime = date('Y-m-d\TH:i:s', strtotime($session->session_date . ' ' . $endTime));

                        // Determine color: past (gray), ongoing (green), future (blue)
                        $now = now();
                        $start = \Carbon\Carbon::parse($startDateTime);
                        $end = \Carbon\Carbon::parse($endDateTime);
                        if ($end->lt($now)) {
                            $color = '#9ca3af'; // gray-400 for past
                        } elseif ($start->lte($now) && $end->gte($now)) {
                            $color = '#22c55e'; // green-500 for ongoing
                        } else {
                            $color = '#2563eb'; // blue-600 for future
                        }

                        $events[] = [
                            'id' => $session->id,
                            'title' => ($session->subject_name ?? 'Tutoring Session') . ' with ' . $studentName,
                            'start' => $startDateTime,
                            'end' => $endDateTime,
                            'allDay' => false,
                            'color' => $color,
                        ];
                    }
                }
            }
            } elseif ($user && $user->role === 'student') {
                $student = $user->student;
                if ($student) {
                    $bookingSessions = \App\Models\BookingSession::where('student_id', $student->id)->get();

                    foreach ($bookingSessions as $session) {
                        // Split session_time by comma to handle multiple time slots
                        $timeRanges = explode(',', $session->session_time);
                        $tutor = $session->tutor()->with('user')->first();
                        $tutorName = $tutor ? $tutor->user->full_name : 'Tutor';

                        foreach ($timeRanges as $timeRange) {
                            $timeRange = trim($timeRange);
                            $timeParts = explode(' - ', $timeRange);
                            $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
                            $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';

                            // Support both "HH:mm" and "HH:mm AM/PM" formats
                            $startDateTime = date('Y-m-d\TH:i:s', strtotime($session->session_date . ' ' . $startTime));
                            $endDateTime = date('Y-m-d\TH:i:s', strtotime($session->session_date . ' ' . $endTime));

                            // Determine color: past (gray), ongoing (green), future (blue)
                            $now = now();
                            $start = \Carbon\Carbon::parse($startDateTime);
                            $end = \Carbon\Carbon::parse($endDateTime);
                            if ($end->lt($now)) {
                                $color = '#9ca3af'; // gray-400 for past
                            } elseif ($start->lte($now) && $end->gte($now)) {
                                $color = '#22c55e'; // green-500 for ongoing
                            } else {
                                $color = '#2563eb'; // blue-600 for future
                            }

                            $events[] = [
                                'title' => ($session->subject_name ?? 'Tutoring Session') . ' with ' . $tutorName,
                                'start' => $startDateTime,
                                'end' => $endDateTime,
                                'allDay' => false,
                                'color' => $color,
                            ];
                        }
                    }
                }
            }

        return response()->json($events);
    }
 
    public function tutorHome()
    {
        $user = auth()->user();
        $sessions = [];

        if ($user && $user->role === 'tutor') {
            $tutor = $user->tutor;
            if ($tutor) {
                // Update booking session statuses based on current date
                $this->updateBookingSessionStatuses($tutor->id);

                // Fetch booking sessions for the tutor
                $bookingSessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)
                    ->orderBy('session_date', 'asc')
                    ->get();

                $sessions = $bookingSessions->map(function ($session) use ($tutor) {
                    $student = $session->student()->with('user')->first();
                    $studentUser = $student ? $student->user : null;
                    
                    // Calculate session status based on date and time
                    $sessionStatus = $this->calculateSessionStatus($session);

                    // Override status to 'past' if session date is in the past, regardless of stored status
                    $now = \Carbon\Carbon::now();
                    $sessionDate = \Carbon\Carbon::parse($session->session_date);
                    if ($sessionDate->lt($now)) {
                        $sessionStatus = 'past';
                    }

                    // Determine default payment method if missing
                    $paymentMethod = $session->payment_method;
                    if (empty($paymentMethod)) {
                        $paymentDetails = $tutor->payment_details;
                        if (is_string($paymentDetails)) {
                            $paymentDetailsArr = json_decode($paymentDetails, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                // Not JSON, treat as comma separated string
                                $paymentDetailsArr = array_map('trim', explode(',', $paymentDetails));
                            }
                        } elseif (is_array($paymentDetails)) {
                            $paymentDetailsArr = $paymentDetails;
                        } else {
                            $paymentDetailsArr = [];
                        }

                        $paymentDetailsArr = array_filter($paymentDetailsArr);

                        if (count($paymentDetailsArr) === 1) {
                            $paymentMethod = $paymentDetailsArr[0];
                        } elseif (in_array('Cash', $paymentDetailsArr)) {
                            $paymentMethod = 'Cash';
                        } elseif (count($paymentDetailsArr) > 0) {
                            $paymentMethod = $paymentDetailsArr[0];
                        } else {
                            $paymentMethod = 'N/A';
                        }
                    }

                    return (object)[
                        'subject' => $session->subject_name ?? 'N/A',
                        'date' => $session->session_date ?? 'N/A',
                        'time' => $session->session_time ?? 'N/A',
                        'id' => $session->id,
                        'student_profile_image' => $studentUser ? $studentUser->profile_image : null,
                        'student_name' => $studentUser ? $studentUser->full_name : 'N/A',
                        'student_email' => $studentUser ? $studentUser->email : 'N/A',
                        'total_price' => is_numeric($session->total_price) ? $session->total_price : 0,
                        'payment_method' => $paymentMethod,
                        'status' => $sessionStatus,
                    ];
                })->toArray();
            }
        }

        return view('home_tutor', compact('sessions'));
    }

    /**
     * Update booking session statuses to 'past' if session date has passed and status is still 'pending' or 'confirmed'.
     */
    private function updateBookingSessionStatuses($tutorId)
    {
        $now = \Carbon\Carbon::now();

        $sessionsToUpdate = \App\Models\BookingSession::where('tutor_id', $tutorId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('session_date', '<', $now->toDateString())
            ->where('is_manual_booking', false) // Only update backend bookings
            ->get();

        foreach ($sessionsToUpdate as $session) {
            $session->status = 'past';
            $session->save();
        }
    }

    /**
     * Calculate session status based on current date/time vs session date/time
     */
    private function calculateSessionStatus($session)
    {
        $now = \Carbon\Carbon::now();
        $sessionDate = \Carbon\Carbon::parse($session->session_date);

        // Parse session time to get start and end times
        // Always use only the first time range if multiple
        $timeRanges = explode(',', $session->session_time);
        $firstTimeRange = trim($timeRanges[0] ?? '');
        $timeParts = explode(' - ', $firstTimeRange);
        $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';

        // Create full datetime objects
        $sessionStart = \Carbon\Carbon::parse($session->session_date . ' ' . $startTime);
        $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime);
        
        // Determine status based on current time
        if ($now->lt($sessionStart)) {
            return 'future';
        } elseif ($now->gte($sessionStart) && $now->lte($sessionEnd)) {
            return 'ongoing';
        } else {
            return 'past';
        }
    }

    private function formatAvailability(array $availability): string
    {
        $today = date('Y-m-d');
        $formattedAvailability = [];

        foreach ($availability as $entry) {
            if (is_array($entry) && isset($entry['date'], $entry['time'])) {
                $date = $entry['date'];
                $times = is_array($entry['time']) ? implode(', ', $entry['time']) : $entry['time'];
                if ($date >= $today) {
                    $formattedAvailability[] = "{$date} ({$times})";
                }
            }
        }

        if (empty($formattedAvailability)) {
            return 'No upcoming availability (all selected dates have passed)';
        }

        return '[' . implode(', ', $formattedAvailability) . ']';
    }

    public function showProfile($userId)
    {
        $user = \App\Models\User::with('student', 'tutor')->findOrFail($userId);

        if ($user->role === 'student') {
            $availability = $user->student->availability ?? [];
            return view('profile.student', ['user' => $user, 'availability' => $availability]);
        } elseif ($user->role === 'tutor') {
            $availability = $user->tutor->availability ?? '[]';
            if (is_string($availability)) {
                $availability = json_decode($availability, true) ?: [];
            }
            return view('profile.tutor', ['user' => $user, 'availability' => $availability]);
        } else {
            abort(404);
        }
    }

    public function bookSession(Request $request)
    {
        \Log::info("bookSession called by user ID: " . auth()->id(), ['request' => $request->all()]);

        $user = auth()->user();
        if (!$user || $user->role !== 'student') {
            \Log::warning("Unauthorized bookSession attempt by user ID: " . auth()->id());
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check for student's existing sessions
        $existingStudentSessions = BookingSession::where('student_id', $user->student->id)
            ->where('session_date', $request->date)
            ->where('status', '!=', 'rejected')
            ->get();

        foreach ($existingStudentSessions as $session) {
            $existingTimes = explode(', ', $session->session_time);
            $requestedTimes = $request->time;
            
            foreach ($existingTimes as $existingTime) {
                if (in_array($existingTime, $requestedTimes)) {
                    // Get tutor name for the conflicting session
                    $conflictingTutor = \App\Models\Tutor::with('user')->find($session->tutor_id);
                    $tutorName = $conflictingTutor ? $conflictingTutor->user->full_name : 'another tutor';
                    
                    return response()->json([
                        'error' => 'Student Scheduling Conflict',
                        'message' => "You already have a session scheduled with {$tutorName} on {$request->date} at {$existingTime}. Please choose a different time slot."
                    ], 409);
                }
            }
        }

        // Check for tutor's existing sessions
        $existingTutorSessions = BookingSession::where('tutor_id', $request->tutor_id)
            ->where('session_date', $request->date)
            ->where('status', '!=', 'rejected')
            ->get();

        foreach ($existingTutorSessions as $session) {
            $existingTimes = explode(', ', $session->session_time);
            $requestedTimes = $request->time;
            
            foreach ($existingTimes as $existingTime) {
                if (in_array($existingTime, $requestedTimes)) {
                    // Get student name for the conflicting session
                    $conflictingStudent = \App\Models\Student::with('user')->find($session->student_id);
                    $studentName = $conflictingStudent ? $conflictingStudent->user->full_name : 'another student';
                    
                    return response()->json([
                        'error' => 'Tutor Scheduling Conflict',
                        'message' => "The tutor already has a session scheduled with {$studentName} on {$request->date} at {$existingTime}. Please choose a different time slot."
                    ], 409);
                }
            }
        }

        $validated = $request->validate([
            'flexible_timing' => 'sometimes|boolean',
            'tutor_id' => 'required|exists:tutors,id',
            'subject' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|array|min:1',
            'time.*' => 'string',
            'payment_method' => 'nullable|string|in:Cash,Online Banking',
        ]);

        // Create booking session
        $bookingSession = new \App\Models\BookingSession();
        $bookingSession->student_id = $user->student->id;
        $bookingSession->tutor_id = $validated['tutor_id'];
        $bookingSession->subject_name = $validated['subject'];
        $bookingSession->session_date = $validated['date'];
        $bookingSession->session_time = implode(', ', $validated['time']);
        $bookingSession->status = 'pending';
        $bookingSession->is_manual_booking = true; // Mark as manual booking
        $bookingSession->flexible_timing = $validated['flexible_timing'] ?? false;
        $bookingSession->total_price = $request->input('total_price', 0);
        $bookingSession->payment_method = $validated['payment_method'] ?? null;
        $bookingSession->save();

        \Log::info("Booking session created with ID: " . $bookingSession->id . " and status: " . $bookingSession->status);

        // Send notification to tutor (real-time)
        $tutorUser = \App\Models\Tutor::find($validated['tutor_id'])->user;
        if ($tutorUser) {
            $tutorUser->notify(new \App\Notifications\BookingSessionRequested($bookingSession));
            \Log::info("Notification sent to tutor user ID: " . $tutorUser->id);
        }

        // After booking, fetch updated tutor availability and filter it
        $tutor = \App\Models\Tutor::find($validated['tutor_id']);
        if ($tutor) {
            $tutor = $this->filterTutorAvailability($tutor, $user->student->id);
            $updatedAvailability = $tutor->availability;
        } else {
            $updatedAvailability = [];
        }

        return response()->json([
            'message' => 'Booking request sent successfully.',
            'updatedAvailability' => $updatedAvailability,
        ]);
    }

    public function tutorSessionRequests()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'tutor') {
            abort(403);
        }

        $tutor = $user->tutor;
        $pendingSessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->with('student.user')
            ->get();

        // Prepare notifications data for tutor
        $notifications = $pendingSessions->map(function ($session) {
            return (object)[
                'id' => $session->id,
                'student_name' => $session->student->user->full_name ?? 'N/A',
                'subject' => $session->subject_name,
                'date' => $session->session_date,
                'time' => $session->session_time,
                'created_at' => $session->created_at,
            ];
        });

        // For tutor, pass notifications to notifications view
        return view('notifications', ['notifications' => $notifications]);
    }

    public function confirmSessionRequest($id)
    {
        \Log::info("ConfirmSessionRequest called for booking session ID: $id by user ID: " . auth()->id());

        $user = auth()->user();
        if (!$user || $user->role !== 'tutor') {
            \Log::warning("Unauthorized confirmSessionRequest attempt by user ID: " . auth()->id());
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session = \App\Models\BookingSession::findOrFail($id);
        if ($session->tutor_id !== $user->tutor->id) {
            \Log::warning("Forbidden confirmSessionRequest attempt by user ID: " . auth()->id() . " for booking session ID: $id");
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Check for overlapping confirmed sessions for the tutor at the same date and time
        $existingConfirmedSessions = \App\Models\BookingSession::where('tutor_id', $user->tutor->id)
            ->where('session_date', $session->session_date)
            ->where('status', 'confirmed')
            ->where('id', '!=', $session->id)
            ->get();

        $sessionTimes = explode(', ', $session->session_time);

        foreach ($existingConfirmedSessions as $existingSession) {
            $existingTimes = explode(', ', $existingSession->session_time);
            foreach ($sessionTimes as $time) {
                if (in_array($time, $existingTimes)) {
                    return response()->json([
                        'error' => 'Scheduling Conflict',
                        'message' => 'You already have a confirmed session at this date and time. Please resolve the conflict before confirming this session.'
                    ], 409);
                }
            }
        }

        $session->status = 'confirmed';
        $session->save();

        \Log::info("Booking session ID: $id status updated to confirmed");

        // Notify student about confirmation
        $studentUser = $session->student->user;
        if ($studentUser) {
            $studentUser->notify(new \App\Notifications\SessionConfirmedNotification($session));
            \Log::info("Notification sent to student user ID: " . $studentUser->id);
        }

        // Update schedules if needed (implementation depends on app logic)
        // Example: mark tutor availability for the session date/time as booked
        $tutor = $user->tutor;
        if ($tutor) {
            $availability = json_decode($tutor->availability ?? '[]', true);
            $updatedAvailability = [];
            foreach ($availability as $slot) {
                if ($slot['date'] === $session->session_date) {
                    $slot['time'] = array_filter($slot['time'], function ($time) use ($session) {
                        return $time !== $session->session_time;
                    });
                }
                $updatedAvailability[] = $slot;
            }
            $tutor->availability = json_encode($updatedAvailability);
            $tutor->save();
        }

        return response()->json(['message' => 'Session confirmed successfully.']);
    }

    public function rejectSessionRequest($id)
    {
        \Log::info("RejectSessionRequest called for booking session ID: $id by user ID: " . auth()->id());

        $user = auth()->user();
        if (!$user || $user->role !== 'tutor') {
            \Log::warning("Unauthorized rejectSessionRequest attempt by user ID: " . auth()->id());
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session = \App\Models\BookingSession::findOrFail($id);
        if ($session->tutor_id !== $user->tutor->id) {
            \Log::warning("Forbidden rejectSessionRequest attempt by user ID: " . auth()->id() . " for booking session ID: $id");
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $session->status = 'rejected';
        $session->save();

        \Log::info("Booking session ID: $id status updated to rejected");

        // Notify student about rejection
        $studentUser = $session->student->user;
        if ($studentUser) {
            $studentUser->notify(new \App\Notifications\SessionRejectedNotification($session));
            \Log::info("Notification sent to student user ID: " . $studentUser->id);
        }

        return response()->json(['message' => 'Session rejected successfully.']);
    }

    /**
     * API endpoint for live search of tutors by name or subject.
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');
        $tutorId = $request->query('tutor_id');
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get all booked times for this date for both tutor and student
        $bookedTimes = [];
        
        // Check tutor's bookings
        $tutorSessions = BookingSession::where('tutor_id', $tutorId)
            ->where('session_date', $date)
            ->where('status', '!=', 'rejected')
            ->get();
            
        foreach ($tutorSessions as $session) {
            $times = explode(', ', $session->session_time);
            $bookedTimes = array_merge($bookedTimes, $times);
        }
        
        // Check student's bookings
        $studentSessions = BookingSession::where('student_id', $user->student->id)
            ->where('session_date', $date)
            ->where('status', '!=', 'rejected')
            ->get();
            
        foreach ($studentSessions as $session) {
            $times = explode(', ', $session->session_time);
            $bookedTimes = array_merge($bookedTimes, $times);
        }
        
        // Remove duplicates
        $bookedTimes = array_unique($bookedTimes);
        
        return response()->json(['bookedTimes' => $bookedTimes]);
    }

    /**
     * Filter tutor's availability based on existing bookings
     */
    private function filterTutorAvailability($tutor, $studentId)
    {
        $originalAvailability = $tutor->availability;
        if (is_string($originalAvailability)) {
            $originalAvailability = json_decode($originalAvailability, true) ?: [];
        }
        $tutor->original_availability = $originalAvailability;

        $availability = $originalAvailability;

        $now = \Carbon\Carbon::now();
        $filteredAvailability = [];

        if (is_array($availability)) {
            foreach ($availability as $slot) {
                // Defensive: skip if not array/object
                if (!is_array($slot) && !is_object($slot)) continue;
                // Defensive: get date and time safely
                $slotDateVal = is_array($slot) ? ($slot['date'] ?? null) : (property_exists($slot, 'date') ? $slot->date : null);
                $slotTimeVal = is_array($slot) ? ($slot['time'] ?? []) : (property_exists($slot, 'time') ? $slot->time : []);
                if (!$slotDateVal) continue;
                $slotDate = \Carbon\Carbon::parse($slotDateVal);
                if ($slotDate->isFuture() || $slotDate->isToday()) {
                    // Get existing bookings for this date
                    $existingBookings = BookingSession::where('tutor_id', $tutor->id)
                        ->where('session_date', $slotDateVal)
                        ->where('status', '!=', 'rejected')
                        ->get();

                    $studentBookings = BookingSession::where('student_id', $studentId)
                        ->where('session_date', $slotDateVal)
                        ->where('status', '!=', 'rejected')
                        ->get();

                    // Combine all booked times
                    $bookedTimes = [];
                    foreach ($existingBookings as $booking) {
                        $bookedTimes = array_merge($bookedTimes, explode(', ', $booking->session_time));
                    }
                    foreach ($studentBookings as $booking) {
                        $bookedTimes = array_merge($bookedTimes, explode(', ', $booking->session_time));
                    }
                    $bookedTimes = array_unique($bookedTimes);

                    // Filter out booked times
                    $availableTimes = array_filter((array)$slotTimeVal, function($timeSlot) use ($bookedTimes) {
                        return !in_array($timeSlot, $bookedTimes);
                    });

                    if (!empty($availableTimes)) {
                        $filteredAvailability[] = [
                            'date' => $slotDateVal,
                            'time' => array_values($availableTimes),
                        ];
                    }
                }
            }
        }

        $tutor->availability = $filteredAvailability;
        return $tutor;
    }

    public function searchTutors(Request $request)
    {
        $query = $request->query('q', '');
        $minRating = (int) $request->query('min_rating', 0);
        $user = auth()->user();

        if (empty($query) || !$user || !$user->student) {
            return response()->json([]);
        }

        $query = strtolower($query);

        $tutors = \App\Models\Tutor::with('user')->get()->filter(function ($tutor) use ($query, $minRating) {
            $user = $tutor->user;
            $fullName = strtolower($user->full_name ?? '');
            $expertise = $tutor->expertise;
            $rating = $tutor->rating ?? 0;

            if (is_string($expertise)) {
                $expertise = json_decode($expertise, true) ?: [];
            }

            $subjectNames = array_map(function ($subject) {
                return strtolower($subject['name'] ?? '');
            }, $expertise);

            $nameMatches = strpos($fullName, $query) !== false;
            $subjectMatches = collect($subjectNames)->contains(function ($subject) use ($query) {
                return strpos($subject, $query) !== false;
            });

            $ratingMatches = $rating >= $minRating;

            return ($nameMatches || $subjectMatches) && $ratingMatches;
        })->map(function ($tutor) use ($user) {
            // Filter availability based on existing bookings
            $tutor = $this->filterTutorAvailability($tutor, $user->student->id);
            
            $expertise = $tutor->expertise;
            if (is_string($expertise)) {
                $expertise = json_decode($expertise, true) ?: [];
            }

            $subjectsWithPrice = array_map(function ($subject) {
                return [
                    'name' => $subject['name'] ?? '',
                    'price_per_hour' => $subject['price_per_hour'] ?? '',
                ];
            }, $expertise);

            return [
                'id' => $tutor->id,
                'full_name' => $tutor->user->full_name ?? '',
                'subjects' => $subjectsWithPrice,
                'availability' => $tutor->availability,
                'original_availability' => $tutor->original_availability ?? [],
                'rating' => $tutor->rating ?? 0,
            ];
        })->values();

        return response()->json($tutors);
    }
}
