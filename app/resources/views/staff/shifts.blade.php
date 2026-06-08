@extends('layouts.app')
@section('title', 'Navbatlar')
@section('page-title', 'Staff Navbatlari')
@section('page-subtitle', 'Kim qachon ishlagan — haftalik jadval')

@section('content')

{{-- STATS --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Bu hafta</p>
        <p class="text-3xl font-bold text-gray-900">{{ $shifts->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">navbat</p>
    </div>
    <div class="bg-white border {{ $activeCount > 0 ? 'border-green-300' : 'border-gray-200' }} rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Hozir ishlaydi</p>
        <p class="text-3xl font-bold {{ $activeCount > 0 ? 'text-green-600' : 'text-gray-900' }}">{{ $activeCount }}</p>
        <p class="text-xs text-gray-400 mt-0.5">xodim</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Jami soat</p>
        <p class="text-3xl font-bold text-gray-900">
            {{ round($shifts->sum(fn($s) => $s->getDurationMinutes()) / 60, 1) }}
        </p>
        <p class="text-xs text-gray-400 mt-0.5">soat</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500 mb-1">Faol xodimlar</p>
        <p class="text-3xl font-bold text-gray-900">{{ $allStaff->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">ta</p>
    </div>
</div>

{{-- HAFTA NAVIGATSIYA --}}
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('staff.shifts', ['week' => \Carbon\Carbon::parse($week)->subWeek()->startOfWeek()->toDateString(), 'staff' => $staffId]) }}"
       class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
    </a>
    <h3 class="font-bold text-gray-900">
        {{ $weekStart->format('d M') }} — {{ $weekEnd->format('d M Y') }}
        @if($weekStart->isCurrentWeek())
        <span class="text-xs text-blue-600 font-normal ml-2">— bu hafta</span>
        @endif
    </h3>
    <a href="{{ route('staff.shifts', ['week' => \Carbon\Carbon::parse($week)->addWeek()->startOfWeek()->toDateString(), 'staff' => $staffId]) }}"
       class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
    </a>
    <a href="{{ route('staff.shifts', ['week' => now()->startOfWeek()->toDateString()]) }}"
       class="text-xs text-gray-500 hover:text-gray-900 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
        Bugun
    </a>

    {{-- Staff filter --}}
    <div class="ml-auto">
        <select onchange="window.location='{{ route('staff.shifts', ['week'=>$week]) }}&staff='+this.value"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-gray-900">
            <option value="">Barcha xodimlar</option>
            @foreach($allStaff as $s)
            <option value="{{ $s->id }}" {{ $staffId == $s->id ? 'selected':'' }}>
                {{ $s->name }} ({{ $s->getRoleLabel() }})
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- CHAP: Haftalik jadval + Statistika --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Haftalik jadval --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm">Haftalik jadval</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($weekDays as $day)
                @php
                    $isToday   = $day['date']->isToday();
                    $dayShifts = $day['shifts'];
                @endphp
                <div class="px-5 py-3 {{ $isToday ? 'bg-blue-50/50' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-20 flex-shrink-0 pt-0.5">
                            <p class="text-xs font-bold {{ $isToday ? 'text-blue-700' : 'text-gray-500' }}">
                                {{ $day['date']->locale('uz')->isoFormat('ddd') }}
                            </p>
                            <p class="text-lg font-bold {{ $isToday ? 'text-blue-900' : 'text-gray-900' }}">
                                {{ $day['date']->format('d') }}
                            </p>
                        </div>

                        <div class="flex-1 min-w-0">
                            @if($dayShifts->isEmpty())
                            <p class="text-xs text-gray-400 pt-2">Navbat yo'q</p>
                            @else
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @foreach($dayShifts as $shift)
                                <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs
                                    {{ $shift->isActive() ? 'bg-green-50 border-green-300 animate-pulse' : 'bg-gray-50 border-gray-200' }}">
                                    <div class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($shift->user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $shift->user->name }}</span>
                                    <span class="text-gray-500">
                                        {{ $shift->shift_start->format('H:i') }}
                                        @if($shift->shift_end)
                                        — {{ $shift->shift_end->format('H:i') }}
                                        <span class="text-gray-400">({{ $shift->getFormattedDuration() }})</span>
                                        @else
                                        <span class="text-green-600 font-bold">● ishlaydi</span>
                                        @endif
                                    </span>
                                    @if($shift->isActive())
                                    <form method="POST" action="{{ route('staff.shifts.clockout', $shift) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs text-red-500 hover:text-red-700 font-bold ml-1">⏹</button>
                                    </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="text-right flex-shrink-0 pt-1">
                            @if($dayShifts->isNotEmpty())
                            <p class="text-xs font-bold text-gray-900">
                                {{ round($dayShifts->sum(fn($s) => $s->getDurationMinutes()) / 60, 1) }}s
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Xodimlar statistikasi --}}
        @if($staffStats->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm">Xodimlar reytingi — bu hafta</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($staffStats as $stat)
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="w-9 h-9 rounded-xl {{ $stat['active'] > 0 ? 'bg-green-600' : 'bg-gray-900' }} text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($stat['user']->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-gray-900 text-sm">{{ $stat['user']->name }}</p>
                            @if($stat['active'] > 0)
                            <span class="text-xs px-1.5 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-full font-semibold animate-pulse">
                                ● ishlaydi
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">{{ $stat['user']->getRoleLabel() }} · {{ $stat['shifts'] }} navbat</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">{{ $stat['hours'] }} soat</p>
                        <p class="text-xs text-gray-400">{{ $stat['total_mins'] }} daqiqa</p>
                    </div>
                    {{-- Progress bar --}}
                    <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                        @php $maxHours = $staffStats->max('hours') ?: 1; @endphp
                        <div class="h-full bg-gray-900 rounded-full"
                             style="width: {{ ($stat['hours'] / $maxHours) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- O'NG: Clock in/out + Qo'shish --}}
    <div class="space-y-4">

        @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
            ❌ {{ session('error') }}
        </div>
        @endif

        {{-- Hozir ishlayotganlar --}}
        @php $activeShifts = \App\Models\StaffShift::with('user')->whereNull('shift_end')->get(); @endphp
        @if($activeShifts->isNotEmpty())
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <h3 class="font-bold text-green-900 text-sm mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Hozir ishlaydi ({{ $activeShifts->count() }})
            </h3>
            <div class="space-y-2">
                @foreach($activeShifts as $active)
                <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $active->user->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $active->shift_start->format('H:i') }} dan
                            · {{ $active->getFormattedDuration() }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('staff.shifts.clockout', $active) }}">
                        @csrf @method('PATCH')
                        <button class="text-xs px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-colors">
                            ⏹ Tugatish
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Clock In --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">⏱ Navbat boshlash</h3>
            <form method="POST" action="{{ route('staff.shifts.clockin') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Xodim *</label>
                    <select name="user_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-gray-900">
                        <option value="">Tanlang...</option>
                        @foreach($allStaff as $s)
                        @php $isActive = $activeShifts->where('user_id', $s->id)->isNotEmpty(); @endphp
                        <option value="{{ $s->id }}" {{ $isActive ? 'disabled':'' }}>
                            {{ $s->getRoleIcon() }} {{ $s->name }}{{ $isActive ? ' (ishlaydi)':'' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Navbat turi</label>
                    <select name="type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-gray-900">
                        <option value="regular">📋 Oddiy</option>
                        <option value="morning">🌅 Ertalabki</option>
                        <option value="evening">🌆 Kechki</option>
                        <option value="night">🌙 Tungi</option>
                        <option value="overtime">⚡ Qo'shimcha</option>
                    </select>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    ▶ Hozir boshlash
                </button>
            </form>
        </div>

        {{-- Qo'lda qo'shish --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">📝 Qo'lda qo'shish</h3>
            <form method="POST" action="{{ route('staff.shifts.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Xodim *</label>
                    <select name="user_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-gray-900">
                        <option value="">Tanlang...</option>
                        @foreach($allStaff as $s)
                        <option value="{{ $s->id }}">{{ $s->getRoleIcon() }} {{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Boshlanish *</label>
                        <input name="shift_start" type="datetime-local" required
                               value="{{ old('shift_start') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tugash</label>
                        <input name="shift_end" type="datetime-local"
                               value="{{ old('shift_end') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tur</label>
                    <select name="type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-gray-900">
                        <option value="regular">📋 Oddiy</option>
                        <option value="morning">🌅 Ertalabki</option>
                        <option value="evening">🌆 Kechki</option>
                        <option value="night">🌙 Tungi</option>
                        <option value="overtime">⚡ Qo'shimcha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Izoh</label>
                    <input name="notes" type="text" placeholder="Ixtiyoriy..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-gray-900">
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                    Qo'shish
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
