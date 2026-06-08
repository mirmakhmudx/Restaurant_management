<?php

namespace App\Services;

use App\Models\OrderHistoryLog;


class OrderHistoryService
{
    private static ?self $instance = null;
    private array $sessionEntries = [];

    protected function __construct() {}

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function log(string $event, array $data = [], ?int $orderId = null): void
    {
        // DB ga saqlash (persistent)
        OrderHistoryLog::create([
            'event'    => $event,
            'data'     => $data,
            'user_id'  => auth()->id(),
            'order_id' => $orderId ?? ($data['order_id'] ?? null),
        ]);

        // Session cache (tezlik uchun)
        $this->sessionEntries[] = [
            'event'      => $event,
            'data'       => $data,
            'timestamp'  => now()->toDateTimeString(),
            'user'       => auth()->user()?->name ?? 'System',
        ];
    }
    public function getRecent(int $limit = 50): \Illuminate\Support\Collection
    {
        return $this->getDbHistory($limit);
    }

    public function getHistory(): array
    {
        return $this->sessionEntries;
    }

    public function getDbHistory(int $limit = 50): \Illuminate\Support\Collection
    {
        return OrderHistoryLog::with(['user', 'order'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
