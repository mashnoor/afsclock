<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\table;
use App\User;
use Carbon\Carbon;
use DateTime;

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

class MonthlySalary{

    public $id = '';
    public $employee ='';
    public $salary_type = '';
    public $calculated_salary = '';
    public $currency = '';
    public $office_days = '';
    public $gross_salary = '';

    public function __construct($id, $employee, $salary_type, $calculated_salary, $currency, $office_days, $gross_salary)
    {
        $this->id = $id;
        $this->employee = $employee;
        $this->salary_type = $salary_type;
        $this->calculated_salary = $calculated_salary;
        $this->currency = $currency;
        $this->office_days = $office_days;
        $this->gross_salary = $gross_salary;
    }
}

class HourlySalary{

    public $id = '';
    public $employee ='';
    public $salary_type = '';
    public $total_hours = '';
    public $calculated_salary = '';
    public $currency = '';
    public $office_days = '';
    public $gross_salary = '';

    public function __construct($id, $employee, $salary_type, $total_hours,$calculated_salary, $currency, $office_days, $gross_salary)
    {
        $this->id = $id;
        $this->employee = $employee;
        $this->salary_type = $salary_type;
        $this->total_hours = $total_hours;
        $this->calculated_salary = $calculated_salary;
        $this->currency = $currency;
        $this->office_days = $office_days;
        $this->gross_salary = $gross_salary;
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

    // Edit Salary Type
    public function edit_salary_types($id)
    {
      $salary_type = table::salary_types()->where('id',$id)->first();
      return view('admin/edit_salary_type', compact('salary_type'));
    }

    // Update Salary Type
    public function update_salary_types(Request $request)
    {
      $id =  $request->id;
      $type = $request->type_name;

      $salary_type = table::salary_types()->where('id',$id)->first();
      if ($salary_type) {
        table::salary_types()->where('id' , $id)->update(array('type' => $type));
      }
      return redirect(url('admin/salary_types'))->with('success', 'Salary type has been updated!');
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
        if ($employee_salary) {
          $user = User::find($employee_salary->reference);
          $salary_type = table::salary_types()->where('id', $employee_salary->salary_type)->first();
          $salary_collection->push(new Salary($employee_salary->id, $user->firstname." ".$user->lastname, $salary_type->type, $employee_salary->gross_salary, $employee_salary->currency));
        }
      }
      return view('admin.employee_salary', compact('salary_collection','employee','salary_types'));
    }

    // Add Employee Salary
    public function add_employee_salary(Request $request)
    {
      $salary_type = $request->salary_type;
      $salary_amount = $request->salary_amount;
      $employee_id = $request->employee_id;
      $currency = $request->currency;

      // dd($employee_id);

      table::employee_salary()->insert(['reference'=>$employee_id,'salary_type' => $salary_type,'gross_salary' => $salary_amount, 'currency' => $currency]);
      return redirect(url('admin/employee_salary'))->with('success', 'Employee salary has been added!');
    }


    public function delete_employee_salary($id)
    {
      table::employee_salary()->delete($id);
      return redirect(url('admin/employee_salary'))->with('success', 'Employee Salary has been deleted!');
    }

    // Holidays main page
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

      $existing_holiday = table::holidays()->where('month', $month)->first();
      if ($existing_holiday) {
        return redirect(url('admin/holidays'))->with('error', 'The follwing month already exist!');
      }else
      {
        table::holidays()->insert(['month'=>$month,'dates' => $dates]);
        return redirect(url('admin/holidays'))->with('success', 'Holiday has been added!');
      }
    }

    // Delete Holidays
    public function delete_holidays($id)
    {
      table::holidays()->delete($id);
      return redirect(url('admin/holidays'))->with('success', 'Holidays have been deleted!');
    }

    // public function edit_holidays($id)
    // {
    //   $holiday = table::holidays()->where('id', $id)->first();
    //
    //   return view('admin.edit_holidays', compact('holiday'));
    // }
    //
    // public function update_holidays(Request $request)
    // {
    //
    //   $month = $request->month;
    //   $dates = $request->dates;
    //
    //   $holiday = table::holidays()->where('month', $month)->first();
    //   if ($holiday)
    //   {
    //     table::holidays()->where('month' , $month)->update(array('dates' => $dates));
    //     return redirect(url('admin/holidays'))->with('success', 'Holidays have been deleted!');
    //   }else{
    //     return redirect(url('admin/holidays'))->with('error', 'Holiday month does not exist!');
    //   }
    // }

    // Salary Calculation
    public function calculate_salary(Request $request)
    {
      $datefrom = $request->datefrom;
      $dateto = $request->dateto;

      $month = Carbon::parse($datefrom)->format('m');
      $year = Carbon::parse($dateto)->format('Y');

      $days_in_this_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);

      $all_employee = table::people()->get();

      // $all_holidays = table::holidays()->where('month', $month)->first();
      //
      // if ($all_holidays) {
      //   $holidays = explode(",", $all_holidays->dates);
      //   dd($holidays[0]);
      //   $total_holidays = sizeof($holidays);
      // }else {
      //   $total_holidays = 0;
      // }

      $total_holidays = 0;

      $salary_collection = collect([]);
      $hourly_salary_collection = collect([]);

      foreach($all_employee as $employee)
      {
        $total_attendance = 0;
        $total_leaves = 0;

        $total_paid_office_days = 0;
        $total_paid_office_hours = 0.0;

        // Checks all attendance
        $all_attendance = table::attendance()->where('reference',$employee->id)->whereBetween('timein', [$datefrom, $dateto])->get();

        if ($all_attendance->count() > 0) {
          $employee_salary = table::employee_salary()->where('reference', $employee->id)->first();
          if ($employee_salary) {

          $total_attendance = sizeof($all_attendance);

          // // Checks all leaves
          // $all_leaves = table::leaves()->where('reference', $employee->id)->where('status', 'Approved')->whereBetween('created_at', [$datefrom, $dateto])->get();
          // if ($all_leaves->count()) {
          //   dd($all_leaves);
          // }else{
          //
          // }
          //
          // if (sizeof($all_leaves) > 0) {
          //   $total_leaves = sizeof($all_leaves);
          // }
          // else {
          //   $total_leaves = 0;
          // }

          $total_leaves = 0;

          // $employee_salary = table::employee_salary()->where('reference', $employee->id)->first();
          $salary_amount = $employee_salary->gross_salary;
          $salary_currency = $employee_salary->currency;
          $salary_type = (int)$employee_salary->salary_type;

          // If salary type if monthly.
          if ($salary_type == 1)
          {
            $total_paid_office_days = $total_attendance + $total_leaves + $total_holidays;
            $daily_salary = $salary_amount / $days_in_this_month;
            $this_month_salary = $total_paid_office_days * $daily_salary;
            $absent_days = $days_in_this_month - $total_paid_office_days;
            $salary_collection->push(new MonthlySalary($employee_salary->id, $employee->lastname, $salary_type, (int)$this_month_salary, $employee_salary->currency, $total_paid_office_days, $salary_amount));
          }
          // If salary type is Hourly
          elseif($salary_type == 2){
            $total_schedule_id_found = 0;
            $truely_total_office_minutes = 0;

            foreach($all_attendance as $attendance)
            {
              $total_office_calculated_hours = 0;
              $total_office_calculated_minutes = 0;

              $total_office_minutes = 0;

              // If there exist schedule id along with attendance
              if ((int)$attendance->schedule_id) {
                $total_schedule_id_found++;

                $str_arr = explode (".", $attendance->totalhours);
                $hours = $str_arr[0];
                $minutes = $str_arr[1];

                // dd($hours, $minutes);
                $total_office_calculated_hours += $hours;
                $total_office_calculated_minutes += $minutes;

                // dd($total_office_calculated_minutes);

                // Break Taken
                $all_breaks = table::daily_breaks()->where([['reference', $employee->id],['attendance_id', $attendance->id]])->get();
                // dd($all_breaks);
                $total_break_duration = 0.0;
                $total_break_hour = 0.0;
                $total_break_minutes = 0.0;

                if (count($all_breaks) > 0) {
                  foreach($all_breaks as $break){
                    if ($break->start_at != NULL && $break->end_at != NULL) {
                      $start_at = date("Y-m-d h:i:s A", strtotime($break->start_at));
                      $end_at = date("Y-m-d h:i:s A", strtotime($break->end_at));
                      $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $start_at);
                      $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $end_at);
                      // $th = $time1->diffInHours($time2);
                      $tm = $time1->diffInMinutes($time2);

                      // $break_duration = floatval($th.".".$tm);

                      // dd($tm);
                      // $total_break_hour += $th;
                      $total_break_minutes += $tm;
                      // $total_break_duration += $break_duration;
                    }
                  }

                  // dd($total_break_minutes);
                  // $the_break_duration = 0;
                  // // Converts break minutes into hour
                  // if ($total_break_minutes >= 60) {
                  //   $the_break_duration = $total_break_minutes / 60;
                  // }
                  $convert_hour = $total_break_minutes / 60;
                  $total_break_hour += (int)$convert_hour;
                  $total_actual_break_minutes = $total_break_minutes - ((int)$convert_hour * 60);

                  $total_break_duration = $total_break_hour.".".$total_actual_break_minutes;

                  $converted_break_minutes = ($total_break_hour * 60) + $total_actual_break_minutes;
                  $converted_office_hour_minutes = ($total_office_calculated_hours * 60) + $total_office_calculated_minutes;


                  // If the break is less than 30 Minutes, it considers as 30 Minutes
                  if ($converted_break_minutes <= 30)
                  {
                    $converted_break_minutes = 30;
                    $total_office_minutes = $converted_office_hour_minutes - $converted_break_minutes;
                    if ($total_office_minutes < 0) {
                      $total_office_minutes = 0;
                    }
                  }else{
                    $total_office_minutes = $converted_office_hour_minutes - $converted_break_minutes;
                  }

                }
              }
              if ($total_office_minutes > 8) {
                $total_office_minutes = 8 * 60;
              }

              $truely_total_office_minutes += $total_office_minutes;
            }

            $converted_paid_office_hours = (int)($truely_total_office_minutes / 60);
            $converted_paid_office_minutes = $truely_total_office_minutes - (int)($converted_paid_office_hours * 60);
            $total_paid_office_hours = $converted_paid_office_hours .".".$converted_paid_office_minutes;

            $calculated_salary = ($converted_paid_office_hours * $salary_amount) + ($converted_paid_office_minutes * ($salary_amount/60));
            $absent_days = $days_in_this_month - $total_attendance;

            $hourly_salary_collection->push(new HourlySalary($employee_salary->id, $employee->lastname, $salary_type, $total_paid_office_hours ,(int)$calculated_salary, $employee_salary->currency, $total_attendance, $salary_amount));

          }

        }else{
          // If there is no attendance simply Pass
        }
      }else{
        // If there is no salary information Pass
      }

      }

      $dateObj = DateTime::createFromFormat('!m', $month);
      $monthName = $dateObj->format('F');

      return view('admin.generated_salary', compact('salary_collection','hourly_salary_collection','monthName', 'year' ));
    }
}
