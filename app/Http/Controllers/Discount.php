<?php

namespace App\Http\Controllers;

use App\Helpers\CommonMethods;

use App\Models\User;
use App\Models\Discounts;
use App\Models\DiscountOutlets;
use App\Models\DiscountItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;
use DB;

class Discount extends Controller
{
    public function discounts(){
        return view('discounts.discounts');
    }

    public function edit_discount($id){
        $discount = Discounts::where('unique_key',$id)->first();
        return view('discounts.discount-form',['discount'=>$discount]);
    }

    public function discount(){
        return view('discounts.discount-form');
    }

    public function save_discount(Request $request){

            $data = $request->except(['id', '_token','selected_outlets']);
            $outlets = $request->outlets;

            $order_type = implode(',',$request->order_type);
            $selected_outlets = $request->selected_outlets;
            $selected_items = $request->discount_item;

			$data['order_type'] = $order_type;
			$data['order_applicable'] = isset($request->order_applicable)?"Yes":"No";
            $data['is_discount_at_delivery'] = isset($request->is_discount_at_delivery)?"Yes":"No";

            if($data['is_discount_at_delivery']=="No"){
                $data['discount_at_delivery'] = null;
                $data['delivery_discount_type'] = null;
            }

           // $data['selected_outlets']=$selected_outlets;
            $data['resto_id'] = CommonMethods::getRestuarantID();;

            $id = $request->id;
            if(empty($id)){
                 $discount = new Discounts();
				$data['unique_key'] = Str::uuid();
				$data['discount_item']=isset($selected_items) && count($selected_items) > 0?implode(',',$selected_items ):"";
			//	dd($data);
               $discount->insert($data);
               $id = DB::getPdo()->lastInsertId();;
            }
           else{
                $discount = Discounts::find($id);
                $id =$discount->update($data);

           }


            if($id > 0){
                if($outlets=="all_outlets")
                   DiscountOutlets::where('discount_id',$id)->delete();
                else{
                  if(isset($selected_outlets)){
                         DiscountOutlets::where('discount_id',$id)->delete();

                                foreach($selected_outlets as $outlet){
                                 $dc = new DiscountOutlets();

                                $dc->resto_id = \App\Helpers\CommonMethods::getRestuarantID();
                                $dc->outlet_id =    $outlet;
                                $dc->discount_id = $id;

                                $dc->save();

                                }



                    }
                }


            if($outlets=="all_items")
                   DiscountItems::where('discount_id',$id)->delete();
                else{
                  if(isset($selected_items)){
                         DiscountItems::where('discount_id',$id)->delete();

                                foreach($selected_items as $item){
                                 $di = new DiscountItems();

                                $di->resto_id = \App\Helpers\CommonMethods::getRestuarantID();
                                $di->item_id =    $item;
                                $di->discount_id = $id;

                                $di->save();

                                }



                    }
                }



                echo json_encode(array('type'=>'success','message'=>'Discount saved!.'));
            }
            else
                echo json_encode(array('type'=>'error','message'=>'Data is not saved!.'));
    }

    public function delete_discount($id){
        $discount = Discounts::where('unique_key',$id)->first();
        $discount->deleted_at = date('Y-m-d H:i:s');
        $discount->is_active = 0;
        $discount->save();
    }

	public function update_status_discount(Request $request){
		$id = $request->id;
		$status = $request->status;

		   $discount = Discounts::where('unique_key',$id)->first();
        $discount->is_active =$status;
        $discount->save();

	}

}
