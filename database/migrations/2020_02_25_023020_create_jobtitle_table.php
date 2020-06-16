<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobtitleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('jobtitle')) {
            Schema::create('jobtitle', function (Blueprint $table) {
                $table->increments('id');
                $table->string('jobtitle', 250)->nullable()->default('');
                $table->integer('dept_code')->nullable();
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
		Schema::drop('jobtitle');
	}

}
