<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaiterTables extends Model
{
    //
    public function waiters(){
        return $this->belongsTo('App\Models\Waiters','waiter_id','id');
    }

    public function tables(){
        return $this->belongsTo('App\Models\RestoTables','table_id','id');
    }
}
