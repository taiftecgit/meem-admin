<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestoUsers;
use App\Models\RestoUserInvites;
use Illuminate\Support\Facades\Auth;
use App\Models\Outlets;
use App\Models\User;
use App\Models\SendEmail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CommonMethods;
use Str;
use App\Models\Restaurants;

class RestoUser extends Controller
{
    //
    public function users(){
        $users = RestoUsers::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->get();
        $invited_users = RestoUserInvites::whereNull('deleted_at')->where('invite_type','pending')->where('resto_id',Auth::user()->restaurants->id)->get();

          return view('users.users',['users'=>$users,'invited_users'=>$invited_users]);
    }

    public function invite(){
        $outlets = Outlets::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->get();
        return view('users.invite',['outlets'=>$outlets]);
    }

    public function send_invitation(Request $request){

        $emails = $request->emails;
        $role = $request->role;
        $selected_outlets = $request->selected_outlets;
        $resto_id = Auth::user()->restaurants->id;
        $role_based_access = $request->role_based_access;

        $emails = explode(',', $emails);
        $existing_email = [];
        $new = false;
        foreach($emails as $email){
             $invite = RestoUserInvites::where('email',$email)->whereNull('deleted_at')->where('resto_id',$resto_id)->first();
             if(isset($invite)){
                $existing_email[] = $email;
             }else{
                $new = true;
                $in = new RestoUserInvites();

                $uuid = Str::uuid();

                $in->email = $email;
                $in->resto_id = $resto_id;
                $in->unique_key = $uuid;
                $in->role = $role;
                $in->access_level = $role_based_access;
                $in->selected_outlets = $selected_outlets;
                $in->invite_type = 'pending';

                $in->save();

                $id = $in->id;

                if($id > 0){
                      $param = array(
                            'email'=>$email,
                            'name' => '',
                            'shop_name' => Auth::user()->restaurants->name,
                            'role' => $role,
                            'access_level' => $role_based_access,
                            'selected_outlets' =>  $selected_outlets,
                            'link' => env('APP_URL').'user/invite/'.$uuid
                      );

        SendEmail::SendInvitationLink($param);
                }
             }



        }

        if($new){

            $message = "Invitation sent successfully.";
            if(count($existing_email) > 0)
                $message .=', '.implode(', ' , $existing_email).' already in database.';
            $array = array(
                'type'=>"success",
                'message'=>$message
            );
            echo json_encode($array);
        }else{
            $message = "Invitation is not sent";
              if(count($existing_email) > 0){
                $message.=", ".implode(",",$existing_email).' are already in database';
              }
            $array = array(
                'type'=>"error",
                'message'=>$message
            );
            echo json_encode($array);
        }

    }

    public function create_user($id){

        $invite = RestoUserInvites::where('unique_key',$id)->where('invite_type','pending')->first();
        $resto = NULL;

        if(isset($invite)){

            $resto = Restaurants::find($invite->resto_id);

        }


        return view('users.create',['user'=>$invite,'resto'=>$resto]);
    }

    public function save_user(Request $request){



        $invite_id = $request->invite_id;
         $invite = RestoUserInvites::where('unique_key',$invite_id)->where('invite_type','pending')->first();
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $mobile_number = $request->country_code.''.$request->mobile_number;
        $email = $request->email;
        $password = $request->password;

         $user = new User();
         $username = CommonMethods::generate_user_name($request->first_name.' '.$request->last_name);

            $user->name = $request->first_name.' '.$request->last_name;
            $user->username = $username;
            $user->password = Hash::make($password);
            if(!empty($request->email))
            $user->email = $request->email;
            $user->is_active = 1;
            $user->role='resto_user';
            $user->save();

            $user_id = $user->id;


            $resto_user = new RestoUsers();

            $resto_user->user_id = $user_id;
            $resto_user->resto_id = $invite->resto_id;
            $resto_user->unique_key = Str::uuid();
            $resto_user->first_name = $first_name;
            $resto_user->last_name = $last_name;

            $resto_user->email = $email;
            $resto_user->mobile_number = $mobile_number;
            $resto_user->is_active = 1;


            $resto_user->role=$invite->role;
            $resto_user->access_level=$invite->access_level;
            $resto_user->selected_outlets=$invite->selected_outlets;
            $resto_user->save();
            $id = $resto_user->id;

            if($id > 0){
                 $invite->invite_type = 'completed';
                 $invite->save();

                 $param  = array(
                            'email'=>$email,
                            'name' => $first_name.' '.$last_name,
                            'shop_name' => Auth::user()->restaurants->name,
                            'username' => $username,
                            'password' => $password

                      );

        SendEmail::sendRestoUserCredentials($param);




                 Auth::loginUsingId($user_id);

                 $response = array(
                    'type'=>'success',
                    'message' => 'Your profile is created'
                 );
                 echo json_encode($response);
            }else{

                 $response = array(
                    'type'=>'error',
                    'message' => 'Your profile is not created, try again'
                 );
                 echo json_encode($response);
            }




    }

    public function delete_invitation($id){
             $invite = RestoUserInvites::where('unique_key',$id)->where('invite_type','pending')->first();
             $invite->deleted_at = date('Y-m-d H:i:s');
             $invite->save();
    }

    public function delete_saved_user($id){
             $invite = RestoUsers::where('id',$id)->first();
             $invite->deleted_at = date('Y-m-d H:i:s');
             $invite->save();
    }

    public function user_profile($id){
        $user = RestoUsers::where('unique_key',$id)->first();
 $outlets = Outlets::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->get();
        return view('users.profile',['user'=>$user,'outlets'=>$outlets]);
    }

    public function save_changes(Request $request){
        $user = $request->user;

        $resto_user = RestoUsers::where('unique_key',$user)->first();


         $role = $request->role;
        $selected_outlets = $request->selected_outlets;
        $resto_id = Auth::user()->restaurants->id;
        $role_based_access = $request->role_based_access;


            $resto_user->role               = $role;
            $resto_user->access_level       = $role_based_access;
            $resto_user->selected_outlets   = $selected_outlets ;

         $resto_user->save();

          $id = $resto_user->id;

            if($id > 0){


                 $response = array(
                    'type'=>'success',
                    'message' => 'Your profile is updated'
                 );
                 echo json_encode($response);
            }else{

                 $response = array(
                    'type'=>'error',
                    'message' => 'Your profile is not updated, try again'
                 );
                 echo json_encode($response);
            }

    }

    public function generate_credentials($id){
        $user = RestoUsers::find($id);
        return response()->json(['username'=>$user->users->username,'password'=>CommonMethods::generateRandomString()]);
    }

     public function update_password(Request $request){
        $user = RestoUsers::find($request->user_id);
        $u = User::find($user->user_id);
        $password = Hash::make($request->password);

        $param  = array(
                            'email'=>$user->email,
                            'name' => $user->first_name.' '.$user->last_name,
                            'shop_name' => Auth::user()->restaurants->name,
                            'username' => $u->username,
                            'password' => $request->password

                      );
       // dd($param);
        SendEmail::sendRestoUserCredentials($param);


        $u->password = $password;
        $u->save();
    }

    public function get_invitation_link($unique_key){
        $invite = RestoUserInvites::where('unique_key',$unique_key)->where('invite_type','pending')->first();
         $param = array(
                            'email'=>$invite->email,
                            'name' => '',
                            'shop_name' => Auth::user()->restaurants->name,
                            'role' => $invite->role,
                            'access_level' => $invite->access_level,
                            'selected_outlets' => $invite->selected_outlets ,
                            'link' => env('APP_URL').'user/invite/'.$invite->unique_key
                      );
         //dd($param);
       try{
            SendEmail::SendInvitationLink($param);
            return response()->json(['type'=>"success",'message'=>"Invitation link sent successfully at ".$invite->email]);

       }catch(Exception $e){
         return response()->json(['type'=>"error",'message'=>"Invitation link is not  successfully "]);
       }


    }
}
