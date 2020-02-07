<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 4.2
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClockController extends Controller
{
    
    public function clock()
    {
        //return date('h:i:s A');
        $data = table::settings()->where('id', 1)->first();
        $cc = $data->clock_comment;
        $tz = $data->timezone;

        return view('clock_cam', compact('cc', 'tz'));
    }
    public function test_clock()
    {
        $data = table::settings()->where('id', 1)->first();
        $cc = $data->clock_comment;
        $tz = $data->timezone;

        return view('clock', compact('cc', 'tz'));
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
            $has = table::attendance()->where([['idno', $idno],['date', $date]])->exists();

            if ($has == 1) 
            {
                $hti = table::attendance()->where([['idno', $idno],['date', $date]])->value('timein');
                $hti = date('h:i A', strtotime($hti));
                return response()->json([
                    "employee" => $employee,
                    "error" => "You already Time In today at ".$hti,
                ]);

            } else {
                $last_in_notimeout = table::attendance()->where([['idno', $idno],['timeout', NULL]])->count();

                if($last_in_notimeout >= 1)
                {
                    return response()->json([
                        "error" => "Please clock-out from your last Clock In."
                    ]);

                } else {

                    $sched_in_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('intime');
                    
                    if($sched_in_time == NULL)
                    {
                        $status_in = "Ok";
                    } else {
                        $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));
                        $time_in_24h = date("H.i", strtotime($time));

                        if ($time_in_24h <= $sched_clock_in_time_24h) 
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
                    ]);
                }
            }
        }


        if($type == "break")
        {
            $has = table::attendance()->where([['idno', $idno],['date', $date]])->exists();
            if ($has == 1)
            {
                //Check if already break in
                $doesnt_have_break_in = table::attendance()->where([['idno', $idno],['date', $date], ['break_in', NULL]])->exists();
                $doesnt_have_break_out = table::attendance()->where([['idno', $idno],['date', $date], ['break_out', NULL]])->exists();

                if($doesnt_have_break_in == 1)
                {
                    table::attendance()->where([['idno', $idno],['date', $date]])->update(array(
                            "break_in" => $date." ".$time,
                        )
                    );
                    return response()->json([
                        "type" => "break_in",
                        "time" => $time,
                        "date" => $date,
                        "lastname" => $lastname,
                        "firstname" => $firstname,
                        "mi" => $mi,
                    ]);
                }
                else if($doesnt_have_break_out == 1)
                {
                    //As already break in, so its time for break out
                    table::attendance()->where([['idno', $idno],['date', $date]])->update(array(
                            "break_out" => $date." ".$time,
                        )
                    );
                    return response()->json([
                        "type" => "break_out",
                        "time" => $time,
                        "date" => $date,
                        "lastname" => $lastname,
                        "firstname" => $firstname,
                        "mi" => $mi,
                    ]);
                }
                else
                {
                    //can't break in/out
                    $hto = table::attendance()->where([['idno', $idno],['date', $date]])->value('break_out');
                    $hto = date('h:i A', strtotime($hto));
                    return response()->json([
                        "employee" => $employee,
                        "error" => "You already break out Out today at ".$hto,
                    ]);

                }


            }
        }

        if ($type == 'timeout') 
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
                    "error" => "You already Time Out today at ".$hto,
                ]);

            } else {
                $sched_out_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('outime');
                
                if($sched_out_time == NULL) 
                {
                    $status_out = "Ok";
                } else {
                    $sched_clock_out_time_24h = date("H.i", strtotime($sched_out_time));
                    $time_out_24h = date("H.i", strtotime($timeOUT));
                    
                    if($time_out_24h >= $sched_clock_out_time_24h) 
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
                ]);
            }
        }
    }
}
