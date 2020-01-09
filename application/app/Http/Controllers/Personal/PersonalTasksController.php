<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
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
