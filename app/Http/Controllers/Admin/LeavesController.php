<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\User;


class Leaves{

    public $id = '';
    public $employee ='';
    public $type = '';
    public $leavefrom = '';
    public $leaveto = '';
    public $returndate = '';
    public $comment = '';
    public $status = '';


    public function __construct($id, $employee, $type, $leavefrom,$leaveto,$returndate, $comment,$status )
    {
        $this->id = $id;
        $this->employee = $employee;
        $this->type = $type;
        $this->leavefrom = $leavefrom;
        $this->leaveto = $leaveto;
        $this->returndate = $returndate;
        $this->comment = $comment;
        $this->status = $status;
    }

}

class LeavesController extends Controller
{
    public function index()
    {
        if (permission::permitted('leaves')=='fail'){ return redirect()->route('denied'); }

        $leaves_collection = collect([]);


        $employee = table::people()->get();
        $leaves = table::leaves()->get();

        foreach($leaves as $leave){
          $the_employee = User::find($leave->reference);
          $leaves_collection->push(new Leaves($leave->id, $the_employee->firstname." ".$the_employee->lastname, $leave->type, $leave->leavefrom, $leave->leaveto, $leave->returndate, $leave->comment,$leave->status));
        }

        $leave_types = table::leavetypes()->get();

        return view('admin.leaves', compact('employee', 'leaves', 'leave_types', 'leaves_collection'));
    }

    public function edit($id, Request $request)
    {
        if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }

        $l = table::leaves()->where('id', $id)->first();
        $l->leavefrom = date('M d, Y', strtotime($l->leavefrom));
        $l->leaveto = date('M d, Y', strtotime($l->leaveto));
        $l->returndate = date('M d, Y', strtotime($l->returndate));
        $leave_types = table::leavetypes()->get();
        $e_id = ($l->id == null) ? 0 : Crypt::encryptString($l->id) ;
        $employee = User::find($l->reference);
        $emp_name = $employee->firstname." ".$employee->lastname;
        return view('admin.edits.edit-leaves', compact('l', 'leave_types', 'e_id','emp_name'));
    }

    public function update(Request $request)
    {
        if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('leave');}

        $v = $request->validate([
            'id' => 'required|max:200',
            'status' => 'required|max:100',
            'comment' => 'max:255',
        ]);

        $id = Crypt::decryptString($request->id);
        $status = $request->status;
        $comment = mb_strtoupper($request->comment);

        table::leaves()
        ->where('id', $id)
        ->update([
                    'status' => $status,
                    'comment' => $comment
        ]);

        return redirect('/leaves')->with('success','Employee leave has been updated!');
    }


    public function delete($id, Request $request)
    {
        if (permission::permitted('leaves-delete')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('leave');}

        table::leaves()->where('id', $id)->delete();

        return redirect('leaves')->with('success','Deleted!');
    }

}
