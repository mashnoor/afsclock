<?php

namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonalAttendanceController extends Controller
{
    public function index()
    {
        $i = \Auth::user()->idno;
//        $a = table::attendance()->where('idno', $i)->get();
        $a = table::daily_attendance()->where('idno', $i)->get();

        $employee_reference_id = \Auth::user()->reference;
        $all_entries = table::daily_entries()->where('reference', $employee_reference_id)->get();

        $all_breaks = table::daily_breaks()->where('reference', $employee_reference_id)->get();
        return view('personal.personal-attendance-view', compact('a', 'all_entries', 'all_breaks'));
    }


    public function details(Request $request){

        $attendanceID = request()->attendanceID;

        $theAttendance = table::daily_attendance()->where('id', $attendanceID)->first();

        $theDate = date("Y-m-d", strtotime($theAttendance->created_at));

        $all_entries = table::daily_entries()->where('reference', $theAttendance->reference)->whereDate('start_at', $theDate)->get();
        $all_breaks = table::daily_breaks()->where('reference', $theAttendance->reference)->whereDate('start_at', $theDate)->get();


        return response()->json( array($all_entries, $all_breaks));

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
