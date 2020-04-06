<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('users_permissions')) {
            Schema::create('users_permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->nullable();
                $table->integer('perm_id')->nullable();
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
		Schema::drop('users_permissions');
	}

}
