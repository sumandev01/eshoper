<?php

namespace App\Enums;

enum PaymentStatusEnums: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'success',
            self::UNPAID => 'danger',
            self::FAILED => 'danger',
            self::REFUNDED => 'danger',
        };
    }
}
