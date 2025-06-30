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
        if (DB::getDriverName() === 'sqlite') {
            // SQLite does not support altering column types easily
            // So we recreate the table or use raw SQL to drop and add column
            // For simplicity, we rename the old column, add new column, copy data, drop old column

            Schema::table('users', function (Blueprint $table) {
                $table->string('role_new')->default('student')->after('password');
            });

            DB::statement('UPDATE users SET role_new = role');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('role_new', 'role');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('student')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role_new', ['student', 'tutor'])->default('student')->after('password');
            });

            DB::statement('UPDATE users SET role_new = role');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('role_new', 'role');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['student', 'tutor'])->default('student')->change();
            });
        }
    }
};
