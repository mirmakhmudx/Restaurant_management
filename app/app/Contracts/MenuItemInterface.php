<?php

namespace App\Contracts;

/**
 * Contract for MenuItem and its Decorators.
 * Enables the Decorator Pattern to wrap menu items
 * with additional behaviour (special offers, chef specials, etc.)
 */
interface MenuItemInterface
{
    public function getName(): string;
    public function getPrice(): float;
    public function getDescription(): string;
    public function getType(): string;
    public function getPrepTime(): int;
    public function isAvailable(): bool;
}
