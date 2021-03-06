<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function view($id, Request $request)
    {
		if (permission::permitted('employees-view')=='fail'){ return redirect()->route('denied'); }

    		$p = table::people()->where('id', $id)->first();
    		$c = table::companydata()->where('reference', $id)->first();
    		$i = table::people()->select('avatar')->where('id', $id)->value('avatar');
    		$leavetype = table::leavetypes()->get();
    		$leavegroup = table::leavegroup()->get();

        return view('admin.profile-view', compact('p', 'c', 'i', 'leavetype', 'leavegroup'));
    }

   	public function delete($id, Request $request)
    {
		if (permission::permitted('employees-delete')=='fail'){ return redirect()->route('denied'); }

		return view('admin.delete-employee', compact('id'));
   	}

	public function clear(Request $request)
	{
		if (permission::permitted('employees-delete')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('employees');}

		$id = $request->id;
		table::people()->where('id', $id)->delete();
		table::companydata()->where('reference', $id)->delete();
		table::attendance()->where('reference', $id)->delete();
		table::schedules()->where('reference', $id)->delete();
		table::leaves()->where('reference', $id)->delete();
		table::users()->where('reference', $id)->delete();

		return redirect('employees')->with('success','Employee information has been deleted!');
	}

   	public function archive($id, Request $request)
    {
		if (permission::permitted('employees-archive')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('employees');}

		$id = $request->id;
		table::people()->where('id', $id)->update(['employmentstatus' => 'Archived']);
		table::users()->where('reference', $id)->update(['status' => '0']);

    	return redirect('employees')->with('success','Employee information has been archived!');
   	}

	public function editPerson($id)
    {
		if (permission::permitted('employees-edit')=='fail'){ return redirect()->route('denied'); }

    		$person_details = table::people()->where('id', $id)->first();
    		$company = table::company()->get();
    		$department = table::department()->get();
    		$jobtitle = table::jobtitle()->get();
    		$leavegroup = table::leavegroup()->get();
    		$e_id = ($person_details->id == null) ? 0 : Crypt::encryptString($person_details->id) ;

        return view('admin.edits.edit-personal-info', compact('person_details', 'company', 'department', 'jobtitle', 'leavegroup', 'e_id'));
    }

    public function updatePerson(Request $request)
    {
		if (permission::permitted('employees-edit')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('employees');}

		$v = $request->validate([
			'id' => 'required|max:200',
			'lastname' => 'required|alpha_dash_space|max:155',
			'firstname' => 'required|alpha_dash_space|max:155',
			'idno' => 'required|max:155',
			'employmentstatus' => 'required|alpha_dash_space|max:155',
		]);

		$id = Crypt::decryptString($request->id);
		$lastname = mb_strtoupper($request->lastname);
		$firstname = mb_strtoupper($request->firstname);
		$mi = mb_strtoupper($request->mi);
		$gender = mb_strtoupper($request->gender);
		$emailaddress =  mb_strtolower($request->emailaddress);
		$civilstatus = mb_strtoupper($request->civilstatus);

		$mobileno = $request->mobileno;
		$birthday = date("Y-m-d", strtotime($request->birthday));
		$nationalid = mb_strtoupper($request->nationalid);
		$birthplace = mb_strtoupper($request->birthplace);
		$homeaddress = mb_strtoupper($request->homeaddress);
		$company = mb_strtoupper($request->company);

    $existing_company = table::company()->where('company', $company)->first();

		$department = mb_strtoupper($request->department);

    $existing_department = table::department()->where('department', $department)->first();

    $jobposition = mb_strtoupper($request->jobposition);

    $existing_jobtitle = table::jobtitle()->where('jobtitle', $jobposition)->first();


		$companyemail = mb_strtolower($request->companyemail);
		$leaveprivilege = $request->leaveprivilege;
		$idno = mb_strtoupper($request->idno);
		$employmenttype = $request->employmenttype;
		$employmentstatus = $request->employmentstatus;
		$startdate = date("Y-m-d", strtotime($request->startdate));
		$dateregularized = date("Y-m-d", strtotime($request->dateregularized));

		$file = $request->file('image');
		if ($file != null)
		{
			$name = $request->file('image')->getClientOriginalName();
			$destinationPath = public_path() . '/assets/faces/';
			$file->move($destinationPath, $name);
		} else {
			$name = table::people()->where('id', $id)->value('avatar');
		}

		table::people()->where('id', $id)->update([
			'lastname' => $lastname,
			'firstname' => $firstname,
			'mi' => $mi,

			'gender' => $gender,
			'emailaddress' => $emailaddress,
			'civilstatus' => $civilstatus,

			'mobileno' => $mobileno,
			'birthday' => $birthday,
			'birthplace' => $birthplace,
			'nationalid' => $nationalid,
			'homeaddress' => $homeaddress,
			'employmenttype' => $employmenttype,
			'employmentstatus' => $employmentstatus,
			'avatar' => $name,
      'company_id' => $existing_company->id,
			'department_id' => $existing_department->id,
			'job_title_id' => $existing_jobtitle->id,
			'companyemail' => $companyemail,
			'leaveprivilege' => $leaveprivilege,
			'idno' => $idno,
			'startdate' => $startdate,
			'dateregularized' => $dateregularized,
		]);

    	return redirect('profile/edit/'.$id)->with('success','Employee information has been updated!');
   	}

	public function viewProfile(Request $request)
	{
		$id = \Auth::user()->id;
		$myuser = table::users()->where('id', $id)->first();
		$myrole = table::roles()->where('id', $myuser->role_id)->value('role_name');

		return view('admin.update-profile', compact('myuser', 'myrole'));
	}

	public function viewPassword()
	{
		return view('admin.update-password');
	}

	public function updateUser(Request $request)
	{
		//if($request->sh == 2){return redirect()->route('updateProfile');}

		$v = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
		]);

		$id = \Auth::id();
		$name = mb_strtoupper($request->name);
		$email = mb_strtolower($request->email);

		if($id == null)
        {
            return redirect('personal/update-user')->with('error', 'Whoops! Please fill the form completely.');
		}

		table::users()->where('id', $id)->update([
			'name' => $name,
			'email' => $email,
		]);

		return redirect('update-profile')->with('success', 'Updated!');
	}

	public function updatePassword(Request $request)
	{
		//if($request->sh == 2){return redirect()->route('updatePassword');}

		$v = $request->validate([
            'currentpassword' => 'required|max:100',
            'newpassword' => 'required|min:8|max:100',
            'confirmpassword' => 'required|min:8|max:100',
		]);

		$id = \Auth::id();
		$p = \Auth::user()->password;
		$c_password = $request->currentpassword;
		$n_password = $request->newpassword;
		$c_p_password = $request->confirmpassword;

		if($id == null)
        {
            return redirect('personal/update-user')->with('error', 'Whoops! Please fill the form completely.');
		}

		if($n_password != $c_p_password)
		{
			return redirect('update-password')->with('error', 'New password does not match!');
		}

		if(Hash::check($c_password, $p))
		{
			table::users()->where('id', $id)->update([
				'password' => Hash::make($n_password),
			]);

			return redirect('update-password')->with('success', 'Updated!');
		} else {
			return redirect('update-password')->with('error', 'Oops! current password does not match.');
		}
	}


}
