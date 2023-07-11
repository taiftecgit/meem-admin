<?php

namespace App\Http\Controllers;

use App\DMCities;
use App\Restaurants;
use App\User;
use App\RestoUsers;
use App\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session;
use Auth;
use File;
use Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redis;
use App\Helpers\CommonMethods;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    public function marketings(){

        return view('marketing.marketings');
    }
	
	public function change_lang($lang){
		session(['app_lang' => $lang]);
	}
	
	public function reset_customer(){
		return view('customers.customers');
	}
	
	public function reset_phone_customer(Request $request){
		$user = User::where('email',$request->mobile_number)->first();
		if(isset($user)){
			$existing_email = $user->email;
			$existing_email = $existing_email.'-reset-at-'.date('y-m-d H:i:s');
			$user->email = $existing_email;
			$user->save();
			echo json_encode(array('type'=>'success','message'=>$request->mobile_number. ' is reset.'));
		}else{
			echo json_encode(array('type'=>'error','message'=>$request->mobile_number. ' not found in db, use correct number with country code'));
		}
	}
	
	public function reset_update_password(Request $request){
		$user_id = $request->user_id;
		
		$user = User::find($user_id);
		
		$username = $user->username;
		
		$password = $request->password;
		
		$user->password = Hash::make($password);
		$user->is_reset_password_sent = "Changed";
        $result = $user->save();
		
		if($result){
			Auth::loginUsingId($user_id);
			return response()->json(array('type'=>'success','message'=>'Password reset successfully.'));
		}
	}
	
	public function reset_password(Request $request){
		$user_id = $request->get('u');
		$user = User::where('id',$user_id)->where('is_reset_password_sent','Yes')->first();;
		
		return view('reset-password',['user'=>$user]);
	}
	
	public function send_reset_link(Request $request){
		$email = $request->email;
		
		$user_id = "";
		
		$user = User::where('username',$email)->first();
		
		if(isset($user))
			$user_id = $user->id;
		
		if(!isset($user)){
			$user = RestoUsers::whereNull('deleted_at')->where('email',$email)->first();
			
			$user_id = isset($user->user_id)?$user->user_id:"";
		}
		
		
	
		
		
		if(!isset($user) && empty($user_id))
			return response()->json(['type'=>'error','message'=>'No user found against this <strong>'.$request->email.'</strong>']);
		
		$u = User::where('id',$user_id)->update(['is_reset_password_sent'=>'Yes']);
		
		 $param = array(
                            'email'=>$request->email,
                            'name' => $user->first_name,
                           	'link'=> env('APP_URL').'reset/my/password?q='.md5(time()).'&u='.$user->user_id.'&action=reset'
                      );

        SendEmail::SendRestPasswordLink($param); 
		
		return response()->json(['type'=>'success','message'=>'Reset link sent to <strong>'.$request->email.'</strong>']);
	}

    public function send_mail(){
        $logo = 'https://meemappaws.imgix.net/meemcdn/31-titanium-store/titanium-store-logo-1669151471?fm=webp&h=500&w=500&trim=color&q=100&fit=center&crop=center';

        $imageData = base64_encode(file_get_contents($logo));
        $logo = 'data:image/png;base64,'.$imageData;
         $param = array(
                            'email'=>$_GET['email'],

                            'shop_name' => 'Titanium Store',
                            'order_message' => 'You have New order placed OrderID: 217 ,  kindly login to  https://admin.meemorder.io to serve customer.',
                            'logo' => $logo
                            
                      );


        SendEmail::sendOrderNotification($param);

        exit;
        $param = array(
                            'email'=>$_GET['email'],
                            'name' => 'Mujtaba Ahmad',
                            'shop_name' => 'NB Flowers',
                            'role' => 'Manager',
                            'access_level' => 'selected-outlets',
                            'selected_outlets' => 'Outlet1, Outlet2',
                            //'link' => 'https://dashboard.chatfood.io/invite/36eaa3c8403efe29d775db4423a62bb0?utm_medium=email&utm_source=mailgun&utm_campaign=user_invitation',
                            'link' => '#'
                      );

        SendEmail::SendInvitationLink($param);
    }

    public function test_redis(){
        Redis::set("user:mujtaba","It is testing code");

        dd(Redis::get('user:mujtaba'));
    }

    public function create_link(Request $request){
        $campaign_name = $request->campaign_name;
        $campaign_date = $request->campaign_date;
        $campaign_type = $request->campaign_type;
        $site_url = $request->site_url;

        $link = '?a='.$campaign_type.'&c='.Str::slug($campaign_name).'&cd='.$campaign_date;

        return $site_url.($link);

    }

    public function make_slug(){
        $resto = Restaurants::all();

        foreach($resto as $r){
            $rr = Restaurants::find($r->id);

            $rr->resto_unique_name = Str::slug($r->name);
            $rr->save();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard(){

      


        if(Auth::user()->role=="administrator")
        return view('dashboards.admin_dashboard');
        return view('dashboards.resto_dashboard');
    }

    public function getLogout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/');
    }

    public function change_password(){
        return view('password');
    }

    public function update_password(Request $request){
        $old_password = $request->old_password;

        $new_password = $request->password;
        $confirm_password = $request->confirm_password;
        $user = Auth::user();
        if ($user && Hash::check($old_password, $user->password)) {

            if($new_password==$confirm_password){

                $u = User::find($user->id);
                $u->password = Hash::make($new_password);
                $u->save();

                echo json_encode(array('type'=>'success','message'=>'Password changed successfully.'));


            }else{
                echo json_encode(array('type'=>'error','message'=>'new password and confirm password are not matched.'));
                exit;
            }

        }else{
            echo json_encode(array('type'=>'error','message'=>'Old password is incorrect, enter correct password.'));
            exit;
        }
    }

    public function download_image(Request $request){
        $data = $request->data;
        $resto = $request->resto;

        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        file_put_contents(public_path('/uploads/qrcode/qrcode.png'), $data);

        $this->image_recreate(public_path('/uploads/qrcode/qrcode.png'),public_path('/uploads/qrcode/'.$resto.'-qrcode.png'));

        echo env('APP_PUBLIC_URL').'uploads/qrcode/'.$resto.'-qrcode.png';
    }


    public function image_recreate($sourceFile,$destinationFile){
        $orig_filename = $sourceFile;
        $new_filename = $orig_filename;

        list($orig_w, $orig_h) = getimagesize($orig_filename);

        $orig_img = imagecreatefromstring(file_get_contents($orig_filename));

        $output_w = 2200;
        $output_h = 2200;

// determine scale based on the longest edge
        if ($orig_h > $orig_w) {
            $scale = $output_h/$orig_h;
        } else {
            $scale = $output_w/$orig_w;
        }

        $scale = $scale-0.1;

        // calc new image dimensions
        $new_w =  ($orig_w * $scale);
        $new_h =  ($orig_h * $scale);



// determine offset coords so that new image is centered
        $offest_x = (($output_w - $new_w) / 2);
        $offest_y = (($output_h - $new_h) / 2);

        // create new image and fill with background colour
        $new_img = imagecreatetruecolor($output_w, $output_h);
        $bgcolor = imagecolorallocate($new_img, 255, 255, 255); // red
        imagefill($new_img, 0, 0, $bgcolor); // fill background colour

        // copy and resize original image into center of new image
        imagecopyresampled($new_img, $orig_img, $offest_x, $offest_y, 0, 0, $new_w, $new_h, $orig_w, $orig_h);

        //save it
        imagejpeg($new_img, $destinationFile, 80);

    }

    public function resizeMainRecipeImages(){
        $path = public_path('uploads/main_image');

        ini_set('max_execution_time', '300');

        $files = File::allfiles($path);



        foreach($files as $file){

           $pth = ($file->getRealPath());
            $file_name = $file->getFileName();
            echo "Main Image: ".$file_name."<br />";
            $destinationPath = public_path('/uploads/main_image/');
            if($file->getExtension()!="jfif") {
                $img = Image::make($destinationPath . '/' . $file_name)->resize(85, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/thumbnails/' . $file_name);
            }
        }
    }



    public function resizeLogo(){
        $path = public_path('uploads/logo');

        ini_set('max_execution_time', '300');

        $files = File::allfiles($path);



        foreach($files as $file){

            $pth = ($file->getRealPath());
            $file_name = $file->getFileName();
            echo "Main Image: ".$file_name."<br />";
            $destinationPath = public_path('/uploads/logo/');
            if($file->getExtension()!="jfif") {
                $img = Image::make($destinationPath . '/' . $file_name)->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/thumbnails/' . $file_name);
            }
        }
    }


    public function resizeGalleryRecipeImages(){
        $path = public_path('uploads/resto-gallery');



        $files = File::allfiles($path);



        foreach($files as $file){

            $pth = ($file->getRealPath());
            $file_name = $file->getFileName();
            echo "Gallery: ".$file_name."<br />";
            $destinationPath = public_path('/uploads/resto-gallery/');
            if($file->getExtension()!="jfif"){
                $img = Image::make($destinationPath . '/' . $file_name)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/thumbnails/' . $file_name);
            }

        }
    }


    public function resizeGalleryRecipeImagesToGallery(){
        $path = public_path('uploads/resto-gallery');



        $files = File::allfiles($path);



        foreach($files as $file){

            $pth = ($file->getRealPath());
            $file_name = $file->getFileName();
            echo "Gallery: ".$file_name."<br />";
            $destinationPath = public_path('/uploads/resto-gallery/');
            if($file->getExtension()!="jfif"){
                $img = Image::make($destinationPath . '/' . $file_name)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save($destinationPath . '/gallery-resized/' . $file_name);
            }

        }
    }

    public function load_json(){
        $jsonString = file_get_contents("https://api.chatfood.io/api/v1/businesses/ceeba7a3-5dd4-48a7-9a07-96111efab2e4/areas");

        $data = json_decode($jsonString, true);
      //  dd($data);

        foreach($data as $city){
            foreach($city as $cc){
               // dump($cc);
                $c = new DMCities();

                $c->city_name = $cc['name'];
                $c->city_unique_id = $cc['id'];

                $c->save();
            }
           /* */
        }
    }
}
