<?php

namespace App\Enums;

enum StaffRole: string
{
    case Manager = 'manager';
    case Waiter  = 'waiter';
    case Chef    = 'chef';
    case Cashier = 'cashier';

    public function label(): string
    {
        return match($this) {
            self::Manager => 'Manager',
            self::Waiter  => 'Waiter',
            self::Chef    => 'Chef',
            self::Cashier => 'Cashier',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Manager => '👔',
            self::Waiter  => '🍽️',
            self::Chef    => '👨‍🍳',
            self::Cashier => '💳',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Manager => 'violet',
            self::Waiter  => 'blue',
            self::Chef    => 'amber',
            self::Cashier => 'emerald',
        };
    }

    public function dashboardRoute(): string
    {
        return match($this) {
            self::Manager => 'dashboard',
            self::Waiter  => 'orders.index',
            self::Chef    => 'kitchen.index',
            self::Cashier => 'billing.index',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::Manager => ['dashboard','orders','kitchen','menu','tables','billing','reports','staff'],
            self::Waiter  => ['dashboard','orders','tables'],
            self::Chef    => ['dashboard','kitchen','orders'],
            self::Cashier => ['dashboard','billing','orders'],
        };
    }
}
