<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingSession;
use Carbon\Carbon;

class UpdateBookingSessionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update booking session statuses to past if session date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Update sessions to past if date has passed and status is pending or confirmed
        $sessionsToPast = BookingSession::whereIn('status', ['pending', 'confirmed'])
            ->where('session_date', '<', $now->toDateString())
            ->where('is_manual_booking', false)
            ->get();

        foreach ($sessionsToPast as $session) {
            $session->status = 'past';
            $session->save();
        }

        // Update sessions to future if date is in the future and status is pending or future
        $sessionsToFuture = BookingSession::whereIn('status', ['pending', 'future'])
            ->where('session_date', '>=', $now->toDateString())
            ->where('is_manual_booking', false)
            ->get();

        foreach ($sessionsToFuture as $session) {
            $session->status = 'future';
            $session->save();
        }

        $this->info('Booking session statuses updated successfully.');
    }
}
