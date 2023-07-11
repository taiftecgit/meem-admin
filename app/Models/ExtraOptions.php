<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraOptions extends Model
{
    //
    protected $table = "tb_dm_extra_options";

    public function extra_option_items(){
        return $this->hasMany('App\Models\ExtraOptionItems','extra_option_id','id')->whereNull('deleted_at')->where('parent_id',0);
    }
}
