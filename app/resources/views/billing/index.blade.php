@extends('layouts.app')
@section('title', 'Billing')
@section('page-title', 'Billing')
@section('page-subtitle', "To'lovlarni qabul qiling — Strategy · Facade Pattern")

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs font-medium text-gray-500 mb-1">Bugungi daromad</p>
        <p class="text-3xl font-bold text-gray-900">£{{ number_format($todayTotal, 2) }}</p>
    </div>
    <div class="bg-white border border-amber-200 rounded-xl p-5 {{ $pending->count() > 0 ? 'bg-amber-50' : '' }}">
        <p class="text-xs font-medium text-gray-500 mb-1">To'lov kutmoqda</p>
        <p class="text-3xl font-bold {{ $pending->count() > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $pending->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <p class="text-xs font-medium text-gray-500 mb-1">Bugun to'langan</p>
        <p class="text-3xl font-bold text-gray-900">{{ $todayCount }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- TO'LOV KUTMOQDA --}}
    <div>
        <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-500 {{ $pending->count() > 0 ? 'animate-pulse' : '' }}"></span>
            To'lov kutmoqda ({{ $pending->count() }})
        </h3>

        @forelse($pending as $order)
        <div class="bg-white border border-amber-200 rounded-2xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                        @if($order->waiter) · {{ $order->waiter->name }} @endif
                        · {{ $order->itemCount() }} ta taom
                        · {{ $order->updated_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-xl text-gray-900">{{ $order->getFormattedTotal() }}</span>
                    <a href="{{ route('billing.create', $order) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        To'lov
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach($order->items->take(4) as $item)
                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                    {{ $item->quantity }}× {{ $item->name }}
                </span>
                @endforeach
                @if($order->items->count() > 4)
                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-400 rounded-full">
                    +{{ $order->items->count() - 4 }} ta
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white border-2 border-dashed border-gray-200 rounded-2xl text-gray-400">
            <p class="text-3xl mb-2">✅</p>
            <p class="text-sm font-medium">Barcha to'lovlar qabul qilingan</p>
        </div>
        @endforelse
    </div>

    {{-- SO'NGGI TO'LOVLAR --}}
    <div>
        <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            So'nggi to'lovlar
        </h3>

        @forelse($recent as $bill)
        <a href="{{ route('billing.show', $bill) }}"
           class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 mb-2 hover:shadow-sm hover:border-gray-300 transition-all">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center text-base">
                    {{ $bill->paymentIcon() }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm font-mono">{{ $bill->order->order_number }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-xs text-gray-500">{{ $bill->pricing_strategy }}</span>
                        @if($bill->hasDiscount())
                        <span class="text-xs text-green-600 font-semibold">−{{ $bill->getFormattedDiscount() }}</span>
                        @endif
                        <span class="text-xs text-gray-400">· {{ $bill->paid_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
            <span class="font-bold text-gray-900">{{ $bill->getFormattedTotal() }}</span>
        </a>
        @empty
        <div class="text-center py-12 bg-white border-2 border-dashed border-gray-200 rounded-2xl text-gray-400">
            <p class="text-sm">Hali to'lov yo'q</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
