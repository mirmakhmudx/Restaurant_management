<?php

namespace App\States;

use App\Models\Order;

/**
 * State Pattern — Abstract base state.
 * Each concrete state defines allowed transitions and actions.
 */
abstract class OrderState
{
    public function __construct(protected Order $order) {}

    abstract public function getStatus(): string;
    abstract public function getLabel(): string;
    abstract public function canConfirm(): bool;
    abstract public function canStartPreparing(): bool;
    abstract public function canMarkReady(): bool;
    abstract public function canServe(): bool;
    abstract public function canBill(): bool;
    abstract public function canCancel(): bool;

    /** Transition guard — throws if invalid */
    protected function deny(string $action): never
    {
        throw new \LogicException("Cannot {$action} an order in '{$this->getStatus()}' status.");
    }
}
