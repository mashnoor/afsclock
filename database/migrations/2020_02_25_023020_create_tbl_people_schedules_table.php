<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblPeopleSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_people_schedules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reference')->nullable();
			$table->string('idno', 11)->nullable();
			$table->string('employee')->nullable();
			$table->text('intime', 65535)->nullable();
			$table->text('outime', 65535)->nullable();
			$table->date('datefrom')->nullable();
			$table->date('dateto')->nullable();
			$table->integer('hours')->nullable();
			$table->string('restday')->nullable();
			$table->integer('archive')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_people_schedules');
	}

}
