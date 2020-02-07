<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\People;
use App\Task;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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



        return redirect('taskmanager')->with('success', 'Task assigned successfully!');
    }
}
