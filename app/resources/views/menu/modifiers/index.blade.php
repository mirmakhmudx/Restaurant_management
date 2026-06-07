@extends('layouts.app')
@section('title', 'Modifiers — ' . $menuItem->name)
@section('page-title', $menuItem->name)
@section('page-subtitle', 'Extra variantlar boshqaruvi')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Yangi modifier --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-900 text-sm mb-4">Yangi Modifier</h3>

        @if(session('success'))
        <div class="mb-4 px-3 py-2.5 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 px-3 py-2.5 bg-red-50 border border-red-200 rounded-lg">
            @foreach($errors->all() as $e)
            <p class="text-red-600 text-xs">{{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('menu.modifiers.store', $menuItem) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Nomi *</label>
                <input name="name" type="text" required value="{{ old('name') }}"
                       placeholder="Extra Cheese, BBQ Sauce..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Qo'shimcha narx (£)</label>
                <input name="price" type="number" step="0.01" min="0" required value="{{ old('price', 0) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                <p class="text-xs text-gray-400 mt-1">0 kiritsangiz — bepul</p>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                + Qo'shish
            </button>
        </form>

        <div class="mt-5 pt-5 border-t border-gray-100">
            <h4 class="text-xs font-semibold text-gray-500 mb-3">Mashhur modifierlar</h4>
            @php
            $presets = [
                ['Extra Cheese', 1.50], ['Bacon', 2.00], ['Avocado', 1.50],
                ['BBQ Sauce', 0.50], ['Spicy', 0.00], ['No Onion', 0.00],
                ['Extra Sauce', 0.50], ['Large Portion', 3.00],
            ];
            @endphp
            <div class="flex flex-wrap gap-1.5">
                @foreach($presets as [$n, $p])
                <button type="button"
                        onclick="document.querySelector('[name=name]').value='{{ $n }}'; document.querySelector('[name=price]').value='{{ $p }}';"
                        class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    {{ $n }} {{ $p > 0 ? '+£'.$p : '' }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modifierlar ro'yxati --}}
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900 text-sm">
                {{ $menuItem->name }} — Modifierlar ({{ $modifiers->count() }})
            </h3>
            <a href="{{ route('menu.edit', $menuItem) }}"
               class="text-xs text-gray-500 hover:text-gray-900 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                ← Menuga qaytish
            </a>
        </div>

        @if($modifiers->isEmpty())
        <div class="text-center py-16 bg-white border-2 border-dashed border-gray-200 rounded-2xl text-gray-400">
            <p class="text-3xl mb-2">🧂</p>
            <p class="text-sm font-semibold text-gray-600 mb-1">Hali modifier yo'q</p>
            <p class="text-xs">Extra cheese, saucelar qo'shing</p>
        </div>
        @else
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Modifier nomi</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Narx</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Holat</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">Harakat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($modifiers as $mod)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$mod->is_available ? 'opacity-50':'' }}">
                        <td class="px-5 py-3.5 font-semibold text-gray-900">
                            {{ $mod->name }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($mod->price > 0)
                            <span class="font-bold text-green-700">+£{{ number_format($mod->price, 2) }}</span>
                            @else
                            <span class="text-gray-400 text-xs">Bepul</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <form method="POST" action="{{ route('menu.modifiers.toggle', $mod) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-full border font-semibold transition-all
                                        {{ $mod->is_available
                                            ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'
                                            : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                                    {{ $mod->is_available ? '● Faol' : '○ Nofaol' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('menu.modifiers.destroy', $mod) }}"
                                  onsubmit="return confirm('O\'chirasizmi?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection
