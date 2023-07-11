<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoMetaDefs extends Model
{
    protected $table = "tb_dm_bussiness_meta_def";
    protected $primaryKey="meta_def_id";

    public function childern(){
        return $this->hasMany(self::class, 'parent_meta_def_id','meta_def_id');
    }

    public function parents(){
        return $this->belongsTo(self::class, 'parent_meta_def_id','meta_def_id');
    }
}
