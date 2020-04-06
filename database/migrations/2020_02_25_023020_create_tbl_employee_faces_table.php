<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblEmployeeFacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_employee_faces')) {
            Schema::create('tbl_employee_faces', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('reference');
                $table->string('image_name');
                $table->timestamps();
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
		Schema::drop('tbl_employee_faces');
	}

}
