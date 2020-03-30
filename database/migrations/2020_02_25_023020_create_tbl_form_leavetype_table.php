<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblFormLeavetypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_form_leavetype', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('leavetype')->nullable();
			$table->string('limit')->nullable();
			$table->string('percalendar')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_form_leavetype');
	}

}
