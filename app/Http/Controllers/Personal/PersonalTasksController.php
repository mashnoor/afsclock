<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\People;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PersonalTasksController extends Controller
{
    public function index()
    {
        $tasks = Task::where('reference', Auth::user()->reference)->get();
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
        $tasks = Task::where('reference', Auth::user()->reference)->get();
        return view('personal.personal-my-tasks', compact('tasks'));

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

        return redirect('/personal/tasks/view')->with('success','Task has been updated!');



    }

}
