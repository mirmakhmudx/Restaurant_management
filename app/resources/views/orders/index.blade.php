@extends('layouts.app')
@section('title', 'Orderlar')
@section('page-title', 'Orderlar')
@section('page-subtitle', 'Barcha orderlarni boshqaring')

@section('content')

{{-- STATUS TABS --}}
<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl flex-wrap">
        @foreach([
            'active'    => ['Faol',      $counts['active']],
            'pending'   => ['Pending',   $counts['pending']],
            'preparing' => ['Kitchen',   $counts['preparing']],
            'ready'     => ['Tayyor',    $counts['ready']],
            'billed'    => ["To'langan", $counts['billed']],
            'all'       => ['Barchasi',  null],
        ] as $val => [$lbl, $cnt])
        <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => $val])) }}"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap
           {{ $status === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            {{ $lbl }}
            @if($cnt !== null && $cnt > 0)
            <span class="text-xs px-1.5 py-0.5 rounded-full font-bold
                {{ $status === $val ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-600' }}">
                {{ $cnt }}
            </span>
            @endif
        </a>
        @endforeach
    </div>
    <a href="{{ route('orders.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Yangi Order
    </a>
</div>

{{-- FILTER PANEL --}}
<form method="GET" action="{{ route('orders.index') }}" id="filter-form">
    <input type="hidden" name="status" value="{{ $status }}">
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Order raqami..."
                       onchange="this.form.submit()"
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none focus:border-gray-900">
            </div>

            {{-- Sana --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5"/>
                </svg>
                <input type="date" name="date" value="{{ $date }}"
                       onchange="this.form.submit()"
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none focus:border-gray-900">
            </div>

            {{-- Waiter --}}
            <select name="waiter" onchange="this.form.submit()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none focus:border-gray-900">
                <option value="">Barcha ofitsiантlar</option>
                @foreach($waiters as $w)
                <option value="{{ $w->id }}" {{ $waiterId == $w->id ? 'selected':'' }}>
                    {{ $w->name }}
                </option>
                @endforeach
            </select>

            {{-- Stol --}}
            <select name="table" onchange="this.form.submit()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none focus:border-gray-900">
                <option value="">Barcha stollar</option>
                <option value="takeaway" {{ $tableId === 'takeaway' ? 'selected':'' }}>🛍 Takeaway</option>
                @foreach($tables as $t)
                <option value="{{ $t->id }}" {{ $tableId == $t->id ? 'selected':'' }}>
                    Stol {{ $t->number }} ({{ $t->capacity }}p)
                </option>
                @endforeach
            </select>
        </div>

        {{-- Faol filterlar --}}
        @if(count($activeFilters) > 0)
        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 flex-wrap">
            <span class="text-xs text-gray-500">Faol filter:</span>
            @if($search)
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-200">
                🔍 "{{ $search }}"
                <a href="{{ route('orders.index', array_merge(request()->query(), ['search'=>''])) }}" class="ml-0.5 hover:text-blue-900">×</a>
            </span>
            @endif
            @if($date)
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-200">
                📅 {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                <a href="{{ route('orders.index', array_merge(request()->query(), ['date'=>''])) }}" class="ml-0.5 hover:text-blue-900">×</a>
            </span>
            @endif
            @if($waiterId)
            @php $wName = $waiters->find($waiterId)?->name ?? $waiterId; @endphp
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-200">
                👤 {{ $wName }}
                <a href="{{ route('orders.index', array_merge(request()->query(), ['waiter'=>''])) }}" class="ml-0.5 hover:text-blue-900">×</a>
            </span>
            @endif
            @if($tableId)
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-200">
                🪑 Stol {{ $tableId }}
                <a href="{{ route('orders.index', array_merge(request()->query(), ['table'=>''])) }}" class="ml-0.5 hover:text-blue-900">×</a>
            </span>
            @endif
            <a href="{{ route('orders.index', ['status' => $status]) }}"
               class="text-xs text-red-500 hover:text-red-700 font-medium ml-1">
                Barchani tozalash ×
            </a>
        </div>
        @endif
    </div>
</form>

{{-- ORDERS TABLE --}}
@if($orders->isEmpty())
<div class="flex flex-col items-center justify-center py-20 text-center bg-white border border-gray-200 rounded-2xl">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4 text-3xl">
        {{ count($activeFilters) > 0 ? '🔍' : '📋' }}
    </div>
    <p class="font-bold text-gray-900 mb-1">
        {{ count($activeFilters) > 0 ? 'Filter natijasi bo\'sh' : 'Order topilmadi' }}
    </p>
    <p class="text-sm text-gray-400 mb-5">
        {{ count($activeFilters) > 0 ? 'Filterni o\'zgartiring yoki tozalang' : 'Yangi order yarating' }}
    </p>
    @if(count($activeFilters) > 0)
    <a href="{{ route('orders.index', ['status' => $status]) }}"
       class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50">
        Filterni tozalash
    </a>
    @else
    <a href="{{ route('orders.create') }}"
       class="px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-colors">
        Yangi Order yaratish
    </a>
    @endif
</div>
@else
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-500">{{ $orders->count() }} ta order topildi</span>
        <span class="text-xs text-gray-400">
            Jami: £{{ number_format($orders->sum('total'), 2) }}
        </span>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Order</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Stol</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Ofitsiant</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Holat</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Vaqt</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">Summa</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">Harakat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($orders as $order)
            @php $nexts = $order->status->nextStatuses(); @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <a href="{{ route('orders.show', $order) }}"
                       class="font-bold text-gray-900 hover:underline font-mono text-sm">
                        {{ $order->order_number }}
                    </a>
                    <p class="text-xs text-gray-400">{{ $order->itemCount() }} ta taom</p>
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-700">
                    {{ $order->table ? 'Stol '.$order->table->number : '🛍 Takeaway' }}
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-500">
                    {{ $order->waiter?->name ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold border {{ $order->status->badgeClasses() }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotColor() }}"></span>
                        {{ $order->status->label() }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-xs text-gray-500">
                    <p>{{ $order->created_at->format('H:i') }}</p>
                    <p class="text-gray-400">{{ $order->created_at->format('d M') }}</p>
                </td>
                <td class="px-5 py-3.5 text-right font-bold text-gray-900">
                    {{ $order->getFormattedTotal() }}
                </td>
                <td class="px-5 py-3.5 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        @php
                        $allowedNexts = array_filter($nexts, fn($n) => match(auth()->user()->role->value) {
                            'manager' => true,
                            'waiter'  => in_array($n->value, ['confirmed','served','cancelled']),
                            'chef'    => in_array($n->value, ['preparing','ready']),
                            'cashier' => $n->value === 'billed',
                            default   => false,
                        });
                        @endphp
                        @if(!empty($allowedNexts))
                        @php $first = array_values($allowedNexts)[0]; @endphp
                        <form method="POST" action="{{ route('orders.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $first->value }}">
                            <button type="submit"
                                class="text-xs px-2.5 py-1.5 rounded-lg font-semibold transition-all
                                {{ $first === \App\Enums\OrderStatus::Cancelled
                                    ? 'border border-red-200 text-red-600 hover:bg-red-50'
                                    : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                                {{ $first->icon() }} {{ $first->label() }}
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('orders.show', $order) }}"
                           class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
