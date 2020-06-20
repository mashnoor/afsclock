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

				// Default user permission data
				$data = [
				    ['role_id'=> 2, 'perm_id'=> 1],
				    ['role_id'=> 2, 'perm_id'=> 2],
						['role_id'=> 2, 'perm_id'=> 22],
						['role_id'=> 2, 'perm_id'=> 21],
						['role_id'=> 2, 'perm_id'=> 23],
						['role_id'=> 2, 'perm_id'=> 24],
						['role_id'=> 2, 'perm_id'=> 25],
						['role_id'=> 2, 'perm_id'=> 3],
						['role_id'=> 2, 'perm_id'=> 31],
						['role_id'=> 2, 'perm_id'=> 32],
						['role_id'=> 2, 'perm_id'=> 4],
				    ['role_id'=> 2, 'perm_id'=> 41],
						['role_id'=> 2, 'perm_id'=> 42],
						['role_id'=> 2, 'perm_id'=> 43],
						['role_id'=> 2, 'perm_id'=> 44],
						['role_id'=> 2, 'perm_id'=> 5],
						['role_id'=> 2, 'perm_id'=> 52],
						['role_id'=> 2, 'perm_id'=> 53],
						['role_id'=> 2, 'perm_id'=> 9],
						['role_id'=> 2, 'perm_id'=> 91],
						['role_id'=> 2, 'perm_id'=> 7],
				    ['role_id'=> 2, 'perm_id'=> 8],
						['role_id'=> 2, 'perm_id'=> 81],
						['role_id'=> 2, 'perm_id'=> 82],
						['role_id'=> 2, 'perm_id'=> 83],
						['role_id'=> 2, 'perm_id'=> 10],
						['role_id'=> 2, 'perm_id'=> 101],
						['role_id'=> 2, 'perm_id'=> 102],
						['role_id'=> 2, 'perm_id'=> 103],
						['role_id'=> 2, 'perm_id'=> 104],
						['role_id'=> 2, 'perm_id'=> 11],
						['role_id'=> 2, 'perm_id'=> 111],
						['role_id'=> 2, 'perm_id'=> 112],
						['role_id'=> 2, 'perm_id'=> 12],
						['role_id'=> 2, 'perm_id'=> 121],
						['role_id'=> 2, 'perm_id'=> 122],
				    ['role_id'=> 2, 'perm_id'=> 13],
						['role_id'=> 2, 'perm_id'=> 131],
						['role_id'=> 2, 'perm_id'=> 132],
						['role_id'=> 2, 'perm_id'=> 14],
						['role_id'=> 2, 'perm_id'=> 141],
						['role_id'=> 2, 'perm_id'=> 142],
						['role_id'=> 2, 'perm_id'=> 15],
						['role_id'=> 2, 'perm_id'=> 151],
						['role_id'=> 2, 'perm_id'=> 153],
				];

				DB::table('users_permissions')->insert($data); 
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
