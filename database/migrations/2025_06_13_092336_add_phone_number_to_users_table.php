<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration is redundant because 'phone_number' column exists in the initial users table migration.
// So, this migration will be disabled by making the class empty.

return new class extends Migration
{
    public function up()
    {
        // Disabled migration
    }

    public function down()
    {
        // Disabled migration
    }
};
