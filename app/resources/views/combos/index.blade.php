@extends('layouts.app')
@section('title', 'Combo Meals')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Combo Meals</h1>
            <p class="text-sm text-gray-500 mt-1">Composite Pattern — {{ $combos->total() }} ta combo</p>
        </div>
        @if(auth()->user()->isManager())
        <a href="{{ route('combos.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800">
            + Yangi Combo
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($combos as $combo)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $combo->name }}</h3>
                        @if($combo->description)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $combo->description }}</p>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium
                          {{ $combo->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $combo->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="space-y-1.5 mb-4">
                    @foreach($combo->items as $item)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold">{{ $item->quantity }}x</span>
                        <span>{{ $item->menuItem?->name ?? "Deleted item" }}</span>
                        <span class="text-gray-400 text-xs">£{{ number_format($item->menuItem?->price ?? 0, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 line-through">£{{ number_format($combo->getOriginalPrice(), 2) }}</p>
                        <p class="text-xl font-bold text-gray-900">£{{ number_format($combo->price, 2) }}</p>
                        <p class="text-xs text-green-600 font-medium">Save £{{ number_format($combo->getSavings(), 2) }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('combos.show', $combo) }}"
                           class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200">
                            Ko'rish
                        </a>
                        @if(auth()->user()->isManager())
                        <form method="POST" action="{{ route('combos.destroy', $combo) }}" onsubmit="return confirm('O\'chirasizmi?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100">
                                O'chir
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🍱</p>
            <p class="font-medium">Hali combo yo'q</p>
        </div>
        @endforelse
    </div>
    {{ $combos->links() }}
</div>
@endsection
