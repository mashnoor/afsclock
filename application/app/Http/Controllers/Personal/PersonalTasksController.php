<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalTasksController extends Controller
{
    public function index()
    {
        $tasks = Task::where('reference', Auth::user()->reference)->get();
        return view('personal.personal-tasks-view', compact('tasks'));
    }
}
