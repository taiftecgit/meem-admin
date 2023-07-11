<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Countries;
use App\Models\PlaceDeliveryPrices;
use App\Models\PlaceCategories;
use App\Models\RestoCategoriesMapped;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaceManagement extends Controller
{
    //

    public function countries(){
        $countries = Countries::whereNull('deleted_at')->get();

        return view('system-management.place-management.counteries',['countries'=>$countries]);
    }

    public function edit_country($id){
        $country = Countries::find($id);
        return $country->toJson();
    }
    public function delete_country($id){
        $country = Countries::find($id);
        $country->deleted_at = date('Y-m-d H:i:s');

        $country->save();
    }

    public function update_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $country = Countries::find($id);
        $country->active = $status;

        $country->save();
    }

    //********************** PLACE CATEGORIES ******************

    public function categories(){
        $categories = PlaceCategories::whereNull('deleted_at')->get();

        return view('system-management.place-management.categories',['categories'=>$categories]);
    }

    public function edit_category($id){
        $category = PlaceCategories::find($id);
        return $category->toJson();
    }
    public function delete_category($id){
        $category = PlaceCategories::find($id);
        $category->deleted_at = date('Y-m-d H:i:s');

        $category->save();
    }

    public function update_status_category(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = PlaceCategories::find($id);
        $category->active = $status;

        $category->save();
    }

    public function save_place_category(Request $request){
        $id = $request->id;

        if(empty($id)){
            $category = new PlaceCategories();
            $category->category_unique_id = Str::uuid();
        }

        else
            $category = PlaceCategories::find($id);

        $category->category_title = $request->category_title;



        $category->save();

        $id = $category->id;
        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"Category's data is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Category's data is not saved, check info again."));
    }

    //---------------------- CITIES -/////////////////////////////

    public function cities(){
        $countries = Countries::whereNull('deleted_at')->get();
        $c = request()->get('country');
        $country_id = isset($c) && !empty($c)?request()->get('country'):"2";

        $cities = Cities::whereNull('deleted_at')->where('country_id',$country_id)->get();

        return view('system-management.place-management.cities',['cities'=>$cities,'countries'=>$countries,'country_id'=>$country_id]);
    }

    public function edit_city($id){
        $city = Cities::find($id);
        return $city->toJson();
    }
    public function delete_city($id){
        $city = Cities::find($id);
        $city->deleted_at = date('Y-m-d H:i:s');

        $city->save();
    }

    public function update_status_city(Request $request){
        $id = $request->id;
        $status = $request->status;
        $city = Cities::find($id);
        $city->is_active = $status;

        $city->save();
    }

    public function save_city(Request $request){
        $id = $request->id;

        if(empty($id)){
            $city = new Cities();
            $city->city_unique_id = Str::uuid();
        }

        else
            $city = Cities::find($id);


        $city->name = $request->city_name;
        $city->country_id = $request->country_id;

        $city->is_active = 1;

        $city->save();

        $id = $city->id;
        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"City's data is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"City's data is not saved, check info again."));
    }

    //////////////////////////////////////////////////////////////////////////////////////
    /// //************************************************************************//

    public function getCityByCountryID($country_id){

        $cities = Cities::whereNull('deleted_at')->where('is_active',1)->where('country_id',$country_id)->get();

        echo json_encode($cities);
    }

    public function save_resto_category(Request $request){
        $resto_id = $request->resto_id;
        $price =$request->price;
        $category = $request->categories;

        RestoCategoriesMapped::where('resto_id',$resto_id)->delete();

        foreach($category as $k=>$p){
            $map = new RestoCategoriesMapped();

            $map->category_id = $p;
            $map->resto_id = $resto_id;
            $map->default_price = $price[$k];

            $map->save();
        }
    }

    public function delete_saved_delivery_fee($id){
        $delivery = PlaceDeliveryPrices::find($id);

        $delivery->delete();
    }

}
