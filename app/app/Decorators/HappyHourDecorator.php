<?php

namespace App\Decorators;

/**
 * Applies Happy Hour pricing (fixed 20% discount on beverages).
 * Used by PricingStrategy when happy hour is active.
 */
class HappyHourDecorator extends MenuItemDecorator
{
    private const DISCOUNT = 0.20;

    public function getPrice(): float
    {
        return round($this->item->getPrice() * (1 - self::DISCOUNT), 2);
    }

    public function getName(): string
    {
        return $this->item->getName() . ' 🍹 Happy Hour';
    }
}
