<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPlanList extends Model
{
    protected $fillable = [
        'network','type','plan','product_code','vendor_amount','amount'
    ];
}
