<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeavetypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('leavetype')) {
            Schema::create('leavetype', function (Blueprint $table) {
                $table->increments('id');
                $table->string('leavetype')->nullable();
                $table->string('limit')->nullable();
                $table->string('percalendar')->nullable();
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
		Schema::drop('leavetype');
	}

}
