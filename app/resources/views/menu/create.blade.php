@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')
@section('page-subtitle', 'Create a new item using the Menu Item Factory')

@section('content')
<div class="max-w-2xl" x-data="{
    type: '',
    prepTimes: { starter: 10, main_course: 20, dessert: 8, beverage: 3 },
    get defaultPrep() { return this.prepTimes[this.type] || 15; }
}">

    {{-- Errors --}}
    @if($errors->any())
    <div class="mb-6 px-4 py-3.5 bg-red-500/8 border border-red-500/20 rounded-xl">
        @foreach($errors->all() as $err)
        <p class="text-red-300 text-sm flex items-center gap-2"><span>⚠️</span> {{ $err }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('menu.store') }}" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-stone-900/60 border border-stone-700/40 rounded-2xl p-6 space-y-5">
            <h3 class="text-stone-300 font-bold text-sm uppercase tracking-wider flex items-center gap-2">
                <span>📋</span> Basic Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Item Name *</label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all"
                        placeholder="e.g. Grilled Salmon Fillet">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">
                        Category / Type * <span class="text-stone-600 normal-case font-normal">(used by Factory Pattern)</span>
                    </label>
                    <select name="type" required
                        x-model="type"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                        <option value="" disabled selected>Select type...</option>
                        @foreach(\App\Enums\MenuItemType::cases() as $t)
                        <option value="{{ $t->value }}" {{ old('type') === $t->value ? 'selected' : '' }}>
                            {{ $t->icon() }} {{ $t->label() }}
                        </option>
                        @endforeach
                    </select>
                    <p x-show="type" class="text-xs text-stone-600 mt-1.5"
                        x-text="'Factory will apply ' + type + ' defaults (prep time: ' + defaultPrep + 'min)'">
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Price (£) *</label>
                    <input name="price" type="number" step="0.01" min="0.01" required
                        value="{{ old('price') }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all"
                        placeholder="0.00">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all resize-none"
                        placeholder="Describe the dish, ingredients, or serving style...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Prep Time (minutes)</label>
                    <input name="prep_time_minutes" type="number" min="1" max="180"
                        x-bind:placeholder="defaultPrep"
                        value="{{ old('prep_time_minutes') }}"
                        class="w-full px-4 py-3 bg-stone-800/60 border border-stone-700/60 rounded-xl
                               text-stone-100 placeholder-stone-500 text-sm outline-none
                               focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">Calories (kcal)</label>
                    <input name="calories" type="number" min="0"
                        value="{{ old('calories') }}"
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
                ] as [$name, $icon, $label])
                <label class="flex items-center gap-3 px-4 py-3 bg-stone-800/40 border border-stone-700/40
                              rounded-xl cursor-pointer hover:border-stone-600/60 transition-all group">
                    <input type="checkbox" name="{{ $name }}" value="1"
                        {{ old($name) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-stone-600 bg-stone-700 text-amber-500
                               focus:ring-amber-500/30 focus:ring-offset-0">
                    <span class="text-sm">{{ $icon }}</span>
                    <span class="text-stone-300 text-sm font-medium">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">Allergens</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach(['gluten','dairy','eggs','nuts','soy','shellfish','fish','sesame'] as $allergen)
                    <label class="flex items-center gap-2.5 px-3 py-2.5 bg-stone-800/40 border border-stone-700/40
                                  rounded-xl cursor-pointer hover:border-stone-600/60 transition-all">
                        <input type="checkbox" name="allergens[]" value="{{ $allergen }}"
                            {{ in_array($allergen, old('allergens', [])) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-stone-600 bg-stone-700 text-amber-500
                                   focus:ring-amber-500/30 focus:ring-offset-0">
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
                    <p class="text-stone-500 text-xs mt-0.5">Item will be visible to waiters when taking orders</p>
                </div>
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" value="1" checked
                    class="w-5 h-5 rounded border-stone-600 bg-stone-700 text-amber-500
                           focus:ring-amber-500/30 focus:ring-offset-0">
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
                Add to Menu
            </button>
            <a href="{{ route('menu.index') }}"
                class="px-5 py-3 text-stone-400 hover:text-stone-200 text-sm font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
