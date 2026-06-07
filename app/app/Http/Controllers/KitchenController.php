<?php

namespace App\Http\Controllers;

use App\Commands\CancelOrderCommand;
use App\Commands\PrepareOrderCommand;
use App\Commands\ReadyOrderCommand;
use App\Models\Order;
use App\Services\KitchenQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function __construct(
        private readonly KitchenQueue $queue
    ) {}

    public function index(): View
    {
        $confirmed = Order::with(['table', 'items'])
            ->where('status', 'confirmed')
            ->oldest('confirmed_at')
            ->get();

        $preparing = Order::with(['table', 'items'])
            ->where('status', 'preparing')
            ->oldest('updated_at')
            ->get();

        $ready = Order::with(['table', 'items'])
            ->where('status', 'ready')
            ->oldest('prepared_at')
            ->get();

        return view('kitchen.index', compact('confirmed', 'preparing', 'ready'));
    }

    /**
     * Uses Command Pattern — dispatches the appropriate kitchen command.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:preparing,ready,cancelled']]);

        $command = match($request->status) {
            'preparing' => new PrepareOrderCommand($order),
            'ready'     => new ReadyOrderCommand($order),
            'cancelled' => new CancelOrderCommand($order),
        };

        $this->queue->dispatch($command);

        return back()->with('success', "Order {$order->order_number} → {$request->status}");
    }
}
