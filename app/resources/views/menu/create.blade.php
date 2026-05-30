@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')
@section('page-subtitle', 'Create a new item using the Menu Item Factory')

@section('content')

<div class="max-w-2xl" x-data="{
    type: '{{ old('type') }}',
    imagePreview: null,
    prepMap: { starter:10, main_course:20, dessert:8, beverage:3 },
    get defaultPrep() { return this.prepMap[this.type] || 15; },
    handle(e) {
        const f = e.target.files[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = ev => this.imagePreview = ev.target.result;
        r.readAsDataURL(f);
    }
}">

    @if($errors->any())
    <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
        @foreach($errors->all() as $e)
        <p class="text-red-600 text-sm">{{ $e }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('menu.store') }}"
          enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- IMAGE --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Taom rasmi</h3>
            <div class="flex items-start gap-5">

                <div class="w-28 h-28 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center flex-shrink-0">
                    <img x-show="imagePreview"
                         x-bind:src="imagePreview"
                         style="display:none"
                         class="w-full h-full object-cover rounded-xl">
                    <div x-show="!imagePreview" class="text-center p-2">
                        <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <p class="text-xs text-gray-400">Rasm yo'q</p>
                    </div>
                </div>

                <div class="flex-1">
                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-all">
                        <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-600">Rasm yuklash</p>
                        <p class="text-xs text-gray-400 mt-0.5">PNG, JPG, WebP · Max 2MB</p>
                        <input type="file" name="image" accept="image/*"
                               class="hidden" x-on:change="handle($event)">
                    </label>
                </div>
            </div>
        </div>

        {{-- BASIC INFO --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                Basic Information
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Item name <span class="text-red-500">*</span>
                    </label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           placeholder="e.g. Grilled Salmon Fillet"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">
                            Category <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">— Factory Pattern</span>
                        </label>
                        <select name="type" required x-model="type"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                            <option value="" disabled selected>Kategoriya tanlang...</option>
                            @foreach(\App\Enums\MenuItemType::cases() as $t)
                            <option value="{{ $t->value }}" {{ old('type') === $t->value ? 'selected' : '' }}>
                                {{ $t->icon() }} {{ $t->label() }}
                            </option>
                            @endforeach
                        </select>
                        <p x-show="type" style="display:none"
                           class="text-xs text-gray-400 mt-1"
                           x-text="'Default prep: ' + defaultPrep + ' min'"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">
                            Price (£) <span class="text-red-500">*</span>
                        </label>
                        <input name="price" type="number" step="0.01" min="0.01" required
                               value="{{ old('price') }}" placeholder="0.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Describe the dish, ingredients, serving style..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 bg-white outline-none resize-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Prep time (min)</label>
                        <input name="prep_time_minutes" type="number" min="1" max="180"
                               value="{{ old('prep_time_minutes') }}"
                               x-bind:placeholder="defaultPrep"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Calories (kcal)</label>
                        <input name="calories" type="number" min="0"
                               value="{{ old('calories') }}" placeholder="Optional"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- DIETARY --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dietary & Allergens
            </h3>

            <div class="grid grid-cols-3 gap-3 mb-5">
                @foreach([['is_vegetarian','🥬','Vegetarian'],['is_vegan','🌱','Vegan'],['is_gluten_free','🌾','Gluten Free']] as [$n,$ic,$lb])
                <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all select-none">
                    <input type="checkbox" name="{{ $n }}" value="1"
                           {{ old($n) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                    <span>{{ $ic }}</span>
                    <span class="text-sm font-medium text-gray-700">{{ $lb }}</span>
                </label>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-3">Allergens</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['gluten','dairy','eggs','nuts','soy','shellfish','fish','sesame'] as $al)
                    <label class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all select-none">
                        <input type="checkbox" name="allergens[]" value="{{ $al }}"
                               {{ in_array($al, old('allergens', [])) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $al }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- AVAILABILITY --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <label class="flex items-center justify-between cursor-pointer select-none">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Available on menu</p>
                    <p class="text-xs text-gray-500 mt-0.5">Visible to waiters when taking orders</p>
                </div>
                <div>
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                </div>
            </label>
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add to Menu
            </button>
            <a href="{{ route('menu.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
