<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    //
    protected $table = "tb_dm_recipe";

    public function categories(){
        return $this->hasMany('App\Models\MapRecipeCategories','recipe_id','id');
    }

	public function product_faqs(){
        return $this->hasMany('App\Models\ProductFAQs','product_id','id')->whereNull('deleted_at');
    }

    public function variations(){
        return $this->hasMany('App\Models\VariationData','product_id','id')->whereNull('deleted_at');
    }
    public function categories_name(){
        return $this->hasOne('App\Models\MapRecipeCategories','recipe_id','id');
    }
    public function main_images(){
        return $this->hasOne('App\Models\Photos','recipe_id','id')->where('photo_type','main_image');
    }

    public function restuarants(){
        return $this->belongsTo('App\Models\Restaurants','resto_id','id');
    }

    public function galleries(){
        return $this->hasMany('App\Models\Photos','recipe_id','id')->where('photo_type','gallery');
    }

    public function extra_options(){
        return $this->hasMany('App\Models\ExtraOptions','recipe_id','id')->whereNull('deleted_at');
    }

    public function extra_options_with_items(){
        return $this->hasMany('App\Models\ExtraOptions','recipe_id','id')->whereNull('deleted_at');
    }

    /*public function recipe_with_category(){
        return $this->belongsTo('App\MapRecipeCategories','recu','category')->select(DB::raw('sum(tb_dm_order_items.qty*tb_dm_order_items.price) AS total_price'))->where("status",1);
    }*/
}
