<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\AdminUsers;
use \App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use File;
use App\Helpers\CommonMethods;
use Image;
use Hash;
use \App\Models\SendEmail;
class AdminUser extends Controller
{
    //
	public function users(){
		$admin_users = AdminUsers::whereNull('deleted_at')->get();
		return view('admin-users.users',['users'=>$admin_users]);
	}
	public function new_user(){
		return view("admin-users.admin_user_form");
	}

	public function edit_user($id){
		$admin_user= AdminUsers::find($id);
		return view("admin_users.admin_user_form",['admin_user'=>$admin_user]);
	}

	public function save_user(Request $request){

		$id = $request->id;
		$password = CommonMethods::generateRandomString();
		$new_user = false;
		if($id==""){
			$new_user = true;
			$user = new User();
         $username = CommonMethods::generate_user_name($request->first_name.' '.$request->last_name);


            $user->name = $request->first_name.' '.$request->last_name;
            $user->username = $username;
            $user->password = Hash::make($password);
            if(!empty($request->email))
            $user->email = $request->email;
            $user->is_active = 1;
            $user->role='admin_user';
            $user->save();

            $user_id = $user->id;

			 $param = array(
                            'email'=>$request->email,
                            'name' => $request->first_name.' '.$request->last_name,
                            'username' => $username,
                            'password' => $password

                      );

        SendEmail::sendAdminUserCredentials($param);

			$admin_user = new AdminUsers();
			$admin_user->user_id = $user_id;
		}

		else
			$admin_user = AdminUsers::find($id);

		$admin_user->first_name = $request->first_name;
		$admin_user->last_name = ($request->last_name);

		$admin_user->mobile_number = $request->mobile_number;

		$admin_user->address = $request->address;
		$url = "";

                $admin_user->save();

				$id = $admin_user->id;
		if($id > 0){
			 echo json_encode(array('type' => 'success', 'message'=>"admin_user's data is saved successfully."));
		}else{
			echo json_encode(array('type' => 'error', 'message'=>"admin_user's data is not saved, check info again."));
		}

	}

	public function delete_user($id){
		$admin_user = AdminUsers::find($id);
		$admin_user->deleted_at = date('Y-m-d H:i:s');
		$admin_user->save();
	}
}
