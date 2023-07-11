<?php

namespace App\Http\Controllers;

use App\Models\SpecialOffers;
use Illuminate\Http\Request;

class SpecialOffer extends Controller
{
    //

    public function save_offers(Request $request){

        $id = $request->id;

        if(empty($id))
            $offer = new SpecialOffers();
        else
            $offer = SpecialOffers::find($id);
        $offer->resto_id = $request->resto_id;
        $offer->offer_title = $request->offer_title;
        $offer->offer_text = $request->offer_text;
        $offer->offer_discount = $request->offer_discount;

        $offer->is_active = 1;

        $offer->save();

        $id = $offer->id;

        if($id > 0)
        {
            echo json_encode(array('type' => 'success', 'message'=>"Special Offer's data is saved successfully."));
            exit;
        }else{
            echo json_encode(array('type' => 'error', 'message'=>"Special Offer's data is saved successfully."));
            exit;
        }



    }

    public function activate_offers(Request $request){
        $id = $request->id;
        $o = SpecialOffers::where('resto_id',$request->resto_id)->update(['is_active'=>0]);
        $offer = SpecialOffers::find($id);
        $offer->is_active = 1;

        $offer->save();
    }

    public function edit_special_offer($id){


        $offer = SpecialOffers::find($id);

        echo $offer->toJSON();
    }

    public function delete_special_offer($id){


        $offer = SpecialOffers::find($id);
        $offer->deleted_at = date('Y-m-d H:i:s');
        $offer->save();
    }


}
