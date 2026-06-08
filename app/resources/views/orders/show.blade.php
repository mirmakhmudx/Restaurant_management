@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('page-title', $order->order_number)
@section('page-subtitle', $state->getLabel() . ' · ' . $order->created_at->format('d M Y, H:i'))

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CHAP: asosiy ma'lumot --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- STATUS TIMELINE --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-gray-900 text-sm">Holat zanjiri — State Pattern</h3>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $order->status->badgeClasses() }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotColor() }}"></span>
                    {{ $order->status->label() }}
                </span>
                </div>

                @php
                    $pipeline = ['pending','confirmed','preparing','ready','served','billed'];
                    $currentIdx = array_search($order->status->value, $pipeline);
                    $isCancelled = $order->status->value === 'cancelled';
                @endphp

                <div class="flex items-center">
                    @foreach($pipeline as $idx => $step)
                        @php
                            $stepStatus = \App\Enums\OrderStatus::from($step);
                            $isPast    = !$isCancelled && $currentIdx !== false && $idx < $currentIdx;
                            $isCurrent = !$isCancelled && $order->status->value === $step;
                        @endphp
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm border-2 transition-all mb-1
                        {{ $isCurrent ? 'border-gray-900 bg-gray-900 text-white ring-4 ring-gray-900/10' :
                           ($isPast   ? 'border-green-500 bg-green-500 text-white' :
                           'border-gray-200 bg-white text-gray-400') }}">
                                @if($isPast)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <span class="text-xs">{{ $stepStatus->icon() }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-medium text-center leading-tight
                        {{ $isCurrent ? 'text-gray-900 font-bold' : ($isPast ? 'text-green-600' : 'text-gray-400') }}">
                        {{ $stepStatus->label() }}
                    </span>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-0.5 -mt-4 mx-1 {{ $isPast ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                        @endif
                    @endforeach
                </div>

                @if($isCancelled)
                    <div class="mt-4 flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl">
                        <span class="text-red-500 text-lg">❌</span>
                        <p class="text-sm font-semibold text-red-700">Bu order bekor qilingan</p>
                    </div>
                @endif
            </div>

            {{-- TAOMLAR RO'YXATI --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm">
                        Buyurtma tarkibi
                    </h3>
                    <span class="text-xs text-gray-500">{{ $order->itemCount() }} ta taom</span>
                </div>

                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Taom</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Miqdor</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">Narx</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">Jami</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-orange-500 mt-0.5">📝 {{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 bg-gray-100 rounded-lg font-bold text-gray-700 text-xs">
                                {{ $item->quantity }}
                            </span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-gray-500">{{ $item->getFormattedPrice() }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-gray-900">{{ $item->getFormattedSubtotal() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                    @if($order->discount > 0)
                        <tr>
                            <td colspan="3" class="px-5 py-2.5 text-right text-sm text-gray-500">Subtotal</td>
                            <td class="px-5 py-2.5 text-right font-semibold text-gray-900">
                                £{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-5 py-2.5 text-right text-sm text-green-600">Chegirma</td>
                            <td class="px-5 py-2.5 text-right font-semibold text-green-600">
                                −£{{ number_format($order->discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="px-5 py-3.5 text-right font-bold text-gray-900">JAMI</td>
                        <td class="px-5 py-3.5 text-right font-bold text-xl text-gray-900">{{ $order->getFormattedTotal() }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            {{-- IZOH --}}
            @if($order->notes)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <span class="text-amber-500 text-lg flex-shrink-0">📝</span>
                    <div>
                        <p class="text-xs font-semibold text-amber-800 mb-0.5">Maxsus izoh</p>
                        <p class="text-sm text-amber-700">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- O'NG: metadata + actions --}}
        <div class="space-y-4">

            {{-- Order info --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-bold text-gray-900 text-sm mb-4">Order ma'lumotlari</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500">Order raqami</dt>
                        <dd class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</dd>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500">Stol</dt>
                        <dd class="font-semibold text-gray-900">
                            {{ $order->table ? 'Stol '.$order->table->number.' ('.$order->table->capacity.'p)' : '🛍️ Takeaway' }}
                        </dd>
                    </div>
                    @if($order->waiter)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Ofitsiant</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->waiter->name }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-gray-500">Yaratilgan</dt>
                        <dd class="text-gray-700">{{ $order->created_at->format('d M, H:i') }}</dd>
                    </div>
                    @if($order->confirmed_at)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Tasdiqlangan</dt>
                            <dd class="text-gray-700">{{ $order->confirmed_at->format('H:i') }}</dd>
                        </div>
                    @endif
                    @if($order->prepared_at)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Tayyor bo'lgan</dt>
                            <dd class="text-gray-700">{{ $order->prepared_at->format('H:i') }}</dd>
                        </div>
                    @endif
                    @if($order->served_at)
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Xizmat qilingan</dt>
                            <dd class="text-gray-700">{{ $order->served_at->format('H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- STATUS O'ZGARTIRISH --}}
            @php $nexts = $order->status->nextStatuses(); @endphp
            @if(!empty($nexts))
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-900 text-sm mb-3">Harakatlar</h3>
                    <div class="space-y-2">
                        @foreach($nexts as $next)
                            @php
                                $canDo = match(auth()->user()->role->value) {
                                    'manager' => true,
                                    'waiter'  => in_array($next->value, ['confirmed', 'served', 'cancelled']),
                                    'chef'    => in_array($next->value, ['preparing', 'ready']),
                                    'cashier' => $next->value === 'billed',
                                    default   => false,
                                };
                            @endphp
                            @if($canDo)
                                <form method="POST" action="{{ route('orders.status', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $next->value }}">
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold transition-all
                {{ $next === \App\Enums\OrderStatus::Cancelled
                    ? 'border-2 border-red-200 text-red-600 hover:bg-red-50 bg-white'
                    : 'bg-gray-900 hover:bg-gray-800 text-white shadow-sm' }}">
                                        <span class="text-base">{{ $next->icon() }}</span>
                                        <span>{{ $next->label() }}</span>
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- BILLING (agar served bo'lsa) --}}
            @if($order->status->value === 'served')
                <a href="{{ route('billing.create', $order) }}"
                   class="flex items-center justify-center gap-2 w-full py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    To'lovni qabul qilish
                </a>
            @endif

            <a href="{{ route('orders.index') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">
                ← Orderlar ro'yxati
            </a>
        </div>
    </div>

@endsection
