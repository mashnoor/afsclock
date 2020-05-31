<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\table;
use App\User;

class Salary{

    public $id = '';
    public $employee ='';
    public $salary_type = '';
    public $amount = '';
    public $currency = '';

    public function __construct($id, $employee, $salary_type, $amount, $currency)
    {
        $this->id = $id;
        $this->employee = $employee;
        $this->salary_type = $salary_type;
        $this->amount = $amount;
        $this->currency = $currency;
    }

}

class SalaryController extends Controller
{
    // Add new salary type.
    // Data comes from the form placed in Salary Types page.
    // Takes only salary type name.
    public function add_salary_types(Request $request)
    {
      $salary_type = $request->type_name;
      // dd($salary_type);
      table::salary_types()->insert(['type' => $salary_type]);
      return redirect(url('admin/salary_types'))->with('success', 'Salary type has been created!');
    }

    // Delete Salary Type
    public function delete_salary_type($id)
    {
      table::salary_types()->delete($id);
      return redirect(url('admin/salary_types'))->with('success', 'Salary type has been deleted!');
    }


    // Salary Types page
    // Displays existing salary types and
    // Form to create new salary type.
    public function salary_types(Request $request)
    {
      $salary_types = table::salary_types()->get();
      return view('admin.salary_types', compact('salary_types'));
    }


    // Salary main page.
    // Contains all necessary functionalities related to salary.
    public function salary(Request $request)
    {
      return view('admin.salary');
    }

    // Employee salary page.
    public function employee_salary(Request $request)
    {
      $employee = table::people()->get();
      $salary_types = table::salary_types()->get();
      $employee_salaries = table::employee_salary()->get();

      $salary_collection = collect([]);

      foreach($employee_salaries as $employee_salary)
      {
        $user = User::find($employee_salary->reference);
        $salary_type = table::salary_types()->where('id', $employee_salary->salary_type)->first();
        $salary_collection->push(new Salary($employee_salary->id, $user->name, $salary_type->type, $employee_salary->gross_salary, $employee_salary->currency));
      }

      // dd($salary_collection);

      return view('admin.employee_salary', compact('salary_collection','employee','salary_types'));
    }

    // Add Employee Salary
    public function add_employee_salary(Request $request)
    {
      $salary_type = $request->salary_type;
      $salary_amount = $request->salary_amount;
      $employee_id = $request->employee_id;
      $currency = $request->currency;

      table::employee_salary()->insert(['reference'=>$employee_id,'salary_type' => $salary_type,'gross_salary' => $salary_amount, 'currency' => $currency]);
      return redirect(url('admin/employee_salary'))->with('success', 'Employee salary has been added!');
    }


    public function holidays(Request $request)
    {

      $holidays = table::holidays()->get();

      return view('admin.holidays', compact('holidays'));
    }


    // Add Holidays
    public function add_holidays(Request $request)
    {
      $month = $request->month;
      $dates = $request->dates;

      table::holidays()->insert(['month'=>$month,'dates' => $dates]);
      return redirect(url('admin/holidays'))->with('success', 'Holiday has been added!');

    }
}
