@extends('layouts.app')
@section('title', 'Menu')
@section('page-title', 'Menu')
@section('page-subtitle', "Restoran taomlar katalogi — Factory · Repository · Decorator")

@section('content')

{{-- TOP BAR --}}
<div class="flex items-center gap-3 mb-5">
    <form method="GET" action="{{ route('menu.index') }}" class="relative flex-1 max-w-xs">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Taom qidirish..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
    </form>

    <span class="text-sm text-gray-400">{{ $items->count() }} ta taom</span>

    <div class="flex-1"></div>

    @if(auth()->user()->isManager())
    <a href="{{ route('menu.categories.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
        </svg>
        Kategoriyalar
    </a>
    <a href="{{ route('menu.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Taom qo'shish
    </a>
    @endif
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('menu.index') }}" id="menuFilter">
    <input type="hidden" name="search" value="{{ $search }}">
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="bg-white border border-gray-200 rounded-2xl mb-5 overflow-hidden">

        {{-- Tabs --}}
        <div class="flex items-center gap-0 border-b border-gray-100 overflow-x-auto">
            @php
            $typeMap = ['starter'=>'starter','main_course'=>'main_course','dessert'=>'dessert','beverage'=>'beverage'];
            @endphp
            <a href="{{ route('menu.index', ['search'=>$search,'type'=>'all','available'=>$available,'price_min'=>$priceMin,'price_max'=>$priceMax,'dietary'=>$dietary]) }}"
               class="flex items-center gap-1.5 px-4 py-3.5 text-sm font-medium transition-all whitespace-nowrap border-b-2
               {{ $type==='all' ? 'border-gray-900 text-gray-900 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                📋 Barchasi
                <span class="text-xs font-bold {{ $type==='all' ? 'text-gray-700':'text-gray-400' }}">{{ $items->count() }}</span>
            </a>
            @foreach(\App\Enums\MenuItemType::cases() as $t)
            @php $cnt = $items->filter(fn($i)=>$i->type->value===$t->value)->count(); @endphp
            @if($cnt > 0)
            <a href="{{ route('menu.index', ['search'=>$search,'type'=>$t->value,'available'=>$available,'price_min'=>$priceMin,'price_max'=>$priceMax,'dietary'=>$dietary]) }}"
               class="flex items-center gap-1.5 px-4 py-3.5 text-sm font-medium transition-all whitespace-nowrap border-b-2
               {{ $type===$t->value ? 'border-gray-900 text-gray-900 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                {{ $t->icon() }} {{ $t->label() }}
                <span class="text-xs font-bold {{ $type===$t->value ? 'text-gray-700':'text-gray-400' }}">{{ $cnt }}</span>
            </a>
            @endif
            @endforeach
            @foreach($categories->whereNotIn('slug', array_values($typeMap)) as $cat)
            <a href="{{ route('menu.index', ['search'=>$search,'type'=>'cat_'.$cat->id]) }}"
               class="flex items-center gap-1.5 px-4 py-3.5 text-sm font-medium transition-all whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
            @endforeach
        </div>

        {{-- Filter controls --}}
        <div class="flex items-center gap-4 px-4 py-3 flex-wrap">
            {{-- Narx --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-gray-500 whitespace-nowrap">💰 Narx:</span>
                <div class="flex items-center gap-1.5">
                    <input type="number" name="price_min" value="{{ $priceMin }}" min="0"
                           placeholder="0" onchange="document.getElementById('menuFilter').submit()"
                           class="w-20 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-center outline-none focus:border-gray-900 bg-white">
                    <span class="text-gray-400 text-xs">—</span>
                    <input type="number" name="price_max" value="{{ $priceMax }}" min="0"
                           placeholder="∞" onchange="document.getElementById('menuFilter').submit()"
                           class="w-20 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-center outline-none focus:border-gray-900 bg-white">
                    <span class="text-xs text-gray-400">£</span>
                </div>
            </div>

            <div class="w-px h-5 bg-gray-200"></div>

            {{-- Mavjudlik --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-gray-500">Holat:</span>
                <div class="flex items-center gap-1">
                    @foreach(['all'=>'Barchasi','yes'=>'✅ Mavjud','no'=>'❌ Yo\'q'] as $val => $lbl)
                    <label class="flex items-center gap-1 px-2.5 py-1 rounded-lg cursor-pointer text-xs font-medium transition-all border
                        {{ $available===$val ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" name="available" value="{{ $val }}"
                               {{ $available===$val ? 'checked':'' }}
                               onchange="document.getElementById('menuFilter').submit()"
                               class="sr-only">
                        {{ $lbl }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="w-px h-5 bg-gray-200"></div>

            {{-- Dietary --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-gray-500">Dietary:</span>
                <div class="flex items-center gap-1">
                    @foreach(['vegetarian'=>['🥬','Vegetarian'],'vegan'=>['🌱','Vegan'],'gluten_free'=>['🌾','GF']] as $d => [$ic,$lbl])
                    <label class="flex items-center gap-1 px-2.5 py-1 rounded-lg cursor-pointer text-xs font-medium transition-all border
                        {{ in_array($d,$dietary) ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <input type="checkbox" name="dietary[]" value="{{ $d }}"
                               {{ in_array($d,$dietary) ? 'checked':'' }}
                               onchange="document.getElementById('menuFilter').submit()"
                               class="sr-only">
                        {{ $ic }} {{ $lbl }}
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Tozalash --}}
            @php
            $hasFilter = $priceMin||$priceMax||$available!=='all'||count($dietary)>0;
            @endphp
            @if($hasFilter)
            <div class="w-px h-5 bg-gray-200"></div>
            <a href="{{ route('menu.index', ['search'=>$search,'type'=>$type]) }}"
               class="flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tozalash
            </a>
            @endif

            <div class="ml-auto text-xs text-gray-400">
                {{ $items->count() }} natija
            </div>
        </div>
    </div>
</form>

{{-- MENU ITEMS --}}
@if($items->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center bg-white border border-gray-200 rounded-2xl">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4 text-3xl">
        {{ $search ? '🔍' : '🍽️' }}
    </div>
    <p class="font-bold text-gray-900 mb-1">Taom topilmadi</p>
    <p class="text-sm text-gray-400 mb-5">
        {{ $search ? 'Boshqa so\'z bilan qidiring' : 'Filterni o\'zgartiring yoki tozalang' }}
    </p>
    @if(auth()->user()->isManager() && !$search && !$hasFilter)
    <a href="{{ route('menu.create') }}"
       class="px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-colors">
        Taom qo'shish
    </a>
    @endif
</div>
@else
@foreach($grouped as $typeValue => $typeItems)
@php $menuType = \App\Enums\MenuItemType::from($typeValue); @endphp
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="text-xl">{{ $menuType->icon() }}</span>
        <h3 class="font-bold text-gray-900">{{ $menuType->label() }}</h3>
        <span class="text-sm text-gray-400">{{ $typeItems->count() }} ta</span>
        <div class="flex-1 h-px bg-gray-200 ml-1"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
        @foreach($typeItems as $item)
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex flex-col hover:shadow-md transition-all group"
             x-data="{ available: {{ $item->is_available ? 'true':'false' }}, saving: false }">

            @if($item->hasImage())
            <div class="relative h-40 overflow-hidden bg-gray-100">
                <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div x-show="!available" class="absolute inset-0 bg-gray-900/60 flex items-center justify-center">
                    <span class="text-white text-xs font-bold px-2 py-1 bg-gray-900/80 rounded-lg">Mavjud emas</span>
                </div>
            </div>
            @else
            <div class="h-28 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative">
                <span class="text-5xl opacity-20">{{ $menuType->icon() }}</span>
                <div x-show="!available" class="absolute inset-0 bg-gray-100/70 flex items-center justify-center">
                    <span class="text-xs text-gray-400 font-medium">Mavjud emas</span>
                </div>
            </div>
            @endif

            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-lg border {{ $item->type->badgeClasses() }}">
                        {{ $item->type->icon() }} {{ $item->type->label() }}
                    </span>
                    @if(auth()->user()->isManager())
                    <button type="button"
                        x-on:click="saving=true; fetch('{{ route('menu.toggle', $item) }}', {
                            method:'PATCH',
                            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
                        }).then(r=>r.json()).then(d=>{available=d.is_available; saving=false;}).catch(()=>saving=false)"
                        class="flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full border transition-all cursor-pointer"
                        x-bind:class="available ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-gray-100 text-gray-500 border-gray-200'">
                        <span x-show="!saving">
                            <span x-show="available" class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Faol
                            </span>
                            <span x-show="!available" style="display:none" class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>O'chiq
                            </span>
                        </span>
                        <svg x-show="saving" style="display:none" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                    @else
                    <span class="flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full border
                        {{ $item->is_available ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $item->is_available ? 'bg-green-500':'bg-gray-400' }}"></span>
                        {{ $item->is_available ? 'Faol' : "O'chiq" }}
                    </span>
                    @endif
                </div>

                <h4 class="font-bold text-gray-900 mb-0.5">{{ $item->name }}</h4>
                @if($item->description)
                <p class="text-xs text-gray-500 line-clamp-2 mb-2 flex-1">{{ $item->description }}</p>
                @else
                <div class="flex-1 mb-2"></div>
                @endif

                @if($item->getDietaryLabels())
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($item->getDietaryLabels() as $lbl)
                    <span class="text-xs px-1.5 py-0.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                        {{ $lbl['icon'] }} {{ $lbl['label'] }}
                    </span>
                    @endforeach
                </div>
                @endif

                <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                    <span>⏱ {{ $item->prep_time_minutes }}min</span>
                    @if($item->calories)<span>· {{ $item->calories }} kcal</span>@endif
                    @if($item->allergens && count($item->allergens)>0)
                    <span class="text-orange-400">· ⚠ {{ count($item->allergens) }}</span>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="font-bold text-gray-900 text-lg">{{ $item->getFormattedPrice() }}</span>
                    @if(auth()->user()->isManager())
                    <div class="flex items-center gap-1">
                        <a href="{{ route('menu.edit', $item) }}"
                           class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                        </a>
                        <a href="{{ route('menu.modifiers.index', $item) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                           title="Modifierlar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('menu.destroy', $item) }}"
                              x-on:submit.prevent="if(confirm('{{ addslashes($item->name) }} ni o\'chirasizmi?')) $el.submit()">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif

@endsection
