<?php

namespace App\Http\Controllers;

use App\Helpers\CommonMethods;
use App\Jobs\SendOrderNotifications;
use App\Jobs\SendOrderPusherNotification;
use App\Models\OrderActivities;
use App\Models\OrderNotifications;
use App\Models\Orders;
use App\Models\Outlets;
use App\Models\Restaurants;
use App\Models\RestoSMSs;
use Carbon\Carbon;
use App\Models\DiscountWithOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Log;
use Illuminate\Support\Facades\Http;
use DB;

class Order extends Controller
{
    private $resto_id;

    function __construct(){

    }

    public function orders(){

        //$recent_till = Carbon::now()->subDays(2);;

        $this->resto_id = CommonMethods::getRestuarantID();

        $start_page = isset($request->start_page)?$request->start_page-1:0;
        $limit = env('ORDER_NUMBER_ITEMS');

        $offset = $start_page * $limit;

       $orders = Orders::where('resto_id',$this->resto_id)->whereIN('status' ,['Placed','Send_to_Kitchen','On_Road','Accepted'])->orderBy('created_at','DESC')->offset($offset)->limit($limit)->get()->toJson();
        // $orders = Auth::user()->restaurants->orders;
        $data = [
            'orders' => $orders
        ];

        if(isset($_GET['type']) && $_GET['type']=="discount")
            return view('orders.orders-discount',$data);
        else
            return view('orders.orders',$data);
    }

    public function all_status_count(){
        $resto_id = CommonMethods::getRestuarantID();
        $this->resto_id = $resto_id;
        $orders = Orders::select(DB::raw(' count(status) as status_count'),'status')->where('resto_id',$this->resto_id)->whereIN('status' ,['Placed','Send_to_Kitchen','On_Road','Accepted'])->groupBy('status')->get();


        $order_statuses = ['Placed','Send_to_Kitchen','On_Road','Accepted'];
        $a = [];
        foreach($orders as $order){


            $status[$order->status] = array('status_count'=>$order->status_count,'status'=>$order->status);






        }
        // dd($status);
        $aa = [];
        foreach($order_statuses as $st){


            if(isset($status[$st]))
                $aa[] = $status[$st];
            else{

                $aa[] = array('status_count'=>0,'status'=>$st);
            }

        }


        return response()->json($aa);
    }

    public function load_live_order(Request  $request){

        $this->resto_id = CommonMethods::getRestuarantID();
        $status=$request->status;

        $limit = 5;

       // $exitCode = Artisan::call('cache:clear');
       // $exitCode = Artisan::call('route:clear');
       // $exitCode = Artisan::call('view:clear');
        // $exitCode = Artisan::call('config:cache');
        // $exitCode = Artisan::call('config:clear');
        $s = ['Placed','Send_to_Kitchen','On_Road','Accepted'];
        if($status=="new")
        {
           // $limit = 5;
            $s = ['Placed'];
        }
        if($status=="kitchen")
            $s = ['Accepted','Send_to_Kitchen'];

        if($status=="route")
            $s = ['On_Road'];
        //$orders = Orders::where('resto_id',$this->resto_id)->whereIN('status' ,$s)->orderBy('created_at','DESC')->paginate($limit);
        $start_page = isset($request->start_page)?$request->start_page-1:0;

        $order_limit = $request->number_of_orders;//2;//env('ORDER_NUMBER_ITEMS');

        $order_offset = $start_page * $order_limit;

        //dump($order_limit.' '.$order_offset);


        $orders = Orders::where('resto_id',$this->resto_id)->whereIn('status' ,$s)->skip($order_offset)->take($order_limit)->orderBy('created_at','DESC')->get();



        $liveOrders = NULL;
        if(isset($orders) && $orders->count() > 0){

            foreach($orders as $order){
                $status = "";
                $bg="";
                $box_bg="";
                $remaining_min = 0;
                $order_delivery_time = !empty($order->delivery_preparation_time)?$order->delivery_preparation_time:0;
                $created_at = $order->created_at;
                // dump("Delivery Time : ".$order_delivery_time);
                $till_to =  strtotime("+".$order_delivery_time.' minutes',strtotime($order->updated_at));
                //  dump("Till To : ".date('H:i',$till_to));
                $updated_at = Carbon::parse(date('Y-m-d H:i',$till_to));
                $now = Carbon::now();

                //    dump("Update time: ".$updated_at);

                $diff = $updated_at->diffInMinutes($now);
                $order_placed_diff = Carbon::parse($created_at)->diffInMinutes($now);
                //   dump($order_placed_diff);

                //    dump("Difference: ".$diff);

                if($diff <=$order_delivery_time )
                    $remaining_min = $diff;

                // dump("Remain_min ".$remaining_min);
                if($order->status=="Placed"){
                    $status = "New";
                    $bg="blu-bg";
                    $box_bg="bg-danger";
                    $bg_color="#0ED0DF";
                }


                if($order->status=="Accepted" || $order->status=="Send_to_Kitchen"){
                    $status = "In Prep";
                    $bg="org-bg";
                    $bg_color="orange";
                }


                if($order->status=="On_Road"){
                    $status = "In Route";
                    $bg = "blu-bg";
                    $bg_color="#0ED0DF";
                }



                $liveOrders[] = array(
                    'id' => $order->id,
                    'order_ref' => $order->order_ref,
                    'campaign_type' => !empty($order->campaign_type) && $order->order_type!="dining"?ucwords($order->campaign_type):"Direct",
                    'status' => $status,
                    'bg'=>$bg,
                    'box_bg'=>$box_bg,
                    'bg_color'=> $bg_color,
                    'remaining_min'=>$remaining_min,
                    'remaining_min_milliseconds'=>strtotime($order->created_at),
                    'created_at' => Carbon::createFromTimeStamp(strtotime($order->created_at))->diffForHumans(),
                    'current_time'=>Carbon::now()->format('H:i'),
                    'updated_at'=> $updated_at->format('H:i'),
                    'placed_min'=>$order_placed_diff,

                );
            }
        }
        if(isset($liveOrders)){
            $liveOrders['pagination']= array(
                'next_page'=>$start_page==0?2:$request->start_page+1
            );

            return response()->json(array('type'=>'success','orders'=>$liveOrders));
        }

        else
            echo json_encode(array('type'=>'error','message'=>'No order found'));
    }

    public function get_detail_json($id){
        $order = Orders::where('id',$id)->with(['order_with_discounts'])->first();
        $this->resto_id = CommonMethods::getRestuarantID();
        $resto = Restaurants::find($this->resto_id);

        $recipes = NULL;


        if(empty($order->selected_area_formatted) && $order->order_type=="delivery"){
            //  dump($order->selected_area);
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($order->selected_area)."&key=AIzaSyBFh6fzq8G7dgWLfz8kccvTlmPCSI_uWXQ";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            $result = (json_decode($response));
            $formatted_address = isset($result->results[0])?$result->results[0]->formatted_address:"";

            $order->selected_area_formatted = $formatted_address;
            $order->save();

        }





        $country_code = $resto->countries->country_code;




        $new_phone = isset($order->customers->users)?$order->customers->users->email:NULL;
        if(isset($order->customers)){
            $pos = strpos($order->customers->users->email,  (string)$country_code);

            if ($pos !== false) {
                $new_phone = substr_replace($order->customers->users->email, '', $pos, strlen(env('COUNTRY_CODE')));

            }
        }


        if(isset($order->orderItems) && $order->orderItems->count() > 0){
            $extra_price = 0;
            $recipes = [];$ex = [];
            foreach($order->orderItems as $item){
                $ex= NULL;
                $recipe  = $item->recipes;

                $cate_name = "";
                $categories = isset($recipe->categories)?$recipe->categories->pluck('category_id'):NULL;
                //dd($categories);
                if($categories){

                    $categories = \App\Models\Categories::whereIn('id',$categories)->pluck('name')->toArray();

                    $cate_name = isset($categories[0])?$categories[0]:"";


                }
                $extra_options = NULL;

                $opt = [];
                if(!empty($item->extra_options)){
                    $ss_item = [];
                    $extra_options = json_decode($item->extra_options);
                    $extra_options_array = (json_decode($item->extra_options,true));

                    if(isset($extra_options_array['sub_items']) && count($extra_options_array['sub_items']) > 0){
                        $extra_sub_options = $extra_options_array['sub_items'];
                        foreach($extra_sub_options as $k=>$s){
                            $itm = \App\ExtraOptionItems::find($s['id']);
                            $ss_item[$k] = array('id'=>$itm->id,'name'=>$itm->name);
                        }


                    }


                    //$opt = "<ul>";
                    if(isset($extra_options)){
                        foreach($extra_options as $option){

//dump($option);
                            if(isset($option->id)){
                                $itm = \App\ExtraOptionItems::find($option->id);
                                if(isset($itm)){

                                    $ex[] = array('id'=>$option->id,'price'=>$option->price,'name'=>$itm->name,'name_ar'=>$itm->name_arabic ,'sub_items'=>isset($ss_item[$option->id])?$ss_item:null);

                                    // dump($ex);

                                    $opt[] = $itm->name;
                                    //  $opt.="<li>".$itm->name.' <span class="ml-2 badge badge-danger">'.($itm->price).'</span>';
                                    $extra_price = $extra_price+$option->price;

                                    if(isset($option->sub_items)){

                                        foreach($option->sub_items as $sub){

                                            $itm = \App\ExtraOptionItems::find($sub->sub_item_id);


                                            $extra_price = $extra_price+$sub->price;
                                        }

                                    }
                                    //$opt.="</li>";
                                }
                            }
                        }
                    }
                    // $opt.="</ul>";
                }

                if(isset($extra_options->color_size)){
                    $ex = array('color'=>$extra_options->color_size->color,'size'=>$extra_options->color_size->size);
                }
                $recipes[] = array(
                    'recipe_name'=>isset($item->recipes)?$item->recipes->name:"",
                    'recipe_arabic_name'=>isset($item->recipes)?$item->recipes->arabic_name:"",
                    'recipe_image'  => isset($item->recipes->main_images)?$item->recipes->main_images->file_name:"",
                    'quantity'=>$item->qty,
                    'item_price'=>$item->price,
                    'discount_amount'=>$item->discount_amount,
                    'discount_type'=>$item->discount_type,
                    'total_price'=>($item->qty*$item->price),
                    'extra_options'=>$ex
                );
            }
        }
        $status = "";
        $bg="";
        $next_action="";
        $next_status="";
        $bg_color="";
        if($order->status=="Placed"){
            $status = "New";
            $bg="bg-danger";
            $box_bg="bg-danger";
            $next_action='Accepted';
            $next_status="Accepted";
        }


        if($order->status=="Accepted" || $order->status=="Send_to_Kitchen"){
            $status = "In Prep";
            $bg="org-bg";
            $next_action=$order->order_type=="delivery"?'On Road':'Pick Up';
            $next_status="On_Road";
            $bg_color="orange";
        }


        if($order->status=="On_Road"){
            $status = "In Route";
            $bg = "blu-bg";
            $bg_color="#0ED0DF";
            $next_action=$order->order_type=="delivery"?'Delivered':'Picked Up';
            $next_status="Has_Delivered";
        }


        $address = "";
        $delivery_notes = "";
        if(isset($order->customers) && isset($order->customers->customer_addresses)){
            $address .= isset($order->customers->customer_addresses[0])?ucwords($order->customers->customer_addresses[0]->label):NULL;
            $address.= isset($order->customers->customer_addresses[0])?", ".$order->customers->customer_addresses[0]->area:NULL;
            $address.= isset($order->customers->customer_addresses[0])?",".$order->customers->customer_addresses[0]->address:NULL;
        }

        if(isset($order->customers) && isset($order->customers->customer_addresses)){
            $delivery_notes .= isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->instructions:NULL;

        }

        $outlet = Outlets::with('delivery_feature')->find($order->outlet_id);
        $customer_name = $order->customer_name;

        if(empty($customer_name)){
            $customer_name = $order->customers->name;
        }



        $estimated_time = isset($outlet->delivery_feature)?explode(' - ',$outlet->delivery_feature->estimated_time):[];
        $time_from = count($estimated_time) > 0?$estimated_time[0]:0;

        $time_to = 0;
        $r = [];
        if(count($estimated_time) > 1)
            $r = explode(":",$estimated_time[1]);

        $time_to = count($r) > 1 ?$r[0]:0;
        $type = count($r) > 1 ?$r[1]:"DAYS";
        $estimated_time = array(
            'preparation_time'=>$time_from,
            'preparation_delivery_time'=>$time_to,
            'time_type'=>$type
        );
        if(session('app_lang') !==null){
            $lang = session('app_lang');
            app()->setLocale($lang);

        }
        $label_next_status = trans(strtolower('label.'.$next_status));
        $order = array(
            'order_ref' => str_pad($order->order_ref,6,0,STR_PAD_LEFT),
            'estimated_code'=>$estimated_time,
            'brand_name' => $resto->name,
            'outlet_name' => isset($outlet)?$outlet->name:"None",
            'order_type' => $order->order_type=="dining"?"Dine in":ucwords($order->order_type),
            'delivery_at' => !empty($order->order_deliver_time)?Carbon::parse($order->order_deliver_time)->format('d/M/Y h:i a'):date('d/M/Y h:i a',strtotime($order->created_at))." - ASAP",
            'customer'  =>$customer_name ,
            'delivery_fee' => $order->delivery_fee,
            'phone' => $new_phone,
            'channel' => !empty($order->campaign_type) && $order->order_type!="dining"?ucwords($order->campaign_type):"Direct",
            'order_placed' =>\Carbon\Carbon::createFromTimeStamp(strtotime($order->created_at))->diffForHumans(),
            'payment' => $order->payment_mode=="COD"?"Cash":"Card",
            'status'=>$status,
            'bg'=>$bg,
            'total_price' => $order->total_price,
            'recipes'=>$recipes,
            'next_action'=>$next_action,
            'next_status' =>$next_status,
            'next_status_label'=> ($label_next_status),
            'address'=>$address,
            'geo_location' => isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->selected_lat_lng:$order->selected_area_formatted,
            'for_table'=>$order->for_table,
            'formatted_address'=>$order->selected_area_formatted,
            'google_formatted_address'=> $order->selected_area,
            'order_instructions'=>$order->order_instructions,
            'delivery_notes' => $order->order_type=="delivery"?$delivery_notes:$order->order_instructions,
            'bg_color'=> $bg_color,
            'recipient_name' => isset($order->recipients)?$order->recipients->recipient_name:null,
            'recipient_phone' => isset($order->recipients)?$order->recipients->recipient_phone:null,
            'greeting_message' => isset($order->recipients)?$order->recipients->greeting_message:null,
            'discount_with_order'=>isset($order->order_with_discounts)?array(
                'discount_type'=>$order->order_with_discounts->discount_type,
                'discount_code'=>$order->order_with_discounts->discount_code,//TODO to display discount code
                'discount_value'=>$order->order_with_discounts->discount_value,
                'discounted_amount'=>$order->order_with_discounts->discounted_amount,
                'total_order_value'=>$order->order_with_discounts->total_order_value,
                'is_for_whole_order'=>$order->order_with_discounts->is_for_whole_order,
                'is_delivery_discount'=>$order->order_with_discounts->is_delivery_discount,
                'delivery_discount_value'=>$order->order_with_discounts->delivery_discount_value,
                'delivery_discount_type'=>$order->order_with_discounts->delivery_discount_type,
            ):null


        );

        echo json_encode($order);
    }

    public function show($id){
        $order = Orders::find($id);
        $activities = OrderActivities::where('order_id',$id)->pluck('status')->toArray();
        // dd($activities);
        $data = [
            'order' => $order,
            'activities' =>$activities
        ];
        return view('orders.show',$data);
    }

    public function update_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        $order = Orders::find($id);
        if($status=="Accepted"){

            //If credit card payment success then update order count
            //$disCountCounter = DiscountWithOrder::where('order_id',$order->id);
            //$disCountCounter->is_parked=0;
            //$disCountCounter->save();// Giving error fix later
            DB::statement("UPDATE tb_dm_order_with_discount SET  is_parked=0 where order_id = ".$id);
            //Logic to check if the order is availed or not

            $order->preparation_time = !empty($request->preparation_time)?$request->preparation_time:"";
            $order->delivery_preparation_time = !empty($request->preperation_delivery)?$request->preperation_delivery:"";
            $order->preparation_type = !empty($request->preparation_type)?$request->preparation_type:"";
        }

        $order->status = $status;

        $order->save();
        $restuarant = Restaurants::find($order->resto_id);

        if(strtolower($status)=="accepted" || strtolower($status)=="rejected" || strtolower($status)=="rejected_by_user"){
            if(!empty($restuarant->domain_name))
                $url = $restuarant->domain_name.$restuarant->resto_unique_name."/track/order?order=".$id."&ref=".$order->order_ref;
            else
                $url = env('QRCODE_HOST_ORDER').$restuarant->resto_unique_name."/track/order?order=".$id."&ref=".$order->order_ref;
            //dump($url);
            if(strtolower($status)=="accepted"){
                // $this->sendAcceptOrderNotification($order);
                $message =env('ORDER_ACCEPTED_MESSAGE').' '.$url;
                $message = "Dear Customer, Your Order #".$order->order_ref." is accepted , kindly track your order at ".$url." ";


                if($restuarant->default_lang=="ar"){
                    $message = "عزيزي العميل طلبك {{1}} تم {{2}} ، لتتبع طلبك افتح الرابط {{3}}";
                    $message = str_replace(["{{1}}",'{{2}}','{{3}}'],["#".$order->order_ref,'قبوله',$url],$message);
                    // echo $message;
                }
            }
            else{
                $message = env('ORDER_CANCELLED_MESSAGE');
                $message = "Dear Customer, Your Order #".$order->order_ref." is rejected , kindly track your order at ".$url." ";
                $message = "Dear Customer, Your Order #".$order->order_ref." is cancelled.";

                $restuarant = Restaurants::find($order->resto_id);
                if($restuarant->default_lang=="ar"){
                    //$message = "عزيزي العميل طلبك {{1}} تم {{2}} ، لتتبع طلبك افتح الرابط {{3}}";
                    //$url = "";
                    // $message = str_replace(["{{1}}",'{{2}}','{{3}}'],["#".$order->order_ref,'رفضه',$url],$message);

                    $message = "عزيزي العميل، طلبك {{1}} تم {{2}}";
                    $url = "";
                    $message = str_replace(["{{1}}",'{{2}}'],["#".$order->order_ref,'الغاؤه ، نعتذر عن عدم القدرة على تلبية طلبك'],$message);

                    // echo $message;
                }
            }

            $mobile_number = isset($order->customers)?$order->customers->users->email:NULL;
            //$mobile_number = "923459635387";
            if($restuarant->allow_whatsapp_notifications_to_customera=="Yes") {
                if (isset($mobile_number)) {
                    $sms = new RestoSMSs();
                    $sms->resto_id = $order->resto_id;
                    $sms->msg = $message;
                    $sms->msg_purpose = "TRACK_ORDER_SMS";
                    $sms->msisdn = $mobile_number;
                    $sms->status = 1;

                    $sms->save();

                    $sms_id = $sms->id;
                    $usrname = 'meem_food_order';

                    if (env('OTP_TEST_IN_DEV') == "1") {//ALWAYS ZERO IN PROD
                        $usrname = 'meem_food_order1';
                    }

                    $data = [
                        'usrname' => $usrname,
                        'pwd' => 'meem@kkew#9',
                        'msisdn' => $mobile_number,
                        'smstxt' => $message,
                        'pricepoint' => 1,
                        'jsonstr' => 'Future'
                    ];
                    Log::info('TRACK_ORDER_SMS Data: ' . json_encode($data));


                    if (env('OTP_DONT_SEND_REQ') == "0") {//IN PROD THIS SHOULD BE 0 IN PROD
                        $wsdlurl = env('WHATSAPP_OPT_API');

                        // $this->sendWhatsappQueueMessage($wsdlurl,$data,$sms_id);
                        $params = array(
                            'message_data' => $data,
                            'sms_id' => $sms_id,
                            'url' => $wsdlurl
                        );
                        dispatch(new SendOrderNotifications($params));


                    }


                }
            }else{
                $data = [
                    'msisdn' => $mobile_number,
                    'smstxt' => $message,
                    'pricepoint'=>1,
                    'jsonstr'=>'Future'
                ];
                Log::info('Without Whatsapp TRACK_ORDER_SMS Data: '.json_encode($data));
            }


        }

        OrderActivities::add_order_activity($id,$status);

        $data['order_id'] = $id;
        $data['order_ref'] = $order->order_ref;
        $data['customer_id'] = $order->customer_id;
        $data['status'] = $status;
        $data['order_resto_id'] = $order->resto_id;
        $data['notification_for'] ="update-order-status";

        dispatch(new SendOrderPusherNotification($data));




    }


    public function update_instruction(Request $request){

        $id = $request->id;
        $txt = $request->text;

        $order = Orders::find($id);

        $order->order_instructions = $txt;

        $order->save();

    }

    public function ajax_order(){
        $offset = request()->get('start');
        $limit = request()->get('length');
        $draw = request()->get('draw');
        //  $offset = ($offset-1) * $limit;
        $this->resto_id = CommonMethods::getRestuarantID();
        // $resto = Restaurants::find($this->resto_id);
        $orders = Orders::where('resto_id',$this->resto_id )->orderBy('created_at','DESC')->offset($offset)->limit($limit)->get();
        $custom_status['Placed'] = ['Accepted'=>'Accepted','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
        $custom_status['Send_to_Kitchen'] = ['On_Road'=>'On the Way','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
        $custom_status['On_Road'] = ['Has_Delivered'=>'Delivered','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];
        $custom_statuses = ['Rejected_by_User'=>'Rejected by User','Accepted'=>'Accepted','Rejected'=>'Rejected','Placed'=>'Placed','Send_to_Kitchen'=>'Send to Kitchen','On_Road'=>'On the Way', 'Has_Delivered'=>'Delivered','Served'=>"Served","Cancelled_by_Customer"=>"Cancelled"];

        $custom_status['Accepted'] = ['On_Road'=>'On the Way','Rejected'=>'Rejected', 'Rejected_by_User'=>'Rejected by User'];

        $data = NULL;

        foreach($orders as $order){
            $data[] = [
                $order->order_ref,
                CommonMethods::formatDateTime($order->created_at),
                $order->customer_name,
                isset($order->customers)?(str_replace(env('COUNTRY_CODE'),'',$order->customers->users->email)):"",
                isset($order->customers) && isset($order->customers->customer_addresses[0])?$order->customers->customer_addresses[0]->address:"",
                number_format($order->total_price+$order->delivery_fee),
                isset($custom_statuses[$order->status])?$custom_statuses[$order->status]:"",
                NULL
            ];
        }

        return response()->json(array('data'=>$data));
    }

    public function print_order($id){
        $order = Orders::find($id);
        $data = [
            'order' => $order
        ];
        return view('orders.print-order',$data);

    }

    public function order_history(){

        //$recent_till = Carbon::now()->subDays(2);;


        $this->resto_id = CommonMethods::getRestuarantID();
        $orders = Orders::where('resto_id',$this->resto_id)->whereIN('status' ,['Rejected_by_User','Rejected','Has_Delivered','Close'])->orderBy('created_at','DESC')->whereNull('deleted_at')->paginate(50);
        // $orders = Auth::user()->restaurants->orders;
        $data = [
            'orders' => $orders
        ];
        return view('orders.orders-history',$data);
    }


    function send_message($mobile_number,$message,$sms_id){
        dd('ETST');

        // $mobile_number = '9647834000012';
        $usrname = 'meem_food_order';
        //OTP_TEST_IN_DEV0,DISABLE_OTP=0,CONSIDER_OTP_ALWAYS_TRUE=0 in prod to make it live
        if(env('OTP_TEST_IN_DEV')=="1"){//ALWAYS ZERO IN PROD
            $usrname = 'meem_food_order1';
        }

        $data = [
            'usrname'=>$usrname,
            'pwd'=>'meem@kkew#9',
            'msisdn' => $mobile_number,
            'smstxt' => $message,
            'pricepoint'=>1,
            'jsonstr'=>'Future'
        ];
        dd($data);
        // $wsdlurl = 'https://taiftec.com/blkdlr/bulk/sendmt';
        $wsdlurl="";//Uncomment in prod

        Log::info('SMS Data: '.json_encode($data));

        if(env('OTP_DONT_SEND_REQ')=="0"){//IN PROD THIS SHOULD BE 0 IN PROD
            $wsdlurl = env('SMS_OPT_API');

            $response = Http::acceptJson()->post($wsdlurl,$data);


            $response = $response->json();
            $response = json_encode($response);
            Log::info('SMS Response: '.$response);

            $sms = RestoSMSs::find($sms_id);
            $sms->otp_req = json_encode($data);
            $sms->otp_res = $response;

            $sms->save();

        }


        if(env('OTP_CONSIDER_ALWAYS_TRUE')=="1"){//IN PROD THIS SHOULD BE 0 IN PROD
            $a = [
                "reqid" => "20210611025322000469",
                "mtid" => "18",
                "errcode" => 1,
                "status" => "ACCEPTED"];
            $response = json_encode($a);
            $access_token = Session::get('access_token');
            $sms_url = env('RESTO_API_URL').'update/sms';
            Http::post($sms_url,['sms_id'=>$sms_id,'req'=>json_encode($data),'resp'=>$response]);
            return $response;
        }

        // dd($response->json());


    }

    public function sendWhatsappQueueMessage($url, $data,$sms_id){
        $response = Http::acceptJson()->post($url,$data);



        $response = $response->json();
        $response = json_encode($response);
        Log::info('TRACK_ORDER_SMS Response: '.$response);

        $sms = RestoSMSs::find($sms_id);
        $sms->otp_req = json_encode($data);

        $resp = json_decode($response);
        $sms->otp_req_status = strtoupper($resp->status)=="ACCEPTED"?"SUCCESS":"FAIL";



        $sms->otp_res = $response;


        $sms->save();
    }


}
