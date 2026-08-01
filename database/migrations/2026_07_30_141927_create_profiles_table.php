<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('hostel')->nullable();
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable(); // Changed to text for longer bio
            $table->string('employee_id')->nullable(); // Only for staff
            $table->string('department')->nullable(); // Only for staff
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'pending', 'inactive'])->default('active');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            // Added index for better performance
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}