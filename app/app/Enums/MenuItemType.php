<?php

namespace App\Enums;

/**
 * Represents the category/type of a menu item.
 * Used by MenuItemFactory to create the correct item variant.
 */
enum MenuItemType: string
{
    case Starter    = 'starter';
    case MainCourse = 'main_course';
    case Dessert    = 'dessert';
    case Beverage   = 'beverage';

    public function label(): string
    {
        return match($this) {
            self::Starter    => 'Starter',
            self::MainCourse => 'Main Course',
            self::Dessert    => 'Dessert',
            self::Beverage   => 'Beverage',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Starter    => '🥗',
            self::MainCourse => '🍽️',
            self::Dessert    => '🍰',
            self::Beverage   => '🥤',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Starter    => 'emerald',
            self::MainCourse => 'amber',
            self::Dessert    => 'pink',
            self::Beverage   => 'blue',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Starter    => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::MainCourse => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::Dessert    => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
            self::Beverage   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        };
    }

    public function defaultPrepTime(): int
    {
        return match($this) {
            self::Starter    => 10,
            self::MainCourse => 20,
            self::Dessert    => 8,
            self::Beverage   => 3,
        };
    }
}
