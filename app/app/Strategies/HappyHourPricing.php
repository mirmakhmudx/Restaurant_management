<?php

namespace App\Strategies;

use App\Contracts\PricingStrategyInterface;


class HappyHourPricing implements PricingStrategyInterface
{
    private const RATE = 0.20;

    public function getName(): string            { return 'Happy Hour'; }
    public function getDescription(): string     { return '20% off — weekdays 3 pm – 6 pm'; }
    public function getDiscountPercent(): float  { return self::RATE * 100; }
    public function getIcon(): string            { return '🕐'; }

    public function calculate(float $subtotal): array
    {
        $discount = round($subtotal * self::RATE, 2);
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => round($subtotal - $discount, 2),
        ];
    }
}
