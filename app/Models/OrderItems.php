<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    //
    protected $table = "tb_dm_order_items";
    protected $primaryKey = "order_item_id";

    public function recipes(){
        return $this->belongsTo('App\Models\Recipes','recipe_id','id');
    }
}
