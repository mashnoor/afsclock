<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblFormCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_form_company')) {
            Schema::create('tbl_form_company', function (Blueprint $table) {
                $table->increments('id');
                $table->string('company', 250)->nullable()->default('');
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
		Schema::drop('tbl_form_company');
	}

}
