<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('daily_attendance')) {
        Schema::create('daily_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reference')->nullable();
            $table->string('idno', 11)->nullable()->default('');
            $table->string('employee')->nullable()->default('');
            $table->string('totalhours')->nullable()->default('');
            $table->string('total_break_hours')->nullable()->default('');
            $table->string('status_timein')->nullable()->default('');
            $table->string('status_timeout')->nullable()->default('');
            $table->string('reason')->nullable()->default('');
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_attendance');
    }
}
