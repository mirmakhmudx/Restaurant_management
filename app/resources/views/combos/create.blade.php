@extends('layouts.app')
@section('title', 'Yangi Combo')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Yangi Combo</h1>
        <p class="text-sm text-gray-500 mt-1">Composite Pattern — bir nechta menudan iborat set</p>
    </div>

    <form method="POST" action="{{ route('combos.store') }}" class="space-y-5">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Combo nomi *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="Family Set, Lunch Special..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tavsif</label>
                <textarea name="description" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Combo narxi (£) *</label>
                <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900">
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5" x-data="comboBuilder()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Combo tarkibi (min 2 ta)</h3>
                <button type="button" x-on:click="addItem()"
                        class="text-sm px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
                    + Qo'sh
                </button>
            </div>

            <div class="space-y-3" id="combo-items">
                <template x-for="(item, index) in items" :key="index">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="flex-1">
                            <select x-bind:name="`items[${index}][id]`"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">Taom tanlang</option>
                                @foreach($menuItems as $mi)
                                <option value="{{ $mi->id }}" data-price="{{ $mi->price }}">
                                    {{ $mi->name }} — £{{ number_format($mi->price, 2) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-20">
                            <input type="number" x-bind:name="`items[${index}][qty]`"
                                   x-model="item.qty" min="1" value="1"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-center">
                        </div>
                        <button type="button" x-on:click="removeItem(index)"
                                class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600">
                            ✕
                        </button>
                    </div>
                </template>
            </div>

            <p class="text-xs text-gray-400 mt-3">* Combo narxi asl narxdan arzon bo'lishi kerak</p>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800">
                Combo yaratish
            </button>
            <a href="{{ route('combos.index') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                Bekor
            </a>
        </div>
    </form>
</div>

<script>
function comboBuilder() {
    return {
        items: [{qty:1},{qty:1}],
        addItem() { this.items.push({qty:1}); },
        removeItem(i) { if(this.items.length > 2) this.items.splice(i,1); }
    }
}
</script>
@endsection
