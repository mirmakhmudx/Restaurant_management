@extends('layouts.app')
@section('title', 'Edit: ' . $item->name)
@section('page-title', 'Edit Menu Item')
@section('page-subtitle', 'Update "' . $item->name . '"')

@section('content')
<div class="max-w-2xl">

    @if($errors->any())
    <div class="mb-6 px-4 py-3.5 bg-red-500/8 border border-red-500/20 rounded-xl">
        @foreach($errors->all() as $err)
        <p class="text-red-300 text-sm flex items-center gap-2"><span>⚠️</span> {{ $err }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('menu.update', $item) }}" class="space-y-6">
        @csrf @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-stone-900/60 border border-stone-700/40 rounded-2xl p-6 space-y-5">
            <h3 class="text-stone-300 font-bold text-sm uppercase tracking-wider flex items-center gap-2">
                <span>📋</span> Basic Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Item Name *</label>
                    <input name="name" type="text" required value="{{ old('name', $item->name) }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Category / Type *</label>
                    <select name="type" required
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                        @foreach(\App\Enums\MenuItemType::cases() as $t)
                        <option value="{{ $t->value }}"
                            {{ old('type', $item->type->value) === $t->value ? 'selected' : '' }}>
                            {{ $t->icon() }} {{ $t->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Price (£) *</label>
                    <input name="price" type="number" step="0.01" min="0.01" required
                        value="{{ old('price', $item->price) }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all resize-none">{{ old('description', $item->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Prep Time (minutes)</label>
                    <input name="prep_time_minutes" type="number" min="1" max="180"
                        value="{{ old('prep_time_minutes', $item->prep_time_minutes) }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Calories (kcal)</label>
                    <input name="calories" type="number" min="0"
                        value="{{ old('calories', $item->calories) }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all"
                        placeholder="Optional">
                </div>
            </div>
        </div>

        {{-- Dietary & Allergens --}}
        <div class="bg-stone-900/60 border border-stone-700/40 rounded-2xl p-6 space-y-5">
            <h3 class="text-stone-300 font-bold text-sm uppercase tracking-wider flex items-center gap-2">
                <span>🌿</span> Dietary Information
            </h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach([
                    ['is_vegetarian', '🥬', 'Vegetarian'],
                    ['is_vegan',      '🌱', 'Vegan'],
                    ['is_gluten_free','🌾', 'Gluten Free'],
                ] as [$fname, $icon, $label])
                <label class="flex items-center gap-3 px-4 py-3 bg-stone-800/40 border border-stone-700/40 rounded-xl cursor-pointer hover:border-stone-600/60 transition-all">
                    <input type="checkbox" name="{{ $fname }}" value="1"
                        {{ old($fname, $item->$fname) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-stone-600 bg-stone-700 text-amber-500 focus:ring-amber-500/30 focus:ring-offset-0">
                    <span class="text-sm">{{ $icon }}</span>
                    <span class="text-stone-300 text-sm font-medium">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">Allergens</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach(['gluten','dairy','eggs','nuts','soy','shellfish','fish','sesame'] as $allergen)
                    <label class="flex items-center gap-2.5 px-3 py-2.5 bg-stone-800/40 border border-stone-700/40 rounded-xl cursor-pointer hover:border-stone-600/60 transition-all">
                        <input type="checkbox" name="allergens[]" value="{{ $allergen }}"
                            {{ in_array($allergen, old('allergens', $item->allergens ?? [])) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-stone-600 bg-stone-700 text-amber-500 focus:ring-amber-500/30 focus:ring-offset-0">
                        <span class="text-stone-300 text-xs font-medium capitalize">{{ $allergen }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Availability --}}
        <div class="bg-stone-900/60 border border-stone-700/40 rounded-2xl p-5">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-stone-200 font-semibold text-sm">Available on menu</p>
                    <p class="text-stone-500 text-xs mt-0.5">Uncheck to hide from order forms</p>
                </div>
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" value="1"
                    {{ old('is_available', $item->is_available) ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-stone-600 bg-stone-700 text-amber-500 focus:ring-amber-500/30 focus:ring-offset-0">
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-400 text-stone-950
                       font-bold text-sm rounded-xl transition-all hover:-translate-y-0.5
                       hover:shadow-[0_8px_20px_rgba(245,158,11,0.25)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
            <a href="{{ route('menu.index') }}"
                class="px-5 py-3 text-stone-400 hover:text-stone-200 text-sm font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
