<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
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

        // Update or create student profile data
        $studentData = $request->only(['major', 'year', 'preferred_course', 'availability']);

        // Convert comma-separated strings back to arrays for preferred_course
        if (isset($studentData['preferred_course']) && is_string($studentData['preferred_course'])) {
            $studentData['preferred_course'] = array_map('trim', explode(',', $studentData['preferred_course']));
        }

        // Convert availability string back to array of [date, (time, time)] entries
        if (isset($studentData['availability']) && is_string($studentData['availability'])) {
            $availabilityEntries = array_map('trim', explode(',', $studentData['availability']));
            $parsedAvailability = [];
            foreach ($availabilityEntries as $entry) {
                // Parse entry like "Date (time, time)"
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $entry, $matches)) {
                    $date = trim($matches[1]);
                    $times = trim($matches[2]);
                    $parsedAvailability[] = [$date, $times];
                } else {
                    $parsedAvailability[] = $entry;
                }
            }
            $studentData['availability'] = $parsedAvailability;
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

        // Update or create tutor profile data
        $tutorData = $request->only(['expertise', 'payment_details', 'availability']);

        // Convert expertise and payment_details from arrays to JSON strings if needed
        if (isset($tutorData['expertise']) && is_array($tutorData['expertise'])) {
            $tutorData['expertise'] = json_encode($tutorData['expertise']);
        }
        if (isset($tutorData['payment_details']) && is_array($tutorData['payment_details'])) {
            $tutorData['payment_details'] = json_encode($tutorData['payment_details']);
        }

        // Convert availability string back to array of [date, (time, time)] entries for tutor
        if (isset($tutorData['availability']) && is_string($tutorData['availability'])) {
            $availabilityEntries = array_map('trim', explode(',', $tutorData['availability']));
            $parsedAvailability = [];
            foreach ($availabilityEntries as $entry) {
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $entry, $matches)) {
                    $date = trim($matches[1]);
                    $times = trim($matches[2]);
                    $parsedAvailability[] = ['date' => $date, 'time' => array_map('trim', explode(',', $times))];
                } else {
                    $parsedAvailability[] = $entry;
                }
            }
            $tutorData['availability'] = json_encode($parsedAvailability);
        }

        // Generate unique tutor_id if creating new tutor record
        if (!$user->tutor) {
            $tutorData['tutor_id'] = 'TUT-' . uniqid();
        } elseif (!isset($tutorData['tutor_id'])) {
            $tutorData['tutor_id'] = $user->tutor->tutor_id;
        }

        $user->tutor()->updateOrCreate(
            ['user_id' => $user->id],
            $tutorData
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
