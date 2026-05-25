<?php

namespace App\Decorators;

/**
 * Marks a menu item as today's Chef's Special.
 * Adds label to name and note to description.
 */
class ChefSpecialDecorator extends MenuItemDecorator
{
    public function getName(): string
    {
        return '⭐ ' . $this->item->getName() . " — Chef's Special";
    }

    public function getDescription(): string
    {
        return $this->item->getDescription()
            . ' Personally recommended by our head chef today.';
    }
}
