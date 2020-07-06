<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\User;
use App\Classes\table;
use App\Classes\permission;
use DB;

use Illuminate\Http\Request;

class WebcamController extends Controller
{
  // Webcam Data Feed.
  // Real time camera data stored in the database table called 'webcam_table'.
  // Following controller function queries the data from the table and
  // sends to the view file.
  public function realtime_webcam_data(Request $request){
    if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }

    // $webcam_data =  DB::table('webcam_data')->select('webcam_data.id', 'webcam_data.last_seen', 'tbl_company_data.idno')->join('tbl_company_data', 'webcam_data.reference','=', 'tbl_company_data.reference')->get();
    $webcam_data = table::webcam_table()->get();
    return view('admin.webcam_data', compact('webcam_data'));
  }


    // Attendace using the Mobile App / CCTV Camera.
    // The remote camera devices are connected with this controller function
    // through an API. This function is the controller fuction of that mentioned API.
    public function webcam_attendance(Request $request)
    {
      // Post request received data
      $type = $request->type;
      $reference = $request->reference;
      $lastseen = Carbon::now()->format('Y-m-d h:i:s A');

      // Necessary Date
      $current_date = Carbon::now()->format('Y-m-d');
      $lastseen_date = date("Y-m-d", strtotime($lastseen));

      // For entry camera
      if ($type == 0) {

        $user = User::where('id', $reference)->first();

        if (!$user) {
          return 'User not found with the given reference id';
        }

        // Finds existing attendance information from database.
        $existing_attendance = table::attendance()->where('reference', $user->id)->whereNotNull('timein')->whereNull('timeout')->orderBy('id', 'desc')->first();

        // If the person still infront of the entry camera before going out.
        if ($existing_attendance)
        {
          return "Your attendance information already exists";
        }

        // If the person come back from outside from break.
        $ongoing_closed_attendance = table::attendance()->where('reference', $reference)->whereNotNull('timeout')->orderBy('id', 'desc')->first();

        if ($ongoing_closed_attendance) {
          $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $ongoing_closed_attendance->timeout);
          $time2 = $lastseen;
          $th = $time1->diffInHours($time2);
        }

        // There exist ongoing attendance and last timeout in less than 4 hours.
        if ($ongoing_closed_attendance && ($th <= 4))
        {
          $start_at = Carbon::createFromFormat("Y-m-d h:i:s A", $ongoing_closed_attendance->timeout);
          $end_at = Carbon::createFromFormat("Y-m-d h:i:s A", $lastseen);

          $break = DB::table('daily_breaks')->insert(
              ['reference' => $reference, 'attendance_id' => $ongoing_closed_attendance->id , 'start_at' => $start_at, 'end_at' => $end_at]
          );

          // If the break is successfully created, timout become null again.
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

        // If there exist no ongoing attendance or timeout more than 4 hours ago.
        $fresh_attendance = table::attendance()->where('reference', $reference)->whereDate('timein', $lastseen_date)->exists();

        if(!$fresh_attendance){
          $assigned_schedule_id = table::schedules()->where([['reference', $reference],['active_status', 1]] )->value('schedule_id');
          $schedule_template = table::sch_template()->where('id', $assigned_schedule_id)->first();

          $today = Carbon::now();
          $day = strtolower($today->isoFormat('dddd'));

          $day_today = $schedule_template->$day;
          if ($day_today) {
            $str_arr = explode ("-", $day_today);
            $in_time = $str_arr[0];
            $out_time = $str_arr[1];
          }else {
            $in_time = NULL;
            $out_time = NULL;
          }


          $time = date('h:i:s A');

          if($in_time == NULL)
          {
              $status_in = "Not Scheduled";
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
                  'employee' => $user->firstname." ".$user->lastname,
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
        $user = User::where('id',$reference)->first();
        if ($user) {
          $existing_attendance = table::attendance()->where('reference', $reference)->whereNotNull('timein')->orderBy('id', 'desc')->first();
        }else {
          return 'User not found with the given reference id';
        }

        // If there exist ongoing attendace
        if ($existing_attendance)
        {
          $assigned_schedule_id = table::schedules()->where([['reference', $reference],['active_status', 1]] )->value('schedule_id');
          $schedule_template = table::sch_template()->where('id', $assigned_schedule_id)->first();

          $today = Carbon::now();
          $day = strtolower($today->isoFormat('dddd'));

          $day_today = $schedule_template->$day;
          if ($day_today) {
            $str_arr = explode ("-", $day_today);
            $in_time = $str_arr[0];
            $out_time = $str_arr[1];
          }else{
            $in_time = NULL;
            $out_time = NULL;
          }


          if($out_time == NULL)
          {
              $status_out = "Not Scheduled";
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
