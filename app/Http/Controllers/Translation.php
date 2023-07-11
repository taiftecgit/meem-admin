<?php

namespace App\Http\Controllers;

use App\Models\Translations;
use Illuminate\Http\Request;
use Str;
use File;
use Storage;
use Carbon\Carbon;
use Response;
class Translation extends Controller
{
   public function translations(){
	   $translations = Translations::where('status',1)->where('context','admin')->get();
	   $t = [];
	   if(isset($translations)){
		   foreach($translations as $translation){
			   $t[$translation->item_key][$translation->lang_id] = $translation->item_val;
		   }
	   }
	   $translations = $t;
	  // dd($translations);
	   return view("translations.translations",['translations'=>$translations,'translation_for'=>'admin']);
   }

	 public function translations_frontend(){
	   $translations = Translations::where('status',1)->where('context','meem_fe_customer')->get();
	   $t = [];
	   if(isset($translations)){
		   foreach($translations as $translation){
			   $t[$translation->item_key][$translation->lang_id] = $translation->item_val;
		   }
	   }
	   $translations = $t;
	  // dd($translations);
	   return view("translations.translations",['translations'=>$translations,'translation_for'=>'frontend']);
   }

	public function save_translation(Request $request){


		$id = $request->id;

		if(empty($id)){
			$translation = new Translations();
			$translation->item_key = str_replace('-','_',Str::slug($request->item_key));

			$translation->item_val = $request->item_en;
			$translation->lang_id = 'en';

			if($request->type=="frontend"){

				$translation->context = 'meem_fe_customer';
				$translation->context_value = 'meem_react';
			}
			if($request->type=="admin"){
				$translation->context = 'admin';
				$translation->context_value = 'admin-label';
			}

			$translation->save();


			$translation = new Translations();
			$translation->item_key = str_replace('-','_',Str::slug($request->item_key));

			$translation->item_val = $request->item_ar;
			$translation->lang_id = 'ar';

			if($request->type=="frontend"){

				$translation->context = 'meem_fe_customer';
				$translation->context_value = 'meem_react';
			}
			if($request->type=="admin"){
				$translation->context = 'admin';
				$translation->context_value = 'admin-label';
			}

			$translation->save();

			return response()->json(array('type' => 'success', 'message'=>"Data is saved successfully."),200);


		}else{
			//dd($request->all());
			$translation = Translations::where('item_key',$id)->where('lang_id','en')->first();

			$translation->item_val = $request->item_en;;
			$translation->save();

			$translation = Translations::where('item_key',$id)->where('lang_id','ar')->first();

			$translation->item_val = $request->item_ar;;
			$translation->save();

			return response()->json(array('type' => 'success', 'message'=>"Data is saved successfully."),200);
		}


	}

	public function download_translation_file($type,$for){
		//echo $for;
		if($for=="frontend")
		$translations = Translations::where('status',1)->where('lang_id',$type)->where('context','meem_fe_customer')->get();
		if($for=="admin")
		$translations = Translations::where('status',1)->where('lang_id',$type)->where('context','admin')->get();

		$t = [];
	   if(isset($translations)){
		   foreach($translations as $translation){
			   $t[$translation->item_key] = $translation->item_val;
		   }
	   }

		if($for=="admin"){
			rename('/var/www/admin.meemorder.io/resources/lang/'.$type.'/label.php', '/var/www/admin.meemorder.io/resources/lang/'.$type.'/label-'.Carbon::now()->format('Ymd').'.php');

		File::put('resources/lang/'.$type.'/label.php',"<?php return ".var_export($t,true)."; ?>");
		}



		if($for=="frontend"){
			$time  = time();
			 File::put('resources/lang/'.$type.'/label.json',json_encode($t,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			 rename('/var/www/admin.meemorder.io/resources/lang/'.$type.'/label.json', '/var/www/admin.meemorder.io/public/uploads/label-'.$type.'-'.$time.'.json');
			//echo env('APP_URL').'uploads/label-'.$type;
			 $headers = array(
              'Content-Type: application/json',
            );
//return download(public_path('uploads/label-'.$type.'.json'));
         return response()->download(public_path('uploads/label-'.$type.'-'.$time.'.json'), 'label-'.$type.'-'.$time.'.json', $headers);
		}


	}
}
