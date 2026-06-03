@extends('layouts.app')
@section('title', 'Yangi Order')
@section('page-title', 'Yangi Order')
@section('page-subtitle', 'Stol va taomlarni tanlang')

@section('content')

<div x-data="{
    tableId: '',
    items: [],
    notes: '',
    search: '',
    showSearch: false,
    activeItem: null,
    showModifiers: false,
    menuItems: @js($menuItems->map(fn($m) => [
        'id'        => $m->id,
        'name'      => $m->name,
        'price'     => (float)$m->price,
        'type'      => $m->type->label(),
        'icon'      => $m->type->icon(),
        'available' => $m->is_available,
        'modifiers' => $m->activeModifiers->map(fn($mod) => [
            'id'    => $mod->id,
            'name'  => $mod->name,
            'price' => (float)$mod->price,
        ])->values()->toArray(),
    ])->values()),
    get filtered() {
        if (!this.search) return [];
        const q = this.search.toLowerCase();
        return this.menuItems.filter(i => i.available && i.name.toLowerCase().includes(q)).slice(0, 8);
    },
    get subtotal() {
        return this.items.reduce((s, i) => {
            const modTotal = i.selectedMods.reduce((ms, m) => ms + m.price, 0);
            return s + (i.price + modTotal) * i.qty;
        }, 0);
    },
    get totalStr() {
        return '£' + this.subtotal.toFixed(2);
    },
    selectItem(item) {
        if (item.modifiers && item.modifiers.length > 0) {
            this.activeItem = {...item, selectedMods: [], notes: ''};
            this.showModifiers = true;
            this.search = '';
            this.showSearch = false;
        } else {
            this.addItem({...item, selectedMods: [], notes: ''});
        }
    },
    confirmModifiers() {
        this.addItem(this.activeItem);
        this.activeItem = null;
        this.showModifiers = false;
    },
    toggleMod(mod) {
        const idx = this.activeItem.selectedMods.findIndex(m => m.id === mod.id);
        if (idx >= 0) {
            this.activeItem.selectedMods.splice(idx, 1);
        } else {
            this.activeItem.selectedMods.push(mod);
        }
    },
    isModSelected(mod) {
        return this.activeItem && this.activeItem.selectedMods.some(m => m.id === mod.id);
    },
    addItem(item) {
        const key = item.id + '_' + JSON.stringify(item.selectedMods.map(m=>m.id).sort());
        const ex  = this.items.find(i => i._key === key);
        if (ex) {
            ex.qty++;
        } else {
            this.items.push({...item, qty: 1, _key: key});
        }
    },
    removeItem(key) {
        this.items = this.items.filter(i => i._key !== key);
    },
    dec(item) {
        if (item.qty > 1) item.qty--;
        else this.removeItem(item._key);
    }
}" class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-on:click.away="showSearch = false">

    {{-- Modifier tanlash modal --}}
    <div x-show="showModifiers" style="display:none"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" x-on:click.stop>
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-gray-900" x-text="activeItem?.name"></p>
                        <p class="text-sm text-gray-500">
                            Asosiy: <span x-text="'£' + (activeItem?.price?.toFixed(2) ?? '0.00')"></span>
                        </p>
                    </div>
                    <button x-on:click="showModifiers=false; activeItem=null"
                            class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 mb-3">Extra variantlar:</p>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    <template x-for="mod in (activeItem?.modifiers ?? [])" :key="mod.id">
                        <label class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all"
                               x-bind:class="isModSelected(mod)
                                   ? 'border-gray-900 bg-gray-50'
                                   : 'border-gray-200 hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all flex-shrink-0"
                                     x-bind:class="isModSelected(mod) ? 'bg-gray-900 border-gray-900' : 'border-gray-300'">
                                    <svg x-show="isModSelected(mod)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-900 text-sm" x-text="mod.name"></span>
                            </div>
                            <span class="text-sm font-bold"
                                  x-bind:class="isModSelected(mod) ? 'text-gray-900' : 'text-green-600'"
                                  x-text="mod.price > 0 ? '+£' + mod.price.toFixed(2) : 'Bepul'"></span>
                            <input type="checkbox" class="hidden" x-on:change="toggleMod(mod)">
                        </label>
                    </template>
                </div>

                <div x-show="activeItem?.selectedMods?.length > 0" style="display:none"
                     class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Qo'shimcha:</span>
                        <span class="font-semibold text-gray-900"
                              x-text="'+£' + (activeItem?.selectedMods?.reduce((s,m)=>s+m.price,0)??0).toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="font-bold text-gray-900">Jami (1x):</span>
                        <span class="font-bold text-gray-900"
                              x-text="'£' + ((activeItem?.price??0) + (activeItem?.selectedMods?.reduce((s,m)=>s+m.price,0)??0)).toFixed(2)"></span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button x-on:click="showModifiers=false; activeItem=null"
                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        Bekor
                    </button>
                    <button x-on:click="confirmModifiers()"
                            class="flex-1 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                        ✓ Qo'shish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">

        @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
            @foreach($errors->all() as $e)
            <p class="text-red-700 text-sm">{{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form id="order-form" method="POST" action="{{ route('orders.store') }}">
            @csrf

            {{-- STOL --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">1. Stol tanlang</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Yashil = bo'sh · Qizil = band</p>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="table_id" value="" class="sr-only"
                                   x-on:change="tableId = ''" checked>
                            <div class="flex flex-col items-center p-3 rounded-xl border-2 transition-all hover:border-gray-400 text-center"
                                 x-bind:class="tableId === '' ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200'">
                                <span class="text-lg mb-0.5">🛍️</span>
                                <span class="text-xs font-semibold" x-bind:class="tableId === '' ? 'text-white' : 'text-gray-700'">Takeaway</span>
                            </div>
                        </label>
                        @foreach($tables as $t)
                        <label class="cursor-pointer {{ !$t->isAvailable() ? 'opacity-50':'' }}">
                            <input type="radio" name="table_id" value="{{ $t->id }}" class="sr-only"
                                   {{ !$t->isAvailable() ? 'disabled':'' }}
                                   x-on:change="tableId = '{{ $t->id }}'">
                            <div class="flex flex-col items-center p-3 rounded-xl border-2 transition-all text-center
                                        {{ $t->isAvailable() ? 'hover:border-gray-900' : 'cursor-not-allowed' }}"
                                 x-bind:class="tableId === '{{ $t->id }}' ? 'border-gray-900 bg-gray-900' : '{{ $t->isAvailable() ? 'border-gray-200' : 'border-gray-100 bg-gray-50' }}'">
                                <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-sm font-bold mb-0.5
                                            {{ $t->isAvailable() ? 'border-green-400' : 'border-gray-300' }}"
                                     x-bind:class="tableId === '{{ $t->id }}' ? 'border-white bg-white text-gray-900' : ''">
                                    {{ $t->number }}
                                </div>
                                <span class="text-xs font-semibold"
                                      x-bind:class="tableId === '{{ $t->id }}' ? 'text-white' : '{{ $t->isAvailable() ? 'text-gray-700' : 'text-gray-400' }}'">
                                    {{ $t->capacity }}p
                                </span>
                                @if(!$t->isAvailable())
                                <span class="text-xs text-red-400 leading-none">band</span>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- TAOMLAR --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">2. Taomlar qo'shing</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Modifierli taomlar uchun extra variantlar chiqadi</p>
                </div>
                <div class="p-5">
                    <div class="relative">
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="search"
                                   x-on:focus="showSearch = true"
                                   x-on:input="showSearch = true"
                                   placeholder="Taom nomini kiriting..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        </div>

                        <div x-show="showSearch && filtered.length > 0" style="display:none"
                             class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-20 overflow-hidden max-h-72 overflow-y-auto">
                            <template x-for="item in filtered" :key="item.id">
                                <button type="button"
                                        x-on:click="selectItem(item)"
                                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors text-left border-b border-gray-50 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <span x-text="item.icon" class="text-lg w-7"></span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900" x-text="item.name"></p>
                                            <div class="flex items-center gap-2">
                                                <p class="text-xs text-gray-400" x-text="item.type"></p>
                                                <span x-show="item.modifiers && item.modifiers.length > 0"
                                                      class="text-xs text-blue-500 font-medium"
                                                      x-text="'+ ' + item.modifiers.length + ' variant'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900" x-text="'£' + item.price.toFixed(2)"></span>
                                        <div class="w-6 h-6 bg-gray-900 rounded-lg flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="items.length > 0" style="display:none" class="mt-4 space-y-2">
                        <template x-for="(item, idx) in items" :key="item._key">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <span x-text="item.icon" class="text-lg flex-shrink-0"></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                                        <div class="flex flex-wrap gap-1 mt-0.5">
                                            <template x-for="mod in item.selectedMods" :key="mod.id">
                                                <span class="text-xs px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-md"
                                                      x-text="mod.name + (mod.price > 0 ? ' +£'+mod.price.toFixed(2) : '')"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" x-on:click="dec(item)"
                                                class="w-7 h-7 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 flex items-center justify-center text-gray-600 font-bold">−</button>
                                        <span class="w-6 text-center font-bold text-gray-900 text-sm" x-text="item.qty"></span>
                                        <button type="button" x-on:click="item.qty++"
                                                class="w-7 h-7 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 flex items-center justify-center text-gray-600 font-bold">+</button>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm w-16 text-right"
                                          x-text="'£' + ((item.price + item.selectedMods.reduce((s,m)=>s+m.price,0)) * item.qty).toFixed(2)"></span>
                                    <button type="button" x-on:click="removeItem(item._key)"
                                            class="text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>

                                    <input type="hidden" x-bind:name="'items[' + idx + '][id]'"           x-bind:value="item.id">
                                    <input type="hidden" x-bind:name="'items[' + idx + '][quantity]'"     x-bind:value="item.qty">
                                    <input type="hidden" x-bind:name="'items[' + idx + '][notes]'"        x-bind:value="item.notes ?? ''">
                                    <input type="hidden" x-bind:name="'items[' + idx + '][modifiers]'"    x-bind:value="JSON.stringify(item.selectedMods.map(m=>({id:m.id,name:m.name,price:m.price})))">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="items.length === 0" class="mt-4 text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                        Hali taom qo'shilmagan. Yuqoridan qidiring.
                    </div>
                </div>
            </div>

            {{-- IZOH --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <label class="block text-sm font-bold text-gray-900 mb-2">Izoh (ixtiyoriy)</label>
                <textarea name="notes" rows="2" x-model="notes"
                          placeholder="Allergiya, maxsus iltimos..."
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 bg-white outline-none resize-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all"></textarea>
            </div>
        </form>
    </div>

    {{-- ORDER SUMMARY --}}
    <div>
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden sticky top-6">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm">Order xulosasi</h3>
            </div>
            <div class="p-5">
                <div x-show="items.length === 0" class="text-center py-8">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272"/>
                    </svg>
                    <p class="text-sm text-gray-400">Taom qo'shing</p>
                </div>

                <div x-show="items.length > 0" style="display:none">
                    <div class="space-y-2 mb-4 max-h-52 overflow-y-auto">
                        <template x-for="item in items" :key="item._key">
                            <div class="text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-700 truncate flex-1"
                                          x-text="item.qty + '× ' + item.name"></span>
                                    <span class="font-semibold text-gray-900 ml-2 flex-shrink-0"
                                          x-text="'£' + ((item.price + item.selectedMods.reduce((s,m)=>s+m.price,0)) * item.qty).toFixed(2)"></span>
                                </div>
                                <template x-for="mod in item.selectedMods" :key="mod.id">
                                    <p class="text-xs text-blue-500 ml-4"
                                       x-text="'+ ' + mod.name + (mod.price > 0 ? ' (+£'+mod.price.toFixed(2)+')' : '')"></p>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-gray-200 pt-3 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Jami</span>
                            <span class="font-bold text-2xl text-gray-900" x-text="totalStr"></span>
                        </div>
                    </div>

                    <button type="submit" form="order-form"
                            class="w-full py-3.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Orderni yuborish
                    </button>
                </div>

                <a href="{{ route('orders.index') }}"
                   class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                    Bekor qilish
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
