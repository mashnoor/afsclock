<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblPeopleAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_people_attendance', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reference')->nullable();
			$table->string('idno', 11)->nullable()->default('');
			$table->date('date')->nullable();
			$table->string('employee')->nullable()->default('');
			$table->string('timein')->nullable();
			$table->string('timeout')->nullable();
			$table->string('break_in')->nullable();
			$table->string('break_out')->nullable();
			$table->string('totalhours')->nullable()->default('');
			$table->string('status_timein')->nullable()->default('');
			$table->string('status_timeout')->nullable()->default('');
			$table->string('reason')->nullable()->default('');
			$table->string('comment')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_people_attendance');
	}

}
