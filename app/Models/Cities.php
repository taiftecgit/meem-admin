<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    //
    protected $table = "tb_dm_cities";

    public function countries(){
        return $this->belongsTo('App\Models\Countries','country_id','id');
    }
}
