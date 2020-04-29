<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('daily_breaks')) {
            Schema::create('daily_breaks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('reference', 11)->nullable()->default('');
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();

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
        Schema::dropIfExists('daily_breaks');
    }
}
