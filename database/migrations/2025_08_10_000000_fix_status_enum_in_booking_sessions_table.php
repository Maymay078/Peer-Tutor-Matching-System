<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixStatusEnumInBookingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite workaround to update enum column
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->enum('status_new', ['ongoing', 'past', 'future', 'pending', 'cancelled'])->default('pending')->after('session_time');
        });

        // Copy data from old status to new status
        DB::statement('UPDATE booking_sessions SET status_new = status');

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes by restoring old enum
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->enum('status_new', ['ongoing', 'past', 'future'])->default('future')->after('session_time');
        });

        DB::statement('UPDATE booking_sessions SET status_new = status');

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }
}
