<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblCompanyDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_company_data')) {
            Schema::create('tbl_company_data', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('reference');
                $table->string('company')->nullable()->default('');
                $table->string('department')->nullable()->default('0');
                $table->string('jobposition')->nullable()->default('');
                $table->string('companyemail')->nullable()->default('');
                $table->string('idno')->nullable()->default('');
                $table->string('startdate')->nullable()->default('');
                $table->string('dateregularized')->nullable()->default('');
                $table->string('reason', 455)->nullable()->default('');
                $table->integer('leaveprivilege')->nullable();
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
		Schema::drop('tbl_company_data');
	}

}
