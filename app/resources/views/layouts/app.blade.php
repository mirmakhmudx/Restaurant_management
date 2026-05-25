<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard') — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: #f4f0eb; }

        /* ── LAYOUT ────────────────────────── */
        .shell { display: flex; height: 100vh; overflow: hidden; }

        /* ── SIDEBAR ───────────────────────── */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: #0f0d0b;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sb-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; gap: 12px;
            flex-shrink: 0;
        }
        .sb-logo-mark {
            width: 36px; height: 36px;
            background: #e8a020;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .sb-logo-text { font-size: 11px; font-weight: 700; color: #4a4038; letter-spacing: 4px; text-transform: uppercase; }

        /* User card */
        .sb-user {
            padding: 16px;
            margin: 12px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 10px;
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .sb-avatar {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .sb-user-name { font-size: 13px; font-weight: 600; color: #e8e3da; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { font-size: 10px; color: #4a4038; margin-top: 1px; }

        /* Nav */
        .sb-nav { flex: 1; padding: 8px; overflow-y: auto; }
        .sb-nav::-webkit-scrollbar { width: 0; }
        .sb-section-label {
            font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 2px; color: #2a2620;
            padding: 16px 12px 6px;
        }

        .sb-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #5a5048;
            font-size: 13px; font-weight: 500;
            transition: all 0.15s;
            position: relative;
            margin-bottom: 1px;
        }
        .sb-link:hover { color: #c5bdb5; background: rgba(255,255,255,0.04); }
        .sb-link.active {
            color: #e8a020;
            background: rgba(232,160,32,0.08);
        }
        .sb-link.active::before {
            content: '';
            position: absolute; left: 0; top: 6px; bottom: 6px;
            width: 3px; background: #e8a020;
            border-radius: 0 3px 3px 0;
        }
        .sb-link-icon { font-size: 15px; width: 20px; text-align: center; flex-shrink: 0; }
        .sb-link-text { flex: 1; }

        /* Sidebar bottom */
        .sb-bottom {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.05);
            flex-shrink: 0;
        }
        .sb-logout {
            display: flex; align-items: center; gap: 10px;
            width: 100%; padding: 9px 12px;
            background: none; border: none; border-radius: 8px;
            color: #3a3028; font-size: 13px; font-weight: 500;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.15s;
        }
        .sb-logout:hover { color: #ef4444; background: rgba(239,68,68,0.06); }

        /* ── MAIN AREA ──────────────────────── */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        /* Top bar */
        .topbar {
            background: #fdf9f5;
            border-bottom: 1px solid #e8e3da;
            padding: 0 32px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .topbar-left {}
        .page-title-main {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 900;
            color: #1a1614; letter-spacing: -0.5px;
            line-height: 1;
        }
        .page-subtitle-main { font-size: 12px; color: #9e9189; margin-top: 2px; }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .clock-wrap { text-align: right; }
        .clock-time { font-size: 14px; font-weight: 600; color: #1a1614; font-variant-numeric: tabular-nums; }
        .clock-date { font-size: 11px; color: #9e9189; margin-top: 1px; }

        .role-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px; font-weight: 600;
            border: 1.5px solid;
        }

        /* Content */
        .content { flex: 1; overflow-y: auto; padding: 28px 32px; background: #f4f0eb; }

        /* Flash */
        .flash-success {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #d1f0dc;
            border-left: 3px solid #22c55e;
            border-radius: 8px;
            color: #15803d;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* ── ROLE COLORS ─────────────────────── */
        .role-manager  { color: #7c3aed; border-color: #ddd6fe; background: #faf5ff; }
        .role-waiter   { color: #2563eb; border-color: #bfdbfe; background: #eff6ff; }
        .role-chef     { color: #b45309; border-color: #fde68a; background: #fffbeb; }
        .role-cashier  { color: #059669; border-color: #a7f3d0; background: #ecfdf5; }
        .avatar-manager  { background: #ede9fe; }
        .avatar-waiter   { background: #dbeafe; }
        .avatar-chef     { background: #fef3c7; }
        .avatar-cashier  { background: #d1fae5; }
    </style>
</head>
<body>
<div class="shell">

    <!-- ══ SIDEBAR ══════════════════════════════ -->
    <aside class="sidebar">

        <!-- Logo -->
        <div class="sb-logo">
            <div class="sb-logo-mark">🍽️</div>
            <div>
                <div class="sb-logo-text">BitePlate</div>
            </div>
        </div>

        <!-- User -->
        <div class="sb-user">
            <div class="sb-avatar avatar-{{ auth()->user()->role->value }}">
                {{ auth()->user()->getRoleIcon() }}
            </div>
            <div style="min-width:0">
                <div class="sb-user-name">{{ auth()->user()->name }}</div>
                <div class="sb-user-role">{{ auth()->user()->getRoleLabel() }}</div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="sb-nav">
            <div class="sb-section-label">Workspace</div>

            @php
            $nav = [
                ['route'=>'dashboard',    'icon'=>'📊', 'label'=>'Dashboard',  'perm'=>null],
                ['route'=>'orders.index', 'icon'=>'📋', 'label'=>'Orders',     'perm'=>'orders'],
                ['route'=>'kitchen.index','icon'=>'👨‍🍳','label'=>'Kitchen',    'perm'=>'kitchen'],
                ['route'=>'billing.index','icon'=>'💳', 'label'=>'Billing',    'perm'=>'billing'],
                ['route'=>'menu.index',   'icon'=>'🍴', 'label'=>'Menu',       'perm'=>'menu'],
                ['route'=>'tables.index', 'icon'=>'🪑', 'label'=>'Tables',     'perm'=>'tables'],
            ];
            $navAdmin = [
                ['route'=>'reports.index','icon'=>'📈', 'label'=>'Reports',    'perm'=>'reports'],
                ['route'=>'staff.index',  'icon'=>'👥', 'label'=>'Staff',      'perm'=>'staff'],
            ];
            @endphp

            @foreach($nav as $item)
                @if(!$item['perm'] || auth()->user()->canAccess($item['perm']))
                <a href="{{ route($item['route']) }}" class="sb-link
                    {{ request()->routeIs(explode('.',$item['route'])[0].'*') ? 'active' : '' }}">
                    <span class="sb-link-icon">{{ $item['icon'] }}</span>
                    <span class="sb-link-text">{{ $item['label'] }}</span>
                </a>
                @endif
            @endforeach

            @if(auth()->user()->isManager())
            <div class="sb-section-label">Management</div>
            @foreach($navAdmin as $item)
            <a href="{{ route($item['route']) }}" class="sb-link
                {{ request()->routeIs(explode('.',$item['route'])[0].'*') ? 'active' : '' }}">
                <span class="sb-link-icon">{{ $item['icon'] }}</span>
                <span class="sb-link-text">{{ $item['label'] }}</span>
            </a>
            @endforeach
            @endif
        </nav>

        <!-- Logout -->
        <div class="sb-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <span style="font-size:15px">🚪</span>
                    <span>Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ══ MAIN ════════════════════════════════ -->
    <div class="main">

        <!-- Top bar -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="page-title-main">@yield('page-title','Dashboard')</div>
                <div class="page-subtitle-main">@yield('page-subtitle','')</div>
            </div>
            <div class="topbar-right">
                <div class="clock-wrap">
                    <div class="clock-time" id="clk">--:--</div>
                    <div class="clock-date" id="clk-date">---</div>
                </div>
                <span class="role-pill role-{{ auth()->user()->role->value }}">
                    {{ auth()->user()->getRoleIcon() }}
                    {{ auth()->user()->getRoleLabel() }}
                </span>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            @if(session('success'))
            <div class="flash-success">✅ {{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
function tick() {
    const n = new Date();
    document.getElementById('clk').textContent =
        n.toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'});
    document.getElementById('clk-date').textContent =
        n.toLocaleDateString('en-GB',{weekday:'short',day:'numeric',month:'short'});
}
tick(); setInterval(tick, 1000);
</script>
@stack('scripts')
</body>
</html>
