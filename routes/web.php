<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home/student', [UserController::class, 'studentHome'])->name('home.student');
    Route::get('/home/tutor', [UserController::class, 'tutorHome'])->name('home.tutor');

    // New routes for tutor session requests management
    Route::get('/tutor/session-requests', [UserController::class, 'tutorSessionRequests'])->name('tutor.session.requests');
    Route::post('/tutor/session-requests/{id}/confirm', [UserController::class, 'confirmSessionRequest'])->name('tutor.session.confirm');
    Route::post('/tutor/session-requests/{id}/reject', [UserController::class, 'rejectSessionRequest'])->name('tutor.session.reject');

    // API route for calendar events
    Route::get('/api/calendar-events', [UserController::class, 'getCalendarEvents'])->name('api.calendar.events');

    // API route for live tutor search by name or subject
    Route::get('/api/search-tutors', [UserController::class, 'searchTutors'])->name('api.search.tutors');

    // Added route for profile.show to fix the error
    Route::get('/profile/{user}', [UserController::class, 'showProfile'])->name('profile.show');

    // New routes for chat and notifications UI

    // Separate chat pages for student and tutor
    Route::get('/chat/student', [ChatController::class, 'showStudentChat'])->name('chat.student');
    Route::get('/chat/tutor', [ChatController::class, 'showTutorChat'])->name('chat.tutor');

    // Old general chat route (optional: you may remove or keep for backward compatibility)
    // Route::get('/chat', [ChatController::class, 'show'])->name('chat');

    // API route to get chat list
    Route::get('/chats', [ChatController::class, 'listChats'])->name('chats.list');

    // API route to get or create chat with tutor for student
    Route::get('/api/chat-with-tutor/{tutor_id}', [ChatController::class, 'getOrCreateChatWithTutor'])->name('chat.with.tutor');

    // API route to get messages for a chat
    Route::get('/chats/{chat}/messages', [ChatController::class, 'getMessages'])->name('chats.getMessages');

    // API route to send message in chat
    Route::post('/chats/{chat}/messages', [ChatController::class, 'sendMessage'])->name('chats.sendMessage');

    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');

    // New route for session scheduling page
    Route::get('/scheduling', [\App\Http\Controllers\UserController::class, 'sessionScheduling'])->name('session.scheduling');

    // API route for booking a session
    Route::post('/api/book-session', [\App\Http\Controllers\UserController::class, 'bookSession'])->name('api.book.session');
    
    // API route for checking session availability
    Route::get('/api/check-availability', [\App\Http\Controllers\UserController::class, 'checkAvailability'])->name('api.check.availability');

    // API route for cancelling a session
    Route::post('/api/cancel-session', [\App\Http\Controllers\UserController::class, 'cancelSession'])->name('api.cancel.session');

    // New route for feedback page
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');

    // Add POST route for feedback submission
    Route::post('/feedback', [FeedbackController::class, 'submit'])->name('feedback.submit');

    // API routes for rescheduling session and fetching tutor availability for reschedule
    Route::get('/api/tutor-availability-for-reschedule', [UserController::class, 'getTutorAvailabilityForReschedule'])->name('api.tutor.availability.reschedule');
    Route::post('/api/reschedule-session', [UserController::class, 'rescheduleSession'])->name('api.reschedule.session');

    // API route for canceling a booking session
    // Route::post('/api/cancel-session/{id}', [\App\Http\Controllers\UserController_CancelSession::class, 'cancelSession'])->name('api.cancel-session');
});

// Separate registration routes for student and tutor
Route::get('/register/student', [RegisteredUserController::class, 'createStudent'])->name('register.student.form');
Route::post('/register/student', [RegisteredUserController::class, 'storeStudent'])->name('register.student');

Route::get('/register/tutor', [RegisteredUserController::class, 'createTutor'])->name('register.tutor.form');
Route::post('/register/tutor', [RegisteredUserController::class, 'storeTutor'])->name('register.tutor');

require __DIR__.'/auth.php';

use App\Http\Middleware\AdminMiddleware;

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])
        ->name('admin.dashboard')
        ->middleware(['auth', AdminMiddleware::class]);

    Route::get('/admin/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');

    // User management routes
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/users/bulk-delete', [App\Http\Controllers\AdminController::class, 'bulkDeleteUsers'])->name('admin.users.bulk-delete');

    // Report routes
    Route::post('/admin/reports/data', [App\Http\Controllers\AdminController::class, 'getReportData'])->name('admin.reports.data');
    Route::get('/admin/reports/export/{format}', [App\Http\Controllers\AdminController::class, 'exportReport'])->name('admin.reports.export');
});
