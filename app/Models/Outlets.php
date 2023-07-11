<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlets extends Model
{
    //
    protected $table = "tb_resto_branches";

    public function main_images(){
        return $this->hasOne('App\Models\Photos','branch_id','id')->where('photo_type','branch');
    }

    public function delivery_feature(){
        return $this->hasOne('App\Models\BranchFeatures','branch_id','id')->where('feature_type','delivery');
    }

    public function delivery_hours_feature(){
        return $this->hasMany('App\Models\BranchHours','branch_id','id')->where('hours_for','delivery');
    }


    public function pickup_feature(){
        return $this->hasOne('App\Models\BranchFeatures','branch_id','id')->where('feature_type','pickup');
    }

    public function contactless_dining_feature(){
        return $this->hasOne('App\Models\BranchFeatures','branch_id','id')->where('feature_type','contactless_dining');
    }

    public function pickup_hours_feature(){

        return $this->hasMany('App\Models\BranchHours','branch_id','id')->where('hours_for','pickup');
    }

    public function countries(){

        return $this->hasOne('App\Models\Countries','id','country_id');
    }

    public function resto_metas(){
        return $this->hasMany('App\Models\RestoMetas','outlet_id','id');
    }

	public function outlet_orders(){
        return $this->hasMany('App\Models\Orders','outlet_id','id')->where('status','Has_Delivered')->whereNull('deleted_at');
    }
}
