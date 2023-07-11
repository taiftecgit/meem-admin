<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Blogs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use File;
use App\Helpers\CommonMethods;
use Image;
class Blog extends Controller
{
    //
	public function blogs(){
		$blogs = Blogs::whereNull('deleted_at')->get();
		return view('blogs.blogs',['blogs'=>$blogs]);
	}
	public function new_blog(){
		return view("blogs.blog_form");
	}

	public function edit_blog($id){
		$blog= Blogs::find($id);
		return view("blogs.blog_form",['blog'=>$blog]);
	}

	public function save_blog(Request $request){

		$id = $request->id;

		if($id=="")
			$blog = new Blogs();
		else
			$blog = Blogs::find($id);

		$blog->title = $request->title;
		$blog->slug = Str::slug($request->title);

		$blog->content = $request->content;
		$blog->short_description = $request->short_description;
		$blog->is_published = isset($request->is_published)?"Yes":"No";

		$url = "";


			if($request->hasFile('media')){
                    $media = $request->file('media');
				//dd($media);

				 $file_name = Str::slug($request->name)."-blog".'-'.time();
                    $extension = $media->getClientOriginalExtension();




                    Storage::disk('logo')->put($file_name.'.'.$extension,  File::get($media));

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
                    $result = CommonMethods::uploadFileToAWSCDN('meemapp-order',time(), 'blog',$file,$file_name);
				$url = isset($result) && isset($result['url'])?$result['url']:"";
				$blog->media = $url;
			}


				$blog->save();

				$id = $blog->id;
		if($id > 0){
			 echo json_encode(array('type' => 'success', 'message'=>"Blog's data is saved successfully."));
		}else{
			echo json_encode(array('type' => 'error', 'message'=>"Blog's data is not saved, check info again."));
		}

	}

	public function delete_blog($id){
		$blog = Blogs::find($id);
		$blog->deleted_at = date('Y-m-d H:i:s');
		$blog->save();
	}
}
