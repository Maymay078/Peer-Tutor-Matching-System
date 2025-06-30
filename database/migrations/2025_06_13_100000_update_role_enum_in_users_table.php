<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // For SQLite, altering enum is tricky, so we use raw SQL
        if (DB::getDriverName() === 'sqlite') {
            // SQLite does not support altering enum, so recreate the table or skip
            // For simplicity, skip in SQLite or handle manually
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['student', 'tutor', 'admin'])->default('student')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            // Skip or handle manually
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['student', 'tutor'])->default('student')->change();
            });
        }
    }
};
