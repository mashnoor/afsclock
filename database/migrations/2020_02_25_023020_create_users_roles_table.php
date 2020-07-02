<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('users_roles')) {
            Schema::create('users_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('role_name')->nullable();
                $table->string('state', 100)->nullable();
            });
        }

				// Insert some stuff
	      DB::table('users_roles')->insert(
	          array(
	              'role_name' => 'MANAGER',
	              'state' => 'Active',
	          )
	      );


	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_roles');
	}

}
