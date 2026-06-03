@extends('layouts.app')
@section('title', 'Kitchen Dashboard')
@section('page-title', 'Kitchen Dashboard')
@section('page-subtitle', 'Bugungi oshxona holati')

@section('content')

<meta http-equiv="refresh" content="30">

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-blue-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Kutmoqda</p>
        <p class="text-4xl font-bold text-blue-600">{{ $confirmed->count() }}</p>
    </div>
    <div class="bg-white border border-amber-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Pishirilmoqda</p>
        <p class="text-4xl font-bold text-amber-600">{{ $preparing->count() }}</p>
    </div>
    <div class="bg-white border border-green-200 rounded-xl p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">Bugun tayyor qilindi</p>
        <p class="text-4xl font-bold text-green-600">{{ $todayDone }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            Kutilmoqda ({{ $confirmed->count() }})
        </h3>
        @forelse($confirmed as $order)
        <div class="bg-white border-2 border-blue-200 rounded-xl p-4 mb-3">
            <div class="flex justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">{{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }} · {{ $order->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <ul class="space-y-1 mb-3">
                @foreach($order->items as $item)
                <li class="flex gap-2 text-sm"><span class="w-6 h-6 bg-blue-50 rounded text-blue-700 font-bold text-xs flex items-center justify-center">{{ $item->quantity }}</span>{{ $item->name }}</li>
                @endforeach
            </ul>
            <form method="POST" action="{{ route('kitchen.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="preparing">
                <button class="w-full py-2 bg-gray-900 text-white text-sm font-bold rounded-xl">🔥 Boshlash</button>
            </form>
        </div>
        @empty
        <div class="text-center py-10 bg-white border-2 border-dashed border-gray-200 rounded-xl text-gray-400">☕ Kutilayotgan yo'q</div>
        @endforelse
    </div>

    <div>
        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            Pishirilmoqda ({{ $preparing->count() }})
        </h3>
        @forelse($preparing as $order)
        <div class="bg-white border-2 border-amber-300 rounded-xl p-4 mb-3">
            <div class="flex justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">{{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }} · 🔥 {{ $order->updated_at->diffInMinutes(now()) }}min</p>
                </div>
            </div>
            <ul class="space-y-1 mb-3">
                @foreach($order->items as $item)
                <li class="flex gap-2 text-sm"><span class="w-6 h-6 bg-amber-50 rounded text-amber-700 font-bold text-xs flex items-center justify-center">{{ $item->quantity }}</span>{{ $item->name }}</li>
                @endforeach
            </ul>
            <form method="POST" action="{{ route('kitchen.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ready">
                <button class="w-full py-2 bg-green-600 text-white text-sm font-bold rounded-xl">🔔 Tayyor!</button>
            </form>
        </div>
        @empty
        <div class="text-center py-10 bg-white border-2 border-dashed border-gray-200 rounded-xl text-gray-400">👨‍🍳 Hech narsa yo'q</div>
        @endforelse
    </div>
</div>
@endsection
