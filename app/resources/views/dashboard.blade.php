@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', now()->format('l, d F Y'))

@section('content')

<div class="bg-gray-900 rounded-2xl p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-white font-bold text-base">
                Xayrli {{ now()->hour < 12 ? 'tong' : (now()->hour < 17 ? 'kun' : 'kech') }},
                {{ auth()->user()->name }} 👋
            </p>
            <p class="text-gray-400 text-sm mt-0.5">Restoran workflow — bosib ishlang</p>
        </div>
    </div>
    <div class="flex items-center gap-1 overflow-x-auto pb-1">
        @foreach([
            ['1','Stol tanlash', 'tables.index',  'tables*'],
            ['2','Order olish',  'orders.create',  'orders*'],
            ['3','Kitchen',      'kitchen.index',  'kitchen*'],
            ['4','Xizmat',       'orders.index',   null],
            ['5',"To'lov",       'billing.index',  'billing*'],
            ['6','Hisobot',      'reports.index',  'reports*'],
        ] as [$n, $title, $route, $match])
        @php $active = $match && request()->routeIs($match); @endphp
        <a href="{{ route($route) }}"
           class="flex-shrink-0 flex items-center gap-2.5 px-4 py-2.5 rounded-xl transition-all hover:bg-white/10 {{ $active ? 'bg-white/15 ring-1 ring-white/30' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $active ? 'bg-white text-gray-900' : 'bg-white/10 text-gray-300' }}">{{ $n }}</div>
            <span class="text-white text-sm font-medium whitespace-nowrap">{{ $title }}</span>
        </a>
        @if(!$loop->last)
        <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        @endif
        @endforeach
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Bugungi orderlar', $stats['orders_today'],
            $stats['pending_orders'].' pending',
            $stats['pending_orders']>0?'text-amber-600':'text-gray-400',
            'bg-blue-50','text-blue-600',
            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'orders.index'],
        ['Band stollar', $stats['active_tables'],
            'ta stol band',
            'text-gray-400',
            'bg-red-50','text-red-500',
            'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6z',
            'tables.index'],
        ["Bugungi daromad", '£'.number_format($stats['revenue_today'],2),
            $stats['pending_bills']." to'lov kutmoqda",
            $stats['pending_bills']>0?'text-amber-600':'text-gray-400',
            'bg-green-50','text-green-600',
            'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75',
            'billing.index'],
        ['Faol xodimlar', $stats['staff_on_duty'],
            $pendingStaff>0 ? $pendingStaff.' tasdiq kutmoqda' : 'Hammasi faol',
            $pendingStaff>0?'text-orange-500':'text-gray-400',
            'bg-purple-50','text-purple-600',
            'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z',
            'staff.index'],
    ] as [$lbl,$val,$sub,$subC,$bg,$ic,$path,$route])
    <a href="{{ route($route) }}"
       class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-gray-300 transition-all block group">
        <div class="flex items-start justify-between mb-4">
            <p class="text-xs font-medium text-gray-500">{{ $lbl }}</p>
            <div class="w-9 h-9 {{ $bg }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 {{ $ic }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $val }}</p>
        <p class="text-xs {{ $subC }}">{{ $sub }}</p>
    </a>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <h3 class="font-bold text-gray-900 text-sm">Jonli holat</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach([
                ['pending_orders','Tasdiqlash kutmoqda','bg-gray-400',  'orders.index',  ['status'=>'pending']],
                ['kitchen_active','Kitchen da',         'bg-amber-500', 'kitchen.index', []],
                ['ready_orders',  'Xizmatga tayyor',   'bg-green-500', 'kitchen.index', []],
                ['pending_bills', "To'lov kutmoqda",   'bg-purple-500','billing.index', []],
            ] as [$key,$lbl,$dot,$route,$param])
            <a href="{{ route($route,$param) }}"
               class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full {{ $dot }} {{ $stats[$key]>0?'animate-pulse':'opacity-30' }}"></span>
                    <span class="text-sm text-gray-700">{{ $lbl }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-xl font-bold text-gray-900">{{ $stats[$key] }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            <a href="{{ route('orders.create') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Yangi order yaratish
            </a>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm">So'nggi orderlar</h3>
            <a href="{{ route('orders.index') }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">Barchasi →</a>
        </div>
        @forelse($recentOrders as $order)
        <a href="{{ route('orders.show', $order) }}"
           class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-gray-700">{{ substr($order->order_number,-3) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="font-semibold text-gray-900 text-sm">{{ $order->order_number }}</span>
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-xs font-medium border {{ $order->status->badgeClasses() }}">
                        {{ $order->status->label() }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 truncate">
                    {{ $order->table ? 'Stol '.$order->table->number : 'Takeaway' }}
                    · {{ $order->itemCount() }} ta taom
                    · {{ $order->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="font-bold text-gray-900">{{ $order->getFormattedTotal() }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
            </div>
        </a>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-center px-5">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-900 mb-1">Hali order yo'q</p>
            <a href="{{ route('orders.create') }}" class="mt-2 px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                Order yaratish
            </a>
        </div>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <div class="lg:col-span-2 space-y-4">

        {{-- Analytics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Bugungi daromad</p>
                    <span class="text-lg">💰</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">£{{ number_format($stats["revenue_today"], 2) }}</p>
                @if($stats["revenue_yesterday"] > 0)
                @php $diff = $stats["revenue_today"] - $stats["revenue_yesterday"]; @endphp
                <p class="text-xs mt-1 {{ $diff >= 0 ? "text-green-600" : "text-red-500" }}">
                    {{ $diff >= 0 ? "↑" : "↓" }} £{{ number_format(abs($diff), 2) }} kecha nisbatan
                </p>
                @endif
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Bugungi orderlar</p>
                    <span class="text-lg">📋</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats["orders_today"] }}</p>
                <p class="text-xs mt-1 {{ $stats["pending_orders"] > 0 ? "text-amber-600" : "text-green-600" }}">
                    {{ $stats["pending_orders"] > 0 ? $stats["pending_orders"]." ta kutilmoqda" : "Hammasi bajarildi ✓" }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Band stollar</p>
                    <span class="text-lg">🪑</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats["active_tables"] }}<span class="text-sm text-gray-400 font-normal">/{{ $stats["total_tables"] }}</span></p>
                <div class="mt-1 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-gray-900 h-1.5 rounded-full" style="width:{{ $stats["total_tables"] > 0 ? ($stats["active_tables"]/$stats["total_tables"]*100) : 0 }}%"></div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Top taom (bugun)</p>
                    <span class="text-lg">⭐</span>
                </div>
                <p class="text-base font-bold text-gray-900 truncate">{{ $stats["top_dish"] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $stats["top_dish_qty"] }} ta buyurtma qilindi</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Bronlar (bugun)</p>
                    <span class="text-lg">📅</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats["reservations_today"] }}</p>
                <p class="text-xs text-gray-400 mt-1">Bugungi rezervatsiyalar</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-medium text-gray-500">Peak soat</p>
                    <span class="text-lg">⏰</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats["peak_hour"] }}</p>
                <p class="text-xs text-gray-400 mt-1">Eng band vaqt</p>
            </div>
        </div>

        {{-- Kitchen Status --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🍳 Kitchen holati</h3>
            <div class="grid grid-cols-4 gap-3">
                @foreach([
                    ["Pending",    $stats["pending_orders"],  "bg-gray-100",   "text-gray-700"],
                    ["Preparing",  $stats["kitchen_active"],  "bg-amber-100",  "text-amber-700"],
                    ["Ready",      $stats["ready_orders"],    "bg-green-100",  "text-green-700"],
                    ["To pay",     $stats["pending_bills"],   "bg-blue-100",   "text-blue-700"],
                ] as [$label, $count, $bg, $text])
                <div class="text-center p-3 {{ $bg }} rounded-xl">
                    <p class="text-2xl font-bold {{ $text }}">{{ $count }}</p>
                    <p class="text-xs {{ $text }} mt-0.5 font-medium">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Akkaunt</h3>
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center text-base font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-gray-900 text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 bg-gray-900 text-white text-xs font-semibold rounded-full">
                {{ auth()->user()->getRoleIcon() }} {{ auth()->user()->getRoleLabel() }}
            </span>
            <div class="border-t border-gray-100 pt-3 mt-3">
                <p class="text-xs text-gray-400 mb-2">Kirish huquqlari</p>
                <div class="flex flex-wrap gap-1">
                    @foreach(auth()->user()->role->permissions() as $perm)
                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">{{ $perm }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Tezkor harakatlar</h3>
            <div class="space-y-2">
                <a href="{{ route('orders.create') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Yangi order
                </a>
                @if(auth()->user()->canAccess('kitchen'))
                    <a href="{{ route('kitchen.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048"/>
                        </svg>
                        Kitchen display
                    </a>
                @endif
                <a href="{{ route('tables.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-all">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z"/>
                    </svg>
                    Stollar holati
                </a>
                <a href="{{ route('billing.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-all">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    To'lov
                </a>
            </div>
        </div>
    </div>
</div>

@endsection


<script>
new Chart(document.getElementById('revenueChart'),{
    type:'bar',
    data:{
        labels:@json($chartLabels),
        datasets:[{label:'Daromad (£)',data:@json($chartData),
        backgroundColor:'rgba(17,24,39,0.85)',borderRadius:8,borderSkipped:false}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true,grid:{color:'#f3f4f6'},ticks:{callback:v=>'£'+v}},
                x:{grid:{display:false}}}}
});
</script>


<script>
new Chart(document.getElementById('revenueChart'),{
    type:'bar',
    data:{
        labels:@json($chartLabels),
        datasets:[{label:'Daromad (£)',data:@json($chartData),
        backgroundColor:'rgba(17,24,39,0.85)',borderRadius:8,borderSkipped:false}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true,grid:{color:'#f3f4f6'},ticks:{callback:v=>'£'+v}},
                x:{grid:{display:false}}}}
});
</script>
