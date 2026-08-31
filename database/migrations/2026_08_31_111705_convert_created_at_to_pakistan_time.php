<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Sab existing tokens ka time +5 hours karein (UTC to Pakistan)
        DB::statement("UPDATE tokens SET created_at = DATE_ADD(created_at, INTERVAL 5 HOUR)");
    }

    public function down(): void
    {
        // Rollback: wapas -5 hours
        DB::statement("UPDATE tokens SET created_at = DATE_SUB(created_at, INTERVAL 5 HOUR)");
    }
};