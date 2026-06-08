<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly string $oldStatus,
        public readonly string $newStatus,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
            new Channel("table.{$this->order->table_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'table'        => $this->order->table ? 'Stol '.$this->order->table->number : 'Takeaway',
            'old_status'   => $this->oldStatus,
            'new_status'   => $this->newStatus,
            'status_label' => $this->order->status->label(),
        ];
    }
}
