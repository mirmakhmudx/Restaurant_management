<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f0d0b;
        }

        .split { display: flex; min-height: 100vh; }

        /* ── LEFT PANEL ──────────────────────── */
        .left-panel {
            width: 52%;
            background: #0f0d0b;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, #c2831840, transparent);
        }

        .bg-text {
            position: absolute;
            bottom: -40px;
            left: -20px;
            font-family: 'Playfair Display', serif;
            font-size: 280px;
            font-weight: 900;
            color: rgba(255,255,255,0.018);
            line-height: 1;
            user-select: none;
            pointer-events: none;
            letter-spacing: -10px;
        }

        .logo-area { position: relative; z-index: 1; }
        .logo-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px; height: 44px;
            background: #e8a020;
            border-radius: 10px;
            font-size: 22px;
            margin-bottom: 16px;
        }
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-weight: 700;
            color: #6b5d54;
            letter-spacing: 6px;
            text-transform: uppercase;
        }

        .left-center { position: relative; z-index: 1; }
        .left-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 5vw, 64px);
            font-weight: 900;
            color: #faf8f5;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 24px;
        }
        .left-heading em {
            font-style: normal;
            color: #e8a020;
        }
        .left-desc {
            color: #6b5d54;
            font-size: 15px;
            line-height: 1.7;
            max-width: 380px;
            margin-bottom: 48px;
        }

        .feature-list { display: flex; flex-direction: column; gap: 12px; }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
        }
        .feature-icon {
            width: 36px; height: 36px;
            background: rgba(232,160,32,0.1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .feature-title { color: #c5bdb5; font-size: 13px; font-weight: 600; }
        .feature-sub { color: #4a4038; font-size: 11px; margin-top: 1px; }

        .left-bottom { position: relative; z-index: 1; }
        .stats { display: flex; gap: 40px; }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 32px; font-weight: 900;
            color: #e8a020; line-height: 1;
        }
        .stat-label { color: #4a4038; font-size: 11px; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px; }

        /* ── RIGHT PANEL ─────────────────────── */
        .right-panel {
            flex: 1;
            background: #fdf9f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 64px;
        }
        .form-wrap { width: 100%; max-width: 380px; }

        .form-heading {
            font-family: 'Playfair Display', serif;
            font-size: 36px; font-weight: 900;
            color: #1a1614;
            letter-spacing: -1px;
            margin-bottom: 6px;
        }
        .form-sub { color: #9e9189; font-size: 14px; margin-bottom: 40px; }

        .alert-pending {
            padding: 14px 16px;
            background: #fef9ec;
            border: 1px solid #f0d080;
            border-radius: 10px;
            color: #8a6a10;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .alert-error {
            padding: 14px 16px;
            background: #fff5f5;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            color: #991b1b;
            font-size: 13px;
            margin-bottom: 24px;
        }

        /* Underline-only inputs */
        .field { margin-bottom: 32px; }
        .field label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #9e9189;
            margin-bottom: 10px;
        }
        .field-inner { position: relative; }
        .field input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #d9d0c4;
            padding: 10px 0;
            font-size: 16px;
            color: #1a1614;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }
        .field input::placeholder { color: #c5bdb5; font-size: 14px; }
        .field input:focus { border-bottom-color: #1a1614; }
        .field-btn {
            position: absolute; right: 0; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #b0a89e; padding: 4px;
            transition: color 0.15s;
        }
        .field-btn:hover { color: #1a1614; }

        .remember-row {
            display: flex; align-items: center;
            margin-bottom: 32px;
        }
        .remember-row label {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; font-size: 13px; color: #9e9189;
        }
        .remember-row input[type=checkbox] {
            width: 15px; height: 15px;
            accent-color: #1a1614;
            cursor: pointer;
        }

        .btn-signin {
            width: 100%;
            padding: 16px;
            background: #1a1614;
            color: #fdf9f5;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s;
            margin-bottom: 28px;
        }
        .btn-signin:hover { background: #2a2420; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,22,20,0.2); }
        .btn-signin:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .register-link { text-align: center; font-size: 13px; color: #9e9189; margin-bottom: 36px; }
        .register-link a { color: #1a1614; font-weight: 600; text-decoration: none; border-bottom: 1px solid #d9d0c4; padding-bottom: 1px; transition: border-color 0.15s; }
        .register-link a:hover { border-color: #1a1614; }

        .demo-section { border-top: 1px solid #e8e3da; padding-top: 28px; }
        .demo-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #c5bdb5; margin-bottom: 12px; text-align: center; }
        .demo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px; }
        .demo-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s;
        }
        .demo-btn:hover { border-color: #1a1614; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .demo-icon { font-size: 18px; }
        .demo-role { font-size: 12px; font-weight: 600; color: #1a1614; }
        .demo-email { font-size: 10px; color: #9e9189; }
        .demo-pwd { text-align: center; font-size: 11px; color: #c5bdb5; }
        .demo-pwd code { font-family: monospace; font-weight: 700; color: #9e9189; background: #f0ebe4; padding: 2px 6px; border-radius: 4px; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { padding: 40px 32px; background: #fdf9f5; }
        }
    </style>
</head>
<body x-data="{ showPwd: false, loading: false, fillDemo(e) { document.getElementById('em').value=e; document.getElementById('pw').value='password'; } }">

<div class="split">

    <!-- LEFT -->
    <div class="left-panel">
        <div class="bg-text">BP</div>

        <div class="logo-area">
            <div class="logo-mark">🍽️</div>
            <div class="brand-name">BitePlate</div>
        </div>

        <div class="left-center">
            <h1 class="left-heading">
                Restaurant<br>
                ops made<br>
                <em>simple.</em>
            </h1>
            <p class="left-desc">
                One unified platform covering every role — from taking orders to settling bills.
            </p>

            <div class="feature-list">
                @foreach([
                    ['📋','Order Management','Command pattern — execute & undo'],
                    ['👨‍🍳','Kitchen Display','Observer — real-time notifications'],
                    ['💳','Smart Billing','Strategy — dynamic pricing engine'],
                    ['📊','Full Reports','Singleton — order history log'],
                ] as [$icon,$title,$desc])
                <div class="feature-item">
                    <div class="feature-icon">{{ $icon }}</div>
                    <div>
                        <div class="feature-title">{{ $title }}</div>
                        <div class="feature-sub">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="left-bottom">
            <div class="stats">
                @foreach([['10+','Design patterns'],['4','Staff roles'],['8','Modules']] as [$n,$l])
                <div>
                    <div class="stat-num">{{ $n }}</div>
                    <div class="stat-label">{{ $l }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
        <div class="form-wrap">

            <h2 class="form-heading">Welcome back.</h2>
            <p class="form-sub">Sign in to your staff account</p>

            @if(session('pending'))
            <div class="alert-pending">⏳ {{ session('pending') }}</div>
            @endif

            @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" x-on:submit="loading=true">
                @csrf

                <div class="field">
                    <label>Email address</label>
                    <div class="field-inner">
                        <input id="em" name="email" type="email" autocomplete="email"
                            value="{{ old('email') }}" required placeholder="your@email.com">
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="field-inner">
                        <input id="pw" name="password" x-bind:type="showPwd?'text':'password'"
                            autocomplete="current-password" required placeholder="••••••••">
                        <button type="button" class="field-btn" x-on:click="showPwd=!showPwd">
                            <svg x-show="!showPwd" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwd" style="display:none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember">
                        Keep me signed in
                    </label>
                </div>

                <button type="submit" class="btn-signin" x-bind:disabled="loading">
                    <span x-show="!loading">Sign in</span>
                    <span x-show="loading" style="display:none" style="display:flex;align-items:center;gap:8px">
                        <span class="btn-spinner"></span> Signing in…
                    </span>
                </button>
            </form>

            <p class="register-link">
                New team member? <a href="{{ route('register') }}">Request access</a>
            </p>

            <div class="demo-section">
                <div class="demo-label">Quick demo access</div>
                <div class="demo-grid">
                    @foreach([['👔','Manager','manager@biteplate.com'],['🍽️','Waiter','waiter@biteplate.com'],['👨‍🍳','Chef','chef@biteplate.com'],['💳','Cashier','cashier@biteplate.com']] as [$ic,$rl,$em])
                    <button class="demo-btn" type="button" x-on:click="fillDemo('{{ $em }}')">
                        <span class="demo-icon">{{ $ic }}</span>
                        <div>
                            <div class="demo-role">{{ $rl }}</div>
                            <div class="demo-email">{{ $em }}</div>
                        </div>
                    </button>
                    @endforeach
                </div>
                <div class="demo-pwd">Password: <code>password</code></div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
