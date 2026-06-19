<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Pehle check karein ke 'role' column exist karta hai ya nahi
            if (!Schema::hasColumn('users', 'status')) {
                // Remove 'after' clause agar 'role' column exist nahi karta
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}