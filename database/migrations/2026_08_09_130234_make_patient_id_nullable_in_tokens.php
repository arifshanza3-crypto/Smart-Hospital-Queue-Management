<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            // Drop foreign key first if exists
            $table->dropForeign(['patient_id']);
            // Make patient_id nullable
            $table->string('patient_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            $table->string('patient_id')->nullable(false)->change();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
        });
    }
};