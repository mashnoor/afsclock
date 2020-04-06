<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblReportViewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('tbl_report_views')) {
            Schema::create('tbl_report_views', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('report_id')->nullable();
                $table->string('last_viewed')->nullable();
                $table->text('title', 65535)->nullable();
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
		Schema::drop('tbl_report_views');
	}

}
