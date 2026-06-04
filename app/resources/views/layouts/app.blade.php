<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    
</head>

{{-- Real-time Notifications --}}
<div id="toast-container"
     style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;max-width:320px"></div>

<script>
    function showToast(message, type = 'info') {
        const colors = {
            info: '#3b82f6',
            success: '#22c55e',
            warning: '#f59e0b',
            kitchen: '#f97316',
        };
        const toast = document.createElement('div');
        toast.style.cssText = `
        background:white;border-left:4px solid ${colors[type] || colors.info};
        padding:12px 16px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);
        font-size:13px;font-family:sans-serif;cursor:pointer;
        animation:slideIn .3s ease;
    `;
        toast.innerHTML = `<p style="font-weight:600;color:#111;margin:0 0 2px">${message.title}</p>
                       <p style="color:#6b7280;margin:0;font-size:12px">${message.body}</p>`;
        toast.onclick = () => toast.remove();
        document.getElementById('toast-container').appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('orders')
            .listen('.status.updated', (e) => {
                const messages = {
                    'confirmed': {title: '✅ Tasdiqlandi', body: e.order_number + ' — ' + e.table, type: 'success'},
                    'preparing': {title: '🔥 Pishirilmoqda', body: e.order_number + ' kitchen da', type: 'kitchen'},
                    'ready': {title: '🔔 TAYYOR!', body: e.order_number + ' — ' + e.table, type: 'warning'},
                    'served': {title: '🍽 Xizmat qilindi', body: e.order_number + ' — ' + e.table, type: 'success'},
                    'cancelled': {title: '❌ Bekor qilindi', body: e.order_number, type: 'info'},
                    'billed': {title: '💳 To\'landi', body: e.order_number, type: 'success'},
                };
                const msg = messages[e.new_status];
                if (msg) showToast(msg, msg.type);
            });
    }
</script>
<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<body class="bg-gray-50 antialiased">

@php
    use App\Models\Order;
    use App\Models\User;
    $_pending = Order::where('status', 'pending')->count();
    $_kitchen = Order::whereIn('status', ['confirmed','preparing'])->count();
    $_ready   = Order::where('status', 'ready')->count();
    $_bills   = Order::where('status', 'served')->count();
    $_staff   = User::where('is_active', false)->count();
@endphp

<div class="min-h-screen flex">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">

        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
            <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm leading-none">BitePlate</p>
                <p class="text-xs text-gray-400 mt-0.5">Restaurant SRMS</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Platform</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
               {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="flex-1">Dashboard</span>
            </a>

            @if(auth()->user()->canAccess('orders'))
                <a href="{{ route('orders.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
               {{ request()->routeIs('orders*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="flex-1">Orders</span>
                    @if($_pending > 0)
                        <span
                            class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ request()->routeIs('orders*') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' }}">{{ $_pending }}</span>
                    @endif
                </a>
            @endif

            @if(auth()->user()->canAccess('kitchen'))
                <a href="{{ route('kitchen.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
               {{ request()->routeIs('kitchen*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/>
                    </svg>
                    <span class="flex-1">Kitchen</span>
                    @if($_kitchen > 0)
                        <span
                            class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ request()->routeIs('kitchen*') ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-700' }}">{{ $_kitchen }}</span>
                    @endif
                    @if($_ready > 0)
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    @endif
                </a>
            @endif

            @if(auth()->user()->canAccess('menu'))
                <a href="{{ route('menu.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
               {{ request()->routeIs('menu*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span class="flex-1">Menu</span>
                </a>
            @endif

            @if(auth()->user()->canAccess('tables'))
                <a href="{{ route('tables.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
               {{ request()->routeIs('tables*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                    <span class="flex-1">Tables</span>
                </a>
            @endif
            {{-- Reservations --}}
            @if(auth()->user()->isManager())
                <a href="{{ route('reservations.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
   {{ request()->routeIs('reservations*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span class="flex-1">Bronlar</span>
                </a>
            @endif

            @if(auth()->user()->canAccess('billing'))
                <a href="{{ route('billing.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
               {{ request()->routeIs('billing*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    <span class="flex-1">Billing</span>
                    @if($_bills > 0)
                        <span
                            class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ request()->routeIs('billing*') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">{{ $_bills }}</span>
                    @endif
                </a>
            @endif
            @if(auth()->user()->isManager())
                <a href="{{ route('tables.qr') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5 text-gray-600 hover:bg-gray-100">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75z"/>
                    </svg>
                    <span class="flex-1">QR Kodlar</span>
                </a>
            @endif

            @if(auth()->user()->isManager())
                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 pb-2">Management</p>
                    <a href="{{ route('reports.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                   {{ request()->routeIs('reports*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="flex-1">Reports</span>
                    </a>
                    <a href="{{ route('staff.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mt-0.5
                   {{ request()->routeIs('staff*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        <span class="flex-1">Staff</span>
                        @if($_staff > 0)
                            <span
                                class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ request()->routeIs('staff*') ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600' }}">{{ $_staff }}</span>
                        @endif
                    </a>
                </div>
            @endif
            <a href="{{ route('combos.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
   {{ request()->routeIs('combos*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                <span class="flex-1">Combos</span>
            </a>
        </nav>

        <div class="px-3 pb-3 border-t border-gray-100 pt-3">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-100 transition-colors">
                <div
                    class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->getRoleLabel() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-7 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-none">@yield('page-title', 'Dashboard')</h1>
                @hasSection('page-subtitle')
                    <p class="text-sm text-gray-500 mt-0.5">@yield('page-subtitle')</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($_ready > 0)
                    <a href="{{ route('kitchen.index') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full text-xs font-semibold text-green-700 hover:bg-green-100 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>{{ $_ready }} tayyor
                    </a>
                @endif
                @if($_pending > 0)
                    <a href="{{ route('orders.index', ['status'=>'pending']) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-xs font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>{{ $_pending }} pending
                    </a>
                @endif
                {{-- Notification Bell --}}
                @php $notifCount = \App\Models\Order::whereIn("status",["pending","ready"])->count(); @endphp
                <a href="{{ route("orders.index") }}" class="relative p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    @if($notifCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $notifCount > 9 ? "9+" : $notifCount }}</span>
                    @endif
                </a>
                <span class="text-sm text-gray-400 font-mono" id="clk">--:--</span>
                @php
                $rc = ["manager"=>"bg-purple-100 text-purple-800 border-purple-300","chef"=>"bg-orange-100 text-orange-800 border-orange-300","waiter"=>"bg-blue-100 text-blue-800 border-blue-300","cashier"=>"bg-green-100 text-green-800 border-green-300"];
                $rcls = $rc[auth()->user()->role->value] ?? "bg-gray-100 text-gray-700 border-gray-200";
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full border {{ $rcls }} shadow-sm">
                    {{ auth()->user()->getRoleIcon() }} {{ auth()->user()->getRoleLabel() }}
                </span>
            </div>
        </header>

        @if(session('success'))
            <div
                class="mx-7 mt-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                class="mx-7 mt-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 overflow-y-auto p-7">
            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        const el = document.getElementById('clk');
        const t = () => {
            if (el) el.textContent = new Date().toLocaleTimeString('en-GB', {hour: '2-digit', minute: '2-digit'});
        };
        t();
        setInterval(t, 30000);
    })();
</script>
@stack('scripts')
</body>
</html>
