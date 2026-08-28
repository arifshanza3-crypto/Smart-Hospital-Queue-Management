<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            // ✅ Add phone column if not exists
            if (!Schema::hasColumn('tokens', 'phone')) {
                $table->string('phone')->nullable()->after('patient_name');
            }
            
            // ✅ Add department column if not exists
            if (!Schema::hasColumn('tokens', 'department')) {
                $table->string('department')->default('General')->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            if (Schema::hasColumn('tokens', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('tokens', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};