<?php

namespace App\Observers;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Services\OrderHistoryService;

/**
 * Observer Pattern — Order model eventlarini kuzatadi.
 * Har qanday status o'zgarishida avtomatik ishga tushadi.
 */
class OrderObserver
{
    public function __construct(
        private readonly OrderHistoryService $history
    ) {}

    public function created(Order $order): void
    {
        $this->history->log('order.created', [
            'order_number' => $order->order_number,
            'table'        => $order->table_id,
            'total'        => $order->total,
        ]);
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            // getRawOriginal() — enum cast qo'llamaydi, har doim string qaytaradi
            $from = $order->getRawOriginal('status');
            $to   = $order->status->value;

            $this->history->log('order.status_changed', [
                'order_number' => $order->order_number,
                'from'         => $from,
                'to'           => $to,
            ]);

            OrderStatusChanged::dispatch($order, $from, $to);

            // Vaqt belgilash
            if ($to === 'confirmed') {
                $order->updateQuietly(['confirmed_at' => now()]);
            }
            if ($to === 'served') {
                $order->updateQuietly(['served_at' => now()]);
            }

            if ($order->wasChanged('status')) {
                $from = $order->getRawOriginal('status');
                $to   = $order->status->value;

                $this->history->log('order.status_changed', [
                    'order_number' => $order->order_number,
                    'from'         => $from,
                    'to'           => $to,
                ]);

                OrderStatusChanged::dispatch($order, $from, $to);

                // YANGI: Real-time broadcast
                \App\Events\OrderStatusUpdated::dispatch($order, $from, $to);

                // ... qolgan kod
            }
        }
    }

    public function deleted(Order $order): void
    {
        $this->history->log('order.deleted', [
            'order_number' => $order->order_number,
        ]);
    }
}
