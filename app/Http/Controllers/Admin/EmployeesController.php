<?php

namespace App\Http\Controllers\admin;
use App\EmployeeFace;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class Employee{

		public $id = '';
    public $idno = '';
    public $name ='';
    public $company = '';
    public $department = '';
		public $jobtitle = '';
		public $status = '';

    public function __construct($id,$idno, $name, $company, $department, $jobtitle, $status)
    {
				$this->id = $id;
        $this->idno = $idno;
        $this->name = $name;
        $this->company = $company;
        $this->department = $department;
				$this->jobtitle = $jobtitle;
				$this->status = $status;
    }

}

class EmployeesController extends Controller
{

	// Finds all employee data and displays them in the view file.
	public function index()
	{
    if (permission::permitted('employees')=='fail'){ return redirect()->route('denied'); }

		$emp_typeR = table::people()
		->where('employmenttype', 'Regular')
		->where('employmentstatus', 'Active')
		->count();

		$emp_typeT = table::people()
		->where('employmenttype', 'Trainee')
		->where('employmentstatus', 'Active')
		->count();

		$emp_genderM = table::people()
		->where('gender', 'Male')
		->count();

		$emp_genderR = table::people()
		->where('gender', 'Female')
		->count();

		$emp_allActive = table::people()
		->where('employmentstatus', 'Active')
		->count();

		$emp_allArchive = table::people()
		->where('employmentstatus', 'Archive')
		->count();

		$data = table::people()->get();

		$employee_collection = collect([]);

		if ($data) {
			foreach ($data as $employee) {
				$employee_company = table::company()->where('id', $employee->company_id)->value('company');
				$employee_department = table::department()->where('id', $employee->department_id)->value('department');
				$employee_jobtitle = table::jobtitle()->where('id', $employee->job_title_id)->value('jobtitle');
				$employee_name = $employee->firstname ." , " .$employee->lastname;
				$employee_collection->push(new Employee($employee->id, $employee->idno, $employee_name, $employee_company, $employee_department, $employee_jobtitle, $employee->employmentstatus));
			}
		}

		$emp_file = table::people()->count();

		if($emp_allArchive != null OR $emp_allActive != null OR $emp_allArchive >= 1 OR $emp_allActive >= 1)
		{
			$number1 = $emp_allArchive / $emp_allActive * 100;
		} else {
			$number1 = null;
		}
	    return view('admin.employees', compact('data', 'emp_typeR', 'emp_typeT', 'emp_genderM', 'emp_genderR', 'emp_allActive', 'emp_file', 'emp_allArchive', 'employee_collection'));
	}

	// Following controller function prepare company employee related information
	// and send them to the view to be displayed in the add new employee form.
	public function new()
	{
		if (permission::permitted('employees-add')=='fail'){ return redirect()->route('denied'); }

		$employees = table::people()->get();
		$company = table::company()->get();
		$department = table::department()->get();
		$jobtitle = table::jobtitle()->get();
		$leavegroup = table::leavegroup()->get();

	    return view('admin.new-employee', compact('company', 'department', 'jobtitle', 'employees', 'leavegroup'));
	}

	//
	public function faceregistration($id)
    {
        return view('admin.employee-face-registration', compact('id'));
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

		// Face Registration Controller Function.
    public function registerface($id, Request $request)
    {

	      $image_name = $this->generateRandomString() . ".jpg";
				$img_data = $request->img_data;
				$img_data = str_replace('data:image/jpeg;base64,', '', $img_data);
				$img_data = str_replace(' ', '+', $img_data);

				$img_data = base64_decode($img_data);

        $destinationPath = public_path() . '/assets/faces/' . $image_name;
        file_put_contents($destinationPath, $img_data);
        $employeeFace = new EmployeeFace();
        $employeeFace->reference = $id;
        $employeeFace->image_name = $image_name;
        $employeeFace->save();
        $msg = "This is a simple message.";
        return response()->json(array('msg'=> $msg), 200);
    }

		// Add the employee to the database.
		// Data received from the add employee table and
		// validates them before storing in the database.
    public function add(Request $request)
    {
		if (permission::permitted('employees-add')=='fail'){ return redirect()->route('denied'); }
		//if($request->sh == 2){return redirect()->route('employees');}

		$v = $request->validate([
			'lastname' => 'required|alpha_dash_space|max:155',
			'firstname' => 'required|alpha_dash_space|max:155',
			// 'mi' => 'required|alpha_dash_space|max:155',
			// 'age' => 'required|digits_between:0,199|max:3',
			// 'gender' => 'required|alpha|max:155',
			//'emailaddress' => 'required|email|max:155',
			// 'civilstatus' => 'required|alpha|max:155',
			// 'height' => 'required|digits_between:0,299|max:3',
			// 'weight' => 'required|digits_between:0,999|max:3',
			// 'mobileno' => 'required|max:155',
			// 'birthday' => 'required|date|max:155',
			// 'nationalid' => 'required|max:155',
			// 'birthplace' => 'required|max:255',
			// 'homeaddress' => 'required|max:255',
			// 'company' => 'required|alpha_dash_space|max:100',
			// 'department' => 'required|alpha_dash_space|max:100',
			// 'jobposition' => 'required|alpha_dash_space|max:100',
			// 'companyemail' => 'required|email|max:155',
			// 'leaveprivilege' => 'required|max:155',
			'idno' => 'required|max:155',
			// 'employmenttype' => 'required|alpha_dash_space|max:155',
			'employmentstatus' => 'required|alpha_dash_space|max:155',
			// 'startdate' => 'required|date|max:155',
			// 'dateregularized' => 'required|date|max:155'
		]);

		$lastname = mb_strtoupper($request->lastname);
		$firstname = mb_strtoupper($request->firstname);
		$mi = mb_strtoupper($request->mi);
		$age = $request->age;
		$gender = mb_strtoupper($request->gender);
		$emailaddress = mb_strtolower($request->emailaddress);
		$civilstatus = mb_strtoupper($request->civilstatus);
		$height = $request->height;
		$weight = $request->weight;
		$mobileno = $request->mobileno;
		$birthday = date("Y-m-d", strtotime($request->birthday));
		$nationalid = mb_strtoupper($request->nationalid);
		$birthplace = mb_strtoupper($request->birthplace);
		$homeaddress = mb_strtoupper($request->homeaddress);
		$company = mb_strtoupper($request->company);
		$department = mb_strtoupper($request->department);
		$jobposition = mb_strtoupper($request->jobposition);
		$companyemail = mb_strtolower($request->companyemail);
		$leaveprivilege = $request->leaveprivilege;
		$idno = mb_strtoupper($request->idno);
		$employmenttype = $request->employmenttype;
		$employmentstatus = $request->employmentstatus;
		$startdate = date("Y-m-d", strtotime($request->startdate));
		$dateregularized = date("Y-m-d", strtotime($request->dateregularized));

		$is_idno_taken = table::people()->where('idno', $idno)->exists();

		if ($is_idno_taken == 1)
		{
			return redirect('employees-new')->with('error', 'Whoops! the ID Number is already taken.');
		}

		$file = $request->file('image');

		if($file != null)
		{
			$name = $request->file('image')->getClientOriginalName();
			$destinationPath = public_path() . '/assets/faces/';
			$file->move($destinationPath, $name);
		} else {
			$name = '';
		}

			table::people()->insert([
    		[
				'lastname' => $lastname,
				'firstname' => $firstname,
				'mi' => $mi,
				'age' => $age,
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
				'company_id' => $company,
				'department_id' => $department,
				'job_title_id' => $jobposition,
				'companyemail' => $companyemail,
				'leaveprivilege' => $leaveprivilege,
				'idno' => $idno,
				'startdate' => $startdate,
				'dateregularized' => $dateregularized,
            ],
    	]);

		$refId = DB::getPdo()->lastInsertId();

    	return redirect('employees')->with('success','Employee has been added!');
    }
}
