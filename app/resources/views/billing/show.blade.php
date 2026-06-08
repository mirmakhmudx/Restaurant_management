@extends('layouts.app')
@section('title', 'Bill #' . $bill->id)
@section('page-title', 'Bill #' . $bill->id)
@section('page-subtitle', 'Payment confirmed · ' . $bill->paid_at->format('d M Y, H:i'))

@section('content')
<div class="max-w-lg">

    {{-- Receipt card --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-gray-900 text-white p-6 text-center">
            <p class="text-3xl mb-1">{{ $bill->paymentIcon() }}</p>
            <h2 class="font-bold text-xl mb-0.5">{{ $bill->getFormattedTotal() }}</h2>
            <p class="text-gray-400 text-sm">Payment Confirmed</p>
        </div>

        <div class="p-6 space-y-4">

            {{-- Order details --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Order Details</p>
                <div class="space-y-1.5">
                    @foreach($bill->order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item->quantity }}× {{ $item->name }}</span>
                        <span class="text-gray-900 font-medium">{{ $item->getFormattedSubtotal() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>


            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Payment Breakdown</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-900">{{ $bill->getFormattedSubtotal() }}</span>
                    </div>
                    @if($bill->hasDiscount())
                    <div class="flex justify-between text-sm">
                        <span class="text-green-600">{{ $bill->pricing_strategy }} discount</span>
                        <span class="text-green-600 font-medium">− {{ $bill->getFormattedDiscount() }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-base border-t border-gray-100 pt-2">
                        <span class="text-gray-900">Total Paid</span>
                        <span class="text-gray-900">{{ $bill->getFormattedTotal() }}</span>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="border-t border-gray-100 pt-4 grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Order</p>
                    <p class="font-medium text-gray-900">{{ $bill->order->order_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Table</p>
                    <p class="font-medium text-gray-900">
                        {{ $bill->order->table ? 'Table '.$bill->order->table->number : 'Takeaway' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Pricing</p>
                    <p class="font-medium text-gray-900">{{ $bill->pricing_strategy }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Payment</p>
                    <p class="font-medium text-gray-900 capitalize">{{ $bill->payment_method }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 mt-5">
        <a href="{{ route('billing.index') }}"
           class="flex-1 text-center py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition-colors">
            Back to Billing
        </a>
        <a href="{{ route('billing.receipt', $bill) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            PDF Chek yuklash
        </a>
        <a href="{{ route('orders.index') }}"
           class="flex-1 text-center py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">
            View Orders
        </a>
    </div>
</div>
@endsection
