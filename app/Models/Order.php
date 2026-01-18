<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_cents',
        'full_name',
        'phone',
        'address_line',
        'city',
        'postal_code',
        'subtotal_cents',
        'discount_cents',
        'coupon_code',
    ];

    public const STATUSES = ['new', 'in_progress', 'shipped', 'canceled'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(\App\Models\OrderStatusChange::class)
            ->orderBy('created_at');
    }
}

