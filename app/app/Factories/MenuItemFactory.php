<?php

namespace App\Factories;

use App\Enums\MenuItemType;
use App\Models\MenuItem;

/**
 * Factory Pattern — creates MenuItem instances based on type.
 * Each type gets sensible defaults appropriate for its category.
 *
 * Design Pattern: Factory Method
 */
class MenuItemFactory
{
    /**
     * Create a MenuItem with type-appropriate defaults.
     */
    public function create(array $data): MenuItem
    {
        $type = MenuItemType::from($data['type']);

        $defaults = match($type) {
            MenuItemType::Starter    => $this->starterDefaults(),
            MenuItemType::MainCourse => $this->mainCourseDefaults(),
            MenuItemType::Dessert    => $this->dessertDefaults(),
            MenuItemType::Beverage   => $this->beverageDefaults(),
        };

        // Merge: provided data overrides defaults
        $merged = array_merge($defaults, array_filter($data, fn($v) => $v !== null && $v !== ''));

        return MenuItem::create($merged);
    }

    // ── Type-specific defaults ─────────────────────────

    private function starterDefaults(): array
    {
        return [
            'prep_time_minutes' => 10,
            'is_available'      => true,
            'is_vegetarian'     => false,
            'is_vegan'          => false,
            'is_gluten_free'    => false,
            'allergens'         => [],
        ];
    }

    private function mainCourseDefaults(): array
    {
        return [
            'prep_time_minutes' => 20,
            'is_available'      => true,
            'is_vegetarian'     => false,
            'is_vegan'          => false,
            'is_gluten_free'    => false,
            'allergens'         => [],
        ];
    }

    private function dessertDefaults(): array
    {
        return [
            'prep_time_minutes' => 8,
            'is_available'      => true,
            'is_vegetarian'     => true,
            'is_vegan'          => false,
            'is_gluten_free'    => false,
            'allergens'         => ['gluten', 'dairy', 'eggs'],
        ];
    }

    private function beverageDefaults(): array
    {
        return [
            'prep_time_minutes' => 3,
            'is_available'      => true,
            'is_vegetarian'     => true,
            'is_vegan'          => true,
            'is_gluten_free'    => true,
            'allergens'         => [],
        ];
    }
}
