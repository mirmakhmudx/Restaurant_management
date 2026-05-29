@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Good ' . (now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening')) . ', ' . auth()->user()->name . '!')

@section('content')

{{-- ── STATS GRID ─────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Orders Today</p>
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['orders_today'] }}</p>
        @if($stats['pending_orders'] > 0)
        <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_orders'] }} pending</p>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Tables</p>
            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_tables'] }}</p>
        <p class="text-xs text-gray-400 mt-1">tables occupied</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Today's Revenue</p>
            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">£{{ number_format($stats['revenue_today'], 2) }}</p>
        @if($stats['pending_bills'] > 0)
        <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_bills'] }} awaiting payment</p>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Staff Active</p>
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['staff_on_duty'] }}</p>
        @if($pendingStaff > 0)
        <a href="{{ route('staff.index') }}" class="text-xs text-orange-600 mt-1 hover:underline">
            {{ $pendingStaff }} pending approval
        </a>
        @endif
    </div>
</div>

{{-- ── LIVE STATUS + RECENT ORDERS ────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Kitchen & Operations status --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Live Operations</h3>
        <div class="space-y-3">
            <a href="{{ route('orders.index', ['status'=>'pending']) }}"
               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gray-400 {{ $stats['pending_orders'] > 0 ? 'animate-pulse' : '' }}"></span>
                    <span class="text-sm text-gray-700">Pending Orders</span>
                </div>
                <span class="font-bold text-gray-900 group-hover:underline">{{ $stats['pending_orders'] }}</span>
            </a>
            <a href="{{ route('kitchen.index') }}"
               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 {{ $stats['kitchen_active'] > 0 ? 'animate-pulse' : '' }}"></span>
                    <span class="text-sm text-gray-700">In Kitchen</span>
                </div>
                <span class="font-bold text-gray-900 group-hover:underline">{{ $stats['kitchen_active'] }}</span>
            </a>
            <a href="{{ route('kitchen.index') }}"
               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 {{ $stats['ready_orders'] > 0 ? 'animate-pulse' : '' }}"></span>
                    <span class="text-sm text-gray-700">Ready to Serve</span>
                </div>
                <span class="font-bold text-gray-900 group-hover:underline">{{ $stats['ready_orders'] }}</span>
            </a>
            <a href="{{ route('billing.index') }}"
               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500 {{ $stats['pending_bills'] > 0 ? 'animate-pulse' : '' }}"></span>
                    <span class="text-sm text-gray-700">Awaiting Payment</span>
                </div>
                <span class="font-bold text-gray-900 group-hover:underline">{{ $stats['pending_bills'] }}</span>
            </a>
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 text-sm">Recent Orders</h3>
            <a href="{{ route('orders.index') }}" class="text-xs text-gray-500 hover:text-gray-900 transition-colors">
                View all →
            </a>
        </div>

        @if($recentOrders->isEmpty())
        <div class="text-center py-8 text-gray-400 text-sm">No orders yet today</div>
        @else
        <div class="space-y-2">
            @foreach($recentOrders as $order)
            <a href="{{ route('orders.show', $order) }}"
               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="text-center w-16">
                        <p class="font-bold text-gray-900 text-sm">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">
                            {{ $order->table ? 'Table '.$order->table->number : 'Takeaway' }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $order->itemCount() }} items</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border {{ $order->status->badgeClasses() }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotColor() }}"></span>
                        {{ $order->status->label() }}
                    </span>
                    <span class="font-semibold text-gray-900 text-sm">{{ $order->getFormattedTotal() }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ── DESIGN PATTERNS + QUICK ACTIONS ────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Quick Actions --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Quick Actions</h3>
        <div class="space-y-2">
            <a href="{{ route('orders.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                New Order
            </a>
            <a href="{{ route('kitchen.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                </svg>
                Kitchen Display
            </a>
            <a href="{{ route('billing.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                </svg>
                Process Payment
            </a>
            @if(auth()->user()->isManager())
            <a href="{{ route('menu.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                Add Menu Item
            </a>
            @endif
        </div>
    </div>

    {{-- Design Patterns Active --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Design Patterns Active</h3>
        <div class="space-y-2">
            @foreach([
                ['Singleton',    'OrderHistoryService',  'bg-violet-50 text-violet-700'],
                ['Command',      'KitchenQueue',          'bg-amber-50  text-amber-700'],
                ['Strategy',     'PricingEngine',         'bg-blue-50   text-blue-700'],
                ['Observer',     'OrderEvents',           'bg-green-50  text-green-700'],
                ['State',        'OrderLifecycle',        'bg-red-50    text-red-700'],
                ['Repository',   'MenuItemRepo',          'bg-indigo-50 text-indigo-700'],
                ['Factory',      'MenuItemFactory',       'bg-orange-50 text-orange-700'],
                ['Facade',       'BillingFacade',         'bg-teal-50   text-teal-700'],
                ['Service Layer','MenuItemService',       'bg-pink-50   text-pink-700'],
                ['Decorator',    'MenuDecorators',        'bg-cyan-50   text-cyan-700'],
            ] as [$pattern, $impl, $color])
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">{{ $pattern }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $color }}">{{ $impl }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Account --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Account</h3>
        <div class="flex flex-col items-center text-center py-3">
            <div class="w-16 h-16 rounded-full bg-gray-900 text-white flex items-center justify-center text-2xl font-bold mb-3">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500 mb-3">{{ auth()->user()->email }}</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-900 text-white text-xs font-medium rounded-full mb-4">
                {{ auth()->user()->getRoleIcon() }} {{ auth()->user()->getRoleLabel() }}
            </span>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-xs font-medium text-gray-400 mb-2">Access</p>
            <div class="flex flex-wrap gap-1">
                @foreach(auth()->user()->role->permissions() as $perm)
                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">{{ $perm }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
