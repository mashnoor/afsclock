<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tbl_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reference');
            $table->integer('assigned_by');
            $table->text('title');
            $table->longText('description');
            $table->date('deadline');
            $table->text('comment');
            $table->tinyInteger('done_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
