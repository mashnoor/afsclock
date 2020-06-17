<?php

namespace App\Http\Controllers\admin;
use DB;
use Carbon\Carbon;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    // The following controller function
    // queries employee attendace information and sends to the view file.
    public function index()
    {
        if (permission::permitted('attendance')=='fail'){ return redirect()->route('denied'); }

        $data = table::attendance()->orderBy('timein', 'desc')->get();
        $cc = table::settings()->value('clock_comment');

        return view('admin.attendance', compact('data', 'cc'));
    }

    // Clock in / out view file represented
    // by this controller function.
    public function clock()
    {
        return view('clock');
    }


    // The following controller fucntion allows
    // admin to edit attendace information of any employee.
    public function edit($id, Request $request)
    {
        if (permission::permitted('attendance-edit')=='fail'){ return redirect()->route('denied'); }

        $a = table::attendance()->where('id', $id)->first();
        $e_id = ($a->id == null) ? 0 : Crypt::encryptString($a->id) ;

        return view('admin.edits.edit-attendance', compact('a', 'e_id'));
    }

    // The delete function allows admin to delete
    // attendace information for any specific employee.
    // Takes an attendace id as an argument.
    public function delete($id, Request $request)
    {
        if (permission::permitted('attendance-delete')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('attendance');}

        $id = $request->id;
        table::attendance()->where('id', $id)->delete();

        return redirect('attendance')->with('success', 'Deleted!');
    }

    // Allow admin to update attendace information.
    public function update(Request $request)
    {
        if (permission::permitted('attendance-edit')=='fail') { return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('attendance');}

        $v = $request->validate([
            'id' => 'required|max:200',
            'idno' => 'required|max:100',
            'timein' => 'required|max:15',
            'timeout' => 'required|max:15',
            'reason' => 'required|max:255',
        ]);

        $id = Crypt::decryptString($request->id);
        $idno = $request->idno;
        $timeIN = date("Y-m-d h:i:s A", strtotime($request->timein_date." ".$request->timein));
        $timeOUT = date("Y-m-d h:i:s A", strtotime($request->timeout_date." ".$request->timeout));
        $reason = $request->reason;

        $sched_in_time = table::schedules()->where([
            ['idno', '=', $idno],
            ['archive', '=', '0'],
        ])->value('intime');

        if($sched_in_time == null)
        {
            $status_in = "Ok";
        } else {
            $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));
            $time_in_24h = date("H.i", strtotime($timeIN));

            if ($time_in_24h <= $sched_clock_in_time_24h)
            {
                $status_in = 'In Time';
            } else {
                $status_in = 'Late Arrival';
            }
        }

        $sched_out_time = table::schedules()->where([
            ['idno', '=', $idno],
            ['archive','=','0'],
        ])->value('outime');

        if($sched_out_time == null)
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

        $t1 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeIN);
        $t2 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeOUT);
        $th = $t1->diffInHours($t2);
        $tm = floor(($t1->diffInMinutes($t2) - (60 * $th)));
        $totalhour = $th.".".$tm;

        table::attendance()->where('id', $id)->update([
            'timein' => $timeIN,
            'timeout' => $timeOUT,
            'reason' => $reason,
            'totalhours' => $totalhour,
            'status_timein' => $status_in,
            'status_timeout' => $status_out,
        ]);

        return redirect('attendance')->with('success','Employee Attendance has been updated!');
    }

    // The following controller function allow admin to view the attendance
    // information in details on a modal.
    public function details(Request $request){

        if (permission::permitted('attendance-edit')=='fail') { return redirect()->route('denied'); }

        $attendanceID = request()->attendanceID;

        return response()->json($attendanceID);

        $theAttendance = table::attendance()->where('id', $attendanceID)->first();

        if ($theAttendance) {
          $all_breaks = table::daily_breaks()->where([['reference', $theAttendance->reference],['attendance_id', $theAttendance->id]])->get();

        }

        return response()->json( $all_breaks);

    }

}
