<?php

namespace App\Decorators;

/**
 * Applies a percentage discount to a menu item price.
 * Example: SpecialOfferDecorator($item, 20) → 20% off
 */
class SpecialOfferDecorator extends MenuItemDecorator
{
    public function __construct(
        MenuItemDecorator|mixed $item,
        private readonly float $discountPercent
    ) {
        parent::__construct($item);
    }

    public function getPrice(): float
    {
        return round($this->item->getPrice() * (1 - $this->discountPercent / 100), 2);
    }

    public function getName(): string
    {
        return $this->item->getName() . " ({$this->discountPercent}% off)";
    }

    public function getDescription(): string
    {
        return $this->item->getDescription() . " — Special offer applied!";
    }
}
