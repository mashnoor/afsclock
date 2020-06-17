<?php

namespace App\Classes;

use DB;

Class table {

		public static function people()
		{
			return DB::table('people');
		}

		public static function salary_types(){
			return DB::table('salary_types');
		}

		public static function holidays(){
			return DB::table('holidays');
		}

		public static function employee_salary(){
			return DB::table('employee_salary');
		}

		public static function task_extension(){
			return DB::table('task_extension');
		}

		public static function webcam_table()
		{
	    	return DB::table('webcam_data');
	   	}

		public static function sch_template(){
			return DB::table('schedule_template');
		}

		public static function schedules(){
			return DB::table('schedules');
		}

    public static function daily_breaks()
    {
        return DB::table('daily_breaks');
    }


    public static function daily_entries()
    {
	    return DB::table('daily_entries');
    }

	public static function companydata()
	{
    	return DB::table('company_data');
   	}

	public static function attendance()
	{
    	return DB::table('attendance');
   	}

	public static function leaves()
	{
    	return DB::table('leaves');
   	}


	public static function reportviews()
	{
    	return DB::table('report_views');
   	}

	public static function permissions()
	{
    	return DB::table('users_permissions');
   	}

	public static function roles()
	{
    	return DB::table('users_roles');
   	}


	public static function company()
	{
    	return DB::table('company');
   	}

	public static function department()
	{
    	return DB::table('department');
   	}

	public static function jobtitle()
	{
    	return DB::table('jobtitle');
   	}

	public static function leavetypes()
	{
    	return DB::table('leavetype');
	}

	public static function leavegroup()
	{
		return DB::table('leavegroup');
	}

	public static function settings()
	{
    	return DB::table('settings');
   	}

   	public static function common_task()
    {
        return DB::table('common_tasks');
    }

}
