@extends('layouts.app')
@section('title', 'Menu')
@section('page-title', 'Menu')
@section('page-subtitle', 'Manage your restaurant\'s full menu catalogue')

@section('content')

{{-- Top bar: title + Add button --}}
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-gray-500">{{ $items->count() }} {{ $items->count() === 1 ? 'item' : 'items' }}</span>
    </div>

    <a href="{{ route('menu.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800
              text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Add Item
    </a>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('menu.index') }}" class="relative mb-5">
    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Search by name or description..."
           class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900
                  placeholder-gray-400 bg-white outline-none
                  focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
    @if($search)
    <a href="{{ route('menu.index') }}"
       class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </a>
    @endif
</form>

{{-- Category tabs --}}
@php
$tabs = [
    'all'         => ['label'=>'All',       'count'=> $items->count()],
    'starter'     => ['label'=>'Starters',  'count'=> $items->filter(fn($i)=>$i->type->value==='starter')->count()],
    'main_course' => ['label'=>'Mains',     'count'=> $items->filter(fn($i)=>$i->type->value==='main_course')->count()],
    'dessert'     => ['label'=>'Desserts',  'count'=> $items->filter(fn($i)=>$i->type->value==='dessert')->count()],
    'beverage'    => ['label'=>'Beverages', 'count'=> $items->filter(fn($i)=>$i->type->value==='beverage')->count()],
];
@endphp
<div class="flex items-center gap-1 mb-6 bg-gray-100 p-1 rounded-lg w-fit">
    @foreach($tabs as $val => $meta)
    <a href="{{ route('menu.index', ['search'=>$search, 'type'=>$val]) }}"
       class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-all whitespace-nowrap
       {{ $type === $val
           ? 'bg-white text-gray-900 shadow-sm'
           : 'text-gray-500 hover:text-gray-700' }}">
        {{ $meta['label'] }}
        <span class="text-xs {{ $type === $val ? 'text-gray-700' : 'text-gray-400' }}">
            {{ $meta['count'] }}
        </span>
    </a>
    @endforeach
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Empty state --}}
@if($items->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
        </svg>
    </div>
    <p class="font-semibold text-gray-900 mb-1">No items found</p>
    <p class="text-sm text-gray-500 mb-5">{{ $search ? 'Try a different search term' : 'Add your first menu item' }}</p>
    @if(!$search)
    <a href="{{ route('menu.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Add First Item
    </a>
    @endif
</div>

@else

{{-- Items grouped by type --}}
@foreach($grouped as $typeValue => $typeItems)
@php $menuType = \App\Enums\MenuItemType::from($typeValue); @endphp

<div class="mb-8">
    <div class="flex items-center gap-2 mb-4">
        <span class="text-base">{{ $menuType->icon() }}</span>
        <h3 class="font-semibold text-gray-900 text-sm">{{ $menuType->label() }}</h3>
        <span class="text-xs text-gray-400">{{ $typeItems->count() }} {{ $typeItems->count() === 1 ? 'item' : 'items' }}</span>
        <div class="flex-1 h-px bg-gray-200 ml-1"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
        @foreach($typeItems as $item)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition-shadow group"
             x-data="{ available: {{ $item->is_available ? 'true' : 'false' }}, saving: false }">

            {{-- Image or placeholder --}}
            @if($item->hasImage())
            <div class="relative h-40 overflow-hidden bg-gray-100">
                <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                {{-- Availability overlay --}}
                @if(!$item->is_available)
                <div class="absolute inset-0 bg-gray-900/50 flex items-center justify-center">
                    <span class="text-white text-xs font-semibold px-2 py-1 bg-gray-900/80 rounded-md">Unavailable</span>
                </div>
                @endif
            </div>
            @else
            <div class="h-32 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative overflow-hidden">
                <span class="text-5xl opacity-30">{{ $menuType->icon() }}</span>
                @if(!$item->is_available)
                <div class="absolute inset-0 bg-gray-50/60 flex items-center justify-center">
                    <span class="text-xs font-medium text-gray-400">Unavailable</span>
                </div>
                @endif
            </div>
            @endif

            <div class="p-4 flex flex-col flex-1">
                {{-- Type badge + availability --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium border {{ $item->type->badgeClasses() }}">
                        {{ $item->type->icon() }} {{ $item->type->label() }}
                    </span>

                    @if(auth()->user()->isManager())
                    <button type="button"
                        x-on:click="saving=true; fetch('{{ route('menu.toggle', $item) }}', { method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} }).then(r=>r.json()).then(d=>{ available=d.is_available; saving=false; }).catch(()=>saving=false)"
                        class="flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full transition-colors cursor-pointer border"
                        x-bind:class="available ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200'">
                        <span x-show="!saving">
                            <span x-show="available" class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>On
                            </span>
                            <span x-show="!available" style="display:none" class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Off
                            </span>
                        </span>
                        <svg x-show="saving" style="display:none" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                    @else
                    <span class="flex items-center gap-1 text-xs px-2 py-0.5 rounded-full border
                        {{ $item->is_available ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $item->is_available ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $item->is_available ? 'On' : 'Off' }}
                    </span>
                    @endif
                </div>

                {{-- Name + Description --}}
                <h4 class="font-semibold text-gray-900 text-sm mb-0.5">{{ $item->name }}</h4>
                @if($item->description)
                <p class="text-xs text-gray-500 line-clamp-2 flex-1 mb-2">{{ $item->description }}</p>
                @else
                <div class="flex-1 mb-2"></div>
                @endif

                {{-- Dietary --}}
                @if($item->getDietaryLabels())
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($item->getDietaryLabels() as $lbl)
                    <span class="text-xs px-1.5 py-0.5 bg-gray-50 border border-gray-200 rounded text-gray-600">
                        {{ $lbl['icon'] }} {{ $lbl['label'] }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Meta --}}
                <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                    <span class="flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $item->prep_time_minutes }}min
                    </span>
                    @if($item->calories)<span>· {{ $item->calories }} kcal</span>@endif
                    @if($item->allergens && count($item->allergens) > 0)
                    <span class="text-orange-400">· ⚠ {{ count($item->allergens) }}</span>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-2.5 border-t border-gray-100">
                    <span class="font-bold text-gray-900">{{ $item->getFormattedPrice() }}</span>
                    @if(auth()->user()->isManager())
                    <div class="flex items-center gap-0.5">
                        <a href="{{ route('menu.edit', $item) }}"
                           class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('menu.destroy', $item) }}"
                              x-on:submit.prevent="if(confirm('Delete \'{{ addslashes($item->name) }}\'?')) $el.submit()">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
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
