@extends('layouts.app')
@section('title', 'Tables')
@section('page-title', 'Tables')
@section('page-subtitle', 'Restaurant floor plan & table status')

@section('content')

{{-- Toolbar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @foreach(\App\Enums\TableStatus::cases() as $st)
        @php $cnt = $tables->where('status', $st)->count(); @endphp
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $st->badgeClasses() }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $st->dotColor() }}"></span>
            {{ $st->label() }} {{ $cnt }}
        </span>
        @endforeach
    </div>
    @if(auth()->user()->isManager())
    <a href="{{ route('tables.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800
              text-white text-sm font-semibold rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Add Table
    </a>
    @endif
</div>

@foreach(\App\Enums\TableLocation::cases() as $loc)
@php $locTables = $tables->where('location', $loc); @endphp
@if($locTables->isNotEmpty())

<div class="mb-8">
    <div class="flex items-center gap-2 mb-4">
        <span>{{ $loc->icon() }}</span>
        <h3 class="font-semibold text-gray-900 text-sm">{{ $loc->label() }}</h3>
        <span class="text-xs text-gray-400">{{ $locTables->count() }} tables</span>
        <div class="flex-1 h-px bg-gray-200 ml-1"></div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3">
        @foreach($locTables->sortBy('number') as $table)
        <div class="bg-white border-2 rounded-xl p-4 flex flex-col items-center text-center
                    hover:shadow-md transition-all cursor-default {{ $table->status->cardBg() }}"
             x-data="{ open: false }" x-on:click.away="open = false">

            {{-- Table number --}}
            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center mb-2
                        {{ $table->status === \App\Enums\TableStatus::Available ? 'border-green-300 bg-green-50' :
                           ($table->status === \App\Enums\TableStatus::Occupied  ? 'border-red-300 bg-red-50' :
                           ($table->status === \App\Enums\TableStatus::Reserved  ? 'border-amber-300 bg-amber-50' :
                           'border-blue-300 bg-blue-50')) }}">
                <span class="font-bold text-lg text-gray-900">{{ $table->number }}</span>
            </div>

            <span class="font-semibold text-gray-900 text-sm">Table {{ $table->number }}</span>
            <span class="text-xs text-gray-400 mb-2">{{ $table->capacityLabel() }}</span>

            {{-- Status badge --}}
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border mb-3
                         {{ $table->status->badgeClasses() }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $table->status->dotColor() }}"></span>
                {{ $table->status->label() }}
            </span>

            {{-- Quick status change --}}
            @if(auth()->user()->isManager())
            <div class="relative w-full" x-data="{}">
                <button x-on:click="open = !open"
                        class="w-full text-xs text-gray-500 hover:text-gray-800 flex items-center justify-center gap-1 py-1 hover:bg-gray-100 rounded-lg transition-colors">
                    Change
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" style="display:none"
                     class="absolute bottom-full left-0 right-0 mb-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">
                    @foreach(\App\Enums\TableStatus::cases() as $st)
                    @if($st !== $table->status)
                    <form method="POST" action="{{ route('tables.status', $table) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $st->value }}">
                        <button type="submit"
                                class="w-full text-left text-xs px-3 py-2 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full {{ $st->dotColor() }}"></span>
                            {{ $st->label() }}
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Edit/Delete --}}
            @if(auth()->user()->isManager())
            <div class="flex items-center gap-1 mt-2 w-full justify-center">
                <a href="{{ route('tables.edit', $table) }}"
                   class="p-1 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('tables.destroy', $table) }}"
                      x-on:submit.prevent="if(confirm('Delete Table {{ $table->number }}?')) $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@endsection
