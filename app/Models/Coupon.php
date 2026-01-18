<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
    'code','type','value','active','expires_at','min_order_cents','max_uses','used_count'
];

protected $casts = [
    'active' => 'boolean',
    'expires_at' => 'datetime',
];

}
