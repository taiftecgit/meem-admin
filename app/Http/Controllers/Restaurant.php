<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Cities;
use App\Models\RestoMetaDefs;
use App\Models\RestoMetas;
use App\Helpers\CommonMethods;
use App\Models\OrderNotifications;
use App\Models\Photos;
use App\Models\Restaurants;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TimeZones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;
use Illuminate\Support\Facades\Storage;
use File;
use Image;
use Validator;
use Redis;
class Restaurant extends Controller
{
    //

    public function restaurants(){

        $restaurants = Restaurants::whereNull('deleted_at')->get();

        $data = [
            'restaurants'=>$restaurants
        ];
        return view('restaurants.restaurants',$data);
    }

    public function new_restaurant(){
        $cities = Cities::where('is_active',1)->get();
        $timezones = TimeZones::all();
        $data = [
            'cities' => $cities,
            'time_zones' => $timezones
        ];
        return view('restaurants.restaurant_form',$data);
    }

    public function save(Request $request){
        $n = isset($request->allow_email_notifications)?"Yes":"No";


        $id = $request->id;

        if(empty($id)){
            $resto = new Restaurants();
            $user = new User();

             $data = [
                'resto_unique_name' => \Illuminate\Support\Str::slug($request->name)
            ];

            $validator = Validator::make($data, [
                'resto_unique_name' => 'unique:restaurants'
            ]);
            if ($validator->fails()) {

                $error = $validator->errors()->all();
                $error[0] = "Business name already taken, try with different name";
                echo json_encode(array('type' => 'error', 'message' => $error));
                exit;
            }

            $user->name = $request->name;
            $user->username = CommonMethods::generate_user_name($request->name);
            $user->password = Hash::make('12345678');
            if(!empty($request->email))
            $user->email = $request->email;
            $user->is_active = 1;
            $user->role='restaurant';
           $user->save();

            $resto->unique_shared_key = Hash::make(Str::slug($request->name));

            $resto->unique_shared_key =Str::uuid();;
            $resto->user_id = $user->id;
        }

        else
            $resto = Restaurants::find($id);

        if(Auth::User()->role=="administrator"){
            if(!isset($request->active) && !empty($id)){
                $u = User::find($resto->user_id);
                $u->is_active = 0;
                $u->save();
            }else{
                if($resto->users->is_active==0){
                    $u = User::find($resto->user_id);
                    $u->is_active = 1;
                    $u->save();
                }
            }
        }
        if(empty($id)) {
            $resto->resto_unique_name = \Illuminate\Support\Str::slug($request->name);
        }

        $resto->name = $request->name;
        $resto->arabic_name = $request->arabic_name;
         if(Auth::User()->role=="restaurant") {
        $resto->short_description = $request->short_description;
        $resto->description = $request->description;
        $resto->address = $request->address;
        $resto->detail_address = $request->detail_address;
        $resto->default_color = $request->default_color;
        $resto->whatsapp_number_notification = $request->whatsapp_number_notification;

        $resto->notification_email = $request->notification_email;

        $resto->time_zone = $request->time_zone;
        $resto->phone_number = $request->phone_number;
        }

        $resto->opening_timing = $request->opening_timing;
        $resto->closing_timing = $request->closing_timing;
        $resto->delivery_time_range = $request->delivery_time_range;
        $resto->min_basket_price = $request->min_basket_price;
        if(Auth::User()->role=="administrator") {
            $resto->outlet_countries = !empty($request->outlet_countries)?implode(',',$request->outlet_countries):NULL;
        $resto->city = $request->city;
		$resto->default_lang = $request->default_lang;
		$resto->domain_name = $request->domain_name;


        $resto->country_id = $request->country_id;
         $resto->active = isset($request->active)?1:0;
         $resto->allow_whatsapp_notifications = isset($request->allow_whatsapp_notifications)?1:0;
            $resto->allow_email_notifications = isset($request->allow_email_notifications)?"Yes":"No";;
            $resto->allow_whatsapp_notifications_to_customera = isset($request->allow_whatsapp_notifications_to_customera)?"Yes":"No";;
      }
		if(isset($request->multiple_langs) && count($request->multiple_langs) > 0)
		$resto->multiple_langs = isset($request->multiple_langs) && count($request->multiple_langs) > 0?implode(',',$request->multiple_langs):NULL;//;$request->multiple_langs;
        $resto->latitude = $request->latitude;
        $resto->longitude = $request->longitude;

        $resto->has_order = isset($request->has_order)?1:0;
        $resto->has_desserts = isset($request->has_desserts)?1:0;

        $resto->save();

        $id = $resto->id;
        if($id > 0){

                if($request->hasFile('logo')){
                    $logo = $request->file('logo');



                    $file_name = Str::slug($request->name)."-logo".'-'.time();
                    $extension = $logo->getClientOriginalExtension();




                    Storage::disk('logo')->put($file_name.'.'.$extension,  File::get($logo));

                    $destinationPath = public_path('/uploads/logo/');


                    $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/' . $file_name.'.'.$extension);

                   /* $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(85, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/thumbnails/' . $file_name.'.'.$extension);*/

                    $file = public_path('/uploads/logo/'.$file_name.'.'.$extension);
                    $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$id, \Illuminate\Support\Str::slug($request->name),$file,$file_name);



                    $logo = Photos::where('resto_id',$id)->where('photo_type','logo')->first();
                    if(!$logo)
                    $logo = new Photos();


                    //$logo->file_name = $file_name.'.'.$extension;
                    //$logo->resto_id = $id;
                   // $logo->photo_type = 'logo';

                   //

                 $logo->file_name = $result['url'];
                $logo->aws_cdn = $result['url'];
                $logo->resto_id = $id;
                $logo->photo_type = 'logo';
                $logo->save();

                    //$resto->text =
                }

            if($request->hasFile('home_image')){
                $logo = $request->file('home_image');



                $file_name = Str::slug($request->name)."-home-image".'-'.time();
                $extension = $logo->getClientOriginalExtension();



                $r = Storage::disk('home_image')->put($file_name.'.'.$extension,  File::get($logo));


                $file = public_path('uploads/home_image/'.$file_name.'.'.$extension);
                  $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$id, \Illuminate\Support\Str::slug($request->name),$file,$file_name);

                $logo = Photos::where('resto_id',$id)->where('photo_type','home_image')->first();
                if(!$logo)
                    $logo = new Photos();


                $logo->file_name = $result['url'];
                $logo->aws_cdn = $result['url'];
                $logo->resto_id = $id;
                $logo->photo_type = 'home_image';

                $logo->save();
                 File::delete(  $file);

                //$resto->text =
            }

                    $resto_metas = isset($request->resto_meta) && count($request->resto_meta) > 0?$request->resto_meta:[];
                  //  dump($resto_metas);
                    $resto_meta_value = isset($request->resto_meta_value) && count($request->resto_meta_value) > 0?$request->resto_meta_value:[];
			//dump($resto_metas);

                    if(count($resto_metas) >  0){
                        RestoMetas::where('bussiness_id',$resto->id)->where('for_role',Auth::user()->role)->delete();
                        foreach($resto_metas as $meta){
                            $rm = RestoMetaDefs::find($meta);

                            if(isset($rm)){
                                $r = new RestoMetas();
                                $r->meta_def_id = $meta;
                                $r->bussiness_id = $resto->id;
                                $r->meta_val = isset($resto_meta_value[$meta])?$resto_meta_value[$meta]:$rm->meta_def_name;
                                 $r->for_role = Auth::user()->role;
                                $r->status = 1;

                                $r->save();
                            }

                        }
                    }



             $keys = Redis::keys('*');

           /*  $key_id =
        dump(Auth::id().': '.Redis::get(str_replace('meem_orders_','',"resto_pakistan_".Auth::id()))); */
            foreach($keys as $k){
                //

                if(str_contains($k,"_".Auth::id())){

                   Redis::del(str_replace('prod_meem_orders_','',$k));
                }

                //dump($k.': '.Redis::get(str_replace('meem_orders_','',$k)));
            }
          //dump($req_method);


            echo json_encode(array('type' => 'success', 'message'=>"Restaurant's data is saved successfully."));
            exit;
        }else{
            echo json_encode(array('type' => 'error', 'message'=>"Restaurant's data is not saved, check info again."));
        }




    }

    public function show($id){
        $id = CommonMethods::decrypt($id);
        $restaurant = Restaurants::find($id);
        $data = [
            'restaurant' => $restaurant
            ];
        return view('restaurants.show',$data);
    }

    public function edit($id){
        $id = CommonMethods::decrypt($id);
        $restaurant = Restaurants::find($id);
        $cities = Cities::where('is_active',1)->get();
         $timezones = TimeZones::all();
       // dd($timezones);
        $data = [
            'restaurant' => $restaurant,
            'cities' => $cities,
            'time_zones' => $timezones
        ];


		if(Auth::User()->role=="administrator"){
        	return view('restaurants.restaurant_form',$data);
		}else{
			return view('restaurants.restaurant_form_marchent',$data);
		}
    }

    public function delete($id){
        //$id = CommonMethods::decrypt($id);
        $restaurant = Restaurants::find($id);
        $restaurant->deleted_at = date('Y-m-d H:i:s');
         $u = User::find($restaurant->user_id);
        $u->is_active = 0;
        $u->save();
        $restaurant->save();


    }

    public function generate_credentials($id){
        $resto = Restaurants::find($id);
        return response()->json(['username'=>$resto->users->username,'password'=>CommonMethods::generateRandomString()]);
    }

    public function update_password(Request $request){
        $resto = Restaurants::find($request->resto_id);
        $u = User::find($resto->user_id);
        $u->password = Hash::make($request->password);
        $u->save();
    }

    public function upload_gallery(Request $request){
        $files =  $request->file('files');;

        $resto_id = $request->resto_id;



        if(isset($files) && count($files) > 0){

            foreach($files as $file){
                $photo = new Photos();
                $extension = $file->getClientOriginalExtension();
                $original_name = $file->getClientOriginalName();
                // $original_name = str_replace(' ', '-', $original_name);
                $extension_array = ['jpg', 'jpeg', 'bmp', 'png'];
                $image_array = ['jpg', 'jpeg', 'bmp', 'png'];
                if (in_array($extension, $extension_array)) {
                    $file_name = 'resto-gallery-'.$resto_id. '-' . time().rand(1000,9999) . '.' . $extension;



                    $destinationPath = public_path('/uploads/resto-gallery/');
                    $file->move($destinationPath, $file_name);

                    $img = Image::make($destinationPath . '/' . $file_name)->resize(1400, 1400, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/' . $file_name);

                    $img = Image::make($destinationPath . '/' . $file_name)->resize(85, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/thumbnails/' . $file_name);
                    // dd($request->file('attachment'));


                    $photo->resto_id = $resto_id;
                    $photo->file_name = $file_name;
                    $photo->photo_type = "gallery";

                    $photo->save();


                }
            }
        }
    }


    public function read_notifications(){
        $id = Auth::user()->restaurants->id;
        $notification = OrderNotifications::where('status','unread')->where('resto_id',$id)->update(['status'=>'read']);
        dd($notification);
    }
}
