<?php

namespace App\Commands;

use App\Contracts\KitchenCommandInterface;
use App\Models\Order;

/**
 * Command Pattern — Cancels an order.
 */
class CancelOrderCommand implements KitchenCommandInterface
{
    private string $previousStatus;

    public function __construct(private Order $order) {}

    public function execute(): void
    {
        $this->previousStatus = $this->order->status->value;
        $this->order->update(['status' => 'cancelled']);
    }

    public function undo(): void
    {
        $this->order->update(['status' => $this->previousStatus]);
    }

    public function getDescription(): string
    {
        return "Cancel Order #{$this->order->order_number}";
    }
}
