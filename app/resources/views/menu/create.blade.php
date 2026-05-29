@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')
@section('page-subtitle', 'Create a new item using the Menu Item Factory')

@section('content')
<div class="max-w-2xl" x-data="{
    type: '{{ old('type') }}',
    prepTimes: { starter: 10, main_course: 20, dessert: 8, beverage: 3 },
    get defaultPrep() { return this.prepTimes[this.type] || 15; }
}">

    @if($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
        @foreach($errors->all() as $e)
        <p class="text-red-600 text-sm flex items-center gap-2">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ $e }}
        </p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('menu.store') }}" class="space-y-5">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                Basic Information
            </h3>

            <div class="grid grid-cols-1 gap-5">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Item name <span class="text-red-500">*</span></label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           placeholder="e.g. Grilled Salmon Fillet"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                  placeholder-gray-400 bg-white outline-none
                                  focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Type --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">
                            Category <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal">(Factory Pattern)</span>
                        </label>
                        <select name="type" required x-model="type"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                       bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                            <option value="" disabled selected>Select category...</option>
                            @foreach(\App\Enums\MenuItemType::cases() as $t)
                            <option value="{{ $t->value }}" {{ old('type') === $t->value ? 'selected' : '' }}>
                                {{ $t->icon() }} {{ $t->label() }}
                            </option>
                            @endforeach
                        </select>
                        <p x-show="type" class="text-xs text-gray-400 mt-1"
                           x-text="'Factory default prep time: ' + defaultPrep + ' min'"></p>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Price (£) <span class="text-red-500">*</span></label>
                        <input name="price" type="number" step="0.01" min="0.01" required
                               value="{{ old('price') }}" placeholder="0.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3" placeholder="Describe the dish, ingredients, or serving style..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                     placeholder-gray-400 bg-white outline-none resize-none
                                     focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Prep time --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Prep time (minutes)</label>
                        <input name="prep_time_minutes" type="number" min="1" max="180"
                               value="{{ old('prep_time_minutes') }}"
                               x-bind:placeholder="defaultPrep"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>

                    {{-- Calories --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Calories (kcal)</label>
                        <input name="calories" type="number" min="0"
                               value="{{ old('calories') }}" placeholder="Optional"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Dietary & Allergens --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dietary Information
            </h3>

            {{-- Dietary options --}}
            <div class="grid grid-cols-3 gap-3 mb-5">
                @foreach([
                    ['is_vegetarian', '🥬', 'Vegetarian'],
                    ['is_vegan',      '🌱', 'Vegan'],
                    ['is_gluten_free','🌾', 'Gluten Free'],
                ] as [$fname, $icon, $label])
                <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all">
                    <input type="checkbox" name="{{ $fname }}" value="1"
                           {{ old($fname) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                    <span class="text-sm">{{ $icon }}</span>
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            {{-- Allergens --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-3">Allergens</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['gluten','dairy','eggs','nuts','soy','shellfish','fish','sesame'] as $allergen)
                    <label class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all">
                        <input type="checkbox" name="allergens[]" value="{{ $allergen }}"
                               {{ in_array($allergen, old('allergens', [])) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $allergen }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Availability --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Available on menu</p>
                    <p class="text-xs text-gray-500 mt-0.5">Item will be visible to waiters when taking orders</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                </div>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800
                           text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add to Menu
            </button>
            <a href="{{ route('menu.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100
                      rounded-lg transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
