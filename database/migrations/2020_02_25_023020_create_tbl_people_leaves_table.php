<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblPeopleLeavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_people_leaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reference')->nullable();
			$table->string('idno', 11)->nullable();
			$table->string('employee')->nullable()->default('');
			$table->integer('typeid')->nullable();
			$table->string('type')->nullable()->default('');
			$table->date('leavefrom')->nullable();
			$table->date('leaveto')->nullable();
			$table->date('returndate')->nullable();
			$table->string('reason')->nullable()->default('');
			$table->string('status')->nullable();
			$table->string('comment')->nullable();
			$table->integer('archived')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_people_leaves');
	}

}
