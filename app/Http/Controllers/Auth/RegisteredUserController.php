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
}
