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
use App\Task;

class Tasks{

    public $id = '';
    public $assigned_to ='';
    public $original_deadline = '';
    public $deadline = '';

    public function __construct($id, $assigned_to, $deadline, $original_deadline)
    {
        $this->id = $id;
        $this->assigned_to = $assigned_to;
        $this->original_deadline = $original_deadline;
        $this->deadline = $deadline;
    }

}

class Activity {

    public $employee = '';
    public $datetime = '';
    public $label = '';

    public function __construct($employee, $datetime, $label)
    {
        $this->employee = $employee;
        $this->datetime = $datetime;
        $this->label = $label;
    }

}



class DashboardController extends Controller
{
    // Admin Dashboard controller function.
    // Pulls all the data from database tables
    // and sends them to database view file which is only accessible by admin.
    public function index(Request $request)
    {
        if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }

        $datenow = date('m/d/Y');
        //$sh = $request->sh;

        $is_online = table::attendance()->where('date', $datenow)->pluck('idno');
        $is_online_arr = json_decode(json_encode($is_online), true);
        $is_online_now = count($is_online);

        $emp_ids = table::people()->pluck('idno');
        $emp_ids_arr = json_decode(json_encode($emp_ids), true);
        $is_offline_now = count(array_diff($emp_ids_arr, $is_online_arr));

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

            $a = table::attendance()
            ->latest('timein')
            ->take(4)
            ->get();

        $all_company = table::company()->count();
        $all_department = table::department()->count();

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

        $recent_breaks = table::daily_breaks()->latest('start_at')->take(6)->get();

        $activity_collection = collect([]);

        if ($a) {
          foreach ($a as $r_e) {
            $user = User::find($r_e->reference);
            if ($r_e->timein && $user) {
              $activity_collection->push(new Activity($user->name, $r_e->timein, 'Clock In'));
            }
            if ($r_e->timeout && $user) {
              $activity_collection->push(new Activity($user->name ,$r_e->timeout, 'Clock Out'));
            }
          }
        }

        if ($recent_breaks) {
          foreach ($recent_breaks as $r_b) {
            $user = User::find($r_b->reference);
            if ($r_b->start_at && $user) {
              $activity_collection->push(new Activity($user->name, $r_b->start_at, 'Break In'));
            }
            if ($r_b->end_at && $user) {
              $activity_collection->push(new Activity($user->name, $r_b->end_at, 'Break Out'));
            }
          }
        }

        $sortedActivities = Arr::sort($activity_collection, function($activity)
        {return $activity->datetime;});

        $tasks = Task::all();

        $task_collection = collect([]);

        if ($tasks) {
          foreach($tasks as $task){
            $extended_task = table::task_extension()->where('task_id', $task->id)->latest('new_deadline')->first();

            $user = User::find($task->reference);

            if ($extended_task && $user) {
              $task_collection->push(new Tasks($task->id, $user->name, $extended_task->new_deadline, $task->deadline));
            }
          }
        }
        return view('admin.dashboard', compact('emp_typeR', 'emp_typeT', $emp_allActive,'emp_leaves_pending', 'emp_leaves_approve', 'emp_leaves_all', 'emp_approved_leave','a', 'is_online_now', 'is_offline_now', 'sortedActivities', 'task_collection', 'all_company', 'all_department'));
    }

    // Attendance Details (Break history and Duration)
    // by the given attendace id.
    public function details(Request $request){
      if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }

        $attendanceID = request()->attendanceID;
        $theAttendance = table::attendance()->where('id', $attendanceID)->first();
        $theDate = date("Y-m-d", strtotime($theAttendance->created_at));

        $all_breaks = table::daily_breaks()->where('reference_id', $theAttendance->reference)->whereDate('start_at', $theDate)->get();
        return response()->json( array($all_breaks));
    }

}
