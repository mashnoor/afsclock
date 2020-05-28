<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\People;
use App\Task;
use Illuminate\Http\Request;
use App\Classes\table;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Classes\permission;

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

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        $employee = People::all();

        return view('admin.taskmanager', compact('tasks', 'employee'));
    }

    public function edit($id)
    {
        $task = Task::find($id);

        $e_id = Crypt::encryptString($id);




        return view('admin.edits.edit-task', compact('task', 'e_id'));

    }


    public function update(Request $request)
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

        return redirect('taskmanager')->with('success','Task has been updated!');



    }

    public function delete($id)
    {


       Task::where('id', $id)->delete();

        return redirect('taskmanager')->with('success', 'Deleted!');
    }


    public function add(Request $request)
    {
        //if (permission::permitted('schedules-add')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('schedule');}

        $v = $request->validate([
            'employee' => 'required',
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required',
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
        return redirect('taskmanager')->with('success', 'Task assigned successfully!');
    }


    public function task_details($id){
      if (permission::permitted('dashboard')=='fail'){ return redirect()->route('denied'); }
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

        // dd($task_history);

      return view('admin.task_details', compact('task', 'task_history', 'assigned_to', 'assigned_by'));
    }
}
