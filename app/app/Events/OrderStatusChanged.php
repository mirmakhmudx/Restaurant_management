<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

class OrderStatusChanged
{
    use Dispatchable;

    public function __construct(
        public readonly Order  $order,
        public readonly string $from,
        public readonly string $to,
    ) {}
}
