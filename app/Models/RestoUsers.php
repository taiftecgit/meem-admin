<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoUsers extends Model
{
    protected $table = "tb_dm_resto_users";

    public function users(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
