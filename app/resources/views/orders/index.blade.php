@extends('layouts.app')
@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Live order management — State Pattern in action')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg">
        @foreach([
            'active'    => ['Active', $counts['active']],
            'pending'   => ['Pending', $counts['pending']],
            'preparing' => ['Kitchen', $counts['preparing']],
            'ready'     => ['Ready', $counts['ready']],
            'billed'    => ['Completed', $counts['billed']],
            'all'       => ['All', null],
        ] as $val => $meta)
        <a href="{{ route('orders.index', ['status' => $val]) }}"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-all whitespace-nowrap
           {{ $status === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            {{ $meta[0] }}
            @if($meta[1] !== null && $meta[1] > 0)
            <span class="text-xs {{ $status === $val ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-600' }} px-1.5 py-0.5 rounded-full">
                {{ $meta[1] }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    <a href="{{ route('orders.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        New Order
    </a>
</div>

@if($orders->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <p class="font-semibold text-gray-900 mb-1">No orders found</p>
    <a href="{{ route('orders.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Create First Order
    </a>
</div>
@else
<div class="space-y-3">
    @foreach($orders as $order)
    @php $state = $order->getState(); @endphp
    <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
        <div class="flex items-center justify-between">
            {{-- Left: order info --}}
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                <div>
                    <div class="flex items-center gap-2">
                        @if($order->table)
                        <span class="text-sm font-medium text-gray-900">Table {{ $order->table->number }}</span>
                        @else
                        <span class="text-sm text-gray-500">Takeaway</span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border {{ $order->status->badgeClasses() }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotColor() }}"></span>
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->itemCount() }} item{{ $order->itemCount() !== 1 ? 's' : '' }}
                        @if($order->waiter) · {{ $order->waiter->name }} @endif
                    </p>
                </div>
            </div>

            {{-- Right: total + actions --}}
            <div class="flex items-center gap-4">
                <span class="font-bold text-gray-900 text-lg">{{ $order->getFormattedTotal() }}</span>

                {{-- Quick status actions --}}
                @php $nexts = $order->status->nextStatuses(); @endphp
                @if(!empty($nexts))
                <div class="flex items-center gap-1">
                    @foreach($nexts as $next)
                    <form method="POST" action="{{ route('orders.status', $order) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $next->value }}">
                        <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                   border transition-colors
                                   {{ $next === \App\Enums\OrderStatus::Cancelled
                                       ? 'border-red-200 text-red-600 hover:bg-red-50'
                                       : 'border-gray-900 text-gray-900 bg-gray-900 text-white hover:bg-gray-800' }}">
                            {{ $next->icon() }} {{ $next->label() }}
                        </button>
                    </form>
                    @endforeach
                </div>
                @endif

                <a href="{{ route('orders.show', $order) }}"
                   class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
