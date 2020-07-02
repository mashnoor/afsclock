<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\People;
use App\Task;
use App\Classes\table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\User;


class TasksHistory{

    public $id = '';
    public $datetime ='';
    public $reason = '';
    public $new_deadline = '';


    public function __construct($id, $datetime, $reason, $new_deadline)
    {
        $this->id = $id;
        $this->datetime = $datetime;
        $this->reason = $reason;
        $this->new_deadline = $new_deadline;
    }

}

class Tasks{

    public $id = '';
    public $assigned_by ='';
    public $title = '';
    public $deadline = '';
    public $finish_date = '';
    public $status = '';
    public $comment = '';

    public function __construct($id, $assigned_by, $title, $deadline, $finish_date, $status, $comment)
    {
        $this->id = $id;
        $this->assigned_by = $assigned_by;
        $this->title = $title;
        $this->deadline = $deadline;
        $this->finish_date = $finish_date;
        $this->status = $status;
        $this->comment = $comment;
    }

}

class PersonalTasksController extends Controller
{
    public function index()
    {
        $tasks = Task::where('reference', Auth::user()->id)->get();
        return view('personal.personal-tasks-view', compact('tasks'));
    }

    public function edit($id)
    {

        $task = Task::find($id);
        $e_id = Crypt::encryptString($id);

        return view('personal.edits.personal-task-edit', compact('task', 'e_id'));

    }



    public function myTasks()
    {
        $tasks = Task::where('reference', Auth::user()->id)->get();

        $task_collection = collect([]);

        foreach($tasks as $task){
          $extended_task = table::task_extension()->where('task_id', $task->id)->latest('new_deadline')->first();

          $assigned_by = $task->assignedBy->firstname . " " . $task->assignedBy->lastname;


          if ($extended_task) {

            $task_collection->push(new Tasks($task->id, $assigned_by, $task->title, $extended_task->new_deadline, $task->finishdate, $task->status, $task->comment));
          }
          else {
            $task_collection->push(new Tasks($task->id, $assigned_by, $task->title, $task->deadline, $task->finishdate, $task->status, $task->comment));
          }
        }



        return view('personal.personal-my-tasks', compact('tasks', 'task_collection'));

    }

    public function add(Request $request)
    {
        //if (permission::permitted('schedules-add')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('schedule');}

        $v = $request->validate([
            'employee' => 'required',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $id = $request->id;
        $assigned_by = Auth::id();
        $title = $request->title;
        $descriptopn = $request->description;
        $deadline = $request->deadline;


        $task = new Task();
        $task->reference = $id;
        $task->assigned_by = $assigned_by;
        $task->title = $title;
        $task->description = $descriptopn;
        $task->deadline = $deadline;
        $task->done_status = 0;

        $task->save();



        return redirect('personal/tasks/assignatask')->with('success', 'Task assigned successfully!');
    }

    public function update_assignATask(Request $request)
    {
        //if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('leave');}

        $v = $request->validate([
            'id' => 'required|max:200',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date'
        ]);

        $id = Crypt::decryptString($request->id);


        $task = Task::find($id);



        $task->title = $request->title;
        $task->description = $request->description;
        $task->deadline = $request->deadline;
        $task->comment = $request->comment;

        $task->save();

        return redirect('personal/tasks/assignatask')->with('success','Task has been updated!');



    }

    public function delete($id)
    {


        Task::where('id', $id)->delete();

        return redirect('personal/tasks/mytasks')->with('success', 'Deleted!');
    }

    public function delete_assignATask($id)
    {


        Task::where('id', $id)->delete();

        return redirect('personal/tasks/assignatask')->with('success', 'Deleted!');
    }


    public function edit_assignATask($id)
    {
        $task = Task::find($id);

        $e_id = Crypt::encryptString($id);


        return view('personal.edits.personal-task-assign-a-task-edit', compact('task', 'e_id'));

    }

    public function assignATask()
    {

        $tasks = Task::where('assigned_by', Auth::id())->get();

        $employee = People::all();

        return view('personal.personal-tasks-assign-a-task', compact('tasks', 'employee'));

    }

    public function update(Request $request)
    {
        //if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('leave');}

        $v = $request->validate([

            'comment' => 'required'
        ]);

        $id = Crypt::decryptString($request->id);


        $task = Task::find($id);

        $task->comment = $request->comment;
        $task->finishdate = date('Y-m-d');

        $task->save();

        return redirect('personal/tasks/mytasks')->with('success','Task has been updated!');

    }


    public function extend_deadline($id){
      $task = Task::find($id);
      return view('personal.personal-tasks-extend-deadline', compact('task'));
    }


    public function update_deadline(Request $request){



      $v = $request->validate([
          'reason' => 'required',
          'new_deadline' => 'required',
      ]);

      $task_id = $request->task_id;
      $new_deadline = $request->new_deadline;
      $reason = $request->reason;

      table::task_extension()->insert(
          ['task_id' => $task_id, 'new_deadline' => $new_deadline, 'reason' => $reason, 'created_at' => Carbon::now()]
      );


      return redirect('personal/tasks/mytasks')->with('success','Task has been updated!');
    }


    public function task_details($id){

      $task = Task::find($id);

      $task_history = collect([]);


        $extended_deadlines = table::task_extension()->where('task_id', $task->id)->get();

        $assigned_to = User::find($task->reference);
        $assigned_by = User::find($task->assigned_by);

        // dd($extended_deadlines);

        if ($extended_deadlines) {
          foreach ($extended_deadlines as $data) {
            $task_history->push(new TasksHistory($task->id, $data->created_at, $data->reason, $data->new_deadline));
          }
        }

      return view('personal.task_details', compact('task', 'task_history', 'assigned_to', 'assigned_by'));
    }

}
