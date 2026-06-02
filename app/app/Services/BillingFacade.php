<?php

namespace App\Services;

use App\Contracts\PricingStrategyInterface;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Table;


class BillingFacade
{
    public function __construct(
        private readonly OrderHistoryService $history
    ) {}

    public function processBill(
        Order                   $order,
        PricingStrategyInterface $strategy,
        string                  $paymentMethod
    ): Bill {
        // Step 1 — Apply pricing strategy (Strategy Pattern in action)
        $pricing = $strategy->calculate($order->subtotal);

        // Step 2 — Update order with final totals & mark billed
        $order->update([
            'discount' => $pricing['discount'],
            'total'    => $pricing['total'],
            'status'   => 'billed',
            'served_at'=> $order->served_at ?? now(),
        ]);

        // Step 3 — Persist the bill record
        $bill = Bill::create([
            'order_id'         => $order->id,
            'pricing_strategy' => $strategy->getName(),
            'subtotal'         => $pricing['subtotal'],
            'discount'         => $pricing['discount'],
            'total'            => $pricing['total'],
            'payment_method'   => $paymentMethod,
            'paid_at'          => now(),
        ]);

        // Step 4 — Free the table
        if ($order->table_id) {
            Table::find($order->table_id)?->update(['status' => 'available']);
        }

        // Step 5 — Audit log via Singleton
        $this->history->log('bill.processed', [
            'bill_id'          => $bill->id,
            'order_number'     => $order->order_number,
            'pricing_strategy' => $strategy->getName(),
            'discount_percent' => $strategy->getDiscountPercent(),
            'total'            => $pricing['total'],
            'payment_method'   => $paymentMethod,
        ]);

        return $bill;
    }

    public static function strategies(): array
    {
        return [
            new \App\Strategies\StandardPricing(),
            new \App\Strategies\HappyHourPricing(),
            new \App\Strategies\LoyaltyPricing(),
            new \App\Strategies\StaffPricing(),
        ];
    }

    public static function resolveStrategy(string $name): PricingStrategyInterface
    {
        return collect(self::strategies())
            ->firstOrFail(fn($s) => $s->getName() === $name);
    }
    
}
