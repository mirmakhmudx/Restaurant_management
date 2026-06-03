@extends('layouts.app')
@section('title', 'Edit Table ' . $table->number)
@section('page-title', 'Edit Table ' . $table->number)
@section('page-subtitle', 'Update table configuration')

@section('content')
<div class="max-w-lg">
    @if($errors->any())
    <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
        @foreach($errors->all() as $e)<p class="text-red-600 text-sm">{{ $e }}</p>@endforeach
    </div>
    @endif
    <form method="POST" action="{{ route('tables.update', $table) }}" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5">Table Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Table number</label>
                    <input name="number" type="number" min="1" max="999" required value="{{ old('number', $table->number) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Capacity</label>
                    <input name="capacity" type="number" min="1" max="20" required value="{{ old('capacity', $table->capacity) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Location</label>
                    <select name="location" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        @foreach($locations as $loc)
                        <option value="{{ $loc->value }}" {{ old('location', $table->location->value) === $loc->value ? 'selected' : '' }}>
                            {{ $loc->icon() }} {{ $loc->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                    <select name="status" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        @foreach($statuses as $st)
                        <option value="{{ $st->value }}" {{ old('status', $table->status->value) === $st->value ? 'selected' : '' }}>
                            {{ $st->icon() }} {{ $st->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Notes</label>
                <input name="notes" type="text" value="{{ old('notes', $table->notes) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
            <a href="{{ route('tables.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
