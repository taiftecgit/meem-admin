<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiTokens extends Model
{
    //
    public function generateToken($user_id)
    {
        $this->user_id = $user_id;
        $this->api_token = Str::random(60);
        $this->save();

        return $this->api_token;
    }

    public function users(){
        return $this->belongsTo('App\User','user_id','id');
    }


}
