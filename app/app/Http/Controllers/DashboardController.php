<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Live stats ────────────────────────────────────────────
        $stats = [
            'orders_today'   => Order::whereDate('created_at', today())->count(),
            'active_tables'  => Table::where('status', 'occupied')->count(),
            'revenue_today'  => Bill::whereDate('paid_at', today())->sum('total'),
            'staff_on_duty'  => User::where('is_active', true)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'kitchen_active' => Order::whereIn('status', ['confirmed','preparing'])->count(),
            'ready_orders'   => Order::where('status', 'ready')->count(),
            'pending_bills'  => Order::where('status', 'served')->count(),
        ];

        // ── Recent orders (last 6) ────────────────────────────────
        $recentOrders = Order::with(['table', 'waiter'])
            ->latest()
            ->take(6)
            ->get();

        // ── Pending approvals ─────────────────────────────────────
        $pendingStaff = User::where('is_active', false)->count();

        return view('dashboard', compact('stats', 'recentOrders', 'pendingStaff'));
    }
}
