<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whitelisted_ips', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('whitelisted_ips', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
