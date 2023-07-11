<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\MapRecipeCategories;
use App\Models\Photos;
use App\Models\Recipes;
use App\Models\Restaurants;
use App\Models\VariationData;
use App\Models\VariationTypes;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\ClothOptions;
use Str;
use File;
use Illuminate\Support\Facades\Hash;
use Image;
use App\Models\ProductFAQs;

use App\Helpers\CommonMethods;

class Recipe extends Controller
{
    public function inventory(){
              $resto_id = CommonMethods::getRestuarantID();
                $resto = Restaurants::find($resto_id);
         $recipes = $resto->recipes();;
         $data = [
            'recipes' => $recipes->get()
        ];
        return view('recipes.inventory',$data);
    }


    //
    public function recipes(){

        Toastr::success('Post added successfully :)','Success');
        $resto_id = CommonMethods::getRestuarantID();
                $resto = Restaurants::find($resto_id);
        // $recipes = $resto->recipes();;
        $categories  = Categories::where('resto_id',$resto_id)->with(['categories_has_recipes'=>function($q){ $q->orderBy('tb_dm_recipe_categories_map.display_order','ASC'); }])->whereNull('deleted_at')->get();


        //dd($categories[0]->items);
        $data = [
            'categories' => $categories
        ];
        return view('recipes.recipes',$data);
    }

    public function new_recipe(){
        $resto_id = CommonMethods::getRestuarantID();

            $categories = Categories::whereNull('deleted_at')->where('is_active',1)->whereIn('resto_id',[$resto_id])->get();



        $data = [
         'categories' => $categories
     ];
        return view('recipes.recipe_form',$data);
    }

    public function editnew($id){
        $resto_id = CommonMethods::getRestuarantID();

            $categories = Categories::whereNull('deleted_at')->where('is_active',1)->whereIn('resto_id',[$resto_id])->get();


        $recipe = Recipes::find($id);
        $data = [
            'categories' => $categories,
            'recipe' =>$recipe
        ];
        return view('recipes.recipe_form_new',$data);
    }
	public function edit($id){
        $resto_id = CommonMethods::getRestuarantID();

        $categories = Categories::whereNull('deleted_at')->where('is_active',1)->whereIn('resto_id',[$resto_id])->get();
        $variant_types = VariationTypes::where('resto_id',$resto_id)->where('status','active')->whereNull('deleted_at')->get();

        $recipe = Recipes::find($id);
        $data = [
            'categories' => $categories,
            'recipe' =>$recipe,
            'variant_types'=>$variant_types
        ];
        return view('recipes.recipe_form',$data);
    }

    public function get_variation_attributes(Request $request){
       $variations = $request->variations;
        $variations = VariationTypes::with('attributes')->whereIn('id',$variations)->get();
       return response()->json($variations);


    }

 public function save(Request $request){
    $resto_id = CommonMethods::getRestuarantID();
                $resto = Restaurants::find($resto_id);


        $id = $request->id;
	 $colorOption = "";

        if(empty($id)){
            $recipe = new Recipes();
            $recipe->unique_shared_key = Str::uuid();
        }

        else{
			$recipe = Recipes::find($id);
			if($request->business_type=="ClothsStore"){
				$colorOption = $recipe->color_option;

			if($colorOption!=$request->color_option)
				$option = ClothOptions::where('resto_id',$resto_id)->where('product_id',$id)->where('type',$colorOption)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
			}


		}


        $recipe->name = $request->name;
        $recipe->arabic_name = $request->arabic_name;
        $recipe->status = '1';


        $recipe->resto_id = $resto_id;
        $recipe->price = $request->price;
        $recipe->show_recipe_main_price = isset($request->show_recipe_main_price)?1:0;
        $recipe->short_description = $request->short_description;
	 	$recipe->short_description_arabic = $request->short_description_arabic;
        $recipe->is_customized = isset($request->is_customized)?1:0;
        $recipe->status = isset($request->status)?1:0;
        $recipe->allow_pre_order = isset($request->allow_pre_order)?"Yes":"No";
	    if($request->business_type=="ClothsStore")
	    $recipe->color_option = $request->color_option;


        $recipe->save();

        $recipe_id = $recipe->id;

        if($recipe_id > 0){

            $categories = $request->category;
            //dd($categories);
            MapRecipeCategories::where('recipe_id',$recipe_id)->delete();

            if(count($categories) > 0){
                foreach($categories as $category){
                    $m_c = new MapRecipeCategories();
                    $m_c->category_id = $category;
                    $m_c->recipe_id =  $recipe_id;
                    $m_c->resto_id =   $resto_id;;
                    $m_c->save();
                }


            }


            if($request->hasFile('main_image')){
                $logo = $request->file('main_image');



                $file_name = Str::slug($request->name)."-main_image".'-'.time();
                $extension = $logo->getClientOriginalExtension();



                Storage::disk('main_image')->put($file_name.'.'.$extension,  File::get($logo));

                $destinationPath = public_path('/uploads/main_image/');


                /*$img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });*/

               // $img->save($destinationPath . '/' . $file_name.'.'.$extension);
                $file = public_path('uploads/main_image/'.$file_name.'.'.$extension);
                $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$resto_id, $resto->resto_unique_name,$file,$file_name);
/*
                $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/thumbnails/' . $file_name.'.'.$extension);*/



                $main_image = Photos::where('recipe_id',$recipe_id)->where('photo_type','main_image')->first();
                if(!$main_image)
                    $main_image = new Photos();


                $main_image->file_name = $result['url'];
                $main_image->aws_cdn = $result['url'];
                $main_image->recipe_id = $recipe_id;
                $main_image->photo_type = 'main_image';
                $main_image->resto_id =  $resto_id;;;

                $main_image->save();
                 File::delete(  $file);

                //$resto->text =
            }
        if($request->business_type=="ClothsStore"){



			$color_image = ($request->color_image);
			if($request->hasFile('color_image'))
                $color_image = $request->file('color_image');


                $colors  = !empty($request->color)?$request->color:NULL;
                $sizes   = !empty($request->size)?$request->size:NULL;

                if(isset($colors) && count($colors) > 0){
					// $option = ClothOptions::where('resto_id',$resto_id)->where('product_id',$recipe_id)->delete();
                    foreach($colors as $color){
						$img_url = NULL;

						if(isset($color_image[$color])){
							// $color_image = $request->file($request->color_image);
							//dump($color_image[$color]);
							$file = $color_image[$color];
							$file_name = "color-image-main_image".'-'.time();
							$extension = $file->getClientOriginalExtension();



							Storage::disk('main_image')->put($file_name.'.'.$extension,  File::get($file));

							$destinationPath = public_path('/uploads/main_image/');


							/*$img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(1400, null, function ($constraint) {
								$constraint->aspectRatio();
							});*/

						   // $img->save($destinationPath . '/' . $file_name.'.'.$extension);
							$file = public_path('uploads/main_image/'.$file_name.'.'.$extension);
							$result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$resto_id, $resto->resto_unique_name,$file,$file_name);
							$img_url = $result['url'];

						}

                      $options = new ClothOptions();
                      $options->resto_id =  $resto_id;
                      $options->product_id = $recipe_id;
                      $options->name = $color;
                      $options->type=$request->color_option;
					  $options->img_url = $img_url;
                      $options->save();
                    }




                }

                if(isset($sizes) && count($sizes) > 0){
					$option = ClothOptions::where('resto_id',$resto_id)->where('product_id',$recipe_id)->where('type','size')->delete();
                    foreach($sizes as $size){
                      $options = new ClothOptions();
                      $options->resto_id =  $resto_id;
                      $options->product_id = $recipe_id;
                      $options->name = $size;
                      $options->type="size";

                      $options->save();
                    }




                }
            }

            echo json_encode(array('type' => 'success', 'message'=>"Item is saved successfully."));
        }

        else
            echo json_encode(array('type' => 'error', 'message'=>"Item is not saved successfully."));



    }
   /* public function save(Request $request){
        $id = $request->id;

        if(empty($id)){
            $recipe = new Recipes();
            $recipe->unique_shared_key = Str::uuid();
        }

        else
            $recipe = Recipes::find($id);

        $recipe->name = $request->name;
        $recipe->status = '1';


        $recipe->resto_id = Auth::user()->restaurants->id;
        $recipe->price = $request->price;
        $recipe->short_description = $request->short_description;
        $recipe->is_customized = isset($request->is_customized)?1:0;
        $recipe->status = isset($request->status)?1:0;


        $recipe->save();

        $recipe_id = $recipe->id;

        if($recipe_id > 0){

            $categories = $request->category;
            //dd($categories);
            MapRecipeCategories::where('recipe_id',$recipe_id)->delete();

            if(count($categories) > 0){
                foreach($categories as $category){
                    $m_c = new MapRecipeCategories();
                    $m_c->category_id = $category;
                    $m_c->recipe_id =  $recipe_id;
                    $m_c->resto_id =   Auth::user()->restaurants->id;;
                    $m_c->save();
                }


            }


            if($request->hasFile('main_image')){
                $logo = $request->file('main_image');



                $file_name = Str::slug($request->name)."-main_image".'-'.time();
                $extension = $logo->getClientOriginalExtension();



                Storage::disk('main_image')->put($file_name.'.'.$extension,  File::get($logo));

                $destinationPath = public_path('/uploads/main_image/');


                $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/' . $file_name.'.'.$extension);

                $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/thumbnails/' . $file_name.'.'.$extension);


                $main_image = Photos::where('recipe_id',$recipe_id)->where('photo_type','main_image')->first();
                if(!$main_image)
                    $main_image = new Photos();


                $main_image->file_name = $file_name.'.'.$extension;
                $main_image->recipe_id = $recipe_id;
                $main_image->photo_type = 'main_image';
                $main_image->resto_id =  Auth::user()->restaurants->id;;;

                $main_image->save();

                //$resto->text =
            }


            echo json_encode(array('type' => 'success', 'message'=>"Recipe's data is saved successfully."));
        }

        else
            echo json_encode(array('type' => 'error', 'message'=>"Recipe's data is not saved successfully."));



    }*/

    public function delete($id){
        $recipe = Recipes::find($id);
        $recipe->deleted_at = date('Y-m-d H:i:s');
        $recipe->save();
    }

    public function upload_gallery(Request $request){

        $resto_id = CommonMethods::getRestuarantID();
                $resto = Restaurants::find($resto_id);
        $files =  $request->file('files');;

        $recipe_id = $request->recipe_id;



        if(isset($files) && count($files) > 0){

            foreach($files as $file){
                $photo = new Photos();
                $extension = $file->getClientOriginalExtension();
                $original_name = $file->getClientOriginalName();
                // $original_name = str_replace(' ', '-', $original_name);
                $extension_array = ['jpg', 'jpeg', 'bmp', 'png'];
                $image_array = ['jpg', 'jpeg', 'bmp', 'png'];
                if (in_array($extension, $extension_array)) {
                    $file_name = 'recipe-gallery-'.$recipe_id. '-' . time().rand(1000,9999) . '.' . $extension;



                    $destinationPath = public_path('/uploads/resto-gallery/');
                    $file->move($destinationPath, $file_name);
                    $file = public_path('uploads/resto-gallery/'.$file_name);
                     $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$resto->id, $resto->resto_unique_name,$file,$file_name);

                   /* $img = Image::make($destinationPath . '/' . $file_name)->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/' . $file_name);

                    $img = Image::make($destinationPath . '/' . $file_name)->resize(85, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $img->save($destinationPath . '/thumbnails/' . $file_name);
*/
                    // dd($request->file('attachment'));


                    $photo->recipe_id = $recipe_id;
                     $photo->file_name = $result['url'];
                    $photo->aws_cdn = $result['url'];
                    $photo->photo_type = "gallery";
                    $photo->resto_id =  $resto->id;;;
                    $photo->save();


                }
            }
        }
    }

    public function show($id){
        $recipe = Recipes::find($id);
        $categories = isset($recipe->categories)?$recipe->categories->pluck('category_id'):NULL;
        if($categories){

            $categories = Categories::whereIn('id',$categories)->pluck('name')->toArray();
            $categories = implode(', ',$categories);

        }
        $data = [
            'recipe' => $recipe,
            'categories' => $categories
        ];

        return view('recipes.show',$data);
    }

    public function remove_main_image(Request $request){
        $id = $request->id;

        $photo = Photos::where('recipe_id',$id)->where('photo_type','main_image')->first();

        $photo->delete();
    }

     public function exclude_outlet(Request $request){
        $is_exclude = $request->is_exclude;
        $outlet_id = $request->outlet_id;
        $recipe_id = $request->recipe_id;

        $recipe = Recipes::find($recipe_id);

        $exclude_outlets = $recipe->exclude_outlets;




        if(empty($exclude_outlets)){
            $o = explode(',',$exclude_outlets);
            $o[$outlet_id] = $outlet_id;
         //   dump($o);
            $recipe->exclude_outlets = $outlet_id;
        }else{
            $o = explode(',',$exclude_outlets);
            if($is_exclude=="true"){

                $key = array_search($outlet_id, $o);

                unset($o[$key]);

            }
            else
            $o[] =  $outlet_id;
            $o = array_unique($o);
             $recipe->exclude_outlets = implode(',',$o);
        }


        $recipe->save();
    }

	public function save_faq(Request $request){

		$id = $request->id;

		if(empty($id))
			$faq = new ProductFAQs();
		else
			$faq = ProductFAQs::find($id);

		$faq->product_id = $request->product_id;
		$faq->question = $request->question;
		$faq->answer = $request->answer;

		$faq->save();

		$faq_id = $faq->id;

		if($faq_id > 0)
			echo json_encode(array('type' => 'success', 'message'=>"FAQ's data is saved successfully."));
		else
			echo json_encode(array('type' => 'error', 'message'=>"FAQ's data is not saved."));
	}

	public function delete_faq(Request $request){
		$faq = ProductFAQs::find($request->id);
		$faq->deleted_at = date('Y-m-d H:i:s');

		$faq->save();
	}

	public function delete_color_image(Request $request){
		$id = $request->id;
		$c = ClothOptions::find($id);
		$c->deleted_at = date('Y-m-s H:i:s');
		$c->save();

	}

    public function update_recipe_orders(Request $request){
        $id = $request->ids;


        foreach($id as $i){
           $item_category_id = explode('-',$i['id']);
           $display_order = $i['position'];

           $item_id = $item_category_id[0];
           $category_id = $item_category_id[1];
           // dump('itemID: '.$item_id.' CategoryID: '.$category_id.' Order: '.$display_order);
           $item_category = MapRecipeCategories::where('recipe_id',$item_id)->where('category_id',$category_id)->first();
           $item_category->display_order = $display_order;
           $item_category->save();


        }
    }

    public function save_variation_data(Request  $request){
        $product_id = $request->product_id;
        $resto_id = CommonMethods::getRestuarantID();
        $data = $request->all();
        $variant_type = $request->variant_type;
        $id = $request->id;



        $keys = [];

        $variant_id = 0;
        $i=0;
        foreach($data as $k=>$d){
            if(is_array($d)){

                $keys[] = $k;

            }
        }
        if(isset($keys[0])){
        $first_data = ($data[$keys[0]]);
        //dd($first_data);
        $i=0;
        foreach($first_data as $k=>$fd){
            $rows = null;

            foreach($keys as $key){
                if(isset($data[$key][$k]) && !empty($data[$key][$k])){
                if($key=="variant_type")
                    $variant_id = $data[$key][$k];

                if($key!="image")
                $rows[$key]=isset($data[$key][$k])?$data[$key][$k]:null;
                else{
                    if(isset($data[$key][$k])){
                        $image = ($data[$key][$k]);
                        $file_name = "variation-image".'-resto-'.$resto_id.'-'.time();
                        $extension = $image->getClientOriginalExtension();

                        Storage::disk('main_image')->put($file_name.'.'.$extension,  File::get($image));

                        $destinationPath = public_path('/uploads/main_image/');

                        $file = public_path('uploads/main_image/'.$file_name.'.'.$extension);
                        $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',$resto_id, '',$file,$file_name);
                        $rows[$key]= isset($result) && count($result) > 0 && $result['type']=="success"?$result['url']:"";



                    }
                }


            }


            }

            if(isset($rows['variation_price']) && isset($rows['variation_quantity'])){

                if(empty($id))
                    $variation_data = new VariationData();
                else
                    $variation_data = VariationData::find($id);


                $variation_data->variation_ids = json_encode($variant_type);
                $variation_data->resto_id = $resto_id;
                $variation_data->product_id = $product_id;
                $variation_data->variations = json_encode($rows);

                $variation_data->save();

                $i++;
            }


        }
        if($i>0)
            return response()->json(array('type'=>'success','message'=>$i.' variants are saved with this item'));
    }
        return response()->json(array('type'=>'error','message'=>'No variants is saved with this item'));
        ;

    }

    public function delete_variation($id){
        $variation = VariationData::find($id)->update(['deleted_at'=>Carbon::now()->format('Y-m-d H:i:s')]);

    }

    public function getVaraitionDataBasedOnID($id){
        $variation = VariationData::find($id);
        $variation_type = json_decode($variation->variation_ids);
        $variations = json_decode($variation->variations);

        return response()->json(array('variation_type'=>$variation_type,'variations'=>$variations));
    }
}
