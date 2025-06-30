<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\BookingSession;
use App\Models\Feedback;

class ListUsersWithDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List students and tutors with role, availability, rating (for tutors), payment details (for tutors), booking sessions, feedback, and email with numbering';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('Do you want to list subject expertise for tutors and preferred subjects for students?')) {
            $this->info('Command cancelled.');
            return 0;
        }

        $users = User::with(['student', 'tutor'])->get();

        foreach ($users as $index => $user) {
            $fullName = $user->full_name ?? 'Unknown';
            $line = ($index + 1) . ". Name: " . $fullName . ", Role: " . $user->role . ", Email: " . $user->email;

            if ($user->role === 'student' && $user->student) {
                $preferredSubjects = $user->student->preferred_course;
                if (is_string($preferredSubjects)) {
                    $preferredSubjects = json_decode($preferredSubjects, true) ?: [];
                }
$line .= ", Preferred Subjects: " . (is_array($preferredSubjects) ? implode(', ', $preferredSubjects) : 'N/A');

                // Remove availability display for students as they no longer have availability
                $line .= ", Availability: N/A";

                // List booking sessions for student
                $bookingSessions = BookingSession::where('student_id', $user->student->id)->get();
                if ($bookingSessions->isNotEmpty()) {
                    $line .= ", Booking Sessions: [";
                    $tutorIds = [];
                    foreach ($bookingSessions as $session) {
                        // Dynamically calculate status based on session date and booking status
                        $status = $session->status;
                        $now = \Carbon\Carbon::now();
                        $sessionDate = \Carbon\Carbon::parse($session->session_date);
                        // Treat null is_manual_booking as false (backend booking)
                        $isManual = $session->is_manual_booking ?? false;
                        if ($sessionDate->lt($now) && !$isManual) {
                            $status = 'past';
                        } elseif ($sessionDate->gt($now) && !$isManual) {
                            // For backend bookings, future date means status future regardless of stored status
                            $status = 'future';
                        }
                        // If status is pending and date is future, keep as pending (manual booking)
                        $line .= "Subject: {$session->subject_name}, Date: {$session->session_date}, Time: {$session->session_time}, Status: {$status}; ";
                        $tutorIds[] = $session->tutor_id;
                    }
                    $line .= "]";
                    $tutorIds = array_unique($tutorIds);
                    $tutors = \App\Models\Tutor::whereIn('id', $tutorIds)->with('user')->get();
                    $tutorNames = $tutors->map(function ($tutor) {
                        return $tutor->user->full_name ?? 'Unknown';
                    })->toArray();
                    $line .= ", Tutors with Booking Sessions: " . implode(', ', $tutorNames);
                    $line .= " (IDs: " . implode(', ', $tutorIds) . ")";
                } else {
                    $line .= ", Booking Sessions: None";
                }

                // List feedback given by student
                $feedbacks = Feedback::where('from_user_id', $user->id)->get();
                if ($feedbacks->isNotEmpty()) {
                    $line .= ", Feedback Given: [";
                    foreach ($feedbacks as $feedback) {
                        $toUser = User::find($feedback->to_user_id);
                        $toUserName = $toUser ? $toUser->full_name : 'Unknown';
                        $line .= "To: {$toUserName}, Rating: {$feedback->rating}, Comment: {$feedback->comment}; ";
                    }
                    $line .= "]";
                } else {
                    $line .= ", Feedback Given: None";
                }
            }

            if ($user->role === 'tutor' && $user->tutor) {
                $expertise = $user->tutor->expertise;
                if (is_string($expertise)) {
                    $expertise = json_decode($expertise, true) ?: [];
                }
                if (!is_array($expertise)) {
                    $expertise = [];
                }
                $subjectDetails = [];
                foreach ($expertise as $subject) {
                    $name = $subject['name'] ?? 'N/A';
                    $price = $subject['price_per_hour'] ?? 'N/A';
                    $subjectDetails[] = "$name (RM$price/hr)";
                }
                $line .= ", Subject Expertise: " . (!empty($subjectDetails) ? implode(', ', $subjectDetails) : 'N/A');

                // Convert availability array to string
                $availability = $user->tutor->availability;
                if (is_string($availability)) {
                    $availabilityDecoded = json_decode($availability, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($availabilityDecoded)) {
                        $flatAvailability = [];
                        array_walk_recursive($availabilityDecoded, function($item) use (&$flatAvailability) {
                            if (is_scalar($item)) {
                                $flatAvailability[] = $item;
                            }
                        });
                        $availabilityStr = !empty($flatAvailability) ? implode(', ', $flatAvailability) : json_encode($availabilityDecoded);
                    } else {
                        $availabilityStr = $availability;
                    }
                } elseif (is_array($availability)) {
                    $flatAvailability = [];
                    array_walk_recursive($availability, function($item) use (&$flatAvailability) {
                        if (is_scalar($item)) {
                            $flatAvailability[] = $item;
                        }
                    });
                    $availabilityStr = !empty($flatAvailability) ? implode(', ', $flatAvailability) : json_encode($availability);
                } else {
                    $availabilityStr = 'N/A';
                }
                $line .= ", Availability: " . $availabilityStr;

                $line .= ", Rating: " . $user->tutor->rating;
                $paymentDetails = $user->tutor->payment_details;
                if (is_string($paymentDetails)) {
                    $paymentDetails = trim($paymentDetails);
                }
                if (empty($paymentDetails)) {
                    $paymentDetails = 'N/A';
                }
                $line .= ", Payment Details: " . $paymentDetails;

                // List booking sessions for tutor
                $bookingSessions = BookingSession::where('tutor_id', $user->tutor->id)->get();
                if ($bookingSessions->isNotEmpty()) {
                    $line .= ", Booking Sessions: [";
                    $studentIds = [];
                    foreach ($bookingSessions as $session) {
                        $line .= "Subject: {$session->subject_name}, Date: {$session->session_date}, Time: {$session->session_time}, Status: {$session->status}; ";
                        $studentIds[] = $session->student_id;
                    }
                    $line .= "]";
                    $studentIds = array_unique($studentIds);
                    $students = \App\Models\Student::whereIn('id', $studentIds)->with('user')->get();
                    $studentNames = $students->map(function ($student) {
                        return $student->user->full_name ?? 'Unknown';
                    })->toArray();
                    $line .= ", Students with Booking Sessions: " . implode(', ', $studentNames);
                    $line .= " (IDs: " . implode(', ', $studentIds) . ")";
                } else {
                    $line .= ", Booking Sessions: None";
                }

                // List feedback received by tutor
                $feedbacks = Feedback::where('to_user_id', $user->id)->get();
                if ($feedbacks->isNotEmpty()) {
                    $line .= ", Feedback Received: [";
                    foreach ($feedbacks as $feedback) {
                        $fromUser = User::find($feedback->from_user_id);
                        $fromUserName = $fromUser ? $fromUser->full_name : 'Unknown';
                        $line .= "From: {$fromUserName}, Rating: {$feedback->rating}, Comment: {$feedback->comment}; ";
                    }
                    $line .= "]";
                } else {
                    $line .= ", Feedback Received: None";
                }
            }

            $this->line($line);
        }

        return 0;
    }
}
