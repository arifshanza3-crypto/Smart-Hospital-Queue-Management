<?php
// database/migrations/xxxxxx_add_full_name_and_employee_id_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ full_name add karo
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->after('id')->nullable();
            }
            
            // ✅ employee_id add karo
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->unique()->after('email')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'employee_id']);
        });
    }
};