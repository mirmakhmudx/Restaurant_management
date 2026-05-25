<?php

namespace App\Decorators;

use App\Contracts\MenuItemInterface;

/**
 * Abstract base Decorator for MenuItem.
 * All concrete decorators extend this class.
 *
 * Design Pattern: Decorator
 * Wraps a MenuItemInterface and delegates all calls
 * to the wrapped component, allowing subclasses to
 * override specific methods to add behaviour.
 */
abstract class MenuItemDecorator implements MenuItemInterface
{
    public function __construct(
        protected readonly MenuItemInterface $item
    ) {}

    public function getName(): string        { return $this->item->getName(); }
    public function getPrice(): float        { return $this->item->getPrice(); }
    public function getDescription(): string { return $this->item->getDescription(); }
    public function getType(): string        { return $this->item->getType(); }
    public function getPrepTime(): int       { return $this->item->getPrepTime(); }
    public function isAvailable(): bool      { return $this->item->isAvailable(); }
}
