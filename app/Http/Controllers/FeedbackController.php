<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\Feedback;
use App\Models\BookingSession;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    /**
     * Display the feedback page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $feedbacks = [];
        $tutors = [];

        if ($user && $user->role === 'tutor') {
            // Fetch feedback received by the tutor from students
            $feedbacks = Feedback::where('to_user_id', $user->id)
                ->with('fromUser')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($feedback) {
                    return (object)[
                        'rating' => $feedback->rating,
                        'comment' => $feedback->comment,
                        'from_user_name' => $feedback->fromUser ? $feedback->fromUser->full_name : 'N/A',
                        'from_user_profile_image' => $feedback->fromUser ? $feedback->fromUser->profile_image : null,
                    ];
                })->toArray();

            Log::info('Feedback count for user '.$user->id.': '.count($feedbacks));
        } else if ($user && $user->role === 'student') {
            // Get tutors that the student has had sessions with (completed at least 1 day ago)
            $oneDayAgo = now()->subDay();
            
            $tutors = Tutor::with(['user', 'feedbacks' => function($query) use ($user) {
                $query->where('from_user_id', $user->id);
            }])
            ->whereHas('bookingSessions', function($query) use ($user, $oneDayAgo) {
                $query->where('student_id', $user->student->id)
                    ->where('status', 'completed')
                    ->where('updated_at', '<=', $oneDayAgo); // Session completed at least 1 day ago
            })
            ->get();

            // Get feedback given by the student to tutors
            $feedbacks = Feedback::where('from_user_id', $user->id)
                ->with('toUser')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($feedback) {
                    return [
                        'rating' => $feedback->rating,
                        'comment' => $feedback->comment,
                        'to_user_name' => $feedback->toUser ? $feedback->toUser->full_name : 'N/A',
                        'to_user_profile_image' => $feedback->toUser ? $feedback->toUser->profile_image : null,
                        'created_at' => $feedback->created_at->format('d M Y'),
                    ];
                });
        }

        return view('feedback', compact('feedbacks', 'tutors'));
    }

    /**
     * Handle feedback form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $tutorId = $request->input('tutor_id');
        $rating = $request->input("rating_{$tutorId}");
        $comment = $request->input("comments_{$tutorId}");

        $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            "rating_{$tutorId}" => 'required|integer|min:1|max:5',
            "comments_{$tutorId}" => 'required|string|max:1000',
        ]);

        // Check if student has completed sessions with this tutor
        $hasCompletedSessions = BookingSession::where('student_id', $user->student->id)
            ->where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedSessions) {
            return redirect()->route('feedback')
                ->with('error', 'You can only provide feedback for tutors you have had completed sessions with.');
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('from_user_id', $user->id)
            ->where('to_user_id', Tutor::find($tutorId)->user->id)
            ->first();

        if ($existingFeedback) {
            // Update existing feedback
            $existingFeedback->update([
                'rating' => $rating,
                'comment' => $comment,
            ]);
            $message = 'Your feedback has been updated!';
        } else {
            // Create new feedback
            Feedback::create([
                'from_user_id' => $user->id,
                'to_user_id' => Tutor::find($tutorId)->user->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);
            $message = 'Thank you for your feedback!';
        }

        // Update tutor's average rating
        $tutor = Tutor::find($tutorId);
        $avgRating = Feedback::where('to_user_id', $tutor->user->id)->avg('rating');
        $tutor->rating = round($avgRating, 1);
        $tutor->save();

        return redirect()->route('feedback')->with('success', $message);
    }
}
