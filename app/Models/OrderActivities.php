<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderActivities extends Model
{
    //
    protected $table = "tb_dm_order_activities";

    public static function add_order_activity($order_id,$status){
        $order = new OrderActivities();

        $order->order_id = $order_id;
        $order->status = $status;

        $order->save();
    }


}
