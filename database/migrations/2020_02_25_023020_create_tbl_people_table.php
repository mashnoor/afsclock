<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblPeopleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_people', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('firstname')->nullable();
			$table->string('mi')->nullable()->default('');
			$table->string('lastname')->nullable();
			$table->integer('age')->nullable();
			$table->string('gender')->nullable()->default('');
			$table->string('emailaddress')->nullable()->default('');
			$table->string('civilstatus')->nullable()->default('');
			$table->string('height')->nullable()->default('');
			$table->string('weight')->nullable()->default('');
			$table->string('mobileno')->nullable()->default('');
			$table->string('birthday')->nullable()->default('');
			$table->string('nationalid')->nullable();
			$table->string('birthplace')->nullable()->default('');
			$table->string('homeaddress')->nullable()->default('');
			$table->string('employmentstatus', 11)->nullable()->default('');
			$table->string('employmenttype', 11)->nullable()->default('');
			$table->string('avatar')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_people');
	}

}
