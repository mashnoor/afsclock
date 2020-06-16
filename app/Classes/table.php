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
	    	$webcam_table = DB::table('webcam_data');
	    	return $webcam_table;
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
    	$leaves = DB::table('people_leaves');
    	return $leaves;
   	}


	public static function reportviews()
	{
    	$reportviews = DB::table('report_views');
    	return $reportviews;
   	}

	public static function permissions()
	{
    	$permissions = DB::table('users_permissions');
    	return $permissions;
   	}

	public static function roles()
	{
    	$roles = DB::table('users_roles');
    	return $roles;
   	}

	public static function users()
	{
    	$users = DB::table('users')->select('id', 'reference', 'idno', 'name', 'email', 'role_id', 'acc_type', 'status');
    	return $users;
   	}

	public static function company()
	{
    	$company = DB::table('company');
    	return $company;
   	}

	public static function department()
	{
    	$department = DB::table('department');
    	return $department;
   	}

	public static function jobtitle()
	{
    	$jobtitle = DB::table('jobtitle');
    	return $jobtitle;
   	}

	public static function leavetypes()
	{
    	$leavetypes = DB::table('leavetype');
    	return $leavetypes;
	}

	public static function leavegroup()
	{
		$leavegroup = DB::table('leavegroup');
		return $leavegroup;
	}

	public static function settings()
	{
    	$settings = DB::table('settings');
    	return $settings;
   	}

   	public static function common_task()
    {
        $common_tasks = DB::table('common_tasks');
        return $common_tasks;
    }

}
