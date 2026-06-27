<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueueReportsTable extends Migration
{
    public function up()
    {
        Schema::create('queue_reports', function (Blueprint $table) {
            $table->id();
            $table->string('token_number')->unique();
            $table->string('patient_name');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('doctor_name');
            $table->string('department');
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'cancelled'])->default('waiting');
            $table->integer('waiting_time')->default(0)->comment('Waiting time in minutes');
            $table->integer('service_time')->default(0)->comment('Service time in minutes');
            $table->timestamp('completed_at')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queue_reports');
    }
}