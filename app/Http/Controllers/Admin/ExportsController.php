<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class ExportsController extends Controller
{

	function company(Request $request)
	{
		if (permission::permitted('company')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('company');}

		$date = date('Y-m-d');
        $time = date('h-i-sa');
		$file = 'companies-'.$date.'T'.$time.'.csv';

		$c = table::company()->get();

		Storage::put($file, '', 'private');

		foreach ($c as $d)
		{
		    Storage::prepend($file, $d->id .','. $d->company);
		}

		Storage::prepend($file, '"ID"' .','. 'COMPANY');

		return Storage::download($file);
    }

	function department(Request $request)
	{
		if (permission::permitted('departments')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('department');}

		$d = table::department()->get();

		$date = date('Y-m-d');
        $time = date('h-i-sa');
		$file = 'departments-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($d as $i)
		{
		    Storage::prepend($file, $i->id .','. $i->department);
		}

		Storage::prepend($file, '"ID"' .','. 'DEPARTMENT');

		return Storage::download($file);
    }

	function jobtitle(Request $request)
	{
		if (permission::permitted('jobtitles')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('jobtitle');}

		$j = table::jobtitle()->get();

		$date = date('Y-m-d');
        $time = date('h-i-sa');
		$file = 'jobtitles-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($j as $d)
		{
		    Storage::prepend($file, $d->id .','. $d->jobtitle .','. $d->dept_code);
		}

		Storage::prepend($file, '"ID"' .','. 'DEPARTMENT' .','. 'DEPARTMENT CODE');

		return Storage::download($file);
    }

	function leavetypes(Request $request)
	{
		if (permission::permitted('leavetypes')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('leavetype');}

		$l = table::leavetypes()->get();

		$date = date('Y-m-d');
        $time = date('h-i-sa');
		$file = 'leavetypes-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($l as $d)
		{
		    Storage::prepend($file, $d->id .','. $d->leavetype .','. $d->limit .','. $d->percalendar);
		}

		Storage::prepend($file, '"ID"' .','. 'LEAVE TYPE' .','. 'LIMIT' .','. 'TYPE');

		return Storage::download($file);
    }

	function employeeList()
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }

		$p = table::people()->get();

		$date = date('Y-m-d');
        $time = date('h-i-sa');
		$file = 'employee-lists-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($p as $d)
		{
		    Storage::prepend($file, $d->id .','. $d->lastname.' '.$d->firstname.' '.$d->mi .','. $d->age .','. $d->gender .','. $d->civilstatus .','. $d->mobileno .','. $d->emailaddress .','. $d->employmenttype .','. $d->employmentstatus);
		}

		Storage::prepend($file, '"ID"' .','. 'EMPLOYEE' .','. 'AGE' .','. 'GENDER' .','. 'CIVILSTATUS' .','. 'MOBILE NUMBER' .','. 'EMAIL ADDRESS' .','. 'EMPLOYMENT TYPE' .','. 'EMPLOYMENT STATUS');

		return Storage::download($file);
	}

	// Employee attendance report CSV file export function.
	function attendanceReport(Request $request)
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }
		// $id =(int)$request->emp_id;
		$name = $request->smartsearch;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;

		// dd($name);



		if ($name == null AND $datefrom == null AND $dateto == null)
		{
			$data = table::attendance()->get();

			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'attendance-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. date('d-m-Y', strtotime($d->timein)) .','. $d->employee .','. date('H:i:s', strtotime($d->timein)) .','. date('H:i:s', strtotime($d->timeout)) .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'DATE' .','. 'EMPLOYEE' .','. 'TIME IN' .','. 'TIME OUT' .','. 'TOTAL HOURS' .','. 'STATUS-IN' .','. 'STATUS-OUT');

			return Storage::download($file);
		}

		if ($name !== null AND $datefrom !== null AND $dateto !== null)
		{
			// $data = table::attendance()->where('reference', $id)->whereBetween('created_at', [$datefrom, $dateto])->get();

			$data = table::attendance()->where('employee','LIKE','%'.$name.'%')->whereBetween('timein', [$datefrom, $dateto])->orWhere('idno','LIKE','%'.$name.'%')->whereBetween('timein', [$datefrom, $dateto])->get();


			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'attendance-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. date('d-m-Y', strtotime($d->timein)) .','. $d->employee .','. date('H:i:s', strtotime($d->timein)) .','. date('H:i:s', strtotime($d->timeout)) .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);

				// Storage::prepend($file, $d->id .','. $d->idno .','. $d->created_at .','. '"'.$d->employee.'"' .','. $d->timein .','. $d->timeout .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'DATE' .','. 'EMPLOYEE' .','. 'TIME IN' .','. 'TIME OUT' .','. 'TOTAL HOURS' .','. 'STATUS-IN' .','. 'STATUS-OUT');

			return Storage::download($file);
		}

		if($name !== null AND $datefrom == null AND $dateto == null )
		{
			$data = table::attendance()->where('employee','LIKE','%'.$name.'%')->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'attendance-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. date('d-m-Y', strtotime($d->timein)) .','. $d->employee .','. date('H:i:s', strtotime($d->timein)) .','. date('H:i:s', strtotime($d->timeout)) .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);

				// Storage::prepend($file, $d->id .','. $d->idno .','. $d->created_at .','. '"'.$d->employee.'"' .','. $d->timein .','. $d->timeout .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'DATE' .','. 'EMPLOYEE' .','. 'TIME IN' .','. 'TIME OUT' .','. 'TOTAL HOURS' .','. 'STATUS-IN' .','. 'STATUS-OUT');

			return Storage::download($file);
		}

		if ($name == null AND $datefrom !== null AND $dateto !== null)
		{
			$data = table::attendance()->whereBetween('timein', [$datefrom, $dateto])->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'attendance-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. date('d-m-Y', strtotime($d->timein)) .','. $d->employee .','. date('H:i:s', strtotime($d->timein)) .','. date('H:i:s', strtotime($d->timeout)) .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);

				// Storage::prepend($file, $d->id .','. $d->idno .','. $d->created_at .','. '"'.$d->employee.'"' .','. $d->timein .','. $d->timeout .','. $d->totalhours .','. $d->status_timein .','. $d->status_timeout);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'DATE' .','. 'EMPLOYEE' .','. 'TIME IN' .','. 'TIME OUT' .','. 'TOTAL HOURS' .','. 'STATUS-IN' .','. 'STATUS-OUT');

			return Storage::download($file);
		}

		return redirect('reports/employee-attendance')->with('error', 'Whoops! Please provide date range or select employee.');
	}

	function leavesReport(Request $request)
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }
		$id = $request->emp_id;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;

		if ($id == null AND $datefrom == null AND $dateto == null)
		{
			$data = table::leaves()->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'leave-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. '"'.$d->employee.'"' .','. $d->type .','. $d->leavefrom .','. $d->leaveto .','. $d->reason .','. $d->status);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'EMPLOYEE' .','. 'TYPE' .','. 'LEAVE FROM' .','. 'LEAVE TO' .','. 'REASON' .','. 'STATUS');

			return Storage::download($file);
		}

		if ($id !== null AND $datefrom !== null AND $dateto !== null)
		{
			$data = table::leaves()->where('idno', $id)->whereBetween('leavefrom', [$datefrom, $dateto])->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'leave-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. '"'.$d->employee.'"' .','. $d->type .','. $d->leavefrom .','. $d->leaveto .','. $d->reason .','. $d->status);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'EMPLOYEE' .','. 'TYPE' .','. 'LEAVE FROM' .','. 'LEAVE TO' .','. 'REASON' .','. 'STATUS');

			return Storage::download($file);
		}

		if($id !== null AND $datefrom == null AND $dateto == null )
		{
			$data = table::leaves()->where('idno', $id)->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'leave-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. '"'.$d->employee.'"' .','. $d->type .','. $d->leavefrom .','. $d->leaveto .','. $d->reason .','. $d->status);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'EMPLOYEE' .','. 'TYPE' .','. 'LEAVE FROM' .','. 'LEAVE TO' .','. 'REASON' .','. 'STATUS');

			return Storage::download($file);
		}

		if ($id == null AND $datefrom !== null AND $dateto !== null)
		{
			$data = table::leaves()->whereBetween('leavefrom', [$datefrom, $dateto])->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'leave-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->id .','. $d->idno .','. '"'.$d->employee.'"' .','. $d->type .','. $d->leavefrom .','. $d->leaveto .','. $d->reason .','. $d->status);
			}

			Storage::prepend($file, '"ID"' .','. 'IDNO' .','. 'EMPLOYEE' .','. 'TYPE' .','. 'LEAVE FROM' .','. 'LEAVE TO' .','. 'REASON' .','. 'STATUS');

			return Storage::download($file);
		}

		return redirect('reports/employee-leaves')->with('error', 'Whoops! Please provide date range or select employee.');
	}

	function birthdaysReport()
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }
		$c = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->get();

		$date = date('Y-m-d');
		$time = date('h-i-sa');
		$file = 'employee-birthdays-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($c as $d)
		{
		    Storage::prepend($file, $d->idno .','. $d->lastname.' '.$d->firstname.' '.$d->mi .','. $d->department .','. $d->jobposition .','. $d->birthday .','. $d->mobileno);
		}

		Storage::prepend($file, '"ID"' .','. 'EMPLOYEE NAME' .','. 'DEPARTMENT' .','. 'POSITION' .','. 'BIRTHDAY' .','. 'MOBILE NUMBER' );

		return Storage::download($file);
	}

	function accountReport()
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }
		$u = table::users()->get();

		$date = date('Y-m-d');
		$time = date('h-i-sa');
		$file = 'employee-accounts-'.$date.'T'.$time.'.csv';

		Storage::put($file, '', 'private');

		foreach ($u as $a)
		{
			if($a->acc_type == 2)
			{
				$a_type = 'Admin';
			} else {
				$a_type = 'Employee';
			}
			Storage::prepend($file, $a->name .','. $a->email .','. $a_type);
			Storage::prepend($file, 'EMPLOYEE NAME' .','. 'EMAIL' .','. 'ACCOUNT TYPE');

			return Storage::download($file);
		}
	}

	function scheduleReport(Request $request)
	{
		if (permission::permitted('reports')=='fail'){ return redirect()->route('denied'); }
		$id = $request->emp_id;

		if ($id == null)
		{
			$data = table::schedules()->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'schedule-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->idno .',"'. $d->employee .'",'. $d->intime .','. '"'.$d->outime.'"' .','. $d->datefrom .','. $d->dateto .','. $d->hours .',"'. $d->restday .'",'. $d->archive);
			}

			Storage::prepend($file, '"IDNO"' .','. 'EMPLOYEE' .','. 'START TIME' .','. 'OFF TIME' .','. 'DATE FROM' .','. 'DATE TO' .','. 'HOURS' .','. 'RESTDAY' .','. 'STATUS');

			return Storage::download($file);
		}

		if ($id !== null)
		{
			$data = table::schedules()->where('idno', $id)->get();
			$date = date('Y-m-d');
			$time = date('h-i-sa');
			$file = 'schedule-reports-'.$date.'T'.$time.'.csv';

			Storage::put($file, '', 'private');

			foreach ($data as $d)
			{
				Storage::prepend($file, $d->idno .',"'. $d->employee .'",'. $d->intime .','. '"'.$d->outime.'"' .','. $d->datefrom .','. $d->dateto .','. $d->hours .',"'. $d->restday .'",'. $d->archive);
			}

			Storage::prepend($file, '"IDNO"' .','. 'EMPLOYEE' .','. 'START TIME' .','. 'OFF TIME' .','. 'DATE FROM' .','. 'DATE TO' .','. 'HOURS' .','. 'RESTDAY' .','. 'STATUS');

			return Storage::download($file);
		}

		return redirect('reports/employee-schedule')->with('error', 'Whoops! Please select employee.');
	}

}
