<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return match($user->role->value) {
            'chef'    => $this->chefDashboard(),
            'waiter'  => $this->waiterDashboard(),
            'cashier' => $this->cashierDashboard(),
            default   => $this->managerDashboard(),
        };
    }

    private function managerDashboard(): View
    {
        // Top dish
        $topDish = \DB::table('order_items')
            ->select('name', \DB::raw('SUM(quantity) as qty'))
            ->groupBy('name')
            ->orderByDesc('qty')
            ->whereDate('created_at', today())
            ->first();

        // Peak hour (eng ko'p order bo'lgan soat)
        $peakHour = Order::whereDate('created_at', today())
            ->selectRaw('EXTRACT(HOUR FROM created_at) as hour, COUNT(*) as cnt')
            ->groupBy('hour')
            ->orderByDesc('cnt')
            ->first();

        // Yesterday revenue (comparison uchun)
        $revenueYesterday = Bill::whereDate('paid_at', now()->subDay())->sum('grand_total') ?:
                            Bill::whereDate('paid_at', now()->subDay())->sum('total');

        $stats = [
            'orders_today'      => Order::whereDate('created_at', today())->count(),
            'active_tables'     => Table::where('status', 'occupied')->count(),
            'total_tables'      => Table::count(),
            'revenue_today'     => Bill::whereDate('paid_at', today())->sum('grand_total') ?:
                                   Bill::whereDate('paid_at', today())->sum('total'),
            'revenue_yesterday' => $revenueYesterday,
            'staff_on_duty'     => User::where('is_active', true)->count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'kitchen_active'    => Order::whereIn('status', ['confirmed','preparing'])->count(),
            'ready_orders'      => Order::where('status', 'ready')->count(),
            'pending_bills'     => Order::where('status', 'served')->count(),
            'reservations_today'=> class_exists(\App\Models\Reservation::class)
                                    ? \App\Models\Reservation::whereDate('reserved_at', today())->count()
                                    : 0,
            'top_dish'          => $topDish?->name ?? 'Ma\'lumot yo\'q',
            'top_dish_qty'      => $topDish?->qty ?? 0,
            'peak_hour'         => $peakHour ? (int)$peakHour->hour . ':00' : '--',
            'cancelled_today'   => Order::whereDate('created_at', today())->where('status','cancelled')->count(),
        ];

        $recentOrders = Order::with(['table','waiter'])->latest()->take(6)->get();
        $pendingStaff = User::where('is_active', false)->count();

        return view('dashboard', compact('stats', 'recentOrders', 'pendingStaff'));
    }

    private function chefDashboard(): View
    {
        $confirmed = Order::with(['table','items'])->where('status','confirmed')->oldest()->get();
        $preparing = Order::with(['table','items'])->where('status','preparing')->oldest()->get();
        $ready     = Order::with(['table','items'])->where('status','ready')->oldest()->get();
        $todayDone = Order::whereIn('status',['served','billed'])->whereDate('updated_at', today())->count();
        return view('dashboard.chef', compact('confirmed','preparing','ready','todayDone'));
    }

    private function waiterDashboard(): View
    {
        $myOrders   = Order::with(['table','items'])
            ->where('waiter_id', auth()->id())
            ->whereNotIn('status', ['billed','cancelled'])
            ->latest()->get();
        $tables     = Table::orderBy('number')->get();
        $readyCount = Order::where('status','ready')
            ->where('waiter_id', auth()->id())->count();
        return view('dashboard.waiter', compact('myOrders','tables','readyCount'));
    }

    private function cashierDashboard(): View
    {
        $pending    = Order::with(['table','items','waiter'])
            ->where('status','served')->oldest()->get();
        $todayBills = Bill::with(['order'])->whereDate('paid_at', today())->latest()->get();
        $todayTotal = $todayBills->sum('grand_total') ?: $todayBills->sum('total');
        return view('dashboard.cashier', compact('pending','todayBills','todayTotal'));
    }
}
