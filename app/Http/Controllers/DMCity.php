<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\DMCities;
use App\Models\PlaceCategories;
use App\Models\PlaceCategoriesMapped;
use App\Models\PlaceDeliveryPrices;
use App\Models\RestoCategoriesMapped;
use App\Models\RestoSMSs;
use Illuminate\Http\Request;

class DMCity extends Controller
{
    //

    public function places(){
        $c = request()->get('country');
        $country_id = isset($c) && !empty($c)?request()->get('country'):"2";


        $country_name = Countries::where('id',$country_id)->first();
        $cities = DMCities::where('country_id',$country_id)->whereNull('deleted_at')->get();

        $d = [
            'cities' => $cities,
            'country_id' =>$country_id,
            'country_name' => $country_name->country_name
        ];
        return view('places.places',$d);
    }

    public function new_place(){
        $countries = Countries::where('active',1)->get();
        $categories = PlaceCategories::whereNull('deleted_at')->get();

        $data = [
            'countries' => $countries,
            'categories' =>$categories
        ];
        return view('places.place',$data);
    }


    public function edit_place($id){
        $countries = Countries::where('active',1)->get();
        $categories = PlaceCategories::whereNull('deleted_at')->get();
        $place = DMCities::find($id);
        $data = [
            'countries' => $countries,
            'place' => $place,
            'categories' =>$categories
        ];
        return view('places.place',$data);
    }

    public function save_place(Request $request){
        $id = $request->id;
        $mapped = "";
        if(empty($id)){
            $place = new DMCities();
            $mapped = new PlaceCategoriesMapped();
        }

        else{
            $place = DMCities::find($id);
            $mapped = PlaceCategoriesMapped::where('place_id',$id)->first();

        }


        $place->country_id = $request->country_id;
        $place->city_id = $request->city_id;
        $place->place_name = $request->place_name;
        $place->category_id = $request->category_id;
        $place->is_active = isset($request->is_active)?1:0;

        $place->save();

        $id = $place->id;
        if(isset($mapped)){
            $mapped->category_id = $request->category_id;
            $mapped->place_id = $id;
            $mapped->country_id = $request->country_id;
            $mapped->city_id = $request->city_id;
            $mapped->save();
        }else{
            $mapped = new PlaceCategoriesMapped();
            $mapped->category_id = $request->category_id;
            $mapped->country_id = $request->country_id;
            $mapped->city_id = $request->city_id;
            $mapped->place_id = $id;

            $mapped->save();
        }

        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"Place's data is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Place's data is not saved, check info again."));
    }

    public function delete_place($id){
        $place = DMCities::find($id);

        $place->deleted_at = date('Y-m-d H:i:s');

        $place->save();
    }
    public function save_country(Request $request){
        $id = $request->id;
        $name = $request->country_name;
        if(empty($id))
        $country = new Countries();
        else
            $country = Countries::find($id);

        $country->country_name = $name;
        $country->country_code = $request->country_code;
        $country->active = 1;

        $country->save();
    }

    public function get_cities($code){
        $cities = DMCities::where('country_id',$code)->groupBy('city')->get(['id','city']);

       echo json_encode($cities);
    }

    public function get_places($name){
        $places = DMCities::where('city',$name)->get(['city_unique_id','city_name','category']);

        echo json_encode($places);
    }

    public function save_delivery_fee(Request $request){
        $category_id = $request->category_id;
        $category_unique_id = $request->category_unique_id;
        $resto_id = $request->resto_id;
        $minimum_delivery_fee = $request->minimum_delivery_fee;


        $delivery_fee = $request->delivery_fee;

        $places = $request->places;

        $resto_category = RestoCategoriesMapped::where('resto_id',$resto_id)->where('category_id',$category_id)->first();

        if(empty($resto_category))
            $resto_category = new RestoCategoriesMapped();

        $resto_category->category_id = $category_id;
        $resto_category->resto_id = $resto_id;
        $resto_category->delivery_fee = $minimum_delivery_fee;


        $resto_category->save();

        $id = $resto_category->id;


        foreach($delivery_fee as $k=>$fee){

            if($fee || $fee=="0"){
                $place_delivery = PlaceDeliveryPrices::where('category_unique_id',$category_unique_id)->where('place_unique_id',$places[$k])->where('resto_id',$resto_id)->first();

                if(!isset($place_delivery))
                $place_delivery = new PlaceDeliveryPrices();

                $place_delivery->category_unique_id =$category_unique_id;
                $place_delivery->place_unique_id = $places[$k];
                $place_delivery->resto_id = $resto_id;
                $place_delivery->delivery_price = $fee;

                $place_delivery->save();

            }
        }

        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"Delivery's data is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Delivery's data is not saved, check info again."));
    }
}
