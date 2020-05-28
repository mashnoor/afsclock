<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskExtensionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if(!Schema::hasTable('task_extension')) {
        Schema::create('task_extension', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('task_id');
            $table->timestamp('new_deadline');
            $table->text('reason');
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
        Schema::dropIfExists('task_extension');
    }
}
