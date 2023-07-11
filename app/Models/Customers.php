<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    protected $table = "tb_dm_customers";

    public function customer_addresses(){
        return $this->hasMany('App\Models\CustomerAddresses','customer_id','id')->where('is_set',1)->whereNull('deleted_at');
    }

    public function users(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
