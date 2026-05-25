@extends('layouts.app')
@section('title', 'Menu')
@section('page-title', 'Menu Management')
@section('page-subtitle', 'Manage your restaurant\'s full menu catalogue')

@section('content')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6"
    x-data="{ search: '{{ $search }}', type: '{{ $type }}' }">

    <div class="flex items-center gap-3 flex-1 w-full sm:w-auto">
        {{-- Search --}}
        <form method="GET" action="{{ route('menu.index') }}" class="flex-1 sm:w-80">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-500"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search menu items..."
                    class="w-full pl-10 pr-4 py-2.5 bg-stone-800/60 border border-stone-700/60 rounded-xl
                           text-stone-100 placeholder-stone-500 text-sm outline-none
                           focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/10">
                @if($search)
                <a href="{{ route('menu.index') }}"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-500 hover:text-stone-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    @if(auth()->user()->isManager())
    <a href="{{ route('menu.create') }}"
        class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-stone-950
               font-bold text-sm rounded-xl transition-all hover:-translate-y-0.5
               hover:shadow-[0_8px_20px_rgba(245,158,11,0.25)] flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add Item
    </a>
    @endif
</div>

{{-- Type filter tabs --}}
<div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
    @foreach([
        ['all',         'All',         '🍴', $items->count()],
        ['starter',     'Starters',    '🥗', $items->filter(fn($i)=>$i->type->value==='starter')->count()],
        ['main_course', 'Mains',       '🍽️', $items->filter(fn($i)=>$i->type->value==='main_course')->count()],
        ['dessert',     'Desserts',    '🍰', $items->filter(fn($i)=>$i->type->value==='dessert')->count()],
        ['beverage',    'Beverages',   '🥤', $items->filter(fn($i)=>$i->type->value==='beverage')->count()],
    ] as [$val, $label, $icon, $count])
    <a href="{{ route('menu.index', ['search' => $search, 'type' => $val]) }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all
        {{ $type === $val
            ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30'
            : 'text-stone-500 hover:text-stone-300 border border-transparent hover:border-stone-700' }}">
        <span>{{ $icon }}</span>
        {{ $label }}
        <span class="text-xs px-1.5 py-0.5 rounded-full {{ $type === $val ? 'bg-amber-500/20 text-amber-300' : 'bg-stone-800 text-stone-500' }}">
            {{ $count }}
        </span>
    </a>
    @endforeach
</div>

{{-- Flash message --}}
@if(session('success'))
<div class="mb-5 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-300 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Empty state --}}
@if($items->isEmpty())
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="text-5xl mb-4">🍴</div>
    <p class="text-stone-300 font-semibold text-lg">No menu items found</p>
    <p class="text-stone-600 text-sm mt-1">
        {{ $search ? 'Try a different search term' : 'Start by adding your first menu item' }}
    </p>
    @if(auth()->user()->isManager() && !$search)
    <a href="{{ route('menu.create') }}"
        class="mt-6 px-5 py-2.5 bg-amber-500 text-stone-950 font-bold text-sm rounded-xl
               hover:bg-amber-400 transition-all">
        + Add First Item
    </a>
    @endif
</div>

@else

{{-- Menu Grid --}}
@foreach($grouped as $typeValue => $typeItems)
@php $menuType = \App\Enums\MenuItemType::from($typeValue); @endphp

<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="text-xl">{{ $menuType->icon() }}</span>
        <h3 class="text-stone-300 font-bold text-base">{{ $menuType->label() }}</h3>
        <span class="text-xs text-stone-600 font-medium">({{ $typeItems->count() }} items)</span>
        <div class="flex-1 h-px bg-stone-800 ml-2"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
        @foreach($typeItems as $item)
        <div class="group bg-stone-900/60 border border-stone-700/40 rounded-2xl p-4
                    hover:border-stone-600/60 transition-all hover:-translate-y-0.5
                    hover:shadow-[0_8px_30px_rgba(0,0,0,0.3)] flex flex-col"
            x-data="{ available: {{ $item->is_available ? 'true' : 'false' }}, loading: false }">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $item->type->badgeClasses() }}">
                    {{ $item->type->icon() }} {{ $item->type->label() }}
                </span>

                {{-- Availability toggle (Manager only) --}}
                @if(auth()->user()->isManager())
                <button
                    x-on:click="
                        loading = true;
                        fetch('{{ route('menu.toggle', $item) }}', {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(d => { available = d.is_available; loading = false; })
                        .catch(() => loading = false)
                    "
                    x-bind:class="available ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/25'
                                            : 'bg-stone-800 text-stone-500 border-stone-700 hover:bg-stone-700'"
                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border transition-all">
                    <span x-show="!loading">
                        <span x-show="available">● On</span>
                        <span x-show="!available" style="display:none">○ Off</span>
                    </span>
                    <svg x-show="loading" style="display:none" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </button>
                @else
                <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border
                    {{ $item->is_available
                        ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                        : 'bg-stone-800 text-stone-500 border-stone-700' }}">
                    {{ $item->is_available ? '● Available' : '○ Unavailable' }}
                </span>
                @endif
            </div>

            {{-- Name & Description --}}
            <h4 class="text-stone-100 font-bold text-base mb-1 leading-tight">{{ $item->name }}</h4>
            @if($item->description)
            <p class="text-stone-500 text-xs leading-relaxed mb-3 flex-1 line-clamp-2">
                {{ $item->description }}
            </p>
            @else
            <div class="flex-1"></div>
            @endif

            {{-- Dietary labels --}}
            @if($item->getDietaryLabels())
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($item->getDietaryLabels() as $label)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-stone-800/80 border border-stone-700/50
                             rounded-md text-stone-400 text-xs">
                    {{ $label['icon'] }} {{ $label['label'] }}
                </span>
                @endforeach
            </div>
            @endif

            {{-- Meta info --}}
            <div class="flex items-center gap-3 text-xs text-stone-600 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $item->prep_time_minutes }}min
                </span>
                @if($item->calories)
                <span>· {{ $item->calories }} kcal</span>
                @endif
                @if($item->allergens && count($item->allergens))
                <span>· ⚠️ {{ count($item->allergens) }} allergen{{ count($item->allergens) > 1 ? 's' : '' }}</span>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-3 border-t border-stone-700/40">
                <span class="text-amber-400 font-bold text-lg tracking-tight">
                    {{ $item->getFormattedPrice() }}
                </span>

                @if(auth()->user()->isManager())
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('menu.edit', $item) }}"
                        class="p-2 text-stone-500 hover:text-stone-200 hover:bg-stone-700/50 rounded-lg transition-all"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('menu.destroy', $item) }}"
                        x-on:submit.prevent="if(confirm('Remove \'{{ $item->name }}\' from the menu?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="p-2 text-stone-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
                            title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif

@endsection
