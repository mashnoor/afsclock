<?php

namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PersonalSchedulesController extends Controller
{
    public function index()
    {
        $i = \Auth::user()->id;
        $s = table::schedules()->where([['reference', $i],['active_status', 1]])->first();
        $schedule_template = table::sch_template()->where('id', $s->schedule_id)->first();

        return view('personal.personal-schedules-view', compact('schedule_template'));
    }

    public function getPS(Request $request)
    {
        $id = \Auth::user()->idno;
        $datefrom = $request->datefrom;
		$dateto = $request->dateto;

        if($datefrom == null || $dateto == null )
        {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->get();
            return response()->json($data);

		} elseif ($datefrom !== null AND $dateto !== null) {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->whereBetween('datefrom', [$datefrom, $dateto])
            ->get();
            return response()->json($data);
        }
    }
}
