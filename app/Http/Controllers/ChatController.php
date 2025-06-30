<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * List chats for the authenticated user.
     * For students, list chats with tutors.
     * For tutors, list chats with students.
     */
    public function listChats(): JsonResponse
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            $chats = Chat::with('tutor')
                ->where('student_id', $user->id)
                ->get()
                ->filter(fn($chat) => $chat->tutor && $chat->tutor->role !== 'admin')
                ->map(fn($chat) => [
                    'id' => $chat->id,
                    'tutor_id' => $chat->tutor_id,
                    'name' => $chat->tutor->full_name ?? 'Tutor',
                    'tutor_details' => [
                        'name' => $chat->tutor->full_name ?? '',
                        'email' => $chat->tutor->email ?? '',
                        'subject_expertise' => json_decode($chat->tutor->tutor->expertise ?? '', true) ?: [],
                        'ratings' => $chat->tutor->tutor->rating ?? 0,
                        'payment_method' => $chat->tutor->tutor->payment_details ?? '',
                    ],
                ]);
        } elseif ($user->role === 'tutor') {
            $chats = Chat::with('student')
                ->where('tutor_id', $user->id)
                ->get()
                ->filter(fn($chat) => $chat->student && $chat->student->role !== 'admin')
                ->map(fn($chat) => [
                    'id' => $chat->id,
                    'student_id' => $chat->student_id,
                    'name' => $chat->student->full_name ?? 'Student',
                    'student_details' => [
                        'name' => $chat->student->full_name ?? '',
                        'email' => $chat->student->email ?? '',
                        'major' => $chat->student->student->major ?? '',
                        'year' => $chat->student->student->year ?? '',
                        'preferred_subjects' => $chat->student->student->preferred_course ?? [],
                    ],
                ]);
        } else {
            $chats = collect();
        }

        return response()->json($chats);
    }

    /**
     * Show chat for student or tutor based on role.
     * For students, accept tutor_id query parameter.
     * For tutors, accept student_id query parameter.
     */
    public function showStudentChat(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            abort(403);
        }

        $tutorId = $request->query('tutor_id');
        $chatId = $request->query('chat_id');
        $chat = null;

        if ($chatId) {
            $chat = Chat::with(['student', 'tutor', 'messages.sender'])->find($chatId);
        } elseif ($tutorId) {
            $chat = Chat::with(['student', 'tutor', 'messages.sender'])
                ->where('student_id', $user->id)
                ->where('tutor_id', $tutorId)
                ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'student_id' => $user->id,
                    'tutor_id' => $tutorId,
                ]);
                $chat->load(['student', 'tutor', 'messages.sender']);
            }
        }

        $studentDetails = null;
        $tutorDetails = null;
        $messages = collect();

        if ($chat) {
            $studentDetails = [
                'name' => $chat->student->full_name,
                'email' => $chat->student->email,
                'major' => $chat->student->student->major ?? '',
                'year' => $chat->student->student->year ?? '',
                'preferred_subjects' => $chat->student->student->preferred_course ?? [],
            ];

            $tutorDetails = [
                'name' => $chat->tutor->full_name,
                'email' => $chat->tutor->email,
                'subject_expertise' => json_decode($chat->tutor->tutor->expertise ?? '', true) ?: [],
                'ratings' => $chat->tutor->tutor->rating ?? 0,
                'payment_method' => $chat->tutor->tutor->payment_details ?? '',
            ];

            $messages = $chat->messages()->with('sender')->orderBy('created_at')->get();
        }

        return view('chat-student', compact('studentDetails', 'tutorDetails', 'messages', 'chat'));
    }

    public function showTutorChat(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'tutor') {
            abort(403);
        }

        $studentId = $request->query('student_id');
        $chatId = $request->query('chat_id');
        $chat = null;

        if ($chatId) {
            $chat = Chat::with(['student', 'tutor', 'messages.sender'])->find($chatId);
        } elseif ($studentId) {
            $chat = Chat::with(['student', 'tutor', 'messages.sender'])
                ->where('student_id', $studentId)
                ->where('tutor_id', $user->id)
                ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'student_id' => $studentId,
                    'tutor_id' => $user->id,
                ]);
                $chat->load(['student', 'tutor', 'messages.sender']);
            }
        }

        $studentDetails = null;
        $tutorDetails = null;
        $messages = collect();

        if ($chat) {
            $studentDetails = [
                'name' => $chat->student->full_name,
                'email' => $chat->student->email,
                'major' => $chat->student->student->major ?? '',
                'year' => $chat->student->student->year ?? '',
                'preferred_subjects' => $chat->student->student->preferred_course ?? [],
            ];

            $tutorDetails = [
                'name' => $chat->tutor->full_name,
                'email' => $chat->tutor->email,
                'subject_expertise' => json_decode($chat->tutor->tutor->expertise ?? '', true) ?: [],
                'ratings' => $chat->tutor->tutor->rating ?? 0,
                'payment_method' => $chat->tutor->tutor->payment_details ?? '',
            ];

            $messages = $chat->messages()->with('sender')->orderBy('created_at')->get();
        }

        return view('chat-tutor', compact('studentDetails', 'tutorDetails', 'messages', 'chat'));
    }

    /**
     * Send a message in a chat.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message_text' => 'required|string',
        ]);

        $user = Auth::user();

        $chat = Chat::findOrFail($request->chat_id);

        if ($user->id !== $chat->student_id && $user->id !== $chat->tutor_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message_text' => $request->message_text,
        ]);

        return response()->json($message);
    }

    /**
     * Get messages for a chat.
     */
    public function getMessages($chatId): JsonResponse
    {
        $user = Auth::user();
        $chat = Chat::with('messages.sender')->find($chatId);

        if (!$chat) {
            return response()->json(['error' => 'Chat not found'], 404);
        }

        if ($user->id !== $chat->student_id && $user->id !== $chat->tutor_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()->with('sender')->orderBy('created_at')->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * API to get or create chat with tutor for student.
     */
    public function getOrCreateChatWithTutor(Request $request, $tutor_id): JsonResponse
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat = Chat::with(['student', 'tutor', 'messages.sender'])
            ->where('student_id', $user->id)
            ->where('tutor_id', $tutor_id)
            ->first();

        if (!$chat) {
            $chat = Chat::create([
                'student_id' => $user->id,
                'tutor_id' => $tutor_id,
            ]);
            $chat->load(['student', 'tutor', 'messages.sender']);
        }

        return response()->json([
            'chat_id' => $chat->id,
            'studentDetails' => [
                'name' => $chat->student->full_name,
                'email' => $chat->student->email,
                'major' => $chat->student->student->major ?? '',
                'year' => $chat->student->student->year ?? '',
                'preferred_subjects' => $chat->student->student->preferred_course ?? [],
            ],
            'tutorDetails' => [
                'name' => $chat->tutor->full_name,
                'email' => $chat->tutor->email,
                'subject_expertise' => json_decode($chat->tutor->tutor->expertise ?? '', true) ?: [],
                'ratings' => $chat->tutor->tutor->rating ?? 0,
                'payment_method' => $chat->tutor->tutor->payment_details ?? '',
            ],
            'messages' => $chat->messages()->with('sender')->orderBy('created_at')->get(),
        ]);
    }

    /**
     * Search tutors by name for chat sidebar search.
     */
    public function searchTutors(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'student') {
            return response()->json([]);
        }

        $query = $request->query('q', '');
        if (empty($query)) {
            return response()->json([]);
        }

        $tutors = \App\Models\Tutor::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('full_name', 'like', '%' . $query . '%');
            })
            ->get()
            ->filter(fn($tutor) => $tutor->user && $tutor->user->role !== 'admin')
            ->map(fn($tutor) => [
                'id' => $tutor->id,
                'name' => $tutor->user->full_name ?? '',
                'tutor_id' => $tutor->id,
                'tutor_details' => [
                    'name' => $tutor->user->full_name ?? '',
                    'email' => $tutor->user->email ?? '',
                    'subject_expertise' => json_decode($tutor->expertise ?? '', true) ?: [],
                    'ratings' => $tutor->rating ?? 0,
                    'payment_method' => $tutor->payment_details ?? '',
                ],
            ])->values();

        return response()->json($tutors);
    }

    /**
     * Search students by name for chat sidebar search (for tutors).
     */
    public function searchStudents(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'tutor') {
            return response()->json([]);
        }

        $query = $request->query('q', '');
        if (empty($query)) {
            return response()->json([]);
        }

        $students = \App\Models\Student::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('full_name', 'like', '%' . $query . '%');
            })
            ->get()
            ->filter(fn($student) => $student->user && $student->user->role !== 'admin')
            ->map(fn($student) => [
                'id' => $student->id,
                'name' => $student->user->full_name ?? '',
                'student_id' => $student->id,
                'student_details' => [
                    'name' => $student->user->full_name ?? '',
                    'email' => $student->user->email ?? '',
                    'major' => $student->major ?? '',
                    'year' => $student->year ?? '',
                    'preferred_subjects' => $student->preferred_course ?? [],
                ],
            ])->values();

        return response()->json($students);
    }
}
