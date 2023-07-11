<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Orders extends Model
{
    //
    protected $table = "tb_dm_orders";

    public function waiters(){
        return $this->hasOne('App\Models\Waiters','id','waiter_id');
    }

    public function tables(){
        return $this->hasOne('App\Models\RestoTables','id','table_id');
    }

    public function orderItems(){
        return $this->hasMany('App\Models\OrderItems','order_id','id')->where("status",1);
    }

    public function orderCollectivePrice(){
        return $this->hasMany('App\Models\OrderItems','order_id','id')->select(DB::raw('sum(tb_dm_order_items.qty*tb_dm_order_items.price) AS total_price'))->where("status",1);
    }

    public function order_activities(){
        return $this->hasMany('App\Models\OrderActivities','order_id','id')->orderBy('created_at','DESC');
    }

    public function customers(){
        return $this->belongsTo('App\Models\Customers','customer_id','id');
    }
    public function recipients(){
        return $this->belongsTo('App\Models\Recipients','recipient_id','id');
    }

	public function order_with_discounts(){
		return $this->hasOne('App\Models\DiscountWithOrder','order_id','id');
	}


}
