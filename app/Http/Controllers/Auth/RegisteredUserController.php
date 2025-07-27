<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function createStudent(): View
    {
        return view('auth.register_student');
    }

    public function storeStudent(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info('Student registration request data:', $request->all());

        try {
            $request->validate([
                'full_name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => [
                    'required',
                    'confirmed',
                    'max:255',
                    // The regex below requires at least one letter, one number, and one special character
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/',
                    Rules\Password::defaults()
                ],
                'phone_number' => ['required', 'string', 'max:20'],
                'dob' => ['required', 'date'],
                'student_subjects' => ['required', 'array', 'min:1'],
                'student_subjects.*' => ['required', 'string', 'max:255'],
            ]);

            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => 'student',
                'date_of_birth' => $request->dob,
            ]);

            $preferredCourse = $request->input('student_subjects', []);
            $user->student()->create([
                'student_id' => 'STU-' . uniqid(),
                'major' => $request->input('major'),
                'year' => $request->input('year'),
                'preferred_course' => $preferredCourse,
            ]);

            // event(new Registered($user)); // <-- comment this out if you don't need email verification

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during student registration:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error during student registration:', ['message' => $e->getMessage()]);
            return response()->json(['errors' => ['error' => 'An unexpected error occurred. Please try again later.']], 500);
        }
    }

    public function createTutor(): View
    {
        return view('auth.register_tutor');
    }

    public function storeTutor(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info('Tutor registration request data:', $request->all());

        try {
            $request->validate([
                'full_name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => [
                    'required',
                    'confirmed',
                    'max:255',
                    // The regex below requires at least one letter, one number, and one special character
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/',
                    Rules\Password::defaults()
                ],
                'phone_number' => ['required', 'string', 'max:20'],
                'dob' => ['required', 'date'],
                'availability' => ['required', 'array'],
                'availability.*' => ['required'],
                'subjects' => ['required', 'array', 'min:1', 'max:5'],
                'subjects.*' => ['required', 'string', 'max:255'],
                'rates' => ['required', 'array', 'min:1', 'max:5'],
                'rates.*' => ['required', 'numeric', 'min:0'],
            ]);

            // Custom validation for availability dates and times based on Malaysian time
            $this->validateAvailabilityDateTime($request->availability);

            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => 'tutor',
                'date_of_birth' => $request->dob,
            ]);

            $availability = $request->availability;
            if (is_string($availability)) {
                $availability = json_decode($availability, true) ?: [];
            }

            // Transform availability data from form format to expected format
            Log::info('Original availability data:', $availability);
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
            Log::info('Transformed availability data:', $transformedAvailability);
            $availability = $transformedAvailability;
            // Combine subjects and rates into expertise array
            $subjects = $request->subjects;
            $rates = $request->rates;
            $expertise = [];
            foreach ($subjects as $i => $subject) {
                $expertise[] = [
                    'name' => $subject,
                    'price_per_hour' => $rates[$i] ?? 0
                ];
            }
            $user->tutor()->create([
                'tutor_id' => 'TUT' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'expertise' => json_encode($expertise),
                'payment_details' => $request->input('payment_method'),
                'availability' => $availability,
            ]);

            // event(new Registered($user)); // <-- comment this out if you don't need email verification

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during tutor registration:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error during tutor registration:', ['message' => $e->getMessage()]);
            return response()->json(['errors' => ['error' => 'An unexpected error occurred. Please try again later.']], 500);
        }
    }

    /**
     * Validate availability dates and times based on Malaysian time zone
     */
    private function validateAvailabilityDateTime($availability)
    {
        // Get current Malaysian time
        $nowMalaysia = \Carbon\Carbon::now('Asia/Kuala_Lumpur');
        $currentHour = $nowMalaysia->hour;

        // Define allowed time slots (8 AM to 6 PM)
        $allowedTimeSlots = [
            '8:00 AM - 9:00 AM',
            '9:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',
            '11:00 AM - 12:00 PM',
            '12:00 PM - 1:00 PM',
            '1:00 PM - 2:00 PM',
            '2:00 PM - 3:00 PM',
            '3:00 PM - 4:00 PM',
            '4:00 PM - 5:00 PM',
            '5:00 PM - 6:00 PM'
        ];

        foreach ($availability as $key => $value) {
            // Check if this is a date field
            if (preg_match('/^date\d+$/', $key)) {
                $selectedDate = \Carbon\Carbon::parse($value, 'Asia/Kuala_Lumpur');

                // Check if date is in the past
                if ($selectedDate->lt($nowMalaysia->startOfDay())) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['availability' => ['Selected date cannot be in the past.']]
                    );
                }

                // If current time is past 6 PM and selected date is today, reject
                if ($currentHour >= 18 && $selectedDate->isSameDay($nowMalaysia)) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['availability' => ['Cannot select today\'s date after 6:00 PM. Please select a future date.']]
                    );
                }
            }

            // Check if this is a time field
            if (preg_match('/^time\d+$/', $key) && is_array($value)) {
                foreach ($value as $timeSlot) {
                    if (!in_array($timeSlot, $allowedTimeSlots)) {
                        throw new \Illuminate\Validation\ValidationException(
                            validator([], []),
                            ['availability' => ['Invalid time slot selected. Time slots must be between 8:00 AM and 6:00 PM.']]
                        );
                    }
                }

                // Additional validation for today's date
                $dateKey = str_replace('time', 'date', $key);
                if (isset($availability[$dateKey])) {
                    $selectedDate = \Carbon\Carbon::parse($availability[$dateKey], 'Asia/Kuala_Lumpur');

                    if ($selectedDate->isSameDay($nowMalaysia)) {
                        foreach ($value as $timeSlot) {
                            $startTime = explode(' - ', $timeSlot)[0];
                            $hour = (int) explode(':', $startTime)[0];
                            $isPM = strpos($startTime, 'PM') !== false;
                            $hour24 = $isPM && $hour !== 12 ? $hour + 12 : (!$isPM && $hour === 12 ? 0 : $hour);

                            if ($hour24 <= $currentHour) {
                                throw new \Illuminate\Validation\ValidationException(
                                    validator([], []),
                                    ['availability' => ['Cannot select past time slots for today\'s date.']]
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}
