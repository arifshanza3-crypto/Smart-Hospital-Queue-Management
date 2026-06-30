<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Rename 'name' to 'full_name' if column exists
            if (Schema::hasColumn('users', 'name') && !Schema::hasColumn('users', 'full_name')) {
                $table->renameColumn('name', 'full_name');
            }

            // ✅ Add 'employee_id' column if not exists
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->unique()->after('email');
            }

            // ✅ Add 'status' column if not exists
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('role');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback if needed
            if (Schema::hasColumn('users', 'employee_id')) {
                $table->dropColumn('employee_id');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'full_name') && !Schema::hasColumn('users', 'name')) {
                $table->renameColumn('full_name', 'name');
            }
        });
    }
};