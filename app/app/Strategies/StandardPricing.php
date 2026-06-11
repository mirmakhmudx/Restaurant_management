<?php

namespace App\Strategies;

use App\Contracts\PricingStrategyInterface;

/**
 * Strategy Pattern — Standard pricing: no discount.
 */
class StandardPricing implements PricingStrategyInterface
{
    public function getName(): string            { return 'Standard'; }
    public function getDescription(): string     { return 'Full price — no discount applied'; }
    public function getDiscountPercent(): float  { return 0.0; }
    public function getIcon(): string            { return '💳'; }

    public function calculate(float $subtotal): array
    {
        return [
            'subtotal' => $subtotal,
            'discount' => 0.0,
            'total'    => $subtotal,
        ];
    }
}
