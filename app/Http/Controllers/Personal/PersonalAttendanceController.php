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

        if ($attendanceID) {
          $all_breaks = table::daily_breaks()->where('attendance_id', $attendanceID)->get();

          $total_working_hour = 0;
          $total_hours = 0;
          $toal_working_minute_new = 0;

          foreach ($all_breaks as $break) {
            $time1 = Carbon::parse($break->start_at);
            $time2 = Carbon::parse($break->end_at);

            $th = $time1->diffInHours($time2);
            $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
            // $totalhour = $th.".".$tm;

            $total_hours += $th;
            $toal_working_minute_new += $tm;

          }

          $total_working_hour = $total_hours.".".$toal_working_minute_new;


          return response()->json([$all_breaks, $total_working_hour]);
        }else {
          return response()->json();
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
