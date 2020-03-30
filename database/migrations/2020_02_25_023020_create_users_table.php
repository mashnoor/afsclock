<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reference')->nullable();
			$table->string('idno', 11)->nullable();
			$table->string('name')->nullable()->default('');
			$table->string('email')->nullable()->default('');
			$table->integer('role_id')->nullable();
			$table->integer('acc_type')->nullable();
			$table->integer('status')->nullable();
			$table->string('password')->nullable();
			$table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
