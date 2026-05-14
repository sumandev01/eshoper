<?php

namespace App\Models;

use App\Enums\OrderStatusEnums;
use App\Enums\PaymentStatusEnums;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'payment_status' => PaymentStatusEnums::class,
        'order_status' => OrderStatusEnums::class,
    ];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
