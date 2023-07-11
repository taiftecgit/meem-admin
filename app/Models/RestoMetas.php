<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoMetas extends Model
{
    protected $table = "tb_dm_bussiness_meta";
    protected $primaryKey="meta_id";


    public function resto_meta_defs(){
       return $this->belongsTo('App\Models\RestoMetaDefs','meta_def_id','meta_def_id');
    }
}
