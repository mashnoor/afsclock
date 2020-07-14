<?php

namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PersonalAttendanceController extends Controller
{
    public function index()
    {
        $i = \Auth::user()->idno;
       $a = table::attendance()->where('idno', $i)->get();
        // $a = table::daily_attendance()->where('idno', $i)->get();

        $employee_reference_id = \Auth::user()->reference;
        // $all_entries = table::daily_entries()->where('reference', $employee_reference_id)->get();

        $all_breaks = table::daily_breaks()->where('reference', $employee_reference_id)->get();
        return view('personal.personal-attendance-view', compact('a', 'all_breaks'));
    }

    public function details(Request $request){

        $attendanceID = request()->attendanceID;

        // return $attendanceID;

        if ($attendanceID) {
          $all_breaks = table::daily_breaks()->where('attendance_id', $attendanceID)->get();

          // return count($all_breaks);

          if (count($all_breaks) > 0) {



            $total_break_minute = 0;

            foreach ($all_breaks as $break) {
              $time1 = Carbon::parse($break->start_at);
              $time2 = Carbon::parse($break->end_at);

              // $th = $time1->diffInHours($time2);
              $tm = ($time1->diffInMinutes($time2));
              // $totalhour = $th.".".$tm;

              // $total_hours += $th;
              $total_break_minute += $tm;
            }

            if ($total_break_minute >= 60 ) {

              $converted_hour_from_minute = (int)($total_break_minute / 60);
              $remaining_minutes = $total_break_minute - ($converted_hour_from_minute * 60);
              $total_break = "Total ".$converted_hour_from_minute." Hour and ".$remaining_minutes." Minutes";
              return response()->json([$all_breaks, $total_break]);
            }else {
              $total_break = "Total ".$total_break_minute." Minutes";
              return response()->json([$all_breaks, $total_break]);
            }


            return $converted_hour_from_minute;

            return response()->json([$all_breaks, $total_working_hour]);
          }else {
            return response()->json(NULL);
          }

        }else {
          return response()->json(NULL);
        }
    }


    public function getPA(Request $request)
    {
		$id = \Auth::user()->idno;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;

        if($datefrom == '' || $dateto == '' )
        {
             $data = table::attendance()
             ->select('date', 'timein', 'timeout', 'totalhours', 'status_timein', 'status_timeout')
             ->where('idno', $id)
             ->get();


//             $data = table::attendance()->select('created_at', 'totalhours', 'status_timein', 'status_timeout')->where('idno', $id)->get();


			return response()->json($data);

		} elseif ($datefrom !== '' AND $dateto !== '') {
            $data = table::attendance()
            ->select('date', 'timein', 'timeout', 'totalhours', 'status_timein', 'status_timeout')
            ->where('idno', $id)
            ->whereBetween('date', [$datefrom, $dateto])
            ->get();

			return response()->json($data);
        }
	}
}
