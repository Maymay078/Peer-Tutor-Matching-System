<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumInBookingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite does not support modifying enum directly, so we recreate the column
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->enum('status', ['ongoing', 'past', 'future', 'pending'])->default('pending')->after('session_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->enum('status', ['ongoing', 'past', 'future'])->default('future')->after('session_time');
        });
    }
}
