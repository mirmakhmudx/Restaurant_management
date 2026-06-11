<?php

namespace App\Strategies;

use App\Contracts\PricingStrategyInterface;

/**
 * Strategy Pattern — Staff meals: 50% discount.
 */
class StaffPricing implements PricingStrategyInterface
{
    private const RATE = 0.50;

    public function getName(): string            { return 'Staff'; }
    public function getDescription(): string     { return '50% off — staff dining benefit'; }
    public function getDiscountPercent(): float  { return self::RATE * 100; }
    public function getIcon(): string            { return '👥'; }

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
