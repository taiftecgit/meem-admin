<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUsers extends Model
{
   protected $table = "tb_dm_admin_users";
   protected $guarded = [];

	public function users(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
