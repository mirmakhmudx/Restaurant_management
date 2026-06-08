<?php

namespace App\Contracts;


interface PricingStrategyInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getDiscountPercent(): float;
    public function getIcon(): string;
    public function calculate(float $subtotal): array;
}
