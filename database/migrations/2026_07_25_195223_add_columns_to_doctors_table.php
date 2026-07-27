<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // ✅ 'photo' column ko reference nahi karein
            if (!Schema::hasColumn('doctors', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
            
            if (!Schema::hasColumn('doctors', 'shift')) {
                $table->string('shift')->nullable();
            }
            
            if (!Schema::hasColumn('doctors', 'experience')) {
                $table->integer('experience')->nullable();
            }
            
            if (!Schema::hasColumn('doctors', 'fee')) {
                $table->decimal('fee', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('doctors', 'display_order')) {
                $table->integer('display_order')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'shift',
                'experience',
                'fee',
                'display_order'
            ]);
        });
    }
};