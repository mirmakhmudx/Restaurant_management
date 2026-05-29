@extends('layouts.app')
@section('title', 'Billing')
@section('page-title', 'Billing')
@section('page-subtitle', 'Process payments — Strategy + Facade patterns')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs text-gray-500 mb-1">Today's Revenue</p>
        <p class="text-2xl font-bold text-gray-900">£{{ number_format($todayTotal, 2) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs text-gray-500 mb-1">Awaiting Payment</p>
        <p class="text-2xl font-bold text-gray-900">{{ $pending->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs text-gray-500 mb-1">Bills Today</p>
        <p class="text-2xl font-bold text-gray-900">{{ $todayCount }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Pending --}}
    <div>
        <h3 class="font-semibold text-gray-900 text-sm mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            Awaiting Payment ({{ $pending->count() }})
        </h3>

        @forelse($pending as $order)
        <div class="bg-white border border-amber-200 rounded-xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $order->table ? 'Table '.$order->table->number : 'Takeaway' }}
                        @if($order->waiter) · {{ $order->waiter->name }} @endif
                        · {{ $order->itemCount() }} items
                        · {{ $order->updated_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-gray-900 text-lg">{{ $order->getFormattedTotal() }}</span>
                    <a href="{{ route('billing.create', $order) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-900 hover:bg-gray-800
                              text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        Bill
                    </a>
                </div>
            </div>

            {{-- Item preview --}}
            <div class="mt-2 flex flex-wrap gap-1">
                @foreach($order->items->take(3) as $item)
                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                    {{ $item->quantity }}× {{ $item->name }}
                </span>
                @endforeach
                @if($order->items->count() > 3)
                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-400 rounded-full">
                    +{{ $order->items->count() - 3 }} more
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm bg-white border border-dashed border-gray-200 rounded-xl">
            <p class="text-2xl mb-2">✅</p>
            No orders awaiting payment
        </div>
        @endforelse
    </div>

    {{-- Recent bills --}}
    <div>
        <h3 class="font-semibold text-gray-900 text-sm mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Recent Bills
        </h3>

        @forelse($recent as $bill)
        <a href="{{ route('billing.show', $bill) }}"
           class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 mb-2 hover:shadow-sm hover:border-gray-300 transition-all">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-base">
                    {{ $bill->paymentIcon() }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $bill->order->order_number }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-xs text-gray-500">{{ $bill->pricing_strategy }}</span>
                        @if($bill->hasDiscount())
                        <span class="text-xs text-green-600 font-medium">−{{ $bill->getFormattedDiscount() }}</span>
                        @endif
                        <span class="text-xs text-gray-400">· {{ $bill->paid_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
            <span class="font-bold text-gray-900">{{ $bill->getFormattedTotal() }}</span>
        </a>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm bg-white border border-dashed border-gray-200 rounded-xl">
            No bills yet
        </div>
        @endforelse
    </div>
</div>
@endsection
