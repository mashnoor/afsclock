<?php

namespace App\Http\Controllers;
use DB;
use App\Task;
use Carbon\Carbon;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class ClockController extends Controller
{

    public function clock()
    {

        $data = table::settings()->where('id', 1)->first();
        $cc = $data->clock_comment;
        $tz = $data->timezone;
        $company_name = $data->company_name;

        return view('clock_cam', compact('cc', 'tz', 'company_name'));
    }
    public function test_clock()
    {
        $data = table::settings()->where('id', 1)->first();
        $cc = $data->clock_comment;
        $tz = $data->timezone;
        $company_name = $data->company_name;

        return view('clock', compact('cc', 'tz', 'company_name'));
    }


    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *  if($type == "break")
    {
    $has = table::attendance()->where([['idno', $idno],['date', $date]])->exists();
    if ($has == 1)
    {
    //Check if already break in
    $doesnt_have_break = table::attendance()->where([['idno', $idno],['date', $date], ['break_in', ""]])->exists();
    if($doesnt_have_break == 1)
    {
    table::attendance()->where([['idno', $idno],['date', $date]])->update(array(
    "break_in" => $date." ".$time,
    )
    );
    return response()->json([
    "type" => $type,
    "time" => $time,
    "date" => $date,
    "lastname" => $lastname,
    "firstname" => $firstname,
    "mi" => $mi,
    ]);
    }


    }
    }
     */

    public function add(Request $request)
    {

        if ($request->idno == NULL || $request->type == NULL)
        {
            return response()->json([
                "error" => "Please enter your ID."
            ]);
        }

        if(strlen($request->idno) >= 20 || strlen($request->type) >= 20)
        {
            return response()->json([
                "error" => "Invalid Employee ID."
            ]);
        }

        $idno = strtoupper($request->idno);
        $type = $request->type;
        $date = date('Y-m-d');
        $time = date('h:i:s A');
        $comment = strtoupper($request->clock_comment);
        $ip = $request->ip();

        // clock-in comment feature
        $clock_comment = table::settings()->value('clock_comment');

        if ($clock_comment == 1)
        {
            if ($request->clock_comment == NULL )
            {
                return response()->json([
                    "error" => "Please provide your comment!"
                ]);
            }
        }

        // ip resriction
        $iprestriction = table::settings()->value('iprestriction');
        if ($iprestriction != NULL)
        {
            $ips = explode(",", $iprestriction);

            if(in_array($ip, $ips) == false)
            {
                $msge = "Whoops! You are not allowed to Clock In or Out from your IP address ".$ip;
                return response()->json([
                    "error" => $msge,
                ]);
            }
        }

        $employee_id = table::companydata()->where('idno', $idno)->value('reference');

        if($employee_id == null) {
            return response()->json([
                "error" => "You enter an invalid ID."
            ]);
        }

        $emp = table::people()->where('id', $employee_id)->first();
        $lastname = $emp->lastname;
        $firstname = $emp->firstname;
        $mi = $emp->mi;
        $employee = mb_strtoupper($lastname.', '.$firstname.' '.$mi);

        if ($type == 'timein')
        {

            // // ATTENDANCE TIME IN REQUEST HANDLING STARTS HERE
            //
            // // Checks if the following employee has attendance record today
            // $isAttendanceToday = table::daily_attendance()->where([['idno', $idno ], ['reference', $employee_id]])->whereDate('created_at', $date)->exists();
            //
            // // If exist in attendance record today
            // if($isAttendanceToday)
            // {
            //     // Finds ongoing entry
            //     $isOngoingEntry = table::daily_entries()->where([['reference', $employee_id],['end_at', NULL]])->whereDate('start_at', $date)->exists();
            //
            //     if(!$isOngoingEntry)
            //     {
            //        // Creates attendance time in
            //        DB::table('daily_entries')->insert(['reference' => $employee_id, 'start_at' => Carbon::now()]);
            //
            //        return response()->json([
            //                               "type" => 'timein',
            //                               "time" => $time,
            //                               "date" => $date,
            //                               "lastname" => $firstname,
            //                               "firstname" => $lastname,
            //                               "mi" => $mi,
            //                               "success" => "Hello, " . $firstname . " " . $lastname . ". Time In is recorded at " . $time . " on " . $date,
            //                           ]);
            //     }
            //     else{
            //         return response()->json([
            //             "employee" => $employee,
            //             "error" => "You already Time In today.",
            //         ]);
            //     }
            // }
            // else{
            //     // Creates attendance time in
            //    $attendanceToday = DB::table('daily_attendance')->insert(['idno' => $idno, 'reference' => $employee_id, 'employee' => $employee, 'totalhours' => 0, 'total_break_hours' => 0,'created_at' => Carbon::now()]);
            //
            //     // Creates attendance time in
            //     DB::table('daily_entries')->insert(['reference' => $employee_id, 'start_at' => Carbon::now()]);
            //
            //
            //     $sched_in_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('intime');
            //
            //         if($sched_in_time == NULL)
            //         {
            //             $status_in = "Ok";
            //         } else {
            //             $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));
            //             $time_in_24h = date("H.i", strtotime($time));
            //
            //             if ($time_in_24h <= $sched_clock_in_time_24h)
            //             {
            //                 $status_in = 'In Time';
            //             } else {
            //                 $status_in = 'Late Arrival';
            //             }
            //         }
            //
            //     DB::table('daily_attendance')
            //         ->where('reference', $employee_id)
            //         ->whereDate('created_at', $date)
            //         ->update(['status_timein' => $status_in]);
            //
            //
            //     return response()->json([
            //                            "type" => 'timein',
            //                            "time" => $time,
            //                            "date" => $date,
            //                            "lastname" => $firstname,
            //                            "firstname" => $lastname,
            //                            "mi" => $mi,
            //                            "success" => "Hello, " . $firstname . " " . $lastname . ". Time In is recorded at " . $time . " on " . $date,
            //                        ]);
            //
            //
            // }
            //
            // // ATTENDANCE TIME IN REQUEST HANDLING ENDS HERE

           $has = table::attendance()->where([['idno', $idno],['date', $date]])->exists();
           if ($has == 1)
           {
               $hti = table::attendance()->where([['idno', $idno],['date', $date]])->value('timein');
               $hti = date('h:i A', strtotime($hti));
               return response()->json([
                   "employee" => $employee,
                   "error" => "You already Time In today at ".$hti,
               ]);

           }
           else {
               $last_in_notimeout = table::attendance()->where([['idno', $idno],['timeout', NULL]])->count();

               if($last_in_notimeout >= 1)
               {
                   return response()->json([
                       "error" => "Please clock-out from your last Clock In."
                   ]);

               } else {

                   // $sched_in_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('intime');

                   $assigned_schedule_id = table::new_schedule()->where([['reference', $employee_id],['active_status', 1]] )->value('schedule_id');
                   $schedule_template = table::sch_template()->where('id', $assigned_schedule_id)->first();

                   $today = Carbon::now();
                   $day = strtolower($today->isoFormat('dddd'));

                   $day_today = $schedule_template->$day;
                   $str_arr = explode ("-", $day_today);
                   $in_time = $str_arr[0];
                   $out_time = $str_arr[1];

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

                   if($clock_comment == 1 && $comment != NULL)
                   {
                       table::attendance()->insert([
                           [
                               'idno' => $idno,
                               'reference' => $employee_id,
                               'date' => $date,
                               'employee' => $employee,
                               'timein' => $date." ".$time,
                               'status_timein' => $status_in,
                               'comment' => $comment,
                               'schedule_id' => $assigned_schedule_id,
                           ],
                       ]);
                   } else {
                       table::attendance()->insert([
                           [
                               'idno' => $idno,
                               'reference' => $employee_id,
                               'date' => $date,
                               'employee' => $employee,
                               'timein' => $date." ".$time,
                               'status_timein' => $status_in,
                               'schedule_id' => $assigned_schedule_id,
                           ],
                       ]);
                   }

                   return response()->json([
                       "type" => $type,
                       "time" => $time,
                       "date" => $date,
                       "lastname" => $lastname,
                       "firstname" => $firstname,
                       "mi" => $mi,
                       "success" => "Hello, " . $firstname . " " . $lastname . ". Time In is recorded at " . $time . " on " . $date,
                   ]);
               }
           }
        }


        if($type == "break")
        {

            // MULTIPLE BREAK IN AND BREAK OUT CONDITION STARTS HERE

            // Checks if the following employee has attendance record today
            $isAttendanceToday = table::attendance()->where([['idno', $idno ], ['reference', $employee_id],['timeout', NULL]])->whereDate('timein', $date)->first();

            // If there exist ongiong attendance
            if($isAttendanceToday){

                // Finds ongoing break
                $isExistingBreak = table::daily_breaks()->where([['reference', $employee_id],['attendance_id', $isAttendanceToday->id], ['end_at', NULL]])->first();

                if ($isExistingBreak) {
                  table::daily_breaks()->where([['reference', $employee_id], ['attendance_id', $isAttendanceToday->id],['end_at', NULL]])->update(array(
                      "end_at" => Carbon::now()
                  ));

                  return response()->json([
                                         "type" => 'break_out',
                                         "time" => $time,
                                         "date" => $date,
                                         "lastname" => $firstname,
                                         "firstname" => $lastname,
                                         "mi" => $mi,
                                         "success" => "Hello, " . $firstname . " " . $lastname . ". Break Out is recorded at " . $time . " on " . $date,
                                    ]);

                }else {
                  DB::table('daily_breaks')->insert(
                      ['reference' => $employee_id, 'attendance_id' => $isAttendanceToday->id , 'start_at' => Carbon::now()]
                  );

                  return response()->json([
                                         "type" => 'break_in',
                                         "time" => $time,
                                         "date" => $date,
                                         "lastname" => $firstname,
                                         "firstname" => $lastname,
                                         "mi" => $mi,
                                         "success" => "Hello, " . $firstname . " " . $lastname . ". Break In is recorded at " . $time . " on " . $date,
                                     ]);
                }

            }else{
                return response()->json([
                        "employee" => $employee,
                        "error" => "Please Clock In first",
                    ]);
            }

            // MULTIPLE BREAK IN AND BREAK OUT CONDITION ENDS HERE


//            $has = table::attendance()->where([['idno', $idno],['date', $date]])->exists();
//            if ($has == 1)
//            {
                //Check if already break in
//                $doesnt_have_break_in = table::attendance()->where([['idno', $idno],['date', $date], ['break_in', NULL]])->exists();
//                $doesnt_have_break_out = table::attendance()->where([['idno', $idno],['date', $date], ['break_out', NULL]])->exists();

//                if($doesnt_have_break_in == 1)
//                {
//                    table::attendance()->where([['idno', $idno],['date', $date]])->update(array(
//                            "break_in" => $date." ".$time,
//                        )
//                    );
//                    return response()->json([
//                        "type" => "break_in",
//                        "time" => $time,
//                        "date" => $date,
//                        "lastname" => $lastname,
//                        "firstname" => $firstname,
//                        "mi" => $mi,
//                        "success" => "Hello, " . $firstname . " " . $lastname . ". Break in is recorded at " . $time . " on " . $date,
//                    ]);
//                }
//                else if($doesnt_have_break_out == 1)
//                {
//                    //As already break in, so its time for break out
//                    table::attendance()->where([['idno', $idno],['date', $date]])->update(array(
//                            "break_out" => $date." ".$time,
//                        )
//                    );
//                    return response()->json([
//                        "type" => "break_out",
//                        "time" => $time,
//                        "date" => $date,
//                        "lastname" => $lastname,
//                        "firstname" => $firstname,
//                        "mi" => $mi,
//                        "success" => "Hello, " . $firstname . " " . $lastname . ". Break out is recorded at " . $time . " on " . $date,
//                    ]);
//                }
//                else
//                {
//                    //can't break in/out
//                    $hto = table::attendance()->where([['idno', $idno],['date', $date]])->value('break_out');
//                    $hto = date('h:i A', strtotime($hto));
//                    return response()->json([
//                        "employee" => $employee,
//                        "error" => "You already break out at ". $hto . " on " . $date,
//                    ]);
//
//                }
//            }
        }

        if ($type == 'timeout')
        {

            // // Checks if the following employee has attendance record today
            // $isAttendanceToday = table::daily_attendance()->where([['idno', $idno ],['reference', $employee_id]])->whereDate('created_at', $date)->exists();
            //
            // if($isAttendanceToday){
            //     // Finds ongoing entry
            //     $isOngoingEntry = table::daily_entries()->where([['reference', $employee_id] ,['end_at', NULL]])->whereDate('start_at', $date)->exists();
            //
            //     if($isOngoingEntry){
            //
            //         table::daily_entries()->where([['reference', $employee_id],['end_at', NULL]])->whereDate('start_at', $date)->update(array(
            //             "end_at" => Carbon::now(),
            //         ));
            //
            //
            //         $all_entries = DB::table('daily_entries')->where('reference', $employee_id)->whereDate('start_at', $date)->get();
            //
            //         if ($all_entries){
            //             // $total_hours = 0;
            //             // foreach ($all_entries as $entry){
            //             //     $starttimestamp = strtotime($entry->start_at);
            //             //     $endtimestamp = strtotime($entry->end_at);
            //             //     $difference = ($endtimestamp - $starttimestamp)/3600;
            //             //     $total_hours = $total_hours + $difference;
            //             // }
            //
            //             // $total_working_hour = 0;
            //             $toal_working_minute = 0;
            //             $total_hours = 0;
            //             $toal_working_minute_new = 0;
            //
            //             foreach ($all_entries as $entry) {
            //               $time1 = Carbon::parse($entry->start_at);
            //               $time2 = Carbon::parse($entry->end_at);
            //
            //               $th = $time1->diffInHours($time2);
            //               $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
            //               $totalhour = $th.".".$tm;
            //
            //
            //               $total_hours += $th;
            //               $toal_working_minute_new += $tm;
            //
            //             }
            //
            //             $total_working_hour = $total_hours.".".$toal_working_minute_new;
            //
            //
            //         }
            //
            //
            //         $theAttendanceToday = table::daily_attendance()->where([['idno', $idno ],['reference', $employee_id]])->whereDate('created_at', $date)->first();
            //
            //         $affected = DB::table('daily_attendance')
            //             ->where('id', $theAttendanceToday->id)
            //             ->update(array(
            //                 'totalhours' => $total_working_hour,
            //                 'updated_at' => Carbon::now()
            //             ));
            //
            //         $timeOUT = date("Y-m-d h:i:s A", strtotime($date." ".$time));
            //
            //         $sched_out_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('outime');
            //
            //         if($sched_out_time == NULL)
            //         {
            //             $status_out = "Ok";
            //         } else {
            //             $sched_clock_out_time_24h = date("H.i", strtotime($sched_out_time));
            //             $time_out_24h = date("H.i", strtotime($timeOUT));
            //
            //             if($time_out_24h >= $sched_clock_out_time_24h)
            //             {
            //                 $status_out = 'On Time';
            //             } else {
            //                 $status_out = 'Early Departure';
            //             }
            //         }
            //
            //         DB::table('daily_attendance')
            //             ->where('id', $theAttendanceToday->id)
            //             ->update(['status_timeout' => $status_out]);
            //
            //
            //         // return response()->json([
            //         //     "success" => "Successfully clock out "
            //         // ]);
            //
            //         return response()->json([
            //                                "type" => 'timeout',
            //                                "time" => $time,
            //                                "date" => $date,
            //                                "lastname" => $firstname,
            //                                "firstname" => $lastname,
            //                                "mi" => $mi,
            //                                "success" => "Hello, " . $firstname . " " . $lastname . ". Time Out is recorded at " . $time . " on " . $date,
            //                            ]);
            //
            //     }else{
            //         return response()->json([
            //             "error" => "Please Clock In before Clocking Out. Ongoing entry does not exist"
            //         ]);
            //     }
            // }else{
            //     return response()->json([
            //         "error" => "Please Clock In before Clocking Out. Attendence does not exist"
            //     ]);
            // }
            $current_user = Auth::user();

            $tasks = Task::where([['reference', $current_user->id ],['done_status', 0]])->get();

            if ($tasks) {
              return response()->json([
                  "pending_task_error" => "Please Clock In before Clocking Out."
              ]);
            }


           $timeIN = table::attendance()->where([['idno', $idno], ['timeout', NULL]])->value('timein');
           $clockInDate = table::attendance()->where([['idno', $idno],['timeout', NULL]])->value('date');
           $hasout = table::attendance()->where([['idno', $idno],['date', $date]])->value('timeout');
           $timeOUT = date("Y-m-d h:i:s A", strtotime($date." ".$time));

           if($timeIN == NULL)
           {
               return response()->json([
                   "error" => "Please Clock In before Clocking Out."
               ]);
           }

           if ($hasout != NULL)
           {
               $hto = table::attendance()->where([['idno', $idno],['date', $date]])->value('timeout');
               $hto = date('h:i A', strtotime($hto));
               return response()->json([
                   "employee" => $employee,
                   "error" => "You already Time Out at ". $hto . " on " . $date,
               ]);

           }
           else {
               $sched_out_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('outime');

               $assigned_schedule_id = table::new_schedule()->where([['reference', $employee_id],['active_status', 1]] )->value('schedule_id');
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
                   $time_out_24h = date("H.i", strtotime($timeOUT));

                   if($time_out_24h >= $out_time)
                   {
                       $status_out = 'On Time';
                   } else {
                       $status_out = 'Early Departure';
                   }
               }

               $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeIN);
               $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeOUT);
               $th = $time1->diffInHours($time2);
               $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
               $totalhour = $th.".".$tm;

               table::attendance()->where([['idno', $idno],['date', $clockInDate]])->update(array(
                   'timeout' => $timeOUT,
                   'totalhours' => $totalhour,
                   'status_timeout' => $status_out)
               );

               return response()->json([
                   "type" => $type,
                   "time" => $time,
                   "date" => $date,
                   "lastname" => $lastname,
                   "firstname" => $firstname,
                   "mi" => $mi,
                   "success" => "Hello, " . $firstname . " " . $lastname . ". Time out is recorded at " . $time . " on " . $date,
               ]);
           }
        }
        if ($type == 'force_timeout')
        {

           $timeIN = table::attendance()->where([['idno', $idno], ['timeout', NULL]])->value('timein');
           $clockInDate = table::attendance()->where([['idno', $idno],['timeout', NULL]])->value('date');
           $hasout = table::attendance()->where([['idno', $idno],['date', $date]])->value('timeout');
           $timeOUT = date("Y-m-d h:i:s A", strtotime($date." ".$time));

           if($timeIN == NULL)
           {
               return response()->json([
                   "error" => "Please Clock In before Clocking Out."
               ]);
           }

           if ($hasout != NULL)
           {
               $hto = table::attendance()->where([['idno', $idno],['date', $date]])->value('timeout');
               $hto = date('h:i A', strtotime($hto));
               return response()->json([
                   "employee" => $employee,
                   "error" => "You already Time Out at ". $hto . " on " . $date,
               ]);

           }
           else {
               $sched_out_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('outime');

               $assigned_schedule_id = table::new_schedule()->where([['reference', $employee_id],['active_status', 1]] )->value('schedule_id');
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
                   $time_out_24h = date("H.i", strtotime($timeOUT));

                   if($time_out_24h >= $out_time)
                   {
                       $status_out = 'On Time';
                   } else {
                       $status_out = 'Early Departure';
                   }
               }

               $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeIN);
               $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeOUT);
               $th = $time1->diffInHours($time2);
               $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
               $totalhour = $th.".".$tm;

               table::attendance()->where([['idno', $idno],['date', $clockInDate]])->update(array(
                   'timeout' => $timeOUT,
                   'totalhours' => $totalhour,
                   'status_timeout' => $status_out)
               );

               return response()->json([
                   "type" => $type,
                   "time" => $time,
                   "date" => $date,
                   "lastname" => $lastname,
                   "firstname" => $firstname,
                   "mi" => $mi,
                   "success" => "Hello, " . $firstname . " " . $lastname . ". Time out is recorded at " . $time . " on " . $date,
               ]);
           }
        }
    }
}
