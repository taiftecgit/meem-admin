<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CommonMethods;
use Str;
use File;
use Image;

class Category extends Controller
{
    //

    public function categories(){
             $resto_id = CommonMethods::getRestuarantID();
             $categories = Categories::whereNull('deleted_at')->whereIn('resto_id',[$resto_id])->orderBy('display_order','ASC')->get();

        $data = [
            'categories' => $categories
        ];
        return view('categories.categories',$data);
    }

    public function new_category(){

        return view('categories.category_form');
    }

    public function save(Request $request)
    {
        //  dd($request->all());

        $id = $request->id;
        if(empty($id)){
            $category = new Categories();
            $category->unique_id = \Illuminate\Support\Str::uuid();
        }

        else
            $category = Categories::find($id);

        $category->name = $request->name;
        $category->arabic_name = $request->arabic_name;
        $category->is_active =1;
        $category->resto_id = CommonMethods::getRestuarantID();
        $category->parent_id = $request->parent_id;
        $category->save();

        $id = $category->id;
        if($request->hasFile('main_image')){
            $logo = $request->file('main_image');



            $file_name = "category-".Str::slug($request->name)."-main_image".'-'.time();
            $extension = $logo->getClientOriginalExtension();



            Storage::disk('main_image')->put($file_name.'.'.$extension,  File::get($logo));

            $destinationPath = public_path('/uploads/main_image');
            $img = Image::make($destinationPath . '/' . $file_name.'.'.$extension)->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            });



             $img->save($destinationPath . '/' . $file_name.'.'.$extension);

			$file = public_path('/uploads/main_image/'.$file_name.'.'.$extension);
                    $result = CommonMethods::uploadFileToAWSCDN('meemapp-order','resto-'.CommonMethods::getRestuarantID(), 'category',$file,$file_name);




            $main_image = Photos::where('category_id',$id)->where('photo_type','main_image')->first();
            if(!$main_image)
                $main_image = new Photos();


			 $main_image->file_name = $result['url'];
              $main_image->aws_cdn = $result['url'];
            $main_image->category_id = $id;
            $main_image->photo_type = 'main_image';
            $main_image->resto_id =  CommonMethods::getRestuarantID();;

            $main_image->save();

            //$resto->text =
        }


        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"Category is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Category is not saved successfully."));
    }

    public function delete($id){
        //$id = CommonMethods::decrypt($id);
        $category = Categories::find($id);
        $category->deleted_at = date('Y-m-d H:i:s');
        $category->save();


    }
    public function edit($id){

        $category = Categories::find($id);

        $data = [
            'category' => $category,
        ];


        return view('categories.category_form',$data);
    }

	public function update_display_orders(Request $request){
dd($request->all());
        $ids = $request->ids;
        $u = Auth::user()->role=="restaurant"?Auth::user()->restaurants->id:0;

        foreach($ids as $k=>$id){
            $c = Categories::whereNull('deleted_at')->where('resto_id',$u)->where('id',$id['id'])->update(['display_order'=>$id['position']]);
        }


    }
}
