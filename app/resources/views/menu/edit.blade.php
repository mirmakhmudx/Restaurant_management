@extends('layouts.app')
@section('title', 'Edit: ' . $item->name)
@section('page-title', 'Edit Menu Item')
@section('page-subtitle', 'Update "' . $item->name . '"')

@section('content')
<div class="max-w-2xl">

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

    <form method="POST" action="{{ route('menu.update', $item) }}" class="space-y-5">
        @csrf @method('PUT')

        {{-- Basic Information --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                Basic Information
            </h3>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Item name <span class="text-red-500">*</span></label>
                    <input name="name" type="text" required value="{{ old('name', $item->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                  bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select name="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                       bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                            @foreach(\App\Enums\MenuItemType::cases() as $t)
                            <option value="{{ $t->value }}" {{ old('type', $item->type->value) === $t->value ? 'selected' : '' }}>
                                {{ $t->icon() }} {{ $t->label() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Price (£) <span class="text-red-500">*</span></label>
                        <input name="price" type="number" step="0.01" min="0.01" required
                               value="{{ old('price', $item->price) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                     bg-white outline-none resize-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">{{ old('description', $item->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Prep time (minutes)</label>
                        <input name="prep_time_minutes" type="number" min="1" max="180"
                               value="{{ old('prep_time_minutes', $item->prep_time_minutes) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Calories (kcal)</label>
                        <input name="calories" type="number" min="0"
                               value="{{ old('calories', $item->calories) }}" placeholder="Optional"
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

            <div class="grid grid-cols-3 gap-3 mb-5">
                @foreach([
                    ['is_vegetarian', '🥬', 'Vegetarian'],
                    ['is_vegan',      '🌱', 'Vegan'],
                    ['is_gluten_free','🌾', 'Gluten Free'],
                ] as [$fname, $icon, $label])
                <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all">
                    <input type="checkbox" name="{{ $fname }}" value="1"
                           {{ old($fname, $item->$fname) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                    <span class="text-sm">{{ $icon }}</span>
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-3">Allergens</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['gluten','dairy','eggs','nuts','soy','shellfish','fish','sesame'] as $allergen)
                    <label class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all">
                        <input type="checkbox" name="allergens[]" value="{{ $allergen }}"
                               {{ in_array($allergen, old('allergens', $item->allergens ?? [])) ? 'checked' : '' }}
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
                    <p class="text-xs text-gray-500 mt-0.5">Uncheck to hide from order forms temporarily</p>
                </div>
                <div>
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1"
                           {{ old('is_available', $item->is_available) ? 'checked' : '' }}
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
            <a href="{{ route('menu.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
