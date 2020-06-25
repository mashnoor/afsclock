<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('people')) {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname')->nullable();
            $table->string('mi')->nullable()->default('');
            $table->string('lastname')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable()->default('');
            $table->string('emailaddress')->nullable()->default('');
            $table->string('civilstatus')->nullable()->default('');
            $table->string('mobileno')->nullable()->default('');
            $table->string('birthday')->nullable()->default('');
            $table->string('nationalid')->nullable();
            $table->string('birthplace')->nullable()->default('');
            $table->string('homeaddress')->nullable()->default('');
            $table->string('employmentstatus', 11)->nullable()->default('');
            $table->string('employmenttype', 11)->nullable()->default('');
            $table->string('avatar')->nullable();
            // Columns above inherited from tbl_people table.
            $table->integer('role_id')->nullable();
            $table->integer('acc_type')->nullable();
            $table->integer('status')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token', 100)->nullable();
            // Colums above inherited from users table
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('job_title_id')->nullable();
            $table->string('companyemail')->nullable()->default('');
            $table->string('idno')->nullable()->default('');
            $table->string('startdate')->nullable()->default('');
            $table->string('dateregularized')->nullable()->default('');
            $table->string('reason', 455)->nullable()->default('');
            $table->integer('leaveprivilege')->nullable();
            // Columns above inherited from the company data table
            $table->timestamps();
        });
      }



      // Insert some stuff
      /*** 
      DB::table('people')->insert(
          array(
              'firstname' => 'Demo',
              'lastname' => 'Manager',
              'gender' => 'Male',
              'role_id' => 2,
              'acc_type' => 2,
              'status' => 1,
              'employmentstatus' => 'Active',
              'companyemail' => 'manager@example.com',
              'password' => '$2y$10$mDAH.R8JG5ThPelt4zRXc.8sxizt.tqXQfndx5s/W/3j0Sq6xS3LG',

          )
      );
      ***/


    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
