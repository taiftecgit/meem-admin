<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DMCities extends Model
{
    //
    protected $table = "tb_dm_places";

    public function cities(){
        return $this->belongsTo('App\Cities','city_id','id');
    }

    public function categories(){
        return $this->belongsTo('App\PlaceCategories','category_id','id');
    }
}
