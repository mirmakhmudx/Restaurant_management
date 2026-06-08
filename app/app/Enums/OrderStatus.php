<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Ready     = 'ready';
    case Served    = 'served';
    case Billed    = 'billed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Preparing => 'Preparing',
            self::Ready     => 'Ready',
            self::Served    => 'Served',
            self::Billed    => 'Billed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending   => '⏳',
            self::Confirmed => '✅',
            self::Preparing => '👨‍🍳',
            self::Ready     => '🔔',
            self::Served    => '🍽️',
            self::Billed    => '💳',
            self::Cancelled => '❌',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Pending   => 'bg-gray-100 text-gray-600 border-gray-200',
            self::Confirmed => 'bg-blue-50 text-blue-700 border-blue-200',
            self::Preparing => 'bg-amber-50 text-amber-700 border-amber-200',
            self::Ready     => 'bg-green-50 text-green-700 border-green-200',
            self::Served    => 'bg-purple-50 text-purple-700 border-purple-200',
            self::Billed    => 'bg-teal-50 text-teal-700 border-teal-200',
            self::Cancelled => 'bg-red-50 text-red-700 border-red-200',
        };
    }

    public function dotColor(): string
    {
        return match($this) {
            self::Pending   => 'bg-gray-400',
            self::Confirmed => 'bg-blue-500',
            self::Preparing => 'bg-amber-500',
            self::Ready     => 'bg-green-500',
            self::Served    => 'bg-purple-500',
            self::Billed    => 'bg-teal-500',
            self::Cancelled => 'bg-red-500',
        };
    }

    /** Valid transitions from this status */
    public function nextStatuses(): array
    {
        return match($this) {
            self::Pending   => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Preparing, self::Cancelled],
            self::Preparing => [self::Ready, self::Cancelled],
            self::Ready     => [self::Served],
            self::Served    => [self::Billed],
            self::Billed,
            self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->nextStatuses(), true);
    }
}
