@extends('layouts.app')
@section('title', 'Waiter Dashboard')
@section('page-title', 'Waiter Dashboard')
@section('page-subtitle', auth()->user()->name . ' — Bugungi navbat')

@section('content')

@if($readyCount > 0)
<div class="mb-5 flex items-center gap-3 px-4 py-3 bg-green-50 border-2 border-green-400 rounded-xl animate-pulse">
    <span class="text-2xl">🔔</span>
    <p class="text-green-800 font-bold">{{ $readyCount }} ta order xizmatga tayyor! Tez olib boring.</p>
    <a href="{{ route('kitchen.index') }}" class="ml-auto text-sm text-green-700 underline font-bold">Ko'rish →</a>
</div>
@endif

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Mening orderlarim</p>
        <p class="text-4xl font-bold text-gray-900">{{ $myOrders->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Bo'sh stollar</p>
        <p class="text-4xl font-bold text-green-600">{{ $tables->where('status', 'available')->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900">Mening orderlarim</h3>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-900 text-white text-xs font-bold rounded-lg">
                + Yangi Order
            </a>
        </div>
        @forelse($myOrders as $order)
        <a href="{{ route('orders.show', $order) }}"
           class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-xl mb-2 hover:shadow-sm transition-all">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-xs font-bold text-gray-700">
                {{ substr($order->order_number,-3) }}
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-900 text-sm">{{ $order->order_number }}</p>
                <p class="text-xs text-gray-400">{{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }} · {{ $order->itemCount() }} ta taom</p>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold border {{ $order->status->badgeClasses() }}">
                {{ $order->status->label() }}
            </span>
        </a>
        @empty
        <div class="text-center py-10 bg-white border-2 border-dashed border-gray-200 rounded-xl text-gray-400">
            <p class="text-sm">Hali order yo'q</p>
        </div>
        @endforelse
    </div>

    <div>
        <h3 class="font-bold text-gray-900 mb-3">Stollar holati</h3>
        <div class="grid grid-cols-4 gap-2">
            @foreach($tables as $table)
            <div class="bg-white border-2 rounded-xl p-3 text-center {{ $table->status->cardBg() }}">
                <p class="font-bold text-xl text-gray-900">{{ $table->number }}</p>
                <p class="text-xs text-gray-500">{{ $table->capacity }}p</p>
                <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full inline-block mt-1 {{ $table->status->badgeClasses() }}">
                    {{ $table->status->label() }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
