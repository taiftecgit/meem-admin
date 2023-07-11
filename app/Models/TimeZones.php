<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeZones extends Model
{
    //
    protected $tableName = "timezone";
    protected $primaryKey = "country_code";
}
