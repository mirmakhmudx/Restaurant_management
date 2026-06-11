@extends('layouts.app')
@section('title', 'Bron — ' . $reservation->guest_name)
@section('page-title', 'Bron Tafsiloti')
@section('page-subtitle', $reservation->reserved_at->format('d M Y, H:i') . ' · ' . $reservation->statusLabel())

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Asosiy ma'lumot --}}
    <div class="lg:col-span-2 space-y-5">

        @if(session('success'))
        <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            ✅ {{ session('success') }}
        </div>
        @endif

        {{-- Mehmon --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Mehmon ma'lumotlari</h3>
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($reservation->guest_name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-lg">{{ $reservation->guest_name }}</p>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $reservation->statusBadge() }}">
                        {{ $reservation->statusLabel() }}
                    </span>
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">📞 Telefon</dt>
                    <dd class="font-semibold text-gray-900">{{ $reservation->guest_phone }}</dd>
                </div>
                @if($reservation->guest_email)
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">✉️ Email</dt>
                    <dd class="font-semibold text-gray-900 text-sm">{{ $reservation->guest_email }}</dd>
                </div>
                @endif
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">📅 Sana va vaqt</dt>
                    <dd class="font-semibold text-gray-900">{{ $reservation->reserved_at->format('d M Y, H:i') }}</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">👥 Mehmonlar soni</dt>
                    <dd class="font-semibold text-gray-900">{{ $reservation->guest_count }} kishi</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">🪑 Stol</dt>
                    <dd class="font-semibold text-gray-900">
                        {{ $reservation->table ? 'Stol '.$reservation->table->number.' ('.$reservation->table->capacity.'p)' : 'Belgilanmagan' }}
                    </dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <dt class="text-xs text-gray-500 mb-1">🕐 Qo'shilgan</dt>
                    <dd class="font-semibold text-gray-900">{{ $reservation->created_at->format('d M, H:i') }}</dd>
                </div>
            </dl>

            @if($reservation->notes)
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-xs font-semibold text-amber-700 mb-1">📝 Maxsus izoh</p>
                <p class="text-sm text-amber-800">{{ $reservation->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Holat o'zgartirish --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Holat o'zgartirish</h3>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    'confirmed' => ['✅ Tasdiqlash',   'bg-green-600 hover:bg-green-700 text-white'],
                    'seated'    => ['🪑 Keldi',         'bg-blue-600 hover:bg-blue-700 text-white'],
                    'cancelled' => ['❌ Bekor qilish',  'border-2 border-red-200 text-red-600 hover:bg-red-50'],
                    'no_show'   => ['🚫 Kelmadi',       'border-2 border-gray-200 text-gray-600 hover:bg-gray-50'],
                ] as $val => [$label, $cls])
                @if($val !== $reservation->status)
                <form method="POST" action="{{ route('reservations.status', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $val }}">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-bold transition-all {{ $cls }}">
                        {{ $label }}
                    </button>
                </form>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- O'ng panel --}}
    <div class="space-y-4">

        {{-- Vaqt qoldi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
            @php
                $diff = now()->diff($reservation->reserved_at);
                $isPast = now() > $reservation->reserved_at;
            @endphp
            @if(!$isPast)
            <p class="text-xs text-gray-500 mb-2">Brongacha qoldi</p>
            <p class="text-3xl font-bold text-gray-900">
                @if($diff->days > 0)
                    {{ $diff->days }} kun
                @elseif($diff->h > 0)
                    {{ $diff->h }} soat
                @else
                    {{ $diff->i }} daqiqa
                @endif
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $reservation->reserved_at->format('d M Y, H:i') }}</p>
            @else
            <p class="text-xs text-gray-500 mb-2">Bron vaqti</p>
            <p class="text-sm font-bold text-gray-500">O'tib ketgan</p>
            <p class="text-xs text-gray-400 mt-1">{{ $reservation->reserved_at->format('d M Y, H:i') }}</p>
            @endif
        </div>

        {{-- Amallar --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Amallar</h3>
            <div class="space-y-2">
                <a href="{{ route('orders.create') }}?table={{ $reservation->table_id }}"
                   class="flex items-center gap-2 w-full py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Order yaratish
                </a>
                <a href="{{ route('reservations.index', ['date' => $reservation->reserved_at->toDateString()]) }}"
                   class="flex items-center gap-2 w-full py-2.5 px-4 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    ← Bron ro'yxatiga qaytish
                </a>
                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}"
                      onsubmit="return confirm('Bronni o\'chirasizmi?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-2 w-full py-2.5 px-4 border border-red-200 text-red-600 text-sm font-medium rounded-xl hover:bg-red-50 transition-colors">
                        🗑 Bronni o'chirish
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
