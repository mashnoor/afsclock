<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\User;


class Activity {

    public function __construct($employee, $datetime, $label)
    {
        $this->employee = $employee;
        $this->datetime = $datetime;
        $this->label = $label;
    }

}



class DashboardController extends Controller
{

    public function index(Request $request)
    {
        if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }

        $datenow = date('m/d/Y');
        //$sh = $request->sh;

        $is_online = table::attendance()->where('date', $datenow)->pluck('idno');
        $is_online_arr = json_decode(json_encode($is_online), true);
        $is_online_now = count($is_online);

        $emp_ids = table::companydata()->pluck('idno');
        $emp_ids_arr = json_decode(json_encode($emp_ids), true);
        $is_offline_now = count(array_diff($emp_ids_arr, $is_online_arr));

		$emp_all_type = table::people()
        ->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')
        ->where('tbl_people.employmentstatus', 'Active')
        ->orderBy('tbl_company_data.startdate', 'desc')
        ->take(8)
        ->get();

		$emp_typeR = table::people()
        ->where('employmenttype', 'Regular')
        ->where('employmentstatus', 'Active')
        ->count();

		$emp_typeT = table::people()
        ->where('employmenttype', 'Trainee')
        ->where('employmentstatus', 'Active')
        ->count();

		$emp_allActive = table::people()
        ->where('employmentstatus', 'Active')
        ->count();

        $a = table::daily_attendance()
        ->latest('created_at')
        ->take(4)
        ->get();

        $emp_approved_leave = table::leaves()
        ->where('status', 'Approved')
        ->orderBy('leavefrom', 'desc')
        ->take(8)
        ->get();

		$emp_leaves_approve = table::leaves()
        ->where('status', 'Approved')
        ->count();

		$emp_leaves_pending = table::leaves()
        ->where('status', 'Pending')
        ->count();

		$emp_leaves_all = table::leaves()
        ->where('status', 'Approved')
        ->orWhere('status', 'Pending')
        ->count();


        $recent_entries = table::daily_entries()->latest('start_at')->take(6)->get();
        $recent_breaks = table::daily_breaks()->latest('start_at')->take(6)->get();


        $activity_collection = collect([]);

        foreach ($recent_entries as $r_e) {
          $user = User::find($r_e->reference);
          $activity_collection->push(new Activity($user->name, $r_e->start_at, 'Clock In'));
          $activity_collection->push(new Activity($user->name ,$r_e->end_at, 'Clock Out'));
        }

        foreach ($recent_breaks as $r_b) {
          $user = User::find($r_b->reference);
          $activity_collection->push(new Activity($user->name, $r_b->start_at, 'Break In'));
          $activity_collection->push(new Activity($user->name, $r_b->end_at, 'Break Out'));
        }


        $sortedActivities = Arr::sort($activity_collection, function($activity)
        {return $activity->datetime;});

        // dd($sortedActivities);

        return view('admin.dashboard', compact('emp_typeR', 'emp_typeT', 'emp_allActive', 'emp_leaves_pending', 'emp_leaves_approve', 'emp_leaves_all', 'emp_approved_leave', 'emp_all_type','a', 'is_online_now', 'is_offline_now', 'sortedActivities'));
    }


    public function details(Request $request){

        $attendanceID = request()->attendanceID;

        $theAttendance = table::daily_attendance()->where('id', $attendanceID)->first();

        $theDate = date("Y-m-d", strtotime($theAttendance->created_at));

        $all_entries = table::daily_entries()->where('reference_id', $theAttendance->reference)->whereDate('start_at', $theDate)->get();
        $all_breaks = table::daily_breaks()->where('reference_id', $theAttendance->reference)->whereDate('start_at', $theDate)->get();

        return response()->json( array($all_entries, $all_breaks));

    }

}
