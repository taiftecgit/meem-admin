<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceDeliveryPrices extends Model
{
    //
    protected $table = "tb_dm_place_delivery";

    public function places(){
        return $this->belongsTo('App\Models\DMCities','place_unique_id','place_unique_id')->whereNull('deleted_at');
    }
}
