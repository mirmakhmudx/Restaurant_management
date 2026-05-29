@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Sales analytics & Singleton audit log')

@section('content')

{{-- Revenue summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach([
        ['Today',     $revenue['today'], 'text-gray-900'],
        ['This Week', $revenue['week'],  'text-gray-900'],
        ['This Month',$revenue['month'], 'text-gray-900'],
    ] as [$label, $val, $cls])
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs font-medium text-gray-500 mb-1">Revenue {{ $label }}</p>
        <p class="text-2xl font-bold {{ $cls }}">£{{ number_format($val, 2) }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Daily revenue bar chart --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Revenue — Last 7 Days</h3>
        <div class="flex items-end gap-2 h-32">
            @foreach($dailyRevenue as $day)
            @php $pct = $maxDaily > 0 ? ($day['revenue'] / $maxDaily) * 100 : 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-500">
                    @if($day['revenue'] > 0) £{{ number_format($day['revenue'], 0) }} @endif
                </span>
                <div class="w-full rounded-t-md bg-gray-900 transition-all"
                     style="height: {{ max(4, $pct) }}%; min-height: 4px;
                            opacity: {{ $day['date'] === today()->toDateString() ? '1' : '0.4' }}">
                </div>
                <span class="text-xs font-medium {{ $day['date'] === today()->toDateString() ? 'text-gray-900' : 'text-gray-400' }}">
                    {{ $day['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Orders by status --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Orders by Status</h3>
        @php $totalOrders = $ordersByStatus->sum() ?: 1; @endphp
        <div class="space-y-2.5">
            @foreach(\App\Enums\OrderStatus::cases() as $st)
            @php $count = $ordersByStatus[$st->value] ?? 0; @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $st->dotColor() }}"></span>
                        <span class="text-gray-600">{{ $st->label() }}</span>
                    </span>
                    <span class="font-medium text-gray-900">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-gray-800 transition-all"
                         style="width: {{ ($count / $totalOrders) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Top menu items --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">Top Items Sold</h3>
        @if($topItems->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No sales data yet</p>
        @else
        <div class="space-y-3">
            @foreach($topItems as $i => $item)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-900">
                        <span class="text-gray-400 mr-1">{{ $i + 1 }}.</span>
                        {{ $item->name }}
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">{{ $item->qty }}x</span>
                        <span class="font-semibold text-gray-900">£{{ number_format($item->revenue, 2) }}</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-gray-900 transition-all"
                         style="width: {{ ($item->qty / $maxQty) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Strategy breakdown --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">
            Pricing Strategies Used
            <span class="text-xs text-gray-400 font-normal ml-1">— Strategy Pattern</span>
        </h3>
        @if($strategies->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No bills processed yet</p>
        @else
        <div class="space-y-3">
            @foreach($strategies as $s)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $s->pricing_strategy }}</p>
                    <p class="text-xs text-gray-500">{{ $s->count }} bill{{ $s->count != 1 ? 's' : '' }}
                        @if($s->saved > 0)
                        · <span class="text-green-600">£{{ number_format($s->saved, 2) }} saved by customers</span>
                        @endif
                    </p>
                </div>
                <span class="font-bold text-gray-900">£{{ number_format($s->revenue, 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Singleton Audit Log --}}
<div class="bg-white border border-gray-200 rounded-xl p-5">
    <h3 class="font-semibold text-gray-900 text-sm mb-1 flex items-center gap-2">
        Activity Log
        <span class="text-xs text-gray-400 font-normal">— OrderHistoryService (Singleton Pattern)</span>
    </h3>
    <p class="text-xs text-gray-400 mb-4">Events captured by the single OrderHistoryService instance this session</p>

    @if(empty($activityLog))
    <p class="text-sm text-gray-400 text-center py-6">
        No activity this session. Place an order to see events logged here.
    </p>
    @else
    <div class="space-y-2">
        @foreach($activityLog as $entry)
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg text-sm">
            <span class="text-gray-400 font-mono text-xs mt-0.5 whitespace-nowrap">
                {{ \Carbon\Carbon::parse($entry['logged_at'])->format('H:i:s') }}
            </span>
            <div>
                <span class="font-medium text-gray-900">{{ $entry['event'] }}</span>
                <span class="text-gray-500 ml-2">
                    @foreach($entry['context'] as $k => $v)
                    <span class="text-gray-400">{{ $k }}:</span>
                    <span class="text-gray-700">{{ is_array($v) ? json_encode($v) : $v }}</span>
                    @if(!$loop->last)<span class="mx-1">·</span>@endif
                    @endforeach
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
