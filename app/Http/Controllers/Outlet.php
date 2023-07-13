<?php

namespace App\Http\Controllers;

use App\Models\BranchDeliveryAreas;
use App\Models\BranchFeatures;
use App\Models\BranchHours;
use App\Helpers\CommonMethods;
use App\Models\Outlets;
use App\Models\Restaurants;
use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Str;
use File;
use Image;
use App\Models\RestoMetaDefs;
use App\Models\RestoMetas;

class Outlet extends Controller
{
    //

    public function outlets(){
          $resto_id = CommonMethods::getRestuarantID();
      //  $resto = Restaurants::find($this->resto_id);
        $outlets = Outlets::whereNull('deleted_at')->where('resto_id',$resto_id)->get();

        return view('outlets.outlets',['outlets'=>$outlets]);
    }

    public function outlet_form(){
        return view('outlets.new-outlets',['outlet'=>NULL]);
    }

    public function outlet_edit($unique_key){
        $outlet = Outlets::where('unique_key',$unique_key)->first();

        return view('outlets.new-outlets',['outlet'=>$outlet]);
    }

    public function outlet_address(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();

        return view('outlets.outlet-address',['outlet'=>$outlet]);
    }
      public function outlet_delivery_area(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();
        $areas = BranchDeliveryAreas::where('branch_id',$outlet->id)->whereNull('deleted_at')->get();
        //dd($areas);
        return view('outlets.outlet-delivery-area',['outlet'=>$outlet,'areas'=>$areas]);
    }

    public function edit_area($id){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();
        $area = BranchDeliveryAreas::find($id);
        $areas = BranchDeliveryAreas::where('branch_id',$outlet->id)->whereNull('deleted_at')->get();
        return view('outlets.outlet-delivery-area',['outlet'=>$outlet,'area'=>$area,'areas'=>$areas]);
    }

    public function outlet_delivery_area_listing(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();

        $areas = BranchDeliveryAreas::where('branch_id',$outlet->id)->whereNull('deleted_at')->get();

        return view('outlets.outlet-delivery-area-listing',['outlet'=>$outlet,'areas'=>$areas]);
    }

    public function outlet_delivery(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();
        $features = isset($outlet->delivery_feature)?$outlet->delivery_feature:NULL;
        //$hours = isset($outlet->delivery_hours_feature)?$outlet->delivery_hours_feature:NULL;
        return view('outlets.outlet-delivery',['outlet'=>$outlet,'features'=>$features]);
    }
    public function outlet_ordering_mode(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();

        return view('outlets.outlet-ordering-mode',['outlet'=>$outlet]);
    }

    public function outlet_pickup(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();
        $features = isset($outlet->pickup_feature)?$outlet->pickup_feature:NULL;
        return view('outlets.outlet-pickup',['outlet'=>$outlet,'features'=>$features]);
    }

    public function outlet_dining(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();
        $features = isset($outlet->contactless_dining_feature)?$outlet->contactless_dining_feature:NULL;
        return view('outlets.outlet-contactless-dining',['outlet'=>$outlet,'features'=>$features]);
    }

    public function update_feature_status_1(Request $request){
        $id = $request->id;
        $status = $request->status;
        $type = $request->type;

        $outlet = Outlets::find($id);
        if($type=="delivery"){
          $outlet->is_delivery = $status;
        }

        if($type=="pickup"){
          $outlet->is_pickup = $status;
        }

        if($type=="dine-in"){
          $outlet->is_contactless_dining = $status;
        }
        $result = $outlet->save();


    }

     public function save_outlet(Request $request){


        $name = $request->outlet_name;
        $outlet_arabic_name = $request->outlet_arabic_name;
        $time_zone = $request->time_zone;
        $email = $request->email;
        $phone = $request->phone;

        $id = $request->id;

        $resto_id = CommonMethods::getRestuarantID();
        $resto = Restaurants::find($resto_id);

        $restaurants =$resto;
        $branch_id = 0;

        if(empty($id)){
            $outlet= new Outlets(); $outlet->unique_key = \Illuminate\Support\Str::uuid();
        }else{
            $outlet = Outlets::find($id);
        }


        $outlet->resto_id = $restaurants->id;
        $outlet->time_zone = $time_zone;
        $outlet->outlet_arabic_name = $outlet_arabic_name;
        $outlet->name = $name;
		 $outlet->whatsapp_number = $request->whatsapp_number;
        $outlet->email = $email;
        $outlet->country_id = $request->country_id;
        $outlet->phone_number = $phone;

        $outlet->resto_id = $restaurants->id;

        $outlet->save();

        $branch_id = $outlet->id;



        if($branch_id > 0){
            if($request->hasFile('image')){
                $logo = $request->file('image');




                $file_name = Str::slug($request->name)."-branch-main-image".'-'.time();
                $extension = $logo->getClientOriginalExtension();



                Storage::disk('logo')->put($file_name.'.'.$extension,  File::get($logo));
                $file = public_path('uploads/logo/'.$file_name.'.'.$extension);
//uploadFileToAWSCDN($budketName, $restoID ,$restoName ,$sourceFile,$fileName )

                $destinationPath = public_path('/uploads/logo/');


               /* $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/' . $file_name.'.'.$extension);*/

                $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$restaurants->id, $restaurants->resto_unique_name,$file,$file_name);




                /* dd($buckets);
                 $path = Storage::disk('s3')->put("meemcdn/".$file_name.'.'.$extension,  File::get($logo));
                 dd($path);*/





                /* $destinationPath = public_path('/uploads/logo/');


                 $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(1400, null, function ($constraint) {
                     $constraint->aspectRatio();
                 });

                 $img->save($destinationPath . '/' . $file_name.'.'.$extension);

                 $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(85, null, function ($constraint) {
                     $constraint->aspectRatio();
                 });

                 $img->save($destinationPath . '/thumbnails/' . $file_name.'.'.$extension);*/


                if($result['type']=="success"){
                    $logo = Photos::where('resto_id',$restaurants->id)->where('branch_id',$branch_id)->where('photo_type','branch')->first();

                    if(!$logo)
                        $logo = new Photos();


                    $logo->file_name = $result['url'];
                    $logo->aws_cdn = $result['url'];
                    $logo->resto_id = $restaurants->id;
                    $logo->branch_id = $branch_id;
                    $logo->photo_type = 'branch';


                    $r = $logo->save();

                    File::delete($file);
                }


                //$resto->text =
            }

            $resto_metas = isset($request->resto_meta) && count($request->resto_meta) > 0?$request->resto_meta:[];
                    //dump($resto_metas);
                    $resto_meta_value = isset($request->resto_meta_value) && count($request->resto_meta_value) > 0?$request->resto_meta_value:[];

                    if(count($resto_metas) >  0){
                        RestoMetas::where('bussiness_id',$resto->id)->where('for_role',Auth::user()->role)->where('outlet_id',$branch_id)->delete();
                        foreach($resto_metas as $meta){

                            $rm = RestoMetaDefs::find($meta);
                         //   dump($rm);
                                $r = new RestoMetas();
                                $r->meta_def_id = $meta;
                                $r->bussiness_id = $resto->id;
                                $r->outlet_id =  $branch_id;
                                $r->meta_val = isset($resto_meta_value[$meta])?$resto_meta_value[$meta]:$rm->meta_def_name;
                                $r->for_role = Auth::user()->role;
                                $r->status = 1;

                                $r->save();
                        }
                    }
            echo json_encode(array('type' => 'success', 'message'=>"Outlet's data is saved successfully.",'unique_key'=>$outlet->unique_key));
        }else{
            echo json_encode(array('type' => 'error', 'message'=>"Outlet's data is not saved, check info again."));
        }






    }

    public function update_outlet(Request $request){
        $id = $request->id;
        $status = $request->status;

        $outlet = Outlets::find($id);
        $outlet->active = $status;

        $outlet->save();

    }

    public function save_address(Request $request){
        $unique_id  = $request->unique_id;

        $address    = $request->address;
        $area       = $request->area;
        $outlet     = Outlets::where('unique_key',$unique_id)->first();
        $outlet->latitude = $request->lat;
        $outlet->longitude = $request->lng;
        if(isset($outlet)){
            $outlet->address = $address;
			$outlet->address_arabic = $request->address_arabic;
            $outlet->place = $area;

            $result = $outlet->save();

            if($result)
                echo json_encode(array('type' => 'success', 'message'=>"Outlet's address is saved successfully."));
            else
                echo json_encode(array('type' => 'error', 'message'=>"Outlet's address is not saved, check info again."));
        }else{
            echo json_encode(array('type' => 'error', 'message'=>"Outlet key is invalid."));
        }


    }

    public function save_branch_feature(Request $request){

       $payment_method = isset($request->payment_methods)?implode(',',$request->payment_methods):NULL;
       $feature_type = $request->feature_type;
       $preparation_time = $request->preparation_time;
       $preparation_delivery_time = $request->preperation_delivery;
       $time_from = $request->time_from;
	   $time_to = $request->time_to;
	   $estimated_time_type = $request->estimated_time_type;

		$estimated_time = $time_from.' - '.$time_to.':'.$estimated_time_type;

        $is_open = $request->is_open;
       $id = $request->id;

       $feature = BranchFeatures::where('branch_id',$id)->where('feature_type',$feature_type)->first();
       if(!isset($feature))
       $feature = new BranchFeatures();

       $feature->payment_methods = $payment_method;
        $feature->feature_type = $feature_type;
        $feature->preparation_timing = $preparation_time;
        $feature->preparation_delivery_time = $preparation_delivery_time;
		$feature->preparation_delivery_type = $request->preparation_delivery_type;
        $feature->estimated_time = $estimated_time;
		//$feature->estimated_time_type = $request->estimated_time_type;
        $feature->branch_id = $id;

        $feature->save();

        $start_time = $request->start_time;
        $end_time = $request->end_time;

        if(isset($start_time)){
            $time = BranchHours::where('branch_id',$id)->where('hours_for',$feature_type)->delete();
            foreach($start_time as $k=>$s){



               // $time = BranchHours::where('day_name',$k)->where('branch_id',$id)->where('hours_for',$feature_type)->first();

               // if(!isset($time))
                foreach($s as $a=>$v){
                    $time = new BranchHours();


                    $time->day_name = $k;
                    $time->branch_id = $id;
                    $time->hours_for = $feature_type;
                    $time->start_time = isset($is_open[strtolower($k)])? date('H:i',strtotime($v)):NULL;
                    $time->end_time = isset($is_open[strtolower($k)])?date('H:i',strtotime($end_time[$k][$a])):NULL;
                    $time->status = isset($is_open[strtolower($k)])?"open":"close";
                    $result = $time->save();
                    //$time->status = 'open';
                }



                //$result = $time->save();

            }
        }



        if($result)
            echo json_encode(array('type' => 'success', 'message'=>"Outlet's ".$feature_type." is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Outlet's ".$feature_type." is not saved, check info again."));


    }

    public function update_feature_status(Request $request){
        $id = $request->outletId;
        $status = $request->is_active=="true"?1:0;
        $feature = $request->feature;

        $outlet = Outlets::find($id);
        $outlet->$feature = $status;

        $result = $outlet->save();


    }

    public function delete_outlet ($id){
        $outlet = Outlets::where('unique_key',$id)->first();

        $outlet->deleted_at =date('Y-m-d H:i:s');
        $outlet->save();
    }

    public function delete_area ($id){
        $outlet = BranchDeliveryAreas::find($id);

        $outlet->deleted_at =date('Y-m-d H:i:s');
        $outlet->save();
    }

    public function save_outlet_area(Request $request){
        $id = $request->id;
        $coordinates    = $request->coordinates;
        $area_name      = $request->area_name;
        $min_price      = $request->min_basket;
        $delivery_fee   = $request->delivery_fee;
        $unique_id      = $request->unique_id;
        $center_radius  = $request->center_radius;

        $outlet         = Outlets::where('unique_key',$unique_id)->first();
        $outlet_id      = $outlet->id;
            if($id==0)
           $area = new BranchDeliveryAreas();
            else
                $area = BranchDeliveryAreas::find($id);

           $area->branch_id = $outlet_id;
           $area->lat_lag = $coordinates;
          // $area->center_radius = $center_radius;
           $area->area_name = $area_name;
           $area->delivery_fee = $delivery_fee;
            $area->min_price = $min_price;
            $result = $area->save();


        if($result)
            echo json_encode(array('type' => 'success', 'message'=>"Area's information is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Outlet's information is not saved, check info again."));
    }

    public function update_area_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        $outlet = BranchDeliveryAreas::find($id);
        $outlet->status = $status;

        $outlet->save();
    }

    public function outlet_digital_menu(){
        $outlet = Outlets::where('unique_key',request()->get('o'))->first();

        return view('outlets.outlet-digital-menu',['outlet'=>$outlet]);
    }

    public function pause_orders(){
              $resto_id = CommonMethods::getRestuarantID();
      //  $resto = Restaurants::find($this->resto_id);
         $outlets = Outlets::whereNull('deleted_at')->where('resto_id',$resto_id)->get();

        return view('outlets.outlets-pause-orders',['outlets'=>$outlets]);
    }
}
