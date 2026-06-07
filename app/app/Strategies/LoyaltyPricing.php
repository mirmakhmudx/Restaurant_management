<?php

namespace App\Strategies;

use App\Contracts\PricingStrategyInterface;

/**
 * Strategy Pattern — Loyalty card: 10% discount.
 */
class LoyaltyPricing implements PricingStrategyInterface
{
    private const RATE = 0.10;

    public function getName(): string            { return 'Loyalty'; }
    public function getDescription(): string     { return '10% off — loyalty card holders'; }
    public function getDiscountPercent(): float  { return self::RATE * 100; }
    public function getIcon(): string            { return '⭐'; }

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
