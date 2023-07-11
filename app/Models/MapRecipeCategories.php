<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapRecipeCategories extends Model
{
    //
    protected $table = "tb_dm_recipe_categories_map";

    public static function getRecipeCategory($recipe_id){
        $mapping  = MapRecipeCategories::where('recipe_id',$recipe_id)
                            ->pluck('category_id')->toArray();

        return $mapping;
    }

    public function mapped_recipes(){
        return $this->belongsTo('App\Models\Recipes','recipe_id','id');
    }
}
