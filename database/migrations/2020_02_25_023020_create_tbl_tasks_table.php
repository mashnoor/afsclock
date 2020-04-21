<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_tasks')) {
            Schema::create('tbl_tasks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('reference');
                $table->integer('assigned_by');
                $table->text('title', 65535);
                $table->text('description');
                $table->timestamp('deadline');
								$table->boolean('done_status');
                $table->timestamp('finishdate')->nullable();
                $table->text('comment', 65535)->nullable();
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
		Schema::drop('tbl_tasks');
	}

}
