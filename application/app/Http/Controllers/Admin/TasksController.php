<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\People;
use App\Task;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        $employee = People::all();

        return view('admin.taskmanager', compact('tasks', 'employee'));
    }

    public function edit($id, Request $request)
    {

        return view('admin.edits.edit-task');

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
