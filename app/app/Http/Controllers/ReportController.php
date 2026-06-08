<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderHistoryService;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly OrderHistoryService $history  // Singleton injected
    ) {}

    public function index(): View
    {
        // ── Revenue ───────────────────────────────────────────────
        $revenue = [
            'today' => Bill::whereDate('paid_at', today())->sum('total'),
            'week'  => Bill::whereBetween('paid_at', [now()->startOfWeek(), now()->endOfDay()])->sum('total'),
            'month' => Bill::whereMonth('paid_at', now()->month)
                          ->whereYear('paid_at', now()->year)->sum('total'),
        ];

        // ── Orders by status ──────────────────────────────────────
        $ordersByStatus = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // ── Top 5 menu items by quantity sold ─────────────────────
        $topItems = OrderItem::selectRaw('name, SUM(quantity) as qty, SUM(subtotal) as revenue')
            ->groupBy('name')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        $maxQty = $topItems->max('qty') ?: 1;

        // ── Strategy breakdown ────────────────────────────────────
        $strategies = Bill::selectRaw('pricing_strategy, COUNT(*) as count, SUM(total) as revenue, SUM(discount) as saved')
            ->groupBy('pricing_strategy')
            ->orderByDesc('count')
            ->get();

        // ── Daily revenue last 7 days ─────────────────────────────
        $dailyRevenue = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'label'   => $date->format('D'),
                'date'    => $date->toDateString(),
                'revenue' => Bill::whereDate('paid_at', $date)->sum('total'),
            ];
        });

        $maxDaily = $dailyRevenue->max('revenue') ?: 1;

        // ── Singleton audit log ───────────────────────────────────
        $activityLog = $this->history->getRecent(10);

        return view('reports.index', compact(
            'revenue', 'ordersByStatus', 'topItems', 'maxQty',
            'strategies', 'dailyRevenue', 'maxDaily', 'activityLog'
        ));
    }
}
