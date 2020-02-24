<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblFormJobtitleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_form_jobtitle', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('jobtitle', 250)->nullable()->default('');
			$table->integer('dept_code')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_form_jobtitle');
	}

}
