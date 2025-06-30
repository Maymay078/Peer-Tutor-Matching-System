<?php

namespace App\Notifications;

use App\Models\BookingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class SessionRejectedNotification extends Notification
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
            'tutor_name' => $this->bookingSession->tutor->user->full_name ?? '',
            'subject' => $this->bookingSession->subject_name,
            'date' => $this->bookingSession->session_date,
            'time' => $this->bookingSession->session_time,
            'status' => 'rejected',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
