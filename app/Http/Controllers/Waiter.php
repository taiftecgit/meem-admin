<?php

namespace App\Http\Controllers;

use App\Helpers\CommonMethods;
use App\Models\Photos;
use App\Models\RestoTables;
use App\Models\User;
use App\Models\Waiters;
use App\Models\WaiterTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;

class Waiter extends Controller
{
    //
    public function waiters(){
        $waiters = Auth::user()->restaurants->waiters;
        $data = [
            'waiters' => $waiters
        ];

        return view('waiters.waiters',$data);
    }

    public function new_waiter(){

        $tables = RestoTables::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->where('is_active',1)->doesnthave('waiter_tables')->get();

        $data = ['tables'=>$tables];

        return view('waiters.waiter_form',$data);
    }

    public function save(Request $request){
        //  dd($request->all());

        $id = $request->id;

        if(empty($id)){
            $waiter = new Waiters();
            $user = new User();

            $user->name = $request->name;
            $user->username = CommonMethods::generate_user_name($request->name);
            $user->password = Hash::make('12345678');
            if(!empty($request->email))
                $user->email = $request->email;
            $user->is_active = 1;
            $user->role='waiter';
            $user->save();


            $waiter->user_id = $user->id;
        }else
            $waiter = Waiters::find($id);

        if(!isset($request->is_active) && !empty($id)){
            //dd('TEST');
            if(!empty($id)) {
            $u = User::find($waiter->user_id);
            $u->is_active = 0;
            $u->save();
            }
        }else{

                if ($waiter->users->is_active == 0) {
                    $u = User::find($waiter->user_id);
                    $u->is_active = 1;
                    $u->save();
                }

        }



        $waiter->name = $request->name;
        $waiter->resto_id = Auth::user()->restaurants->id;

        $waiter->address = $request->address;


        $waiter->phone = $request->phone;

        $waiter->is_active = isset($request->is_active)?1:0;

        $waiter->save();

        $id = $waiter->id;
        if($id > 0){

            if($request->hasFile('profile')){
                $logo = $request->file('profile');



                $file_name = Str::slug($request->name)."-profile".'-'.time();
                $extension = $logo->getClientOriginalExtension();



                Storage::disk('profile')->put($file_name.'.'.$extension,  File::get($logo));
                $logo = Photos::where('waiter_id',$id)->where('photo_type','profile')->first();
                if(!$logo)
                    $logo = new Photos();


                $logo->file_name = $file_name.'.'.$extension;
                $logo->waiter_id = $id;
                $logo->photo_type = 'profile';

                $logo->save();

                //$resto->text =
            }

            $table_id = isset($request->table_id)?$request->table_id:"";

            if(!empty($table_id)){
                $w_t = WaiterTables::where('table_id',$table_id)->first();

                if(!isset($wt))
                    $w_t = new  WaiterTables();
                $w_t->waiter_id = $id;
                $w_t->table_id = $table_id;

                $w_t->save();
            }




            echo json_encode(array('type' => 'success', 'message'=>"Waiter's data is saved successfully."));
            exit;
        }else{
            echo json_encode(array('type' => 'error', 'message'=>"Waiter's data is not saved, check info again."));
        }




    }

    public function show($id){

        $waiter = Waiters::find($id);
        $data = [
            'waiter' => $waiter,

        ];
        return view('waiters.show',$data);
    }

    public function edit($id){

        $waiter = Waiters::find($id);
        $tables = RestoTables::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->where('is_active',1)->doesnthave('waiter_tables')->get();

        $data = [
            'waiter' => $waiter,
            'tables'=>$tables
        ];


        return view('waiters.waiter_form',$data);
    }

    public function delete($id){
        //$id = CommonMethods::decrypt($id);
        $waiter = Waiters::find($id);
        $waiter->deleted_at = date('Y-m-d H:i:s');
        $waiter->save();


    }

    public function generate_credentials($id){
        $waiter = Waiters::find($id);
        return response()->json(['username'=>$waiter->users->username,'password'=>CommonMethods::generateRandomString()]);
    }

    public function update_password(Request $request){

        $waiter = Waiters::find($request->waiter_id);

        $u = User::find($waiter->user_id);
        $u->password = Hash::make($request->password);
        $u->save();
    }
}
