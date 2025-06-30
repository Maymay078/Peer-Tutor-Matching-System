<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsManualBookingToBookingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->boolean('is_manual_booking')->default(false)->after('status')->comment('Indicates if booking was created manually via session scheduling page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_sessions', function (Blueprint $table) {
            $table->dropColumn('is_manual_booking');
        });
    }
}
