<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationTypes extends Model
{
    //
    public function attributes(){
        return $this->hasMany('App\Models\VariationAttributes','variant_id','id')->where('status','active');
    }
}
