<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    private function formatAvailability(array $availability): string
    {
        $today = date('Y-m-d');
        $formattedAvailability = [];

        foreach ($availability as $entry) {
            if (is_array($entry) && count($entry) >= 2) {
                $date = $entry[0];
                $times = $entry[1];
                if ($date >= $today) {
                    $formattedAvailability[] = "{$date} ({$times})";
                }
            } elseif (is_array($entry) && isset($entry['date'], $entry['time'])) {
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

    public function edit(Request $request): View
    {
        $user = $request->user();
        $student = $user->student ?? null;
        $tutor = $user->tutor ?? null;

        // Debug: Log availability data to storage/logs/laravel.log
        \Log::info('Student availability data:', ['availability' => $student ? $student->availability : null]);
        \Log::info('Tutor availability data:', ['availability' => $tutor ? $tutor->availability : null]);

        // Convert arrays to comma-separated strings for view (student)
        $preferredCourseStr = '';
        $availabilityStr = '';
        if ($student) {
            $preferredCourseStr = is_array($student->preferred_course) ? implode(', ', $student->preferred_course) : ($student->preferred_course ?? '');

            if ($student->availability) {
                if (is_string($student->availability)) {
                    $availabilityData = json_decode($student->availability, true);
                } else {
                    $availabilityData = $student->availability;
                }
                if (is_array($availabilityData)) {
                    $availabilityStr = $this->formatAvailability($availabilityData);
                } else {
                    $availabilityStr = $student->availability ?? '';
                }
            }
        }

        // Convert arrays to appropriate format for view (tutor)
        $expertise = [];
        $paymentDetails = [];
        $tutorAvailabilityStr = '';
        if ($tutor) {
            $expertise = is_string($tutor->expertise) ? json_decode($tutor->expertise, true) : ($tutor->expertise ?? []);
            $paymentDetails = is_string($tutor->payment_details) ? json_decode($tutor->payment_details, true) : ($tutor->payment_details ?? []);
            $availabilityData = [];
            if ($tutor->availability) {
                if (is_string($tutor->availability)) {
                    $availabilityData = json_decode($tutor->availability, true);
                } else {
                    $availabilityData = $tutor->availability;
                }
            }
            if (is_array($availabilityData)) {
                $tutorAvailabilityStr = $this->formatAvailability($availabilityData);
            } else {
                $tutorAvailabilityStr = $tutor->availability ?? '';
            }
        }

        return view('profile.edit', [
            'user' => $user,
            'student' => $student,
            'preferredCourseStr' => $preferredCourseStr,
            'availabilityStr' => $availabilityStr,
            'tutor' => $tutor,
            'expertise' => $expertise,
            'paymentDetails' => $paymentDetails,
            'tutorAvailabilityStr' => $tutorAvailabilityStr,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                // Delete old image
                \Storage::delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update or create student profile data (only for students)
        if ($user->role === 'student') {
            $studentData = $request->only(['major', 'year', 'preferred_course']);

            // Convert comma-separated strings back to arrays for preferred_course
            if (isset($studentData['preferred_course']) && is_string($studentData['preferred_course'])) {
                $studentData['preferred_course'] = array_map('trim', explode(',', $studentData['preferred_course']));
            }

            // Generate unique student_id if creating new student record
            if (!$user->student) {
                $studentData['student_id'] = 'STU-' . uniqid();
            } elseif (!isset($studentData['student_id'])) {
                $studentData['student_id'] = $user->student->student_id;
            }

            $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                $studentData
            );
        }

        // Update or create tutor profile data (only for tutors)
        if ($user->role === 'tutor') {
            $tutorData = $request->only(['expertise', 'payment_details', 'availability']);
            \Log::info('Tutor profile update data:', $tutorData);

            // Convert expertise from array to JSON string if needed
            if (isset($tutorData['expertise']) && is_array($tutorData['expertise'])) {
                $tutorData['expertise'] = json_encode($tutorData['expertise']);
            }

            // Handle payment_details - convert array to JSON string
            if (isset($tutorData['payment_details']) && is_array($tutorData['payment_details'])) {
                $tutorData['payment_details'] = json_encode($tutorData['payment_details']);
            } elseif (!isset($tutorData['payment_details'])) {
                $tutorData['payment_details'] = json_encode([]);
            }

            // Transform availability data from form format to expected format
            if (isset($tutorData['availability']) && is_array($tutorData['availability'])) {
                $availability = $tutorData['availability'];
                $transformedAvailability = [];

                foreach ($availability as $key => $value) {
                    if (preg_match('/^date(\d+)$/', $key, $matches)) {
                        $index = $matches[1];
                        $timeKey = 'time' . $index;
                        $date = $value;
                        $times = isset($availability[$timeKey]) ? $availability[$timeKey] : [];

                        if ($date && !empty($times)) {
                            $transformedAvailability[] = [
                                'date' => $date,
                                'time' => $times
                            ];
                        }
                    }
                }
                $tutorData['availability'] = $transformedAvailability;
            }

            // Generate unique tutor_id if creating new tutor record
            if (!$user->tutor) {
                $tutorData['tutor_id'] = 'TUT' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            } elseif (!isset($tutorData['tutor_id'])) {
                $tutorData['tutor_id'] = $user->tutor->tutor_id;
            }

            $user->tutor()->updateOrCreate(
                ['user_id' => $user->id],
                $tutorData
            );
        }

            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                // Refresh user data to get updated values
                $user->refresh();
                $user->load(['student', 'tutor']);

                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'user' => $user
                ]);
            }

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Profile update error: ' . $e->getMessage());

            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 500);
            }

            return Redirect::route('profile.edit')->with('error', 'Failed to update profile.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
