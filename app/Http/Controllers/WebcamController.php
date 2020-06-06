<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\User;
use App\Classes\table;
use DB;

use Illuminate\Http\Request;

class WebcamController extends Controller
{

  public function realtime_webcam_data(Request $request){
    if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }

    $webcam_data =  DB::table('webcam_data')->select('webcam_data.id', 'webcam_data.last_seen', 'tbl_company_data.idno')->join('tbl_company_data', 'webcam_data.reference','=', 'tbl_company_data.reference')->get();

    return view('admin.webcam_data', compact('webcam_data'));

  }



    public function webcam_attendance(Request $request)
    {
      // Post request received data
      $type = $request->type;
      $lastseen = $request->lastseen;
      $reference = $request->reference;

      // Necessary Date
      $current_date = Carbon::now()->format('Y-m-d');
      $lastseen_date = date("Y-m-d", strtotime($lastseen));

      // For entry camera
      if ($type == 0) {

        // $existing_attendance = table::attendance()->where('reference', $reference)->whereDate('timein', $lastseen_date)->whereNull('timeout')->exists();
        $existing_attendance = table::attendance()->where('reference', $reference)->whereNotNull('timein')->whereNull('timeout')->orderBy('id', 'desc')->first();

        // If the person still infront of the entry camera before going out.
        if ($existing_attendance)
        {
          return "Your attendance information already exists";
        }

        // If the person come back from outside from break.

        $ongoing_closed_attendance = table::attendance()->where('reference', $reference)->whereNotNull('timeout')->orderBy('id', 'desc')->first();

        $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $ongoing_closed_attendance->timein);
        $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $lastseen);
        $th = $time1->diffInHours($time2);

        if ($ongoing_closed_attendance && ($th <= 4))
        {

          $start_at = Carbon::createFromFormat("Y-m-d h:i:s A", $ongoing_closed_attendance->timein);
          $end_at = Carbon::createFromFormat("Y-m-d h:i:s A", $lastseen);

          $break = DB::table('daily_breaks')->insert(
              ['reference' => $reference, 'attendance_id' => $ongoing_closed_attendance->id , 'start_at' => $start_at, 'end_at' => $end_at]
          );
          if ($break) {

            $attendance = table::attendance()->where('id', $ongoing_closed_attendance->id)->update(array(
                'timeout' => NULL,
                'totalhours' => NULL,
                'status_timeout' => NULL,)
            );

            if ($attendance) {
              return "Welcome back from break.";
            }
            else{
              return "Break is recorded but failed to modify attendance timeout.";
            }
          }else {
            return "Failed to record break.";
          }

        }

        $fresh_attendance = table::attendance()->where('reference', $reference)->whereDate('timein', $lastseen_date)->exists();

        if(!$fresh_attendance){
          $user = User::find('reference', $reference)->first();

          $assigned_schedule_id = table::new_schedule()->where([['reference', $reference],['active_status', 1]] )->value('schedule_id');
          $schedule_template = table::sch_template()->where('id', $assigned_schedule_id)->first();

          $today = Carbon::now();
          $day = strtolower($today->isoFormat('dddd'));

          $day_today = $schedule_template->$day;
          $str_arr = explode ("-", $day_today);
          $in_time = $str_arr[0];
          $out_time = $str_arr[1];

          $time = date('h:i:s A');

          if($in_time == NULL)
          {
              $status_in = "Ok";
          } else {
              // $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));
              $time_in_24h = date("H.i", strtotime($time));

              if ($time_in_24h <= $in_time)
              {
                  $status_in = 'In Time';
              } else {
                  $status_in = 'Late Arrival';
              }
          }

          $attendance = table::attendance()->insert([
              [
                  'idno' => $user->idno,
                  'reference' => $reference,
                  'date' => $current_date,
                  'employee' => $user->name,
                  'timein' => $lastseen,
                  'status_timein' => $status_in,
                  'comment' => "",
                  'schedule_id' => $assigned_schedule_id,
              ],
          ]);

          if ($attendance) {
            return "Attendance recorded Successfully";
          }else {
            return "Attendance failed to be recorded!";
          }
        }

      }

      // For Exit camera
      elseif ($type == 1) {
        // $existing_attendance = table::attendance()->where('reference', $reference)->whereDate('timein', $lastseen_date)->whereNull('timeout')->exists();

        $existing_attendance = table::attendance()->where('reference', $reference)->whereNotNull('timein')->orderBy('id', 'desc')->first();

        $user = User::find('reference',$reference)->first();

        // If there exist ongoing attendace
        if ($existing_attendance)
        {

          $assigned_schedule_id = table::new_schedule()->where([['reference', $reference],['active_status', 1]] )->value('schedule_id');
          $schedule_template = table::sch_template()->where('id', $assigned_schedule_id)->first();

          $today = Carbon::now();
          $day = strtolower($today->isoFormat('dddd'));

          $day_today = $schedule_template->$day;
          $str_arr = explode ("-", $day_today);
          $in_time = $str_arr[0];
          $out_time = $str_arr[1];

          if($out_time == NULL)
          {
              $status_out = "Ok";
          } else {
              // $sched_clock_out_time_24h = date("H.i", strtotime($sched_out_time));
              $time_out_24h = date("H.i", strtotime($lastseen));

              if($time_out_24h >= $out_time)
              {
                  $status_out = 'On Time';
              } else {
                  $status_out = 'Early Departure';
              }
          }
          $clockInDate = $existing_attendance->date;
          $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $existing_attendance->timein);
          $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $lastseen);
          $th = $time1->diffInHours($time2);
          $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
          $totalhour = $th.".".$tm;

          $attendance = table::attendance()->where('id', $existing_attendance->id)->update(array(
              'timeout' => $lastseen,
              'totalhours' => $totalhour,
              'status_timeout' => $status_out)
          );

          if ($attendance) {
            return "Clock out Successfully";
          }

        }else{
          return "No attendace there";
        }
      }

    }
}
