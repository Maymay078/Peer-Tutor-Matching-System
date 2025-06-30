<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\BookingSession;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class BulkUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjectsList = [
            ['name' => 'Data Mining', 'price_per_hour' => 20],
            ['name' => 'Machine Learning', 'price_per_hour' => 18],
            ['name' => 'Statistics', 'price_per_hour' => 15],
            ['name' => 'Mathematics', 'price_per_hour' => 12],
            ['name' => 'Physics', 'price_per_hour' => 10],
            ['name' => 'Chemistry', 'price_per_hour' => 14],
            ['name' => 'Biology', 'price_per_hour' => 16],
            ['name' => 'Computer Science', 'price_per_hour' => 22],
            ['name' => 'English Literature', 'price_per_hour' => 13],
            ['name' => 'History', 'price_per_hour' => 11],
            ['name' => 'Geography', 'price_per_hour' => 9],
            ['name' => 'Economics', 'price_per_hour' => 17],
            ['name' => 'Psychology', 'price_per_hour' => 19],
            ['name' => 'Philosophy', 'price_per_hour' => 21],
            ['name' => 'Art History', 'price_per_hour' => 8],
            ['name' => 'Political Science', 'price_per_hour' => 14],
            ['name' => 'Sociology', 'price_per_hour' => 15],
            ['name' => 'Environmental Science', 'price_per_hour' => 16],
            ['name' => 'Business Studies', 'price_per_hour' => 18],
        ];

        $sharedAvailabilityArray = [
            ['date' => '2025-07-10', 'time' => ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM']],
            ['date' => '2025-07-20', 'time' => ['1:00 PM - 2:00 PM']],
            ['date' => '2025-07-25', 'time' => ['3:00 PM - 4:00 PM']],
            ['date' => '2025-08-01', 'time' => ['9:00 AM - 10:00 AM']],
            ['date' => '2025-08-05', 'time' => ['2:00 PM - 3:00 PM']],
        ];

        $sharedAvailability = json_encode($sharedAvailabilityArray);

        // Create 20 students
        User::factory()
            ->count(20)
            ->state(['role' => 'student', 'password' => bcrypt('Password@123')])
            ->create()
            ->each(function ($user, $index) use ($subjectsList) {
                
                // Assign preferred subjects (1 to 5), ensuring at least one matches tutor subjects
                $preferredSubjectsCount = rand(1, 5);
                $preferredSubjects = [];
                for ($i = 0; $i < $preferredSubjectsCount; $i++) {
                    $preferredSubjects[] = $subjectsList[array_rand($subjectsList)]['name'];
                }
                $preferredSubjects = array_unique($preferredSubjects);

                Student::factory()->create([
                    'user_id' => $user->id,
                    'preferred_course' => json_encode($preferredSubjects),
                ]);
            });

            // Create 20 tutors
            $tutors = User::factory()
                ->count(20)
                ->state(['role' => 'tutor', 'password' => bcrypt('Password@123')])
                ->create()
                ->each(function ($user, $index) use ($subjectsList, $sharedAvailability) {
                $availability = null;
                // For some tutors, add availability data
                if ($index % 6 == 0) {
                    $availabilityArray = json_decode($sharedAvailability, true);
                } elseif ($index % 6 == 1) {
                    $availabilityArray = [
                        ['date' => '2025-07-08', 'time' => ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM']],
                        ['date' => '2025-07-22', 'time' => ['1:00 PM - 2:00 PM']],
                    ];
                } elseif ($index % 6 == 2) {
                    $availabilityArray = [
                        ['date' => '2025-08-03', 'time' => ['11:00 AM - 12:00 PM', '12:00 PM - 1:00 PM']],
                        ['date' => '2025-08-17', 'time' => ['3:00 PM - 4:00 PM']],
                    ];
                } elseif ($index % 6 == 3) {
                    $availabilityArray = [
                        ['date' => '2025-07-15', 'time' => ['8:00 AM - 9:00 AM']],
                        ['date' => '2025-07-22', 'time' => ['2:00 PM - 3:00 PM']],
                        ['date' => '2025-08-07', 'time' => ['4:00 PM - 5:00 PM']],
                    ];
                } elseif ($index % 6 == 4) {
                    $availabilityArray = [];
                } else {
                    $availabilityArray = [];
                }

                    // Ensure at least 3 time slots per day if availability exists
                    foreach ($availabilityArray as &$slot) {
                        if (count($slot['time']) < 3) {
                            $existingTimes = $slot['time'];
                            $additionalTimes = ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM', '11:00 AM - 12:00 PM', '1:00 PM - 2:00 PM', '2:00 PM - 3:00 PM'];
                            foreach ($additionalTimes as $time) {
                                if (count($slot['time']) >= 3) {
                                    break;
                                }
                                if (!in_array($time, $existingTimes)) {
                                    $slot['time'][] = $time;
                                }
                            }
                        }
                    }
                    unset($slot);

                    $availability = json_encode($availabilityArray);

                $numSubjects = rand(1, 5);
                $selectedSubjects = [];
                $subjectNames = array_column($subjectsList, 'name');
                for ($i = 0; $i < $numSubjects; $i++) {
                    $subjectName = $subjectNames[array_rand($subjectNames)];
                    // Assign a random price between 5 and 20 RM for each subject per tutor
                    $price = rand(5, 20);
                    $selectedSubjects[$subjectName] = $price;
                }
                // Format expertise as array of ['name' => ..., 'price_per_hour' => ...]
                $expertise = [];
                foreach ($selectedSubjects as $name => $price) {
                    $expertise[] = ['name' => $name, 'price_per_hour' => $price];
                }
                $paymentOptions = ['Cash', 'Online Banking', 'Cash or Online Banking'];
                $paymentPreference = $paymentOptions[array_rand($paymentOptions)];
                Tutor::factory()->create([
                    'user_id' => $user->id,
                    'availability' => $availability,
                    'expertise' => json_encode($expertise),
                    'payment_details' => $paymentPreference,
                    'rating' => fake()->randomElement([3, 4, 5]),
                ]);
                });

        // Create 20 students with preferred subjects overlapping tutors' expertise
        $overlapCounts = [5, 4, 3, 2, 1];
        $tutorExpertiseSubjects = [];

        // Collect all tutors' expertise subjects
        foreach ($tutors as $tutorUser) {
            $expertise = json_decode($tutorUser->tutor->expertise, true);
            foreach ($expertise as $exp) {
                $tutorExpertiseSubjects[$tutorUser->id][] = $exp['name'];
            }
        }

        User::factory()
            ->count(20)
            ->state(['role' => 'student', 'password' => bcrypt('Password@123')])
            ->create()
            ->each(function ($user, $index) use ($subjectsList, $tutorExpertiseSubjects, $overlapCounts) {
                $preferredSubjects = [];

                // Determine overlap count for this student (cycle through overlapCounts)
                $overlapCount = $overlapCounts[$index % count($overlapCounts)];

                // Pick a random tutor's expertise subjects to overlap with
                $randomTutorSubjects = [];
                if (!empty($tutorExpertiseSubjects)) {
                    $randomTutorSubjects = $tutorExpertiseSubjects[array_rand($tutorExpertiseSubjects)];
                }

                // Select subjects to overlap
                $overlapSubjects = array_slice($randomTutorSubjects, 0, $overlapCount);

                // Fill the rest of preferred subjects randomly from subjectsList if needed
                $allSubjectNames = array_column($subjectsList, 'name');
                $remainingCount = max(0, rand(1, 5) - count($overlapSubjects));
                $randomSubjects = [];

                while (count($randomSubjects) < $remainingCount) {
                    $subject = $allSubjectNames[array_rand($allSubjectNames)];
                    if (!in_array($subject, $overlapSubjects) && !in_array($subject, $randomSubjects)) {
                        $randomSubjects[] = $subject;
                    }
                }

                $preferredSubjects = array_unique(array_merge($overlapSubjects, $randomSubjects));

                Student::factory()->create([
                    'user_id' => $user->id,
                    'preferred_course' => json_encode($preferredSubjects),
                ]);
            });

        // Seed booking sessions and feedback for all tutors
        foreach ($tutors as $tutorUser) {
            $expertise = json_decode($tutorUser->tutor->expertise, true);
            $studentIds = Student::pluck('id')->toArray();
            $subjects = array_column($expertise, 'name');

            // Number of sessions proportional to number of expertise subjects, minimum 1, max 5
            $numSessions = min(max(count($subjects), 1), 5);

            // If tutor has no availability, no booking sessions
            if (empty($tutorUser->tutor->availability)) {
                $numSessions = 0;
            }

            for ($j = 0; $j < $numSessions; $j++) {
                $studentId = $studentIds[array_rand($studentIds)];
                $subjectName = $subjects[array_rand($subjects)];

                // For past sessions, date is in the past
                if (rand(0, 1) === 0) {
                    $sessionDate = now()->subDays(rand(1, 30))->toDateString();
                    $status = 'past';
                } else {
                    // For future sessions, date is no earlier than 2025-07-20
                    $sessionDate = now()->addDays(rand(1, 30))->toDateString();
                    $status = 'future';
                }
                $sessionTime = '10:00 AM - 11:00 AM';

                // Calculate total price based on tutor's expertise price for the subject
                $pricePerHour = 0;
                foreach ($expertise as $exp) {
                    if ($exp['name'] === $subjectName) {
                        $pricePerHour = $exp['price_per_hour'];
                        break;
                    }
                }
                $totalPrice = $pricePerHour;

                $bookingSession = BookingSession::create([
                    'student_id' => $studentId,
                    'tutor_id' => $tutorUser->tutor->id,
                    'subject_name' => $subjectName,
                    'session_date' => $sessionDate,
                    'session_time' => $sessionTime,
                    'total_price' => $totalPrice,
                    'status' => $status,
                ]);

                // Seed feedback for past sessions only
                if ($status === 'past') {
                    $studentUserId = Student::find($studentId)->user_id;
                    $tutorUserId = $tutorUser->id;

                    // Feedback from student to tutor only (tutors only receive feedback)
                    Feedback::create([
                        'booking_session_id' => $bookingSession->id,
                        'from_user_id' => $studentUserId,
                        'to_user_id' => $tutorUserId,
                        'rating' => rand(3, 5),
                        'comment' => 'Great session with the tutor.',
                    ]);
                }
            }
        }
    }
}
