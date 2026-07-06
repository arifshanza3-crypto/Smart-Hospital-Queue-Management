<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
   public function up()
{
    Schema::create('doctors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->nullable();  // ✅ Make nullable
        $table->string('specialization');
        $table->string('qualification')->nullable();
        $table->string('email')->unique();
        $table->string('phone');
        $table->enum('status', ['active', 'inactive', 'on_duty'])->default('active');
        $table->string('photo')->nullable();
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}