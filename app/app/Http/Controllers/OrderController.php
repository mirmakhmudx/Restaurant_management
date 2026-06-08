<?php

namespace App\Http\Controllers;

use App\Commands\CancelOrderCommand;
use App\Commands\PrepareOrderCommand;
use App\Commands\ReadyOrderCommand;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use App\Services\KitchenQueue;
use App\Services\MenuItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly KitchenQueue   $queue,
        private readonly MenuItemService $menuService
    ) {}

    public function index(Request $request): View
    {
        $status   = $request->query('status', 'active');
        $search   = $request->query('search', '');
        $date     = $request->query('date', '');
        $waiterId = $request->query('waiter', '');
        $tableId  = $request->query('table', '');

        $query = Order::with(['table', 'waiter', 'items']);

        // Status filter
        match($status) {
            'active'    => $query->whereNotIn('status', ['billed','cancelled']),
            'all'       => null,
            default     => $query->where('status', $status),
        };

        // Search — order raqami bo'yicha
        if ($search) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        // Sana filter
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Waiter filter
        if ($waiterId) {
            $query->where('waiter_id', $waiterId);
        }

        // Stol filter
        if ($tableId) {
            $query->where('table_id', $tableId);
        }

        $orders = $query->latest()->get();

        $counts = [
            'active'    => Order::whereNotIn('status', ['billed','cancelled'])->count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'preparing' => Order::whereIn('status', ['confirmed','preparing'])->count(),
            'ready'     => Order::where('status', 'ready')->count(),
            'billed'    => Order::where('status', 'billed')->count(),
        ];

        $waiters = User::where('role', 'waiter')->where('is_active', true)->get();
        $tables  = Table::orderBy('number')->get();
        $menuItems = $this->menuService->getAll()->where('is_available', true)->values();
        $activeFilters = array_filter([
            'search'  => $search,
            'date'    => $date,
            'waiter'  => $waiterId,
            'table'   => $tableId,
        ]);

        return view('orders.index', compact(
            'orders','status','search','date','waiterId','tableId',
            'counts','waiters','tables','menuItems','activeFilters'
        ));
    }

    public function create(): View
    {
        $tables    = Table::orderBy('number')->get();
        $menuItems = $this->menuService->getAll()->where('is_available', true)->values();        return view('orders.create', compact('tables', 'menuItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'items'             => ['required', 'array', 'min:1'],
            'items.*.id'        => ['required', 'exists:menu_items,id'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],
        ]);

        $order = Order::create([
            'table_id'     => $request->table_id ?: null,
            'waiter_id'    => auth()->id(),
            'status'       => 'pending',
            'notes'        => $request->notes,
            'subtotal'     => 0,
            'discount'     => 0,
            'total'        => 0,
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $menuItem = \App\Models\MenuItem::find($item['id']);
            $orderItem = $order->items()->create([
                'menu_item_id' => $menuItem->id,
                'name'         => $menuItem->name,
                'price'        => $menuItem->price,
                'quantity'     => $item['quantity'],
                'notes'        => $item['notes'] ?? null,
                'subtotal'     => $menuItem->price * $item['quantity'],
            ]);
            // Modifierlarni saqlash
            $modifiers = json_decode($item['modifiers'] ?? '[]', true);
            if (!empty($modifiers)) {
                foreach ($modifiers as $mod) {
                    $orderItem->modifiers()->create([
                        'name'  => $mod['name'],
                        'price' => $mod['price'],
                    ]);
                }
                $subtotal += collect($modifiers)->sum('price') * $item['quantity'];
            }
            $subtotal += $menuItem->price * $item['quantity'];
        }

        $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);

        if ($request->table_id) {
            Table::find($request->table_id)?->update(['status' => 'occupied']);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} yaratildi.");
    }

    public function show(Order $order): View
    {
        $order->load(['table', 'waiter', 'items']);
        $state = $order->getState();
        return view('orders.show', compact('order', 'state'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => ['required', 'string']]);
        $newStatus = OrderStatus::from($request->status);
        $user      = auth()->user();

        $allowed = match($user->role->value) {
            'manager' => true,
            'waiter'  => in_array($newStatus->value, ['confirmed','served','cancelled']),
            'chef'    => in_array($newStatus->value, ['preparing','ready']),
            'cashier' => $newStatus->value === 'billed',
            default   => false,
        };

        if (!$allowed) {
            return back()->with('error', "Sizning rolingiz bu amalni bajarishga ruxsatga ega emas.");
        }

        if (!$order->status->canTransitionTo($newStatus)) {
            return back()->with('error', "Bu holatga o'tish mumkin emas.");
        }

        match($newStatus) {
            OrderStatus::Preparing  => $this->queue->dispatch(new PrepareOrderCommand($order)),
            OrderStatus::Ready      => $this->queue->dispatch(new ReadyOrderCommand($order)),
            OrderStatus::Cancelled  => $this->queue->dispatch(new CancelOrderCommand($order)),
            default                 => $order->update(['status' => $newStatus->value]),
        };

        if ($newStatus === OrderStatus::Billed && $order->table_id) {
            Table::find($order->table_id)?->update(['status' => 'available']);
        }

        return back()->with('success', "Order {$order->order_number} → {$newStatus->label()}");
    }
}
