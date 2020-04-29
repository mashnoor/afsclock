<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('daily_entries')) {
            Schema::create('daily_entries', function (Blueprint $table) {
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
        Schema::dropIfExists('daily_entries');
    }
}
