<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToQueueReportsTable extends Migration
{
    public function up()
    {
        Schema::table('queue_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('queue_reports', 'type')) {
                $table->string('type')->default('physical')->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('queue_reports', function (Blueprint $table) {
            if (Schema::hasColumn('queue_reports', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}