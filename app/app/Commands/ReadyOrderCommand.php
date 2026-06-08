<?php

namespace App\Commands;

use App\Contracts\KitchenCommandInterface;
use App\Models\Order;

/**
 * Command Pattern — Marks an order as ready for service.
 */
class ReadyOrderCommand implements KitchenCommandInterface
{
    private string $previousStatus;

    public function __construct(private Order $order) {}

    public function execute(): void
    {
        $this->previousStatus = $this->order->status->value;
        $this->order->update([
            'status'      => 'ready',
            'prepared_at' => now(),
        ]);
    }

    public function undo(): void
    {
        $this->order->update([
            'status'      => $this->previousStatus,
            'prepared_at' => null,
        ]);
    }

    public function getDescription(): string
    {
        return "Mark Order #{$this->order->order_number} as ready";
    }
}
