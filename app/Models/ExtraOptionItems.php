<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraOptionItems extends Model
{
    //
    protected $table = "tb_dm_extra_option_items";

    public function childern() {
        return $this->hasMany('App\Models\ExtraOptionItems', 'parent_id','id');
    }
}
