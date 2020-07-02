<?php

namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class Users {
    public $id = '';
    public $name = '';
    public $email = '';
    public $role = '';
    public $type = '';
    public $status = '';

    public function __construct($id,$name, $email, $role, $type, $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->type = $type;
        $this->status = $status;
    }

}


class UsersController extends Controller
{
    // Finds all users and sends them to the view file
    // which are only visible to admin.
    public function index()
    {
        if (permission::permitted('users')=='fail'){ return redirect()->route('denied'); }

        $roles = table::roles()->get();
        $employees = table::people()->get();
        $users = table::people()->whereNotNull('password')->get();

        $users_collection = collect([]);
        foreach ($users as $user) {
          $role = table::roles()->where('id', $user->role_id)->first();

          if ($role) {
            $role_name = $role->role_name;
          }else {
            $role_name = 'N/A';
          }

          if($user->acc_type == 2)
           $type = 'Admin';
          else
           $type = 'Employee';

          if($user->status == '1'){
            $status = 'Enabled';
          }
          else
          {
            $status = 'Disabled';
          }

          $users_collection->push(new Users($user->id,$user->firstname." ".$user->lastname, $user->companyemail, $role_name, $type, $status));
        }

        return view('admin.users', compact('users','employees','roles','users_collection'));



    }

    // Register new user.
    public function register(Request $request)
    {
        if (permission::permitted('users-add')=='fail'){ return redirect()->route('denied'); }

        $this->validate($request,[
           'ref' => 'required|max:100',
            'name' => 'required|max:100',
            //'email' => 'required|email|max:100',
            'role_id' => 'required|digits_between:1,99|max:2',
            'acc_type' => 'required|digits_between:1,99|max:2',
            'password' => 'required|min:8|max:100',
            'password_confirmation' => 'required|min:8|max:100',
            'status' => 'required|boolean|max:1',
        ]);

        $ref = $request->ref;
        $name = $request->name;
      	$email = $request->email;
    		$role_id = $request->role_id;
    		$acc_type = $request->acc_type;
    		$password = $request->password;
    		$password_confirmation = $request->password_confirmation;
    		$status = $request->status;

        if ($password != $password_confirmation)
        {
            return redirect('users')->with('error', 'Whoops! Password confirmation does not match!');
        }

        $is_user_exist =table::people()->where('companyemail', $email)->whereNotNull('password')->first();

        if($is_user_exist)
        {
            return redirect('users')->with('error', 'Whoops! this user already exist');
        }

    	table::people()->where('id', $ref)->update(array(
				'role_id' => $role_id,
				'acc_type' => $acc_type,
				'password' => Hash::make($password),
				'status' => $status,
    	));

    	return redirect('/users')->with('success','New User has been added.');
    }


    public function edit($id)
    {
        if (permission::permitted('users-edit')=='fail'){ return redirect()->route('denied'); }

        $u = table::people()->where('id', $id)->first();
        $r = table::roles()->get();
        $e_id = ($u->id == null) ? 0 : Crypt::encryptString($u->id) ;

        return view('admin.edits.edit-user', compact('u', 'r', 'e_id'));
    }

    public function update(Request $request)
    {
        if (permission::permitted('users-edit')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('users');}

        $v = $request->validate([
            'ref' => 'required|max:200',
            'role_id' => 'required|digits_between:1,99|max:2',
            'acc_type' => 'required|digits_between:1,99|max:2',
            'status' => 'required|boolean|max:1',
        ]);

        $ref = Crypt::decryptString($request->ref);
    		$role_id = $request->role_id;
    		$acc_type = $request->acc_type;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;
        $status = $request->status;

        if ($password !== null && $password_confirmation !== null)
        {
            $v = $request->validate([
                'password' => 'required|min:8|max:100',
                'password_confirmation' => 'required|min:8|max:100',
            ]);

            if ($password != $password_confirmation)
            {
                return redirect('users')->with('error', 'Whoops! Password confirmation does not match!');
            }

            table::people()->where('id', $ref)->update([
                'role_id' => $role_id,
                'acc_type' => $acc_type,
                'status' => $status,
                'password' => Hash::make($password),
            ]);
        } else {
            table::people()->where('id', $ref)->update([
                'role_id' => $role_id,
                'acc_type' => $acc_type,
                'status' => $status,
            ]);
        }

    	return redirect('users')->with('success','User Account has been updated!');
    }

    public function delete($id, Request $request)
    {
        if (permission::permitted('users-delete')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('users');}

    	table::users()->where('id', $id)->delete();
    	return redirect('users')->with('success','User Account has been deleted!');
    }
}
