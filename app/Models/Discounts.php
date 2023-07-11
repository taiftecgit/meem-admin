<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
   protected $table = "tb_dm_discounts";
   protected $guarded = [];



   public function discount_outlets(){
       return $this->hasMany('App\Models\DiscountOutlets','discount_id','id')->orderBy('created_at','DESC');
   }

   public function discount_items(){
       return $this->hasMany('App\Models\DiscountItems','discount_id','id')->orderBy('created_at','DESC');
   }

   public function getDiscountNamesAttribute(){
       return $this->attributes['discount_names'] = array('ar'=>$this->discount_name_arabic,'en'=>$this->discount_name);
   }

}
