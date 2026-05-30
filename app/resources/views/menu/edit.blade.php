cat > app/resources/views/menu/edit.blade.php << 'ENDOFFILE'
@extends('layouts.app')
@section('title', 'Edit: ' . $item->name)
@section('page-title', 'Edit Menu Item')
@section('page-subtitle', 'Update "' . $item->name . '"')

@section('content')

    <div class="max-w-2xl" x-data="{
    imagePreview: null,
    currentImage: '{{ $item->getImageUrl() ?? '' }}',
    removeImage: false,
    handle(e) {
        const f = e.target.files[0];
        if (!f) return;
        this.removeImage = false;
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

        <form method="POST" action="{{ route('menu.update', $item) }}"
              enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            {{-- IMAGE --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="font-bold text-gray-900 text-sm mb-4">Taom rasmi</h3>
                <div class="flex items-start gap-5">

                    <div class="w-28 h-28 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center flex-shrink-0">
                        <img x-show="imagePreview"
                             x-bind:src="imagePreview"
                             style="display:none"
                             class="w-full h-full object-cover">
                        <img x-show="!imagePreview && currentImage && !removeImage"
                             x-bind:src="currentImage"
                             class="w-full h-full object-cover">
                        <div x-show="!imagePreview && (!currentImage || removeImage)" class="text-center p-2">
                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                            <p class="text-xs text-gray-400">Rasm yo'q</p>
                        </div>
                    </div>

                    <div class="flex-1 space-y-2">
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-all">
                            <svg class="w-5 h-5 text-gray-400 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-600">
                                {{ $item->hasImage() ? 'Rasmni almashtirish' : 'Rasm yuklash' }}
                            </p>
                            <p class="text-xs text-gray-400">PNG, JPG, WebP · Max 2MB</p>
                            <input type="file" name="image" accept="image/*"
                                   class="hidden" x-on:change="handle($event)">
                        </label>

                        @if($item->hasImage())
                            <div>
                                <input type="hidden" name="remove_image" x-bind:value="removeImage ? '1' : '0'">
                                <button type="button"
                                        x-on:click="removeImage=true; imagePreview=null; currentImage=null"
                                        x-show="!removeImage"
                                        class="text-xs text-red-500 hover:text-red-700 transition-colors">
                                    🗑 Rasmni o'chirish
                                </button>
                                <p x-show="removeImage" style="display:none" class="text-xs text-amber-600">
                                    ⚠ Saqlanganda rasm o'chiriladi
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

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
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Category <span class="text-red-500">*</span></label>
                            <select name="type" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
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
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none resize-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">{{ old('description', $item->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Prep time (minutes)</label>
                            <input name="prep_time_minutes" type="number" min="1" max="180"
                                   value="{{ old('prep_time_minutes', $item->prep_time_minutes) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Calories (kcal)</label>
                            <input name="calories" type="number" min="0"
                                   value="{{ old('calories', $item->calories) }}" placeholder="Optional"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
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
            {{-- MODIFIERS --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Extra variantlar (Modifiers)</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Extra cheese, sauce, topping...</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $item->modifiers->count() }} ta</span>
                </div>

                {{-- Mavjud modifierlar --}}
                @if($item->modifiers->isNotEmpty())
                    <div class="divide-y divide-gray-50">
                        @foreach($item->modifiers as $mod)
                            <div class="flex items-center justify-between px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $mod->is_available ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                    <span class="font-medium text-gray-900 text-sm">{{ $mod->name }}</span>
                                    @if($mod->price > 0)
                                        <span class="text-xs text-green-600 font-semibold">+£{{ number_format($mod->price, 2) }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">Bepul</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <form method="POST" action="{{ route('menu.modifiers.toggle', $mod) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs px-2 py-1 border rounded-lg transition-all
                        {{ $mod->is_available ? 'border-green-200 text-green-600 hover:bg-green-50' : 'border-gray-200 text-gray-400 hover:bg-gray-50' }}">
                                            {{ $mod->is_available ? 'Faol' : 'O\'chiq' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('menu.modifiers.destroy', $mod) }}"
                                          onsubmit="return confirm('O\'chirasizmi?')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Yangi modifier qo'shish --}}
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-600 mb-3">Yangi qo'shish:</p>
                    <form method="POST" action="{{ route('menu.modifiers.store', $item) }}"
                          class="flex items-end gap-2 flex-wrap">
                        @csrf
                        <div class="flex-1 min-w-40">
                            <label class="block text-xs text-gray-500 mb-1">Nomi *</label>
                            <input name="name" type="text" required placeholder="Extra Cheese, BBQ Sauce..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900 bg-white">
                        </div>
                        <div class="w-28">
                            <label class="block text-xs text-gray-500 mb-1">Narx (£)</label>
                            <input name="price" type="number" step="0.01" min="0" value="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900 bg-white text-center">
                        </div>
                        <button type="submit"
                                class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-lg transition-colors whitespace-nowrap">
                            + Qo'shish
                        </button>
                    </form>

                    {{-- Tez tanlash --}}
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        <span class="text-xs text-gray-400">Tez:</span>
                        @foreach([['Extra Cheese',1.50],['Bacon',2.00],['BBQ Sauce',0.50],['Spicy',0],['No Onion',0],['Large Portion',3.00]] as [$n,$p])
                            <button type="button"
                                    onclick="
                        this.closest('.bg-gray-50').querySelector('[name=name]').value='{{ $n }}';
                        this.closest('.bg-gray-50').querySelector('[name=price]').value='{{ $p }}';
                    "
                                    class="text-xs px-2 py-1 bg-white border border-gray-200 hover:border-gray-400 text-gray-600 rounded-lg transition-colors">
                                {{ $n }}{{ $p > 0 ? ' +£'.$p : '' }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('menu.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection

