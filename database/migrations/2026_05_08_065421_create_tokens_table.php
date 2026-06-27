<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_number')->unique();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->string('department');
            $table->enum('status', ['waiting', 'calling', 'serving', 'completed', 'missed'])->default('waiting');
            $table->integer('estimated_time')->default(15);
            $table->integer('position')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tokens');
    }
};