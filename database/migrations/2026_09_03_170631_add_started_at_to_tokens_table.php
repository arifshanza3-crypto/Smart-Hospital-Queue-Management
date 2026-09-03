<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            // ✅ Add started_at column if not exists
            if (!Schema::hasColumn('tokens', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('called_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            if (Schema::hasColumn('tokens', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};