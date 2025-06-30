<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateNullIsManualBookingInBookingSessions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('booking_sessions')
            ->whereNull('is_manual_booking')
            ->update(['is_manual_booking' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
}
