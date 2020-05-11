<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebcamDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   if(!Schema::hasTable('webcam_data')) {
        Schema::create('webcam_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reference')->nullable();
            $table->string('last_seen')->nullable();
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
        Schema::dropIfExists('webcam_data');
    }
}
