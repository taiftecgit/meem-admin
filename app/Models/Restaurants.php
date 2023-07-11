<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Restaurants extends Model
{
    //

    public function photos(){
        return $this->hasOne('App\Models\Photos','resto_id','id')->where('photo_type','logo');
    }

    public function home_images(){
        return $this->hasOne('App\Models\Photos','resto_id','id')->where('photo_type','home_image');
    }

    public function users(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function places(){
        return $this->belongsTo('App\Models\DMCities','place','id');
    }
    public function recipes(){
        return $this->hasMany('App\Models\Recipes','resto_id','id')->whereNull('deleted_at')->orderBy('display_order','DESC');
    }

    public function waiters(){
        return $this->hasMany('App\Models\Waiters','resto_id','id')->whereNull('deleted_at');
    }
     public function countries(){
        return $this->hasOne('App\Models\Countries','id','country_id');
    }

    public function cities(){
        return $this->hasOne('App\Models\Cities','id','city');
    }

    public function orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->orderBy('created_at','DESC')->whereNull('deleted_at');
    }

    public function orders_count(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->orderBy('created_at','DESC')->withCount('orders')->whereNull('deleted_at');
    }

    public function placed_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Placed')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

	public function today_placed_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Placed')->orderBy('created_at','DESC')->whereDate('created_at',date('Y-m-d'))->whereNull('deleted_at');;
    }



    public function send_to_kitchen_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Send_to_Kitchen')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function served_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Served')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function rejected_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Rejected')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function delivered_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Has_Delivered')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function cancelled_by_customer_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->whereIn('status',['Cancelled_by_Customer','Rejected'])->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function preparing_order_orders(){
        return $this->hasMany('App\Models\Orders','resto_id','id')->where('status','Preparing_Order')->orderBy('created_at','DESC')->whereNull('deleted_at');;
    }

    public function resto_metas(){
        return $this->hasMany('App\Models\RestoMetas','bussiness_id','id');
    }

    public function galleries(){
        return $this->hasMany('App\Models\Photos','resto_id','id')->where('photo_type','gallery');
    }

    public function tables(){
        return $this->hasMany('App\Models\RestoTables','resto_id','id')->whereNull('deleted_at');
    }

    public function special_offers(){
        return $this->hasMany('App\Models\SpecialOffers','resto_id','id')->whereNull('deleted_at');
    }

    public function delivery_fee(){
        return $this->hasMany('App\Models\PlaceDeliveryPrices','resto_id','id');
    }


    public function special_offers_active(){
        return $this->hasOne('App\Models\SpecialOffers','resto_id','id')->where('is_active',1)->whereNull('deleted_at');
    }

    public function order_notifications_unread(){
        return $this->hasMany('App\Models\OrderNotifications','resto_id','id')->orderBy('created_at','DESC')->where('status','unread');
    }

    public function order_notifications_all(){
        return $this->hasMany('App\Models\OrderNotifications','resto_id','id')->orderBy('created_at','DESC');
    }

    public static function totalRevenue($resto_id){
        $total_revenue = DB::table('tb_dm_orders')
            ->select(DB::raw('(sum(total_price)) as total_price '))
            ->where('resto_id' , $resto_id)
            ->where('status','Has_Delivered')
			->whereNull('deleted_at')
            ->get()->toArray();

        //return $this->withCount('App\Contacts','contactgrp_def_id','def_id');
        return $total_revenue;
    }

    public static function totalOnlineOrders($resto_id){
        $total_revenue = DB::table('tb_dm_orders')
            ->select(DB::raw('count(*) as total_orders '))
            ->where('resto_id' , $resto_id)
->whereNull('deleted_at')

            ->get()->toArray();

        //return $this->withCount('App\Contacts','contactgrp_def_id','def_id');
        return $total_revenue;
    }

    public static function totalCountRevenue($resto_id){
        $date = \Carbon\Carbon::today()->subDays(7);
        $total_revenue = DB::table('tb_dm_orders')
            ->select(DB::raw('(sum(total_price)+sum(delivery_fee)) as total_price '))
            ->where('resto_id' , $resto_id)
            ->where('created_at','>=',$date)
            ->where('status','Has_Delivered')
			->whereNull('deleted_at')
            ->get()->toArray();

        //return $this->withCount('App\Contacts','contactgrp_def_id','def_id');
        return $total_revenue;
    }

    public static function totalRevenueLast7Days($resto_id){
        $date = \Carbon\Carbon::today()->subDays(7);
        $orders = Orders::select(DB::raw('(sum(total_price)+sum(delivery_fee)) as total_price'),DB::raw('DATE(created_at) date'),DB::raw('DATE_FORMAT(created_at,"%a") day_name'))->where('status','Has_Delivered')->groupby('date')->orderBy('date','ASC')
            ->where('created_at','>=',$date)
->whereNull('deleted_at')
                ->get()->toJson();

        return $orders;
    }
    public static function totalCountOnlineOrdersLast7Days($resto_id){
        $date = \Carbon\Carbon::today()->subDays(7);
        $orders = Orders::select(DB::raw('count(*) as total_orders'))
            ->where('created_at','>=',$date)
->whereNull('deleted_at')
            ->get()->toArray();

        return $orders;
    }

    public static function totalOnlineOrdersLast7Days($resto_id){
        $date = \Carbon\Carbon::today()->subDays(7);
        $orders = Orders::select(DB::raw('count(*) as total_orders'),DB::raw('DATE(created_at) date'),DB::raw('DATE_FORMAT(created_at,"%a") day_name'))->groupby('date')->orderBy('date','ASC')
            ->where('created_at','>=',$date)
->whereNull('deleted_at')
            ->get()->toJson();

        return $orders;
    }

        public static function totalOrdersBySource($resto_id){

        $orders = Orders::select(DB::raw('(count(id)) as order_source'),DB::raw('campaign_type as campaign_type'))
            ->where('status','Has_Delivered')->groupby('campaign_type')
			->where('resto_id',$resto_id)
->whereNull('deleted_at')
            ->get()->toArray();

        return $orders;
    }





}
