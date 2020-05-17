<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Carbon\Carbon;
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

    // Schedule temlate page
    public function templates(){
      // Checks user permission
      if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }

      $templates = table::sch_template()->get();

      // dd($templates);

      return view('admin.schedule_templates', compact('templates'));

    }

    // Create template page
    public function create_templates(){
      // Checks user permission
      if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }

      return view('admin.create-schedule-template');
    }

    // Add template
    public function add_templates(Request $request){
      // Checks user permission
      if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }

      $name = $request->template_name;

      $sat_intime = $request->sat_time_in;
      $sat_outime = $request->sat_time_out;

      $saturday = (string)$sat_intime . "-" . (string)$sat_outime;

      $sun_intime = $request->sun_time_in;
      $sun_outime = $request->sun_time_out;

      $sunday = (string)$sun_intime . "-" . (string)$sun_outime;

      $mon_intime = $request->mon_time_in;
      $mon_outime = $request->mon_time_out;

      $monday = (string)$mon_intime . "-" . (string)$mon_outime;

      $tue_intime = $request->tue_time_in;
      $tue_outime = $request->tue_time_out;

      $tuesday = (string)$tue_intime . "-" . (string)$tue_outime;

      $wed_intime = $request->wed_time_in;
      $wed_outime = $request->wed_time_out;

      $wednesday = (string)$wed_intime . "-" . (string)$wed_outime;

      $thu_intime =  $request->thu_time_in;
      $thu_outime =  $request->thu_time_out;

      $thursday = (string)$thu_intime . "-" . (string)$thu_outime;

      $fri_intime = $request->fri_time_in;
      $fri_outime = $request->fri_time_out;

      $friday = (string)$fri_intime . "-" . (string)$fri_outime;

      $break_allowence = $request->break_allowence;

      $restday = ($request->restday != null) ? implode(', ', $request->restday) : null ;

      // dd($name,$saturday,$sunday,$monday, $tuesday, $wednesday, $thursday, $friday, $break_allowence, $restday);

      DB::table('schedule_template')->insert(['name' => $name,'saturday' => $saturday, 'sunday' => $sunday, 'monday' => $monday, 'tuesday' => $tuesday, 'wednesday' => $wednesday, 'thursday' => $thursday, 'friday' => $friday, 'break_allowence' => $break_allowence, 'restdays' => $restday, 'created_at' => Carbon::now() ]);;

      return redirect('/schedules/templates')->with('success', 'Schedule has been created Successfully!');;

    }





    public function add(Request $request)
    {
        if (permission::permitted('schedules-add')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('schedule');}

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
        //if($request->sh == 2){return redirect()->route('schedule');}

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
        //if($request->sh == 2){return redirect()->route('schedule');}

        table::schedules()->where('id', $id)->delete();

        return redirect('schedules')->with('success', 'Deleted!');
    }

    public function archive($id, Request $request)
    {
		if (permission::permitted('schedules-archive')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('schedule');}

		$id = $request->id;
		table::schedules()->where('id', $id)->update(['archive' => '1']);

    	return redirect('schedules')->with('success','Schedule has been archived.');
   	}

}
