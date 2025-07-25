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

    /**
     * API endpoint to get tutor availability for rescheduling a session.
     */
    public function getTutorAvailabilityForReschedule(Request $request)
    {
        $sessionId = $request->query('session_id');
        $user = auth()->user();

        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session = \App\Models\BookingSession::find($sessionId);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $tutor = $session->tutor;
        if (!$tutor) {
            return response()->json(['error' => 'Tutor not found'], 404);
        }

        // Filter tutor availability excluding booked times
        $tutor = $this->filterTutorAvailability($tutor, $user->student->id);

        // Get the subject of the session to find pricing
        $sessionSubject = $session->subject_name ?? null;

        // Get tutor expertise with pricing
        $expertise = $tutor->expertise;
        if (is_string($expertise)) {
            $expertise = json_decode($expertise, true) ?: [];
        }

        // Find price per hour for the session subject
        $pricePerHour = null;
        foreach ($expertise as $subject) {
            if (isset($subject['name']) && $subject['name'] === $sessionSubject) {
                $pricePerHour = $subject['price_per_hour'] ?? null;
                break;
            }
        }

        // Add price info to each time slot in availability
        $availabilityWithPrice = [];
        foreach ($tutor->availability as $slot) {
            $availabilityWithPrice[] = [
                'date' => $slot['date'] ?? null,
                'time' => array_map(function ($time) use ($pricePerHour) {
                    return [
                        'time' => $time,
                        'price_per_hour' => $pricePerHour,
                    ];
                }, $slot['time'] ?? []),
            ];
        }

        // If no availability, add a flag
        $noAvailability = empty($availabilityWithPrice);

        return response()->json([
            'availability' => $availabilityWithPrice,
            'price_per_hour' => $pricePerHour,
            'no_availability' => $noAvailability,
            'tutor_id' => $tutor->id,  // Added tutor_id to response
        ]);
    }

    /**
     * API endpoint to reschedule a session with selected date and time.
     */
    public function rescheduleSession(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'session_id' => 'required|exists:booking_sessions,id',
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        $session = \App\Models\BookingSession::find($validated['session_id']);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        if ($session->student_id !== $user->student->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Validate that the selected date and time are not in the past
        $now = \Carbon\Carbon::now('Asia/Kuala_Lumpur');
        $selectedDate = \Carbon\Carbon::parse($validated['date'], 'Asia/Kuala_Lumpur');
        if ($selectedDate->lt($now->startOfDay())) {
            return response()->json(['error' => 'Selected date is in the past.'], 422);
        }

        $timeRanges = explode(',', $validated['time']);
        foreach ($timeRanges as $range) {
            $range = trim($range);
            $times = explode(' - ', $range);
            if (count($times) == 2) {
                $start = \Carbon\Carbon::createFromFormat('g:i A', trim($times[0]), 'Asia/Kuala_Lumpur');
                $end = \Carbon\Carbon::createFromFormat('g:i A', trim($times[1]), 'Asia/Kuala_Lumpur');
                $startDateTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $start->format('H:i'), 'Asia/Kuala_Lumpur');
                $endDateTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $end->format('H:i'), 'Asia/Kuala_Lumpur');

                if ($endDateTime->lt($now)) {
                    return response()->json(['error' => 'Selected time slot is in the past.'], 422);
                }

                // Check for tutor's existing sessions that conflict with the selected time slot
                $conflictingSessions = \App\Models\BookingSession::where('tutor_id', $session->tutor_id)
                    ->where('session_date', $validated['date'])
                    ->where('status', '!=', 'rejected')
                    ->where('id', '!=', $session->id)
                    ->get();

                foreach ($conflictingSessions as $conflict) {
                    $conflictTimeRanges = explode(',', $conflict->session_time);
                    foreach ($conflictTimeRanges as $conflictRange) {
                        $conflictRange = trim($conflictRange);
                        $conflictTimes = explode(' - ', $conflictRange);
                        if (count($conflictTimes) == 2) {
                            $conflictStart = \Carbon\Carbon::createFromFormat('g:i A', trim($conflictTimes[0]), 'Asia/Kuala_Lumpur');
                            $conflictEnd = \Carbon\Carbon::createFromFormat('g:i A', trim($conflictTimes[1]), 'Asia/Kuala_Lumpur');
                            $conflictStartDateTime = \Carbon\Carbon::parse($conflict->session_date . ' ' . $conflictStart->format('H:i'), 'Asia/Kuala_Lumpur');
                            $conflictEndDateTime = \Carbon\Carbon::parse($conflict->session_date . ' ' . $conflictEnd->format('H:i'), 'Asia/Kuala_Lumpur');

                            // Check for overlap
                            if ($startDateTime->lt($conflictEndDateTime) && $endDateTime->gt($conflictStartDateTime)) {
                                return response()->json(['error' => 'Selected time slot conflicts with another session.'], 409);
                            }
                        }
                    }
                }
            }
        }

        // Calculate total hours from session_time
        $totalHours = 0;
        foreach ($timeRanges as $range) {
            $range = trim($range);
            $times = explode(' - ', $range);
            if (count($times) == 2) {
                $start = \Carbon\Carbon::createFromFormat('g:i A', trim($times[0]));
                $end = \Carbon\Carbon::createFromFormat('g:i A', trim($times[1]));
                $diff = $end->diffInMinutes($start);
                $totalHours += $diff / 60;
            }
        }

        // Get tutor's expertise to find price per hour for the subject
        $tutor = $session->tutor;
        $expertise = $tutor->expertise;
        if (is_string($expertise)) {
            $expertise = json_decode($expertise, true) ?: [];
        }

        $pricePerHour = 0;
        foreach ($expertise as $subject) {
            if (isset($subject['name']) && $subject['name'] === $session->subject_name) {
                $pricePerHour = $subject['price_per_hour'] ?? 0;
                break;
            }
        }

        // Calculate new total price
        $newTotalPrice = $pricePerHour * $totalHours;

        // Update session date, time, status, and total price
        $session->session_date = $validated['date'];
        $session->session_time = $validated['time'];
        $session->total_price = $newTotalPrice;
        $session->status = 'pending'; // Reset status to pending on reschedule
        $session->save();

        // Fetch updated sessions for the student
        $student = $user->student;
        $now = now();
        $sessions = \App\Models\BookingSession::where('student_id', $student->id)
            ->orderBy('session_date', 'asc')
            ->get()
            ->filter(function ($session) use ($now) {
                $sessionDate = \Carbon\Carbon::parse($session->session_date, 'Asia/Kuala_Lumpur');
                $timeRanges = explode(',', $session->session_time);
                $lastTimeRange = trim(end($timeRanges));
                $timeParts = explode(' - ', $lastTimeRange);
                $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime, 'Asia/Kuala_Lumpur');
                return $sessionEnd->gte($now);
            })
            ->sort(function ($a, $b) {
                // First sort by date
                $dateComparison = strcmp($a->session_date, $b->session_date);
                if ($dateComparison !== 0) {
                    return $dateComparison;
                }
                
                // If dates are the same, sort by start time
                $aTimeRanges = explode(',', $a->session_time);
                $bTimeRanges = explode(',', $b->session_time);
                
                $aFirstTime = trim($aTimeRanges[0] ?? '');
                $bFirstTime = trim($bTimeRanges[0] ?? '');
                
                $aStartTime = explode(' - ', $aFirstTime)[0] ?? '';
                $bStartTime = explode(' - ', $bFirstTime)[0] ?? '';
                
                // Convert to 24-hour format for comparison
                $aTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($aStartTime))->format('H:i');
                $bTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($bStartTime))->format('H:i');
                
                return strcmp($aTime24, $bTime24);
            })
            ->map(function ($session) {
                $tutor = $session->tutor()->with('user')->first();
                $tutorName = $tutor ? $tutor->user->full_name : 'Tutor';

                // Calculate session status based on date and time
                $now = \Carbon\Carbon::now();
                $sessionDate = \Carbon\Carbon::parse($session->session_date);
                $timeRanges = explode(',', $session->session_time);
                $firstTimeRange = trim($timeRanges[0] ?? '');
                $timeParts = explode(' - ', $firstTimeRange);
                $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
                $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                $sessionStart = \Carbon\Carbon::parse($session->session_date . ' ' . $startTime);
                $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime);

                if ($sessionEnd->lt($now)) {
                    $status = 'past';
                } elseif ($sessionStart->lte($now) && $sessionEnd->gte($now)) {
                    $status = 'ongoing';
                } else {
                    $status = 'future';
                }

                return [
                    'id' => $session->id,
                    'subject' => $session->subject_name ?? 'N/A',
                    'date' => $session->session_date ?? 'N/A',
                    'time' => $session->session_time ?? 'N/A',
                    'tutor_name' => $tutorName,
                    'status' => $status,
                ];
            })->values()->all();

        return response()->json([
            'message' => 'Session rescheduled successfully.',
            'sessions' => $sessions,
        ]);
    }

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

                    // Sort availability dates ascendingly by date
                    if (is_array($tutor->availability)) {
                        $availability = $tutor->availability;
                        usort($availability, function ($a, $b) {
                            return strtotime($a['date']) <=> strtotime($b['date']);
                        });
                        $tutor->availability = $availability;
                    }
                    
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

        $user = auth()->user();
        $sessions = [];

        if ($user && $user->role === 'student') {
            $student = $user->student;
            if ($student) {
                $now = now();
                $sessions = \App\Models\BookingSession::where('student_id', $student->id)
                    ->orderBy('session_date', 'asc')
                    ->get()
                    ->filter(function ($session) use ($now) {
                        $sessionDate = \Carbon\Carbon::parse($session->session_date, 'Asia/Kuala_Lumpur');
                        $timeRanges = explode(',', $session->session_time);
                        $lastTimeRange = trim(end($timeRanges));
                        $timeParts = explode(' - ', $lastTimeRange);
                        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                        $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime, 'Asia/Kuala_Lumpur');
                        return $sessionEnd->gte($now);
                    })
                    ->sort(function ($a, $b) {
                        // First sort by date
                        $dateComparison = strcmp($a->session_date, $b->session_date);
                        if ($dateComparison !== 0) {
                            return $dateComparison;
                        }
                        
                        // If dates are the same, sort by start time
                        $aTimeRanges = explode(',', $a->session_time);
                        $bTimeRanges = explode(',', $b->session_time);
                        
                        $aFirstTime = trim($aTimeRanges[0] ?? '');
                        $bFirstTime = trim($bTimeRanges[0] ?? '');
                        
                        $aStartTime = explode(' - ', $aFirstTime)[0] ?? '';
                        $bStartTime = explode(' - ', $bFirstTime)[0] ?? '';
                        
                        // Convert to 24-hour format for comparison
                        $aTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($aStartTime))->format('H:i');
                        $bTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($bStartTime))->format('H:i');
                        
                        return strcmp($aTime24, $bTime24);
                    })
                    ->map(function ($session) {
                        $tutor = $session->tutor()->with('user')->first();
                        $tutorName = $tutor ? $tutor->user->full_name : 'Tutor';

                        // Calculate session status based on date and time
                        $now = \Carbon\Carbon::now();
                        $sessionDate = \Carbon\Carbon::parse($session->session_date);
                        $timeRanges = explode(',', $session->session_time);
                        $firstTimeRange = trim($timeRanges[0] ?? '');
                        $timeParts = explode(' - ', $firstTimeRange);
                        $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
                        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                        $sessionStart = \Carbon\Carbon::parse($session->session_date . ' ' . $startTime);
                        $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime);

                        if ($sessionEnd->lt($now)) {
                            $status = 'past';
                        } elseif ($sessionStart->lte($now) && $sessionEnd->gte($now)) {
                            $status = 'ongoing';
                        } else {
                            $status = 'future';
                        }

                    return (object)[
                        'id' => $session->id,
                        'subject' => $session->subject_name ?? 'N/A',
                        'date' => $session->session_date ?? 'N/A',
                        'time' => $session->session_time ?? 'N/A',
                        'tutor_name' => $tutorName,
                        'tutor_id' => $session->tutor_id,  // Added tutor_id here
                        'status' => $status,
                    ];
                    })->toArray();
            }
        }

        return view('home_student', compact('tutors', 'sessions'));
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
                    $now = now();
                $bookingSessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)
                    ->get();

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
                        $startDateTime = \Carbon\Carbon::parse($session->session_date . ' ' . $startTime)->toIso8601String();
                        $endDateTime = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime)->toIso8601String();

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
                                $color = ''; // no color for past
                            } elseif ($start->lte($now) && $end->gte($now)) {
                                $color = ''; // no color for ongoing
                            } else {
                                $color = ''; // no color for future
                            }

                            $events[] = [
                                'title' => ($session->subject_name ?? 'Tutoring Session') . ' with ' . $tutorName,
                                'start' => $startDateTime,
                                'end' => $endDateTime,
                                'allDay' => false,
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
        $upcomingSessions = [];
        $allSessions = [];

        if ($user && $user->role === 'tutor') {
            $tutor = $user->tutor;
            if ($tutor) {
                // Update booking session statuses based on current date
                $this->updateBookingSessionStatuses($tutor->id);

                // Fetch booking sessions for the tutor with sorting
                $bookingSessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)
                    ->orderBy('session_date', 'asc')
                    ->get()
                    ->sort(function ($a, $b) {
                        // First sort by date
                        $dateComparison = strcmp($a->session_date, $b->session_date);
                        if ($dateComparison !== 0) {
                            return $dateComparison;
                        }
                        
                        // If dates are the same, sort by start time
                        $aTimeRanges = explode(',', $a->session_time);
                        $bTimeRanges = explode(',', $b->session_time);
                        
                        $aFirstTime = trim($aTimeRanges[0] ?? '');
                        $bFirstTime = trim($bTimeRanges[0] ?? '');
                        
                        $aStartTime = explode(' - ', $aFirstTime)[0] ?? '';
                        $bStartTime = explode(' - ', $bFirstTime)[0] ?? '';
                        
                        // Convert to 24-hour format for comparison
                        $aTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($aStartTime))->format('H:i');
                        $bTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($bStartTime))->format('H:i');
                        
                        return strcmp($aTime24, $bTime24);
                    });

                $now = \Carbon\Carbon::now();

                // Filter out past sessions based on session_date and session_time end for upcoming sessions
                $upcomingSessionsCollection = $bookingSessions->filter(function ($session) use ($now) {
                    $timeRanges = explode(',', $session->session_time);
                    $lastTimeRange = trim(end($timeRanges));
                    $timeParts = explode(' - ', $lastTimeRange);
                    $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                    $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime);
                    return $sessionEnd->gte($now);
                });

                // Map upcoming sessions
                $upcomingSessions = $upcomingSessionsCollection->map(function ($session) use ($tutor) {
                    $student = $session->student()->with('user')->first();
                    $studentUser = $student ? $student->user : null;

                    $sessionStatus = $this->calculateSessionStatus($session);

                    $now = \Carbon\Carbon::now();
                    $sessionDate = \Carbon\Carbon::parse($session->session_date);
                    if ($sessionDate->lt($now)) {
                        $sessionStatus = 'past';
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
                        'payment_method' => $paymentMethod ?? 'N/A',
                        'status' => $sessionStatus,
                    ];
                })->toArray();

                // All sessions (for "Your Tutoring Sessions" section) - also sorted
                $allSessions = $bookingSessions->map(function ($session) use ($tutor) {
                    $student = $session->student()->with('user')->first();
                    $studentUser = $student ? $student->user : null;

                    $sessionStatus = $this->calculateSessionStatus($session);

                    return (object)[
                        'subject' => $session->subject_name ?? 'N/A',
                        'date' => $session->session_date ?? 'N/A',
                        'time' => $session->session_time ?? 'N/A',
                        'id' => $session->id,
                        'student_profile_image' => $studentUser ? $studentUser->profile_image : null,
                        'student_name' => $studentUser ? $studentUser->full_name : 'N/A',
                        'student_email' => $studentUser ? $studentUser->email : 'N/A',
                        'total_price' => is_numeric($session->total_price) ? $session->total_price : 0,
                        'payment_method' => $paymentMethod ?? 'N/A',
                        'status' => $sessionStatus,
                    ];
                })->toArray();
            }
        }

        return view('home_tutor', ['upcomingSessions' => $upcomingSessions, 'sessions' => $allSessions]);
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
            $tutor = $user->tutor;
            if ($tutor) {
                $tutor = $this->filterTutorAvailability($tutor, $user->id);
                $availability = $tutor->availability ?? [];
            } else {
                $availability = [];
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

    public function cancelSession(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !in_array($user->role, ['student', 'tutor'])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $sessionId = $request->input('session_id');
            if (!$sessionId) {
                return response()->json(['error' => 'Session ID is required'], 400);
            }

            $session = \App\Models\BookingSession::find($sessionId);
            if (!$session) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            if ($user->role === 'student' && $session->student_id !== $user->student->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            if ($user->role === 'tutor' && $session->tutor_id !== $user->tutor->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            // Delete the session
            $session->delete();

            $now = now();

            if ($user->role === 'student') {
                // Fetch updated sessions for the student
                $student = $user->student;
                $sessions = \App\Models\BookingSession::where('student_id', $student->id)
                    ->orderBy('session_date', 'asc')
                    ->get()
                    ->filter(function ($session) use ($now) {
                        $sessionDate = \Carbon\Carbon::parse($session->session_date, 'Asia/Kuala_Lumpur');
                        $timeRanges = explode(',', $session->session_time);
                        $lastTimeRange = trim(end($timeRanges));
                        $timeParts = explode(' - ', $lastTimeRange);
                        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                        $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime, 'Asia/Kuala_Lumpur');
                        return $sessionEnd->gte($now);
                    })
                    ->sort(function ($a, $b) {
                        // First sort by date
                        $dateComparison = strcmp($a->session_date, $b->session_date);
                        if ($dateComparison !== 0) {
                            return $dateComparison;
                        }
                        
                        // If dates are the same, sort by start time
                        $aTimeRanges = explode(',', $a->session_time);
                        $bTimeRanges = explode(',', $b->session_time);
                        
                        $aFirstTime = trim($aTimeRanges[0] ?? '');
                        $bFirstTime = trim($bTimeRanges[0] ?? '');
                        
                        $aStartTime = explode(' - ', $aFirstTime)[0] ?? '';
                        $bStartTime = explode(' - ', $bFirstTime)[0] ?? '';
                        
                        // Convert to 24-hour format for comparison
                        $aTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($aStartTime))->format('H:i');
                        $bTime24 = \Carbon\Carbon::createFromFormat('g:i A', trim($bStartTime))->format('H:i');
                        
                        return strcmp($aTime24, $bTime24);
                    })
                    ->map(function ($session) {
                        $tutor = $session->tutor()->with('user')->first();
                        $tutorName = ($tutor && $tutor->user) ? $tutor->user->full_name : 'Tutor';

                        // Calculate session status based on date and time
                        $now = \Carbon\Carbon::now();
                        $sessionDate = \Carbon\Carbon::parse($session->session_date);
                        $timeRanges = explode(',', $session->session_time);
                        $firstTimeRange = trim($timeRanges[0] ?? '');
                        $timeParts = explode(' - ', $firstTimeRange);
                        $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '00:00';
                        $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '23:59';
                        $sessionStart = \Carbon\Carbon::parse($session->session_date . ' ' . $startTime);
                        $sessionEnd = \Carbon\Carbon::parse($session->session_date . ' ' . $endTime);

                        if ($sessionEnd->lt($now)) {
                            $status = 'past';
                        } elseif ($sessionStart->lte($now) && $sessionEnd->gte($now)) {
                            $status = 'ongoing';
                        } else {
                            $status = 'future';
                        }

                        return [
                            'id' => $session->id,
                            'subject' => $session->subject_name ?? 'N/A',
                            'date' => $session->session_date ?? 'N/A',
                            'time' => $session->session_time ?? 'N/A',
                            'tutor_name' => $tutorName,
                            'status' => $status,
                        ];
                    })->values()->all();
            } else {
                // Fetch updated sessions for the tutor
                $tutor = $user->tutor;
                $sessions = \App\Models\BookingSession::where('tutor_id', $tutor->id)
                    ->orderBy('session_date', 'asc')
                    ->get()
                    ->map(function ($session) {
                        $student = $session->student()->with('user')->first();
                        $studentName = ($student && $student->user) ? $student->user->full_name : 'Student';
                        $studentEmail = ($student && $student->user) ? $student->user->email : 'N/A';
                        $studentProfileImage = ($student && $student->user) ? $student->user->profile_image : null;

                        $sessionStatus = $this->calculateSessionStatus($session);

                        return [
                            'id' => $session->id,
                            'subject' => $session->subject_name ?? 'N/A',
                            'date' => $session->session_date ?? 'N/A',
                            'time' => $session->session_time ?? 'N/A',
                            'student_name' => $studentName,
                            'student_email' => $studentEmail,
                            'student_profile_image' => $studentProfileImage,
                            'status' => $sessionStatus,
                        ];
                    })->values()->all();
            }

            // Fetch updated tutor availability for all tutors (optional optimization: only for tutors with sessions)
            $tutors = \App\Models\Tutor::with('user')->get();
            $updatedTutors = $tutors->map(function ($tutor) use ($user) {
                $studentId = $user->student ? $user->student->id : null;
                $tutor = $this->filterTutorAvailability($tutor, $studentId);
                return [
                    'id' => $tutor->id,
                    'availability' => $tutor->availability,
                ];
            })->values()->all();

            return response()->json([
                'sessions' => $sessions,
                'tutors' => $updatedTutors,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in cancelSession: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Server error occurred while cancelling session.'], 500);
        }
    }
}






