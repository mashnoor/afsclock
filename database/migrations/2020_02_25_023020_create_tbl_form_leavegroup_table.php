<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblFormLeavegroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_form_leavegroup')) {
            Schema::create('tbl_form_leavegroup', function (Blueprint $table) {
                $table->increments('id');
                $table->string('leavegroup')->nullable();
                $table->string('description')->nullable();
                $table->string('leaveprivileges')->nullable();
                $table->integer('status')->nullable();
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
		Schema::drop('tbl_form_leavegroup');
	}

}
