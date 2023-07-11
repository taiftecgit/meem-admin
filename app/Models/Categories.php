<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Categories extends Model
{
    //
    protected $table = "tb_dm_recipe_categories";

    public function recipes(){
        return $this->hasMany('App\Models\MapRecipeCategories','category_id','id');
    }



    public function categories_has_recipes(){
        $a= $this->belongsToMany('App\Models\Recipes','tb_dm_recipe_categories_map','category_id',
        'recipe_id')->whereNull('deleted_at');

        return $a;
    }

    public function main_images(){
        return $this->hasOne('App\Models\Photos','category_id','id')->where('photo_type','main_image');
    }

    public static function getCategoriesHasRecipes($resto_id){
        $query = "    SELECT category.* FROM `tb_dm_recipe_categories_map` as category_map
                      INNER JOIN tb_dm_recipe recipe on recipe.id = category_map.recipe_id
                      INNER JOIN tb_dm_recipe_categories category on category.id = category_map.category_id
                      where category_map.resto_id=".$resto_id." and recipe.deleted_at IS NULL group by category_map.category_id";

        $query = "    SELECT category.*,p.file_name as main_images FROM `tb_dm_recipe_categories_map` as category_map
                      INNER JOIN tb_dm_recipe recipe on recipe.id = category_map.recipe_id
                      INNER JOIN tb_dm_recipe_categories category on category.id = category_map.category_id
					            INNER JOIN photos p on category.id = p.category_id
                      where  category_map.resto_id=".$resto_id." and recipe.deleted_at IS NULL  and p.photo_type='main_image' group by category_map.category_id,p.file_name";


        $categories = DB::select($query);
        return $categories;
    }


    public static function getRecipeByCategoriesResto($resto_id,$category_id){
        $query = "    SELECT recipe.*,p.file_name as main_images FROM `tb_dm_recipe_categories_map` as category_map
                      INNER JOIN tb_dm_recipe recipe on recipe.id = category_map.recipe_id
                      INNER JOIN tb_dm_recipe_categories category on category.id = category_map.category_id
					  INNER JOIN photos p on p.recipe_id = recipe.id
                      where recipe.deleted_at is NULL and category_map.resto_id=".$resto_id." AND p.photo_type='main_image' and category_map.category_id=".$category_id." AND recipe.status = 1 group by category_map.recipe_id,p.file_name";

        $recipes = DB::select($query);
        return $recipes;
    }

    public function parent_category(){
        return $this->hasOne('App\Models\Categories','id','parent_id');
    }

    public function childern(){
        return $this->hasMany('App\Models\Categories','parent_id','id')->whereNull('deleted_at');
    }
}
