<?php

namespace App\Enums;

enum TableLocation: string
{
    case Indoor  = 'indoor';
    case Outdoor = 'outdoor';
    case Bar     = 'bar';
    case Private = 'private';

    public function label(): string
    {
        return match($this) {
            self::Indoor  => 'Indoor',
            self::Outdoor => 'Outdoor',
            self::Bar     => 'Bar',
            self::Private => 'Private',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Indoor  => '🏠',
            self::Outdoor => '🌿',
            self::Bar     => '🍸',
            self::Private => '🔒',
        };
    }
}
