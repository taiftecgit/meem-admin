<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceCategories extends Model
{
    //
    protected $table = "tb_dm_place_categories";

    public function places(){
        return $this->hasMany('App\Models\DMCities','category_id','id')->whereNull('deleted_at');
    }

    public function category_mapped(){
        return $this->hasMany('App\Models\PlaceCategoriesMapped','category_id','id');
    }
}
