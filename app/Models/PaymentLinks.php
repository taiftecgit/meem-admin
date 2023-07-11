<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLinks extends Model
{
    //
    public function outlets(){
        return $this->belongsTo('\App\Models\Outlets','outlet_id','id');
    }
    public function restuarents(){
        return $this->belongsTo('\App\Models\Restaurants','resto_id','id');
    }

    public function payments(){
        return $this->hasMany('\App\Models\PaymentLinkPayments','payment_link_code','unique_id');
    }
}
