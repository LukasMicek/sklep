<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusChange extends Model
{
    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by_user_id',
    ];
}
