<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->nullable();
                $table->string('token')->nullable();
                $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
		Schema::drop('password_resets');
	}

}
