@extends('layouts.app')
@section('title', 'Kitchen')
@section('page-title', 'Kitchen Display')
@section('page-subtitle', 'Live order queue — Command Pattern in action')

@section('content')

{{-- Auto-refresh every 30 seconds --}}
<meta http-equiv="refresh" content="30">

{{-- Stats bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-sm font-medium text-blue-700">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            {{ $confirmed->count() }} Confirmed
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-sm font-medium text-amber-700">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            {{ $preparing->count() }} Preparing
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full text-sm font-medium text-green-700">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            {{ $ready->count() }} Ready
        </span>
    </div>
    <span class="text-xs text-gray-400">Auto-refreshes every 30s</span>
</div>

{{-- Kanban board --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    {{-- CONFIRMED --}}
    <div>
        <div class="flex items-center gap-2 mb-3 pb-2 border-b-2 border-blue-400">
            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
            <h3 class="font-semibold text-gray-900 text-sm">Confirmed</h3>
            <span class="ml-auto text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">
                {{ $confirmed->count() }}
            </span>
        </div>

        @forelse($confirmed as $order)
        <div class="bg-white border border-blue-200 rounded-xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $order->table ? 'Table '.$order->table->number : 'Takeaway' }}
                        · {{ $order->created_at->diffForHumans() }}
                    </p>
                </div>
                <span class="text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2 py-1 rounded-lg font-medium">
                    Waiting
                </span>
            </div>

            <ul class="space-y-1 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center text-xs font-bold text-gray-600">
                        {{ $item->quantity }}
                    </span>
                    {{ $item->name }}
                    @if($item->notes)
                    <span class="text-xs text-orange-500 italic">— {{ $item->notes }}</span>
                    @endif
                </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('kitchen.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="preparing">
                <button type="submit"
                    class="w-full py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                    </svg>
                    Start Preparing
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm bg-white border border-dashed border-gray-200 rounded-xl">
            No orders waiting
        </div>
        @endforelse
    </div>

    {{-- PREPARING --}}
    <div>
        <div class="flex items-center gap-2 mb-3 pb-2 border-b-2 border-amber-400">
            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
            <h3 class="font-semibold text-gray-900 text-sm">Preparing</h3>
            <span class="ml-auto text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">
                {{ $preparing->count() }}
            </span>
        </div>

        @forelse($preparing as $order)
        <div class="bg-white border border-amber-200 rounded-xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $order->table ? 'Table '.$order->table->number : 'Takeaway' }}
                        · {{ $order->updated_at->diffForHumans() }}
                    </p>
                </div>
                <span class="text-xs bg-amber-50 text-amber-600 border border-amber-200 px-2 py-1 rounded-lg font-medium animate-pulse">
                    Cooking…
                </span>
            </div>

            <ul class="space-y-1 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="w-5 h-5 bg-amber-50 rounded flex items-center justify-center text-xs font-bold text-amber-700">
                        {{ $item->quantity }}
                    </span>
                    {{ $item->name }}
                </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('kitchen.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ready">
                <button type="submit"
                    class="w-full py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    Mark Ready
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm bg-white border border-dashed border-gray-200 rounded-xl">
            Nothing cooking
        </div>
        @endforelse
    </div>

    {{-- READY --}}
    <div>
        <div class="flex items-center gap-2 mb-3 pb-2 border-b-2 border-green-500">
            <div class="w-2 h-2 rounded-full bg-green-500"></div>
            <h3 class="font-semibold text-gray-900 text-sm">Ready to Serve</h3>
            <span class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">
                {{ $ready->count() }}
            </span>
        </div>

        @forelse($ready as $order)
        <div class="bg-white border-2 border-green-400 rounded-xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $order->table ? 'Table '.$order->table->number : 'Takeaway' }}
                        @if($order->prepared_at)
                        · Ready {{ $order->prepared_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
                <span class="text-xs bg-green-50 text-green-700 border border-green-300 px-2 py-1 rounded-lg font-bold">
                    🔔 READY
                </span>
            </div>

            <ul class="space-y-1 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <span class="w-5 h-5 bg-green-50 rounded flex items-center justify-center text-xs font-bold text-green-700">
                        {{ $item->quantity }}
                    </span>
                    {{ $item->name }}
                </li>
                @endforeach
            </ul>

            <a href="{{ route('orders.show', $order) }}"
               class="block w-full py-2 text-center border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                View Order
            </a>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm bg-white border border-dashed border-gray-200 rounded-xl">
            Nothing ready yet
        </div>
        @endforelse
    </div>

</div>
@endsection
