@extends('layouts.app')
@section('title', 'Kitchen Display')
@section('page-title', 'Kitchen Display')
@section('page-subtitle', 'Jonli buyurtma monitori — Command Pattern')

@section('content')

<meta http-equiv="refresh" content="30">

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-sm font-semibold text-blue-700">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            {{ $confirmed->count() }} Kutmoqda
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-sm font-semibold text-amber-700">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            {{ $preparing->count() }} Pishirilmoqda
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full text-sm font-semibold text-green-700">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            {{ $ready->count() }} Tayyor
        </div>
    </div>
    <div class="flex items-center gap-2 text-xs text-gray-400">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Har 30 soniyada yangilanadi
    </div>
</div>

<div class="grid gap-5" style="grid-template-columns: repeat(3, 1fr);">
    {{-- CONFIRMED --}}
    <div>
        <div class="flex items-center gap-2 mb-4 pb-2 border-b-2 border-blue-400">
            <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
            <h3 class="font-bold text-gray-900">Kutilmoqda</h3>
            <span class="ml-auto text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ $confirmed->count() }}</span>
        </div>

        @forelse($confirmed as $order)
        @php $waitMin = $order->created_at->diffInMinutes(now()); @endphp
        <div class="bg-white border-2 {{ $waitMin > 15 ? 'border-red-300' : 'border-blue-200' }} rounded-2xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg
                        {{ $waitMin > 15 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-blue-50 text-blue-600 border border-blue-200' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $waitMin }}min
                    </span>
                </div>
            </div>

            <ul class="space-y-1.5 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm">
                    <span class="w-6 h-6 bg-blue-50 rounded-lg flex items-center justify-center text-xs font-bold text-blue-700 flex-shrink-0">
                        {{ $item->quantity }}
                    </span>
                    <span class="text-gray-800 font-medium">{{ $item->name }}</span>
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
                    class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048"/>
                    </svg>
                    Pishirishni boshlash
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm bg-white border-2 border-dashed border-gray-200 rounded-2xl">
            <p class="text-2xl mb-2">☕</p>
            Kutilayotgan order yo'q
        </div>
        @endforelse
    </div>

    {{-- PREPARING --}}
    <div>
        <div class="flex items-center gap-2 mb-4 pb-2 border-b-2 border-amber-400">
            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></div>
            <h3 class="font-bold text-gray-900">Pishirilmoqda</h3>
            <span class="ml-auto text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">{{ $preparing->count() }}</span>
        </div>

        @forelse($preparing as $order)
        @php $cookMin = $order->updated_at->diffInMinutes(now()); @endphp
        <div class="bg-white border-2 border-amber-300 rounded-2xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                    </p>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                    🔥 {{ $cookMin }}min
                </span>
            </div>

            <ul class="space-y-1.5 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm">
                    <span class="w-6 h-6 bg-amber-50 rounded-lg flex items-center justify-center text-xs font-bold text-amber-700 flex-shrink-0">
                        {{ $item->quantity }}
                    </span>
                    <span class="text-gray-800 font-medium">{{ $item->name }}</span>
                </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('kitchen.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ready">
                <button type="submit"
                    class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    Tayyor deb belgilash
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm bg-white border-2 border-dashed border-gray-200 rounded-2xl">
            <p class="text-2xl mb-2">👨‍🍳</p>
            Hech narsa pishirilmayapti
        </div>
        @endforelse
    </div>

    {{-- READY --}}
    <div>
        <div class="flex items-center gap-2 mb-4 pb-2 border-b-2 border-green-500">
            <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
            <h3 class="font-bold text-gray-900">Xizmatga Tayyor</h3>
            <span class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">{{ $ready->count() }}</span>
        </div>

        @forelse($ready as $order)
        @php $readyMin = $order->prepared_at ? $order->prepared_at->diffInMinutes(now()) : 0; @endphp
        <div class="bg-white border-2 border-green-400 rounded-2xl p-4 mb-3 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                    </p>
                </div>
                <span class="text-xs font-bold px-2 py-1 bg-green-50 text-green-700 border border-green-300 rounded-lg">
                    🔔 TAYYOR · {{ $readyMin }}min
                </span>
            </div>

            <ul class="space-y-1.5 mb-4">
                @foreach($order->items as $item)
                <li class="flex items-center gap-2 text-sm">
                    <span class="w-6 h-6 bg-green-50 rounded-lg flex items-center justify-center text-xs font-bold text-green-700 flex-shrink-0">
                        {{ $item->quantity }}
                    </span>
                    <span class="text-gray-800 font-medium">{{ $item->name }}</span>
                </li>
                @endforeach
            </ul>

            <a href="{{ route('orders.show', $order) }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">
                Orderni ko'rish →
            </a>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm bg-white border-2 border-dashed border-gray-200 rounded-2xl">
            <p class="text-2xl mb-2">🍽️</p>
            Hali tayyor order yo'q
        </div>
        @endforelse
    </div>

</div>

@endsection
