<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiters extends Model
{
    //
    public function users(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function profiles(){
        return $this->hasOne('App\Models\Photos','waiter_id','id')->where('photo_type','profile');
    }

    public function waiter_tables(){
        return $this->hasOne('App\Models\WaiterTables','waiter_id','id');
    }
}
