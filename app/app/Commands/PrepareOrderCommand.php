<?php

namespace App\Commands;

use App\Contracts\KitchenCommandInterface;
use App\Models\Order;

class PrepareOrderCommand implements KitchenCommandInterface
{
    private string $previousStatus;

    public function __construct(private Order $order) {}

    public function execute(): void
    {
        $this->previousStatus = $this->order->status->value;
        $this->order->update([
            'status'      => 'preparing',
            'prepared_at' => null,
        ]);
    }

    public function undo(): void
    {
        $this->order->update(['status' => $this->previousStatus]);
    }

    public function getDescription(): string
    {
        return "Start preparing Order #{$this->order->order_number}";
    }
}
