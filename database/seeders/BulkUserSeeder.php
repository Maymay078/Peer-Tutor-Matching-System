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

        // Generate dynamic availability dates with more dates in August
        $today = \Carbon\Carbon::today();
        $sharedAvailabilityArray = [];

        // Add dates from July 28 to July 31
        $startJuly = \Carbon\Carbon::create(2025, 7, 28);
        $endJuly = \Carbon\Carbon::create(2025, 7, 31);
        for ($date = $startJuly->copy(); $date->lte($endJuly); $date->addDay()) {
            if ($date->gte($today)) {
                $sharedAvailabilityArray[] = ['date' => $date->toDateString(), 'time' => ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM']];
            }
        }

        // Add more dates in August from August 1 to August 30
        $startAugust = \Carbon\Carbon::create(2025, 8, 1);
        $endAugust = \Carbon\Carbon::create(2025, 8, 30);
        for ($date = $startAugust->copy(); $date->lte($endAugust); $date->addDay()) {
            if ($date->gte($today)) {
                $sharedAvailabilityArray[] = ['date' => $date->toDateString(), 'time' => ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM']];
            }
        }

        $sharedAvailability = json_encode($sharedAvailabilityArray);

        // Removed first batch of 20 students creation to avoid duplication and ensure control in second batch


            // Create 20 tutors
            $tutors = User::factory()
                ->count(20)
                ->state(['role' => 'tutor', 'password' => bcrypt('Password@123')])
                ->create()
                ->each(function ($user, $index) use ($subjectsList, $sharedAvailability, $today) {
                $availability = null;
                // For some tutors, add availability data
                if ($index % 6 == 0) {
                    // Randomly select between 3 to 5 availability dates from July 30 to August 31
                    $availabilityArray = [];
                    $startDate = \Carbon\Carbon::create(2025, 7, 30);
                    $endDate = \Carbon\Carbon::create(2025, 8, 31);
                    $allDates = [];
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[] = $date->toDateString();
                    }
                    shuffle($allDates);
                    $numDates = rand(3, 5);
                    $selectedDates = array_slice($allDates, 0, $numDates);
                    $timeSlotsPool = [
                        '9:00 AM - 10:00 AM',
                        '10:00 AM - 11:00 AM',
                        '11:00 AM - 12:00 PM',
                        '1:00 PM - 2:00 PM',
                        '2:00 PM - 3:00 PM',
                        '3:00 PM - 4:00 PM',
                        '4:00 PM - 5:00 PM',
                    ];
                    foreach ($selectedDates as $date) {
                        // Randomly select 3 to 5 time slots for each date
                        shuffle($timeSlotsPool);
                        $numTimeSlots = rand(3, 5);
                        $timeSlots = array_slice($timeSlotsPool, 0, $numTimeSlots);
                        $availabilityArray[] = ['date' => $date, 'time' => $timeSlots];
                    }
                } elseif ($index % 6 == 1) {
                    // Randomly select between 3 to 5 availability dates from July 30 to August 31
                    $availabilityArray = [];
                    $startDate = \Carbon\Carbon::create(2025, 7, 30);
                    $endDate = \Carbon\Carbon::create(2025, 8, 31);
                    $allDates = [];
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[] = $date->toDateString();
                    }
                    shuffle($allDates);
                    $numDates = rand(3, 5);
                    $selectedDates = array_slice($allDates, 0, $numDates);
                    foreach ($selectedDates as $date) {
                        $availabilityArray[] = ['date' => $date, 'time' => ['9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM']];
                    }
                } elseif ($index % 6 == 2) {
                    // Randomly select between 3 to 5 availability dates from July 30 to August 31
                    $availabilityArray = [];
                    $startDate = \Carbon\Carbon::create(2025, 7, 30);
                    $endDate = \Carbon\Carbon::create(2025, 8, 31);
                    $allDates = [];
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[] = $date->toDateString();
                    }
                    shuffle($allDates);
                    $numDates = rand(3, 5);
                    $selectedDates = array_slice($allDates, 0, $numDates);
                    foreach ($selectedDates as $date) {
                        $availabilityArray[] = ['date' => $date, 'time' => ['11:00 AM - 12:00 PM', '12:00 PM - 1:00 PM']];
                    }
                } elseif ($index % 6 == 3) {
                    // Randomly select between 3 to 5 availability dates from July 30 to August 31
                    $availabilityArray = [];
                    $startDate = \Carbon\Carbon::create(2025, 7, 30);
                    $endDate = \Carbon\Carbon::create(2025, 8, 31);
                    $allDates = [];
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $allDates[] = $date->toDateString();
                    }
                    shuffle($allDates);
                    $numDates = rand(3, 5);
                    $selectedDates = array_slice($allDates, 0, $numDates);
                    foreach ($selectedDates as $date) {
                        $availabilityArray[] = ['date' => $date, 'time' => ['8:00 AM - 9:00 AM']];
                    }
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
        $overlapCounts = [1, 2, 2, 3, 3, 3, 4, 5]; // More 2s and 3s, fewer 1s and 4s, some 5s
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

                // Ensure at least 2 subjects total for the student
                $minSubjects = 2;
                $allSubjectNames = array_column($subjectsList, 'name');
                $remainingCount = max(0, $minSubjects - count($overlapSubjects));
                $randomSubjects = [];

                while (count($randomSubjects) < $remainingCount) {
                    $subject = $allSubjectNames[array_rand($allSubjectNames)];
                    if (!in_array($subject, $overlapSubjects) && !in_array($subject, $randomSubjects)) {
                        $randomSubjects[] = $subject;
                    }
                }

                // Add additional random subjects up to a max of 5 total subjects
                $maxSubjects = 5;
                $additionalCount = rand(0, $maxSubjects - $minSubjects);
                while (count($randomSubjects) < $remainingCount + $additionalCount) {
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
        $now = now();
        $studentIds = Student::pluck('id')->toArray();

        foreach ($tutors as $tutorUser) {
            $expertise = json_decode($tutorUser->tutor->expertise, true);
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

                // Determine session type for student: past, future, both, or none
                // Randomly assign session type per student
                $sessionTypeOptions = ['past', 'future', 'both', 'none'];
                $sessionType = $sessionTypeOptions[array_rand($sessionTypeOptions)];

                // Generate session dates based on session type
                $sessionDates = [];

                if ($sessionType === 'past') {
                    // Past session date: between 1 and 30 days ago
                    $sessionDates[] = $now->copy()->subDays(rand(1, 30))->toDateString();
                } elseif ($sessionType === 'future') {
                    // Future session date: between tomorrow and end of August 2025
                    $futureDatesPool = [
                        '2025-07-23', '2025-07-25', '2025-07-28', '2025-07-30',
                        '2025-08-01', '2025-08-05', '2025-08-10', '2025-08-15',
                        '2025-08-20', '2025-08-25', '2025-08-30'
                    ];
                    $sessionDates[] = $futureDatesPool[array_rand($futureDatesPool)];
                } elseif ($sessionType === 'both') {
                    // One past and one future session
                    $sessionDates[] = $now->copy()->subDays(rand(1, 30))->toDateString();
                    $futureDatesPool = [
                        '2025-07-23', '2025-07-25', '2025-07-28', '2025-07-30',
                        '2025-08-01', '2025-08-05', '2025-08-10', '2025-08-15',
                        '2025-08-20', '2025-08-25', '2025-08-30'
                    ];
                    $sessionDates[] = $futureDatesPool[array_rand($futureDatesPool)];
                } else {
                    // none - skip creating session
                    continue;
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

                foreach ($sessionDates as $sessionDate) {
                    $status = $now->toDateString() > $sessionDate ? 'past' : 'future';

                    $bookingSession = BookingSession::create([
                        'student_id' => $studentId,
                        'tutor_id' => $tutorUser->tutor->id,
                        'subject_name' => $subjectName,
                        'session_date' => $sessionDate,
                        'session_time' => $sessionTime,
                        'total_price' => $totalPrice,
                        'status' => $status,
                    ]);

                    // Seed feedback for past sessions only, for both student and tutor
                    if ($status === 'past') {
                        $studentUserId = Student::find($studentId)->user_id;
                        $tutorUserId = $tutorUser->id;

                        $rating = rand(3, 5);
                        $comment = 'Great session with the tutor.';

                        // Feedback from student to tutor
                        Feedback::create([
                            'booking_session_id' => $bookingSession->id,
                            'from_user_id' => $studentUserId,
                            'to_user_id' => $tutorUserId,
                            'rating' => $rating,
                            'comment' => $comment,
                        ]);

                        // Feedback from tutor to student
                        Feedback::create([
                            'booking_session_id' => $bookingSession->id,
                            'from_user_id' => $tutorUserId,
                            'to_user_id' => $studentUserId,
                            'rating' => $rating,
                            'comment' => 'Great session with the student.',
                        ]);
                    }
                }
            }
        }
    }
}
