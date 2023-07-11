<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoCategoriesMapped extends Model
{
    //
    protected $table = "tb_dm_resto_place_category_map";

    public function place_categories(){
        return $this->belongsTo('App\Models\PlaceCategories','category_id','id');
    }


}
