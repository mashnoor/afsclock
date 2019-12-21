<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 4.2
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class SchedulesController extends Controller
{
    public function index() 
    {
        if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }
        
        $employee = table::people()->get();
        $schedules = table::schedules()->get();
    
        return view('admin.schedules', compact('employee', 'schedules'));
    }

    public function add(Request $request) 
    {
        if (permission::permitted('schedules-add')=='fail'){ return redirect()->route('denied'); }
        if($request->sh == 2){return redirect()->route('schedule');}

        $v = $request->validate([
            'id' => 'required|max:20',
            'employee' => 'required|max:100',
            'intime' => 'required|max:15',
            'outime' => 'required|max:15',
            'datefrom' => 'required|date|max:15',
            'dateto' => 'required|date|max:15',
            'hours' => 'required|max:3',
            'restday' => 'required|max:155',
        ]);

    	$id = $request->id;
		$employee = mb_strtoupper($request->employee);
		$intime = $request->intime;
		$outime = $request->outime;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;
		$hours = $request->hours;
        $restday = ($request->restday != null) ? implode(', ', $request->restday) : null ;
        
        $ref = table::schedules()->where([['reference', $id],['archive', 0]])->exists();

        if ($ref == 1) 
        {
            return redirect('schedules')->with('error', 'Oops! This employee has schedule already. Please arhive the present schedule to add new schedule.');
        }

        $emp_id = table::companydata()->where('reference', $id)->value('idno');
    
        table::schedules()->where('id', $id)->insert([
        	'reference' => $id,
        	'idno' => $emp_id,
        	'employee' => $employee,
        	'intime' => $intime,
        	'outime' => $outime,
        	'datefrom' => $datefrom,
        	'dateto' => $dateto,
        	'hours' => $hours,
        	'restday' => $restday,
        	'archive' => '0',
    	]);

    	return redirect('schedules')->with('success', 'New Schedule Added!');
	}

    public function edit($id, Request $request) 
    {
        if (permission::permitted('schedules-edit')=='fail'){ return redirect()->route('denied'); }

        $s = table::schedules()->where('id', $id)->first();
        $r = explode(', ', $s->restday);
        $e_id = ($s->id == null) ? 0 : Crypt::encryptString($s->id) ;
        
        return view('admin.edits.edit-schedule', compact('s','r', 'e_id'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('schedules-edit')=='fail'){ return redirect()->route('denied'); }
        if($request->sh == 2){return redirect()->route('schedule');}

        $v = $request->validate([
            'id' => 'required|max:200',
            'intime' => 'required|max:15',
            'outime' => 'required|max:15',
            'datefrom' => 'required|date|max:15',
            'dateto' => 'required|date|max:15',
            'hours' => 'required|max:3',
            'restday' => 'required|max:155',
        ]);

        $id = Crypt::decryptString($request->id);
        $intime = $request->intime;
        $outime = $request->outime;
        $datefrom = $request->datefrom; 
        $dateto = $request->dateto; 
        $hours = $request->hours;
        $restday = implode(', ', $request->restday);

        table::schedules()
        ->where('id', $id)
        ->update([
                'intime' => $intime,
                'outime' => $outime,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'hours' => $hours,
                'restday' => $restday,
        ]);

        return redirect('schedules')->with('success', 'Schedule has been updated!');
    }

    public function delete($id, Request $request) 
    {
        if (permission::permitted('schedules-delete')=='fail'){ return redirect()->route('denied'); }
        if($request->sh == 2){return redirect()->route('schedule');}

        table::schedules()->where('id', $id)->delete();

        return redirect('schedules')->with('success', 'Deleted!');
    }

    public function archive($id, Request $request)
    {
		if (permission::permitted('schedules-archive')=='fail'){ return redirect()->route('denied'); }
        if($request->sh == 2){return redirect()->route('schedule');}
        
		$id = $request->id;
		table::schedules()->where('id', $id)->update(['archive' => '1']);

    	return redirect('schedules')->with('success','Schedule has been archived.');
   	}

}