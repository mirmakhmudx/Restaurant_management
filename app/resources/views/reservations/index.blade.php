@extends('layouts.app')
@section('title', 'Bronlar')
@section('page-title', 'Rezervatsiyalar')
@section('page-subtitle', 'Stol bron qilish tizimi')

@section('content')

{{-- STATS --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Bugun</p>
        <p class="text-3xl font-bold text-gray-900">{{ $counts['today'] }}</p>
    </div>
    <div class="bg-white border border-amber-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Kutilmoqda</p>
        <p class="text-3xl font-bold text-amber-600">{{ $counts['pending'] }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Keyingi kunlar</p>
        <p class="text-3xl font-bold text-blue-600">{{ $counts['upcoming'] }}</p>
    </div>
</div>

{{-- 14 KUNLIK SANA STRIP --}}
<div class="bg-white border border-gray-200 rounded-xl p-4 mb-5">
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-semibold text-gray-500">Kunni tanlang</p>
        <form method="GET" action="{{ route('reservations.index') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}"
                   onchange="this.form.submit()"
                   class="border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-gray-900">
        </form>
    </div>
    <div class="flex gap-1.5 overflow-x-auto pb-1">
        @for($i = -1; $i <= 13; $i++)
        @php
            $d       = today()->addDays($i);
            $dStr    = $d->toDateString();
            $isSel   = $dStr === $date;
            $isToday = $d->isToday();
            $cnt     = $busyDays[$dStr] ?? 0;
        @endphp
        <a href="{{ route('reservations.index', ['date' => $dStr, 'status' => $status]) }}"
           class="flex-shrink-0 flex flex-col items-center rounded-xl px-3 py-2.5 transition-all min-w-12
           {{ $isSel
               ? 'bg-gray-900 text-white shadow-sm'
               : ($isToday
                   ? 'bg-gray-100 text-gray-900 ring-1 ring-gray-300'
                   : 'hover:bg-gray-50 text-gray-600') }}">
            <span class="text-xs font-medium {{ $isSel ? 'text-gray-300' : 'text-gray-400' }}">
                {{ $d->locale('uz')->isoFormat('dd') }}
            </span>
            <span class="text-sm font-bold mt-0.5">{{ $d->format('d') }}</span>
            @if($cnt > 0)
            <span class="mt-1 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center
                {{ $isSel ? 'bg-white text-gray-900' : 'bg-blue-100 text-blue-700' }}">
                {{ $cnt }}
            </span>
            @else
            <span class="mt-1 w-5 h-5"></span>
            @endif
        </a>
        @endfor
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- BRONLAR RO'YXATI --}}
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="font-bold text-gray-900">
                    {{ \Carbon\Carbon::parse($date)->locale('uz')->isoFormat('D MMMM, dddd') }}
                    @if($date === today()->toDateString())
                    <span class="text-xs text-blue-600 font-normal ml-1">— bugun</span>
                    @endif
                </p>
                <p class="text-xs text-gray-400">{{ $reservations->count() }} ta bron</p>
            </div>
            <select onchange="window.location='{{ route('reservations.index') }}?date={{ $date }}&status='+this.value"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-gray-900 bg-white">
                <option value="all"       {{ $status==='all'       ? 'selected':'' }}>Barchasi</option>
                <option value="pending"   {{ $status==='pending'   ? 'selected':'' }}>Kutilmoqda</option>
                <option value="confirmed" {{ $status==='confirmed' ? 'selected':'' }}>Tasdiqlangan</option>
                <option value="seated"    {{ $status==='seated'    ? 'selected':'' }}>Keldi</option>
                <option value="cancelled" {{ $status==='cancelled' ? 'selected':'' }}>Bekor</option>
            </select>
        </div>

        @if(session('success'))
        <div class="mb-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            ✅ {{ session('success') }}
        </div>
        @endif

        @forelse($reservations as $res)
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:shadow-sm transition-shadow"
             x-data="{ open: false }">
            <div class="flex items-center gap-3">
                {{-- Vaqt --}}
                <div class="text-center flex-shrink-0 w-12">
                    <p class="text-base font-bold text-gray-900">{{ $res->reserved_at->format('H:i') }}</p>
                    <p class="text-xs text-gray-400">{{ $res->reserved_at->format('d.m') }}</p>
                </div>

                <div class="w-px h-10 bg-gray-200 flex-shrink-0"></div>

                {{-- Avatar --}}
                <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr($res->guest_name, 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-bold text-gray-900">{{ $res->guest_name }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $res->statusBadge() }}">
                            {{ $res->statusLabel() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5 flex-wrap">
                        <span>📞 {{ $res->guest_phone }}</span>
                        <span>👥 {{ $res->guest_count }}p</span>
                        @if($res->table)<span>🪑 Stol {{ $res->table->number }}</span>@endif
                        @if($res->notes)<span class="text-orange-400">📝 {{ Str::limit($res->notes, 30) }}</span>@endif
                    </div>
                </div>

                {{-- Amallar --}}
                <div class="flex items-center gap-1 flex-shrink-0 relative">
                    <button x-on:click="open = !open"
                            class="text-xs px-2.5 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        ▾
                    </button>
                    <div x-show="open" x-on:click.away="open=false" style="display:none"
                         class="absolute right-8 top-0 bg-white border border-gray-200 rounded-xl shadow-lg z-20 overflow-hidden min-w-36">
                        @foreach(['confirmed'=>'✅ Tasdiqlash','seated'=>'🪑 Keldi','cancelled'=>'❌ Bekor','no_show'=>'🚫 Kelmadi'] as $val => $lbl)
                        @if($val !== $res->status)
                        <form method="POST" action="{{ route('reservations.status', $res) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $val }}">
                            <button class="w-full text-left text-xs px-3 py-2.5 hover:bg-gray-50">{{ $lbl }}</button>
                        </form>
                        @endif
                        @endforeach
                    </div>
                    <a href="{{ route('reservations.show', $res) }}"
                       class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('reservations.destroy', $res) }}"
                          onsubmit="return confirm('O\'chirasizmi?')">
                        @csrf @method('DELETE')
                        <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-14 bg-white border-2 border-dashed border-gray-200 rounded-2xl">
            <p class="text-4xl mb-3">📅</p>
            <p class="text-sm font-semibold text-gray-700 mb-1">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }} uchun bron yo'q
            </p>
            <p class="text-xs text-gray-400">Yuqoridagi sana stripidan boshqa kunni tanlang</p>
        </div>
        @endforelse
    </div>

    {{-- YANGI BRON FORMASI --}}
    <div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">➕ Yangi Bron</h3>
            <form method="POST" action="{{ route('reservations.store') }}" class="space-y-3">
                @csrf
                @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach($errors->all() as $e)<p class="text-red-600 text-xs">{{ $e }}</p>@endforeach
                </div>
                @endif
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Mehmon ismi *</label>
                    <input name="guest_name" type="text" required value="{{ old('guest_name') }}"
                           placeholder="Alisher Navoiy"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Telefon *</label>
                    <input name="guest_phone" type="text" required value="{{ old('guest_phone') }}"
                           placeholder="+998 90 123 45 67"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                    <input name="guest_email" type="email" value="{{ old('guest_email') }}"
                           placeholder="email@example.com"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Sana & vaqt *</label>
                        <input name="reserved_at" type="datetime-local" required
                               value="{{ old('reserved_at') }}"
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Mehmonlar *</label>
                        <input name="guest_count" type="number" min="1" max="20" required
                               value="{{ old('guest_count', 2) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Stol</label>
                    <select name="table_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                        <option value="">Avtomatik</option>
                        @foreach($tables as $t)
                        <option value="{{ $t->id }}" {{ !$t->isAvailable() ? 'disabled':'' }}>
                            Stol {{ $t->number }} ({{ $t->capacity }}p){{ !$t->isAvailable() ? ' — band':'' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Izoh</label>
                    <textarea name="notes" rows="2" placeholder="Maxsus so'rovlar..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none resize-none focus:border-gray-900">{{ old('notes') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                    Bron qilish
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
