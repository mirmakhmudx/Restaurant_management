@extends('layouts.app')
@section('title', 'Hisobotlar')
@section('page-title', 'Hisobotlar')
@section('page-subtitle', 'Savdo tahlili — Singleton audit log bilan')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach([
        ['Bugun',  $revenue['today'], 'bg-gray-900 text-white border-gray-900'],
        ['Hafta',  $revenue['week'],  'bg-white text-gray-900 border-gray-200'],
        ['Oy',     $revenue['month'], 'bg-white text-gray-900 border-gray-200'],
    ] as [$lbl, $val, $cls])
    <div class="border rounded-xl p-5 {{ $cls }}">
        <p class="text-xs font-medium opacity-60 mb-1">Daromad — {{ $lbl }}</p>
        <p class="text-3xl font-bold">£{{ number_format($val, 2) }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- 7 KUNLIK GRAFIK --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-900 text-sm mb-5">So'nggi 7 kun daromadi</h3>
        <div class="flex items-end gap-2 h-36">
            @foreach($dailyRevenue as $day)
            @php $pct = $maxDaily > 0 ? ($day['revenue'] / $maxDaily) * 100 : 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                @if($day['revenue'] > 0)
                <span class="text-xs text-gray-500">£{{ number_format($day['revenue'],0) }}</span>
                @else
                <span class="text-xs text-transparent">0</span>
                @endif
                <div class="w-full rounded-t-lg transition-all bg-gray-900"
                     style="height: {{ max(4, $pct) }}%; opacity: {{ $day['date'] === today()->toDateString() ? '1' : '0.35' }}">
                </div>
                <span class="text-xs font-medium {{ $day['date'] === today()->toDateString() ? 'text-gray-900 font-bold' : 'text-gray-400' }}">
                    {{ $day['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- HOLAT BO'YICHA ORDERLAR --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-900 text-sm mb-5">Orderlar holati bo'yicha</h3>
        @php $totalOrders = $ordersByStatus->sum() ?: 1; @endphp
        <div class="space-y-3">
            @foreach(\App\Enums\OrderStatus::cases() as $st)
            @php $count = $ordersByStatus[$st->value] ?? 0; @endphp
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="flex items-center gap-1.5 text-gray-600 font-medium">
                        <span class="w-2 h-2 rounded-full {{ $st->dotColor() }}"></span>
                        {{ $st->label() }}
                    </span>
                    <span class="font-bold text-gray-900">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full bg-gray-800 transition-all"
                         style="width: {{ $totalOrders > 0 ? ($count/$totalOrders)*100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- TOP TAOMLAR --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-900 text-sm mb-5">Top 5 taom (miqdor bo'yicha)</h3>
        @if($topItems->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">Hali sotuv yo'q</p>
        @else
        <div class="space-y-3">
            @foreach($topItems as $i => $item)
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <span class="font-medium text-gray-900">
                        <span class="text-gray-400 mr-1">{{ $i+1 }}.</span>{{ $item->name }}
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">{{ $item->qty }}x</span>
                        <span class="font-bold text-gray-900">£{{ number_format($item->revenue,2) }}</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-gray-900"
                         style="width: {{ $maxQty > 0 ? ($item->qty/$maxQty)*100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- PRICING STRATEGY --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-900 text-sm mb-1">Pricing Strategy statistikasi</h3>
        <p class="text-xs text-gray-400 mb-5">Strategy Pattern — qaysi narx algoritmi ko'p ishlatildi</p>
        @if($strategies->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">Hali to'lov yo'q</p>
        @else
        <div class="space-y-3">
            @foreach($strategies as $s)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $s->pricing_strategy }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $s->count }} ta to'lov
                        @if($s->saved > 0)
                        · <span class="text-green-600 font-medium">£{{ number_format($s->saved,2) }} chegirma berildi</span>
                        @endif
                    </p>
                </div>
                <span class="font-bold text-gray-900">£{{ number_format($s->revenue,2) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- SINGLETON AUDIT LOG --}}
<div class="bg-white border border-gray-200 rounded-xl p-5">
    <div class="flex items-center gap-2 mb-1">
        <h3 class="font-bold text-gray-900 text-sm">Faoliyat jurnali</h3>
        <span class="text-xs px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-full font-semibold">Singleton Pattern</span>
    </div>
    <p class="text-xs text-gray-400 mb-4">OrderHistoryService — bitta instance ushbu sessiyada barcha eventlarni yig'adi</p>

    @if(empty($activityLog))
    <div class="text-center py-10 bg-gray-50 rounded-xl">
        <p class="text-sm text-gray-400 mb-1">Bu sessiyada hali faoliyat yo'q</p>
        <p class="text-xs text-gray-300">Order yarating → billing qiling → bu yerga qayting</p>
    </div>
    @else
    <div class="space-y-2 max-h-64 overflow-y-auto">
        @foreach($activityLog as $entry)
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl text-sm">
            <span class="text-gray-400 font-mono text-xs mt-0.5 whitespace-nowrap">
                {{ \Carbon\Carbon::parse($entry['logged_at'])->format('H:i:s') }}
            </span>
            <div class="min-w-0">
                <span class="font-semibold text-gray-900">{{ $entry['event'] ?? '' }}</span>
                <span class="text-gray-500 ml-2 text-xs">
                    @foreach($entry['data'] ?? [] as $k => $v)
                    <span class="text-gray-400">{{ $k }}:</span>
                    <span class="text-gray-700">{{ is_array($v) ? json_encode($v) : $v }}</span>
                    @if(!$loop->last)<span class="mx-1">·</span>@endif
                    @endforeach
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
