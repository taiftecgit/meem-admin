<?php

namespace App\Http\Controllers;

use App\Models\ExtraOptionItems;
use App\Models\ExtraOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExtraOption extends Controller
{
    //

    public function save(Request $request)
    {

        $extra_option = new ExtraOptions();

        $name = $request->option;

        $ex = ExtraOptions::where('name',$name)->where('resto_id',$request->resto_id)->whereNull('deleted_at')->where('recipe_id',$request->recipe_id)->first();

        if(isset($ex)){
            echo json_encode(array('type' => 'error', 'message'=>$name." is already exist with, try with different"));
            exit;
        }


        $extra_option->name = $request->option;
		 $extra_option->name_arabic = $request->arabic_option;
        $extra_option->price = $request->price;
        $extra_option->resto_id = $request->resto_id;
        $extra_option->recipe_id = $request->recipe_id;
        $extra_option->save();
        $extra_option_id = $extra_option->id;
        $items = isset($request->item_name)?$request->item_name:[];
        $prices = isset($request->item_price)?$request->item_price:[];
        $item_type = isset($request->item_type)?$request->item_type:[];
        if (count($items) > 0) {
            foreach ($items as $k => $item) {
                $new_item = new ExtraOptionItems();
                $new_item->extra_option_id = $extra_option_id;
                $new_item->name = $item;
                $new_item->price = $prices[$k];
                $new_item->item_type = $item_type[$k];
                $new_item->save();
            }
        }

        echo json_encode(array('type' => 'success', 'message'=>"Extra Options's data is saved successfully."));
    }

    public function load_items($id){
        $items = ExtraOptionItems::with('childern')->where('extra_option_id',$id)->whereNull('deleted_at')->where('parent_id',0)->get()->toArray();
        return response()->json(['data'=>$items]);
    }

    public function load_option($id){
        $option = ExtraOptions::find($id)->toArray();
        return response()->json(['data'=>$option]);
    }

    public function update(Request $request){
       $id = $request->id;
       $option =ExtraOptions::find($id);

       $option->name = $request->option;
		$option->name_arabic = $request->arabic_option;
       $option->price = $request->price;

       $option->save();
    }

    public function update_item(Request $request){
        $id = $request->id;
        $item =ExtraOptionItems::find($id);
        $item->item_type = $request->item_type;
        $item->name = $request->option;
		$item->name_arabic = $request->name_arabic;
        $item->price = $request->price;

        $item->save();
    }

    public function delete_option($id){
        $option =ExtraOptions::find($id);


        $option->deleted_at = Carbon::now();

        $option->save();
    }

    public function delete_item_option($id){
        $item =ExtraOptionItems::find($id);


        $item->deleted_at = Carbon::now();

        $item->save();
    }
    public function load_item($id){
        $option = ExtraOptionItems::find($id)->toArray();
        return response()->json(['data'=>$option]);
    }

    public function add_new_items(Request $request){
        $extra_option_id = $request->extra_option_id;
        $parent_id = $request->parent_id;
        $items = isset($request->item_name)?$request->item_name:[];
		$items_ar = isset($request->item_name_arabic)?$request->item_name_arabic:[];

        $prices = isset($request->item_price)?$request->item_price:[];
        $item_type = isset($request->item_type)?$request->item_type:[];
        if (count($items) > 0) {
            foreach ($items as $k => $item) {
                $new_item = new ExtraOptionItems();
                $new_item->extra_option_id = $extra_option_id;
                $new_item->name = $item;
				$new_item->name_arabic = $items_ar[$k];
                $new_item->parent_id = !empty($parent_id)?$parent_id:0;
                $new_item->price = $prices[$k];
                $new_item->item_type = $item_type[$k];
                $new_item->save();
            }
        }

        echo json_encode(array('type' => 'success', 'message'=>"Extra Options's data is saved successfully."));
    }

    public function update_mandatory_item(Request $request){
        $id = $request->id;
        $option =ExtraOptions::find($id);

        $items = (isset($option->extra_option_items) && $option->extra_option_items->count() > 0)?$option->extra_option_items->count():0;

        if($items==0){
            echo json_encode(array('type' => 'error', 'message'=>"No item found with this extra option."));
            exit;
        }

        //if($items<$request->mandatory_amount){
       //     echo json_encode(array('type' => 'error', 'message'=>"Your number is exceed total number of items (".$items.")."));
      //      exit;
      //  }


        $option->is_mandatory = 1;
        $option->mandatory_amount = $request->mandatory_amount;

        $option->save();

        echo json_encode(array('type' => 'success', 'message'=>"Mandatory option is added,"));
        exit;
    }

    public function remove_mandatory_item($id){
        $option =ExtraOptions::find($id);
        $option->is_mandatory = NULL;
        $option->mandatory_amount = NULL;

        $option->save();
    }
}
