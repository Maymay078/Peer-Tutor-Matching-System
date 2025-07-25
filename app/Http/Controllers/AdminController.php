<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\BookingSession;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTutors = Tutor::count();
        $totalUsers = $totalStudents + $totalTutors; // Only count students and tutors

        $students = Student::with('user')->get();

        // Load tutors with their ratings (using the rating field that's updated by FeedbackController)
        $tutors = Tutor::with('user')
            ->get()
            ->map(function ($tutor) {
                // Use the rating field that's already calculated and stored
                $tutor->average_rating = $tutor->rating;

                // Parse expertise JSON
                $expertise = [];
                if ($tutor->expertise) {
                    try {
                        $expertise = json_decode($tutor->expertise, true);
                        if (!is_array($expertise)) {
                            $expertise = [];
                        }
                    } catch (\Exception $e) {
                        $expertise = [];
                    }
                }
                $tutor->parsed_expertise = $expertise;

                return $tutor;
            });

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact('totalUsers', 'totalStudents', 'totalTutors', 'students', 'tutors', 'recentActivities'));
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent user registrations (last 10)
        $recentUsers = User::whereIn('role', ['student', 'tutor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user_registration',
                'title' => 'New ' . ucfirst($user->role) . ' Registration',
                'description' => $user->full_name . ' joined as a ' . $user->role,
                'user_name' => $user->full_name,
                'user_role' => $user->role,
                'created_at' => $user->created_at,
                'icon' => $user->role === 'student' ? 'user-plus' : 'academic-cap'
            ]);
        }

        // Recent booking sessions (last 10)
        $recentBookings = BookingSession::with(['student.user', 'tutor.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentBookings as $booking) {
            $activities->push([
                'type' => 'session_booking',
                'title' => 'New Session Booking',
                'description' => $booking->student->user->full_name . ' booked a session with ' . $booking->tutor->user->full_name,
                'user_name' => $booking->student->user->full_name,
                'user_role' => 'student',
                'subject' => $booking->subject_name,
                'session_date' => $booking->session_date,
                'status' => $booking->status,
                'created_at' => $booking->created_at,
                'icon' => 'calendar'
            ]);
        }

        // Recent feedback submissions (last 5)
        $recentFeedbacks = Feedback::with(['fromUser', 'toUser', 'bookingSession'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentFeedbacks as $feedback) {
            $activities->push([
                'type' => 'feedback_submission',
                'title' => 'New Feedback Submitted',
                'description' => $feedback->fromUser->full_name . ' rated ' . $feedback->toUser->full_name . ' (' . $feedback->rating . '/5 stars)',
                'user_name' => $feedback->fromUser->full_name,
                'user_role' => $feedback->fromUser->role,
                'rating' => $feedback->rating,
                'created_at' => $feedback->created_at,
                'icon' => 'star'
            ]);
        }

        // Sort all activities by created_at descending and take the most recent 10
        return $activities->sortByDesc('created_at')->take(10)->values();
    }

    /**
     * Delete a single user (student or tutor)
     */
    public function deleteUser(User $user)
    {
        try {
            DB::beginTransaction();

            // Check if user is student or tutor (not admin)
            if (!in_array($user->role, ['student', 'tutor'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete admin users'
                ], 403);
            }

            // Delete related records based on user role
            if ($user->role === 'student') {
                // Delete student-specific data
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    // Delete booking sessions where user is student
                    BookingSession::where('student_id', $student->id)->delete();

                    // Delete feedback given by or received by this student
                    Feedback::where('from_user_id', $user->id)
                           ->orWhere('to_user_id', $user->id)
                           ->delete();

                    // Delete student record
                    $student->delete();
                }
            } elseif ($user->role === 'tutor') {
                // Delete tutor-specific data
                $tutor = Tutor::where('user_id', $user->id)->first();
                if ($tutor) {
                    // Delete booking sessions where user is tutor
                    BookingSession::where('tutor_id', $tutor->id)->delete();

                    // Delete feedback given by or received by this tutor
                    Feedback::where('from_user_id', $user->id)
                           ->orWhere('to_user_id', $user->id)
                           ->delete();

                    // Delete tutor record
                    $tutor->delete();
                }
            }

            // Finally delete the user
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($user->role) . ' deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete multiple users
     */
    public function bulkDeleteUsers(Request $request)
    {
        try {
            $userIds = $request->input('user_ids', []);

            if (empty($userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users selected for deletion'
                ], 400);
            }

            DB::beginTransaction();

            $deletedCount = 0;
            $errors = [];

            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);

                    if (!$user) {
                        $errors[] = "User with ID {$userId} not found";
                        continue;
                    }

                    // Check if user is student or tutor (not admin)
                    if (!in_array($user->role, ['student', 'tutor'])) {
                        $errors[] = "Cannot delete admin user: {$user->full_name}";
                        continue;
                    }

                    // Delete related records based on user role
                    if ($user->role === 'student') {
                        $student = Student::where('user_id', $user->id)->first();
                        if ($student) {
                            BookingSession::where('student_id', $student->id)->delete();
                            Feedback::where('from_user_id', $user->id)
                                   ->orWhere('to_user_id', $user->id)
                                   ->delete();
                            $student->delete();
                        }
                    } elseif ($user->role === 'tutor') {
                        $tutor = Tutor::where('user_id', $user->id)->first();
                        if ($tutor) {
                            BookingSession::where('tutor_id', $tutor->id)->delete();
                            Feedback::where('from_user_id', $user->id)
                                   ->orWhere('to_user_id', $user->id)
                                   ->delete();
                            $tutor->delete();
                        }
                    }

                    $user->delete();
                    $deletedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Error deleting user {$userId}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "{$deletedCount} user(s) deleted successfully";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error during bulk deletion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get report data for the specified date range
     */
    public function getReportData(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date'
            ]);

            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            // Session Analytics
            $sessions = $this->getSessionAnalytics($fromDate, $toDate);

            // Popular Subjects
            $popularSubjects = $this->getPopularSubjects($fromDate, $toDate);

            // Peak Hours
            $peakHours = $this->getPeakHours($fromDate, $toDate);

            // Top Tutors
            $topTutors = $this->getTopTutors($fromDate, $toDate);

            return response()->json([
                'success' => true,
                'data' => [
                    'sessions' => $sessions,
                    'popular_subjects' => $popularSubjects,
                    'peak_hours' => $peakHours,
                    'top_tutors' => $topTutors
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Report generation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error generating report: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSessionAnalytics($fromDate, $toDate)
    {


        // Use session_date instead of created_at for filtering since that's more relevant
        $completed = BookingSession::where('status', 'past')
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->count();

        $cancelled = BookingSession::where('status', 'cancelled')
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->count();

        $pending = BookingSession::whereIn('status', ['pending', 'future'])
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->count();

        // Also get ongoing sessions
        $ongoing = BookingSession::where('status', 'ongoing')
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->count();



        return [
            'completed' => $completed,
            'cancelled' => $cancelled,
            'pending' => $pending + $ongoing // Combine pending and ongoing
        ];
    }

    private function getPopularSubjects($fromDate, $toDate)
    {
        $subjects = BookingSession::select('subject_name', DB::raw('count(*) as count'))
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->whereNotNull('subject_name')
            ->groupBy('subject_name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $total = BookingSession::whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])->count();



        return $subjects->map(function ($subject) use ($total) {
            return [
                'name' => $subject->subject_name ?? 'Unknown',
                'count' => $subject->count,
                'total' => $total
            ];
        })->toArray();
    }

    private function getPeakHours($fromDate, $toDate)
    {
        // Since session_time is a string, we need to parse it differently
        $sessions = BookingSession::select('session_time')
            ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->get();



        $hourCounts = [];

        foreach ($sessions as $session) {
            $sessionTime = $session->session_time;

            // Handle the seeder format: "10:00 AM - 11:00 AM"
            // Extract all time components from the string

            // Pattern for "10:00 AM - 11:00 AM" format
            if (preg_match_all('/(\d{1,2}):(\d{2})\s*(AM|PM)/i', $sessionTime, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $hour = (int)$match[1];
                    $ampm = strtoupper($match[3]);

                    // Convert to 24-hour format
                    if ($ampm === 'PM' && $hour !== 12) {
                        $hour += 12;
                    } elseif ($ampm === 'AM' && $hour === 12) {
                        $hour = 0;
                    }

                    if ($hour >= 0 && $hour <= 23) {
                        $hourCounts[$hour] = ($hourCounts[$hour] ?? 0) + 1;
                    }
                }
            }
            // Fallback: try other formats
            else {
                // Split by common separators for other formats
                $times = preg_split('/[,;-]/', $sessionTime);

                foreach ($times as $time) {
                    $time = trim($time);
                    $hour = null;

                    // Pattern 1: "14:00" or "14:30"
                    if (preg_match('/^(\d{1,2}):/', $time, $matches)) {
                        $hour = (int)$matches[1];
                    }
                    // Pattern 2: "2 PM" or "2:00 PM"
                    elseif (preg_match('/^(\d{1,2})(?::\d{2})?\s*(AM|PM)/i', $time, $matches)) {
                        $hour = (int)$matches[1];
                        if (strtoupper($matches[2]) === 'PM' && $hour !== 12) {
                            $hour += 12;
                        } elseif (strtoupper($matches[2]) === 'AM' && $hour === 12) {
                            $hour = 0;
                        }
                    }
                    // Pattern 3: Just a number like "14" or "2"
                    elseif (preg_match('/^(\d{1,2})$/', $time, $matches)) {
                        $hour = (int)$matches[1];
                    }

                    if ($hour !== null && $hour >= 0 && $hour <= 23) {
                        $hourCounts[$hour] = ($hourCounts[$hour] ?? 0) + 1;
                    }
                }
            }
        }

        // Sort by count and take top 5
        arsort($hourCounts);
        $topHours = array_slice($hourCounts, 0, 5, true);

        $result = [];
        foreach ($topHours as $hour => $count) {
            $result[] = [
                'hour' => $hour,
                'count' => $count
            ];
        }

        return $result;
    }

    private function getTopTutors($fromDate, $toDate)
    {
        // Get all tutors with their basic info
        $tutors = Tutor::with('user')->get();



        $tutorData = [];

        foreach ($tutors as $tutor) {
            // Get sessions for this tutor in the date range (use all statuses for more data)
            $sessions = BookingSession::where('tutor_id', $tutor->id)
                ->whereIn('status', ['past', 'ongoing', 'future', 'pending']) // Include all sessions
                ->whereBetween('session_date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->get();

            $sessionsCount = $sessions->count();
            $earnings = $sessions->sum('total_price');

            // Only include tutors with some activity or rating
            if ($sessionsCount > 0 || ($tutor->rating && $tutor->rating > 0)) {
                $tutorData[] = [
                    'name' => $tutor->user->full_name ?? 'N/A',
                    'rating' => $tutor->rating ?? 0,
                    'sessions_count' => $sessionsCount,
                    'earnings' => $earnings
                ];
            }
        }



        // Sort by rating first, then by sessions count
        usort($tutorData, function($a, $b) {
            if ($a['rating'] == $b['rating']) {
                return $b['sessions_count'] - $a['sessions_count'];
            }
            return $b['rating'] <=> $a['rating'];
        });

        // Return top 10
        return array_slice($tutorData, 0, 10);
    }

    /**
     * Export report data as CSV or PDF
     */
    public function exportReport(Request $request, $format)
    {
        try {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            if ($format === 'csv') {
                return $this->exportCSV($fromDate, $toDate);
            } elseif ($format === 'pdf') {
                return $this->exportPDF($fromDate, $toDate);
            }

            return response()->json(['error' => 'Invalid format'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error exporting report: ' . $e->getMessage()
            ], 500);
        }
    }

    private function exportCSV($fromDate, $toDate)
    {
        $sessions = $this->getSessionAnalytics($fromDate, $toDate);
        $popularSubjects = $this->getPopularSubjects($fromDate, $toDate);
        $topTutors = $this->getTopTutors($fromDate, $toDate);

        $filename = 'report_' . $fromDate->format('Y-m-d') . '_to_' . $toDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sessions, $popularSubjects, $topTutors) {
            $file = fopen('php://output', 'w');

            // Session Analytics
            fputcsv($file, ['Session Analytics']);
            fputcsv($file, ['Type', 'Count']);
            fputcsv($file, ['Completed', $sessions['completed']]);
            fputcsv($file, ['Cancelled', $sessions['cancelled']]);
            fputcsv($file, ['Pending', $sessions['pending']]);
            fputcsv($file, []);

            // Popular Subjects
            fputcsv($file, ['Popular Subjects']);
            fputcsv($file, ['Subject', 'Count', 'Percentage']);
            foreach ($popularSubjects as $subject) {
                $percentage = $subject['total'] > 0 ? round(($subject['count'] / $subject['total']) * 100, 2) : 0;
                fputcsv($file, [$subject['name'], $subject['count'], $percentage . '%']);
            }
            fputcsv($file, []);

            // Top Tutors
            fputcsv($file, ['Top Tutors']);
            fputcsv($file, ['Name', 'Rating', 'Sessions', 'Earnings']);
            foreach ($topTutors as $tutor) {
                fputcsv($file, [
                    $tutor['name'],
                    $tutor['rating'],
                    $tutor['sessions_count'],
                    'RM ' . number_format($tutor['earnings'], 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPDF($fromDate, $toDate)
    {
        $sessions = $this->getSessionAnalytics($fromDate, $toDate);
        $popularSubjects = $this->getPopularSubjects($fromDate, $toDate);
        $topTutors = $this->getTopTutors($fromDate, $toDate);

        // Calculate success rate
        $total = $sessions['completed'] + $sessions['cancelled'];
        $successRate = $total > 0 ? round(($sessions['completed'] / $total) * 100, 2) : 0;

        // Create HTML content for PDF
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Peer Tutor System Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; font-size: 28px; }
        .header p { color: #666; margin: 5px 0; font-size: 16px; }
        .section { margin: 30px 0; }
        .section h2 { color: #4f46e5; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; font-size: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0; }
        .stat-card { background: #f8fafc; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-number { font-size: 32px; font-weight: bold; color: #1f2937; margin: 10px 0; }
        .stat-label { color: #6b7280; font-size: 14px; text-transform: uppercase; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f8fafc; font-weight: 600; color: #374151; }
        .rank { background: #4f46e5; color: white; border-radius: 50%; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; }
        .rating { color: #f59e0b; }
        .footer { margin-top: 50px; text-align: center; color: #6b7280; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Peer Tutor System Report</h1>
        <p>Analytics Report for Period: ' . $fromDate->format('M d, Y') . ' to ' . $toDate->format('M d, Y') . '</p>
        <p>Generated on: ' . now()->format('M d, Y \a\t H:i A') . '</p>
    </div>

    <div class="section">
        <h2>📊 Session Analytics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Completed Sessions</div>
                <div class="stat-number" style="color: #10b981;">' . $sessions['completed'] . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Cancelled Sessions</div>
                <div class="stat-number" style="color: #ef4444;">' . $sessions['cancelled'] . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Sessions</div>
                <div class="stat-number" style="color: #f59e0b;">' . $sessions['pending'] . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Success Rate</div>
                <div class="stat-number" style="color: #3b82f6;">' . $successRate . '%</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>📚 Popular Subjects</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Subject</th>
                    <th>Sessions</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($popularSubjects as $index => $subject) {
            $percentage = $subject['total'] > 0 ? round(($subject['count'] / $subject['total']) * 100, 2) : 0;
            $html .= '<tr>
                <td><span class="rank">' . ($index + 1) . '</span></td>
                <td>' . htmlspecialchars($subject['name']) . '</td>
                <td>' . $subject['count'] . '</td>
                <td>' . $percentage . '%</td>
            </tr>';
        }

        $html .= '</tbody>
        </table>
    </div>

    <div class="section">
        <h2>⭐ Top Performing Tutors</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Tutor Name</th>
                    <th>Rating</th>
                    <th>Sessions</th>
                    <th>Earnings</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($topTutors as $index => $tutor) {
            $stars = str_repeat('★', floor($tutor['rating'])) . str_repeat('☆', 5 - floor($tutor['rating']));
            $html .= '<tr>
                <td><span class="rank">' . ($index + 1) . '</span></td>
                <td>' . htmlspecialchars($tutor['name']) . '</td>
                <td><span class="rating">' . $stars . '</span> (' . number_format($tutor['rating'], 1) . ')</td>
                <td>' . $tutor['sessions_count'] . '</td>
                <td>RM ' . number_format($tutor['earnings'], 2) . '</td>
            </tr>';
        }

        $html .= '</tbody>
        </table>
    </div>

    <div class="footer">
        <p>This report was automatically generated by the Peer Tutor Management System</p>
        <p>© ' . date('Y') . ' Peer Tutor System. All rights reserved.</p>
    </div>
</body>
</html>';

        $filename = 'report_' . $fromDate->format('Y-m-d') . '_to_' . $toDate->format('Y-m-d') . '.html';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
