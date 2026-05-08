<?php

namespace App\Enums;

enum PaymentStatusEnums: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
}
