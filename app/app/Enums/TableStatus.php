<?php

namespace App\Enums;

enum TableStatus: string
{
    case Available = 'available';
    case Occupied  = 'occupied';
    case Reserved  = 'reserved';
    case Cleaning  = 'cleaning';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::Occupied  => 'Occupied',
            self::Reserved  => 'Reserved',
            self::Cleaning  => 'Cleaning',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Available => '✅',
            self::Occupied  => '🔴',
            self::Reserved  => '🟡',
            self::Cleaning  => '🔵',
        };
    }

    public function dotColor(): string
    {
        return match($this) {
            self::Available => 'bg-green-500',
            self::Occupied  => 'bg-red-500',
            self::Reserved  => 'bg-amber-500',
            self::Cleaning  => 'bg-blue-500',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Available => 'bg-green-50 text-green-700 border-green-200',
            self::Occupied  => 'bg-red-50 text-red-700 border-red-200',
            self::Reserved  => 'bg-amber-50 text-amber-700 border-amber-200',
            self::Cleaning  => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    public function cardBg(): string
    {
        return match($this) {
            self::Available => 'border-green-200 bg-white',
            self::Occupied  => 'border-red-200 bg-red-50/30',
            self::Reserved  => 'border-amber-200 bg-amber-50/30',
            self::Cleaning  => 'border-blue-200 bg-blue-50/30',
        };
    }
}
