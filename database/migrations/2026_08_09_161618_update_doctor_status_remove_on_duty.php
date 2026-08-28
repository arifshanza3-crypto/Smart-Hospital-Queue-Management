<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Update existing 'on_duty' to 'active'
        \DB::table('doctors')
            ->where('status', 'on_duty')
            ->update(['status' => 'active']);
        
        // ✅ Update existing 'on duty' to 'active' (if any)
        \DB::table('doctors')
            ->where('status', 'on duty')
            ->update(['status' => 'active']);

        // ✅ Change enum column to only active/inactive
        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'on_duty'])->default('active')->change();
        });
    }
};