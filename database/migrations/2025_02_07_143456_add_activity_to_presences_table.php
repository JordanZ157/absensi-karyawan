<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->string('activity')->nullable()->after('presence_out_time');
        });
    }
    
    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn('activity');
        });
    }
};