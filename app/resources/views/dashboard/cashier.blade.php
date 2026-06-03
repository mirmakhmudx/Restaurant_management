@extends('layouts.app')
@section('title', 'Cashier Dashboard')
@section('page-title', 'Cashier Dashboard')
@section('page-subtitle', "Bugungi to'lovlar")

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border {{ $pending->count() > 0 ? 'border-amber-300' : 'border-gray-200' }} rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">To'lov kutmoqda</p>
        <p class="text-4xl font-bold {{ $pending->count() > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $pending->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Bugun to'landi</p>
        <p class="text-4xl font-bold text-green-600">{{ $todayBills->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Bugungi daromad</p>
        <p class="text-2xl font-bold text-gray-900">£{{ number_format($todayTotal, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div>
        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            To'lov kutmoqda
        </h3>
        @forelse($pending as $order)
        <div class="bg-white border border-amber-200 rounded-xl p-4 mb-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                        @if($order->waiter) · {{ $order->waiter->name }} @endif
                        · {{ $order->itemCount() }} ta taom
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-xl text-gray-900">{{ $order->getFormattedTotal() }}</span>
                    <a href="{{ route('billing.create', $order) }}"
                       class="px-3 py-2 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-colors">
                        To'lov
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white border-2 border-dashed border-gray-200 rounded-xl text-gray-400">
            <p class="text-3xl mb-2">✅</p>
            <p class="text-sm">Barcha to'lovlar qabul qilingan</p>
        </div>
        @endforelse
    </div>

    <div>
        <h3 class="font-bold text-gray-900 mb-3">Bugungi to'lovlar</h3>
        @forelse($todayBills as $bill)
        <a href="{{ route('billing.show', $bill) }}"
           class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl mb-2 hover:shadow-sm transition-all">
            <div class="flex items-center gap-3">
                <span class="text-xl">{{ $bill->paymentIcon() }}</span>
                <div>
                    <p class="font-bold text-sm text-gray-900 font-mono">{{ $bill->order->order_number }}</p>
                    <p class="text-xs text-gray-400">{{ $bill->pricing_strategy }} · {{ $bill->paid_at->format('H:i') }}</p>
                </div>
            </div>
            <span class="font-bold text-gray-900">{{ $bill->getFormattedTotal() }}</span>
        </a>
        @empty
        <div class="text-center py-8 text-gray-400 text-sm">Hali to'lov yo'q</div>
        @endforelse
    </div>
</div>
@endsection
