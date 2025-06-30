<?php

namespace App\Notifications;

use App\Models\BookingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookingSessionRequested extends Notification
{
    use Queueable;

    public $bookingSession;

    public function __construct(BookingSession $bookingSession)
    {
        $this->bookingSession = $bookingSession;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'booking_session_id' => $this->bookingSession->id,
            'student_name' => $this->bookingSession->student->user->full_name ?? '',
            'student_id' => $this->bookingSession->student->id ?? '',
            'subject' => $this->bookingSession->subject_name,
            'date' => $this->bookingSession->session_date,
            'time' => $this->bookingSession->session_time,
            'total_price' => $this->bookingSession->total_price,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
