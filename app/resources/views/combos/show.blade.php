@extends('layouts.app')
@section('title', $combo->name)
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $combo->name }}</h1>
            <p class="text-sm text-gray-500">Composite Pattern — set meal</p>
        </div>
        <a href="{{ route('combos.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Orqaga</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Tarkibi:</h3>
        <div class="space-y-3">
            @foreach($combo->items as $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold">
                        {{ $item->quantity }}x
                    </span>
                    <div>
                        <p class="font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->menuItem->description }}</p>
                    </div>
                </div>
                <span class="font-medium text-gray-900">
                    £{{ number_format($item->menuItem->price * $item->quantity, 2) }}
                </span>
            </div>
            @endforeach
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 space-y-2">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Asl narx:</span>
                <span class="line-through">£{{ number_format($combo->getOriginalPrice(), 2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-green-600 font-medium">
                <span>Tejash:</span>
                <span>-£{{ number_format($combo->getSavings(), 2) }}</span>
            </div>
            <div class="flex justify-between text-xl font-bold text-gray-900">
                <span>Combo narxi:</span>
                <span>£{{ number_format($combo->price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
