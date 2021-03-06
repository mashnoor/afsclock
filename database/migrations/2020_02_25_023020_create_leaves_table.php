<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasTable('leaves')) {
            Schema::create('leaves', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('reference')->nullable();
                $table->integer('typeid')->nullable();
                $table->string('type')->nullable()->default('');
								$table->timestamp('leavefrom')->nullable();
                $table->timestamp('leaveto')->nullable();
								$table->timestamp('returndate')->nullable();
                $table->string('reason')->nullable()->default('');
                $table->string('status')->nullable();
                $table->string('comment')->nullable();
                $table->integer('archived')->nullable();
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
		Schema::drop('people_leaves');
	}

}
