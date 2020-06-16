<?php

namespace App\Http\Controllers\personal;
use App\Task;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    public $datetime ='';
    public $label = '';

    public function __construct($datetime, $label)
    {
        $this->datetime = $datetime;
        $this->label = $label;
    }

}


class PersonalDashboardController extends Controller
{
    public function index()
    {

        $data = table::settings()->where('id', 1)->first();
        $tz = $data->timezone;

        $id = \Auth::user()->reference;
        $sm = date('m/01/Y');
        $em = date('m/31/Y');

        // $cs = table::schedules()->where([
        //     ['reference', $id],
        //     ['archive', '0']
        // ])->first();

        // $ps = table::schedules()->where([
        //     ['reference', $id],
        //     ['archive', '1'],
        // ])->take(8)->get();

        $al = table::leaves()->where([['reference', $id], ['status', 'Approved']])->count();
        $ald = table::leaves()->where([['reference', $id], ['status', 'Approved']])->take(8)->get();
        $pl = table::leaves()->where([['reference', $id], ['status', 'Declined']])->orWhere([['reference', $id], ['status', 'Pending']])->count();
        $a = table::attendance()->where('reference', $id)->latest('timein')->take(4)->get();

        $la = table::attendance()->where([['reference', $id], ['status_timein', 'Late Arrival']])->whereBetween('date', [$sm, $em])->count();
        $ed = table::attendance()->where([['reference', $id], ['status_timeout', 'Early Departure']])->whereBetween('date', [$sm, $em])->count();

        $tasks = Task::where('reference', $id)->get();

        $pending_tasks = Task::where([['reference', $id],['done_status', 0]])->get();

        $no_of_pending_tasks = Task::where([['reference', $id], ['finishdate', null]])->count();
        $no_of_done_tasks = $tasks->count()-$no_of_pending_tasks;

        // $recent_entries = table::daily_entries()->where('reference', $id)->latest('start_at')->take(4)->get();
        $recent_breaks = table::daily_breaks()->where([['reference', $id]])->latest('start_at')->take(4)->get();


        $activity_collection = collect([]);



        foreach ($a as $r_e) {
          if ($r_e->timein) {
            $activity_collection->push(new Activity($r_e->timein, 'Clock In'));
          }
          if ($r_e->timeout) {
            $activity_collection->push(new Activity($r_e->timeout, 'Clock Out'));
          }
        }

        foreach ($recent_breaks as $r_b) {
          if ($r_b->start_at) {
            $activity_collection->push(new Activity($r_b->start_at, 'Break In'));
          }
          if ($r_b->end_at) {
            $activity_collection->push(new Activity($r_b->end_at, 'Break Out'));
          }
        }


        $sortedActivities = Arr::sort($activity_collection, function($activity)
        {return $activity->datetime;});

        // dd($sortedActivities);
        $current_user = Auth::user();

        // dd($current_user->name);

        $tasks = Task::where('assigned_by', $current_user->id)->get();

        $task_collection = collect([]);

        foreach($tasks as $task){
          $extended_task = table::task_extension()->where('task_id', $task->id)->latest('new_deadline')->first();

          $user = User::find($task->reference);

          if ($extended_task) {
            $task_collection->push(new Tasks($task->id, $user->name, $extended_task->new_deadline, $task->deadline));
          }
        }

        // dd($task_collection);

        return view('personal.personal-dashboard', compact('al', 'pl', 'ald', 'a', 'la', 'ed', 'tz', 'no_of_pending_tasks', 'no_of_done_tasks', 'tasks', 'pending_tasks', 'sortedActivities','task_collection'));
    }



    // Pending task reminder
    public function task_reminder(Request $request){

      $reference_id = request()->reference_id;

      $pending_tasks = Task::where([['reference', $reference_id],['done_status', 0]])->get();

      $current_date_time = Carbon::now()->toDateTimeString();



      if($pending_tasks){
        $minimum_difference = 0;
        $given_time = 0;
        $pending_task = null;
          foreach ($pending_tasks as $single_task){
              $deadline = strtotime($single_task->deadline);
              $now = strtotime($current_date_time);

              $created_at = strtotime($single_task->created_at);


              if ($deadline < $now) {
                continue;
              }else{
                $total_given_time = round(($deadline - $created_at)/3600, 2);
                $remaining_time = round(($deadline - $now)/3600, 2);

                if ($minimum_difference == 0 || $remaining_time < $minimum_difference) {
                  $minimum_difference = $remaining_time;
                  $given_time = $total_given_time;
                  $pending_task = $single_task;
                }else{
                  continue;
                }
              }

          }

          $twenty_percent = $given_time / 10 * 2;

          if ($minimum_difference < $twenty_percent) {
            return response()->json($pending_task);
          }

          return response()->json("");
      }

      return response()->json("");

    }
}
