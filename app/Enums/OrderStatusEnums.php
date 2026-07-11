<?php

namespace App\Enums;

enum OrderStatusEnums: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::PROCESSING => 'info',
            self::SHIPPED => 'success',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'fa-shopping-basket',
            self::CONFIRMED => 'fa-check-circle',
            self::PROCESSING => 'fa-box',
            self::SHIPPED => 'fa-truck',
            self::DELIVERED => 'fa-home',
            self::CANCELLED => 'fa-times',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Order Placed',
            self::CONFIRMED => 'Confirmed',
            self::PROCESSING => 'Processing & Packing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Canceled',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'We have received your order.',
            self::CONFIRMED => 'Your order has been confirmed.',
            self::PROCESSING => 'Your item is being packed.',
            self::SHIPPED => 'Your order has been shipped.',
            self::DELIVERED => 'Package arrived safely.',
            self::CANCELLED => 'Your order has been canceled.',
        };
    }
}
