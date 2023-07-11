<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoTables extends Model
{
    //

    public function waiter_tables(){
        return $this->hasOne('App\Models\WaiterTables','table_id','id');
    }

    public function orders(){
        return $this->hasMany('App\Models\Orders','table_id','id');
    }
}
