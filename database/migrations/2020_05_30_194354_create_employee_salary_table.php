<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('employee_salary')) {
        Schema::create('employee_salary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reference');
            $table->integer('salary_type')->default(1);
            $table->string('gross_salary', 10)->default('');
            $table->string('currency', 5)->default('');
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
        Schema::dropIfExists('employee_salary');
    }
}
