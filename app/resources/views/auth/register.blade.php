<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Access — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: #0f0d0b; }

        .split { display: flex; min-height: 100vh; }

        .left-panel {
            width: 42%;
            background: #0f0d0b;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, #c2831840, transparent);
        }
        .bg-text {
            position: absolute; bottom: -60px; left: -30px;
            font-family: 'Playfair Display', serif;
            font-size: 300px; font-weight: 900;
            color: rgba(255,255,255,0.015);
            line-height: 1; user-select: none; pointer-events: none;
        }
        .logo-mark {
            display: inline-flex; align-items: center; justify-content: center;
            width: 40px; height: 40px;
            background: #e8a020; border-radius: 9px;
            font-size: 20px; margin-bottom: 12px;
        }
        .brand-name { font-size: 11px; font-weight: 700; color: #4a4038; letter-spacing: 5px; text-transform: uppercase; }

        .left-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 4vw, 52px);
            font-weight: 900; color: #faf8f5;
            line-height: 1.1; letter-spacing: -1.5px;
            margin-bottom: 20px;
        }
        .left-heading em { font-style: normal; color: #e8a020; }
        .left-desc { color: #6b5d54; font-size: 14px; line-height: 1.7; margin-bottom: 40px; }

        .steps { display: flex; flex-direction: column; gap: 0; }
        .step {
            display: flex; gap: 16px; align-items: flex-start;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 28px; height: 28px;
            background: rgba(232,160,32,0.1);
            border: 1px solid rgba(232,160,32,0.2);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #e8a020;
            flex-shrink: 0; margin-top: 1px;
        }
        .step-title { font-size: 13px; font-weight: 600; color: #c5bdb5; }
        .step-desc { font-size: 11px; color: #4a4038; margin-top: 2px; line-height: 1.5; }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: #4a4038; font-size: 12px; text-decoration: none;
            transition: color 0.15s;
        }
        .back-link:hover { color: #c5bdb5; }

        .right-panel {
            flex: 1;
            background: #fdf9f5;
            display: flex; align-items: center; justify-content: center;
            padding: 48px 56px;
            overflow-y: auto;
        }
        .form-wrap { width: 100%; max-width: 400px; padding: 20px 0; }

        .form-heading {
            font-family: 'Playfair Display', serif;
            font-size: 34px; font-weight: 900;
            color: #1a1614; letter-spacing: -1px; margin-bottom: 6px;
        }
        .form-sub { color: #9e9189; font-size: 14px; margin-bottom: 32px; }

        .alert-info {
            padding: 12px 16px;
            background: #fffbf0;
            border: 1px solid #f0d896;
            border-left: 3px solid #e8a020;
            border-radius: 8px;
            color: #7a6010;
            font-size: 12px; line-height: 1.6;
            margin-bottom: 28px;
        }
        .alert-error {
            padding: 12px 16px;
            background: #fff5f5;
            border: 1px solid #fca5a5;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            color: #991b1b; font-size: 12px;
            margin-bottom: 24px;
        }

        .field { margin-bottom: 28px; }
        .field label {
            display: block; font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 2px;
            color: #9e9189; margin-bottom: 10px;
        }
        .field-inner { position: relative; }
        .field input, .field select {
            width: 100%; background: transparent;
            border: none; border-bottom: 1.5px solid #d9d0c4;
            padding: 10px 0; font-size: 15px;
            color: #1a1614; font-family: 'Inter', sans-serif;
            outline: none; transition: border-color 0.2s;
            appearance: none;
        }
        .field input::placeholder { color: #c5bdb5; font-size: 14px; }
        .field input:focus, .field select:focus { border-bottom-color: #1a1614; }
        .field-btn {
            position: absolute; right: 0; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #b0a89e; padding: 4px; transition: color 0.15s;
        }
        .field-btn:hover { color: #1a1614; }

        /* Role cards */
        .role-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #9e9189; margin-bottom: 12px; }
        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px; }
        .role-card {
            padding: 14px;
            background: #fff;
            border: 1.5px solid #e8e3da;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }
        .role-card:hover { border-color: #1a1614; }
        .role-card.selected { border-color: #1a1614; background: #faf8f5; }
        .role-card.selected::after {
            content: '✓';
            position: absolute; top: 8px; right: 10px;
            font-size: 10px; font-weight: 700; color: #1a1614;
        }
        .role-card-icon { font-size: 22px; margin-bottom: 8px; display: block; }
        .role-card-name { font-size: 13px; font-weight: 600; color: #1a1614; }
        .role-card-desc { font-size: 10px; color: #9e9189; margin-top: 2px; }
        .role-hint { font-size: 11px; color: #c5bdb5; margin-bottom: 28px; }

        /* Strength bar */
        .strength-wrap { margin-top: 10px; }
        .strength-bars { display: flex; gap: 4px; margin-bottom: 6px; }
        .strength-bar {
            height: 2px; flex: 1; border-radius: 999px;
            background: #e8e3da; transition: background 0.3s;
        }
        .strength-bar.active-1 { background: #ef4444; }
        .strength-bar.active-2 { background: #f59e0b; }
        .strength-bar.active-3 { background: #3b82f6; }
        .strength-bar.active-4 { background: #10b981; }
        .strength-text { font-size: 11px; color: #9e9189; }

        .btn-submit {
            width: 100%; padding: 16px;
            background: #1a1614; color: #fdf9f5;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
            letter-spacing: 0.5px; border: none; border-radius: 10px;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s; margin-top: 8px; margin-bottom: 24px;
        }
        .btn-submit:hover { background: #2a2420; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,22,20,0.15); }
        .btn-submit:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
        .btn-spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .signin-link { text-align: center; font-size: 13px; color: #9e9189; }
        .signin-link a { color: #1a1614; font-weight: 600; text-decoration: none; border-bottom: 1px solid #d9d0c4; padding-bottom: 1px; }

        .footer-note { text-align: center; font-size: 11px; color: #c5bdb5; margin-top: 32px; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { padding: 40px 28px; }
        }
    </style>
</head>
<body x-data="{
    role: '{{ old('role') }}',
    password: '',
    showPwd: false,
    showCon: false,
    loading: false,
    get s() {
        let n=0,p=this.password;
        if(p.length>=8)n++;
        if(/[A-Z]/.test(p))n++;
        if(/[0-9]/.test(p))n++;
        if(/[^A-Za-z0-9]/.test(p))n++;
        return n;
    },
    get sText() { return ['','Weak','Fair','Good','Strong'][this.s]; }
}">

<div class="split">

    <!-- LEFT -->
    <div class="left-panel">
        <div class="bg-text">BP</div>

        <div style="position:relative;z-index:1">
            <div class="logo-mark">🍽️</div>
            <div class="brand-name">BitePlate</div>
        </div>

        <div style="position:relative;z-index:1">
            <h1 class="left-heading">
                Request<br>your<br>
                <em>access.</em>
            </h1>
            <p class="left-desc">Submit your details and a manager will review and activate your account before you can sign in.</p>

            <div class="steps">
                @foreach([
                    ['01','Submit your details','Fill in name, email, role and set a password'],
                    ['02','Manager reviews','A manager will verify and approve your request'],
                    ['03','Account activated','You will be notified when access is granted'],
                ] as [$n,$t,$d])
                <div class="step">
                    <div class="step-num">{{ $n }}</div>
                    <div>
                        <div class="step-title">{{ $t }}</div>
                        <div class="step-desc">{{ $d }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="position:relative;z-index:1">
            <a href="{{ route('login') }}" class="back-link">
                ← Back to sign in
            </a>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
        <div class="form-wrap">

            <h2 class="form-heading">Create account.</h2>
            <p class="form-sub">Your account will require manager approval</p>

            <div class="alert-info">
                ⚠ New accounts are inactive until approved by a manager.
            </div>

            @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" x-on:submit="loading=true">
                @csrf

                <div class="field">
                    <label>Full name</label>
                    <input name="name" type="text" autocomplete="name" required
                        value="{{ old('name') }}" placeholder="Sarah Johnson">
                </div>

                <div class="field">
                    <label>Email address</label>
                    <input name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}" placeholder="you@biteplate.com">
                </div>

                <!-- Role selection -->
                <div style="margin-bottom:28px">
                    <div class="role-label">Select your role</div>
                    <input type="hidden" name="role" x-bind:value="role">
                    <div class="role-grid">
                        @foreach([
                            ['manager','👔','Manager','Full access'],
                            ['waiter','🍽️','Waiter','Orders & tables'],
                            ['chef','👨‍🍳','Chef','Kitchen & queue'],
                            ['cashier','💳','Cashier','Billing & payments'],
                        ] as [$val,$icon,$name,$desc])
                        <div class="role-card" x-bind:class="role==='{{ $val }}'?'selected':''"
                            x-on:click="role='{{ $val }}'">
                            <span class="role-card-icon">{{ $icon }}</span>
                            <div class="role-card-name">{{ $name }}</div>
                            <div class="role-card-desc">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="role-hint" x-show="!role">← Select a role to continue</div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="field-inner">
                        <input name="password" x-bind:type="showPwd?'text':'password'"
                            x-model="password" autocomplete="new-password" required placeholder="Min. 8 characters">
                        <button type="button" class="field-btn" x-on:click="showPwd=!showPwd">
                            <svg x-show="!showPwd" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwd" style="display:none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="strength-wrap" x-show="password.length>0" style="display:none">
                        <div class="strength-bars">
                            <template x-for="i in 4" :key="i">
                                <div class="strength-bar"
                                    x-bind:class="i<=s ? 'active-'+s : ''"></div>
                            </template>
                        </div>
                        <div class="strength-text" x-text="sText"></div>
                    </div>
                </div>

                <div class="field">
                    <label>Confirm password</label>
                    <div class="field-inner">
                        <input name="password_confirmation" x-bind:type="showCon?'text':'password'"
                            autocomplete="new-password" required placeholder="Re-enter password">
                        <button type="button" class="field-btn" x-on:click="showCon=!showCon">
                            <svg x-show="!showCon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showCon" style="display:none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" x-bind:disabled="loading||!role">
                    <span x-show="!loading">Submit request</span>
                    <span x-show="loading" style="display:none;align-items:center;gap:8px">
                        <span class="btn-spinner"></span> Submitting…
                    </span>
                </button>
            </form>

            <p class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>

            <p class="footer-note">
                BitePlate SRMS · Unit 27: Advanced Programming · BTEC Level 5
            </p>
        </div>
    </div>
</div>
</body>
</html>
EOFcat > app/resources/views/auth/register.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Access — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: #0f0d0b; }

        .split { display: flex; min-height: 100vh; }

        .left-panel {
            width: 42%;
            background: #0f0d0b;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, #c2831840, transparent);
        }
        .bg-text {
            position: absolute; bottom: -60px; left: -30px;
            font-family: 'Playfair Display', serif;
            font-size: 300px; font-weight: 900;
            color: rgba(255,255,255,0.015);
            line-height: 1; user-select: none; pointer-events: none;
        }
        .logo-mark {
            display: inline-flex; align-items: center; justify-content: center;
            width: 40px; height: 40px;
            background: #e8a020; border-radius: 9px;
            font-size: 20px; margin-bottom: 12px;
        }
        .brand-name { font-size: 11px; font-weight: 700; color: #4a4038; letter-spacing: 5px; text-transform: uppercase; }

        .left-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 4vw, 52px);
            font-weight: 900; color: #faf8f5;
            line-height: 1.1; letter-spacing: -1.5px;
            margin-bottom: 20px;
        }
        .left-heading em { font-style: normal; color: #e8a020; }
        .left-desc { color: #6b5d54; font-size: 14px; line-height: 1.7; margin-bottom: 40px; }

        .steps { display: flex; flex-direction: column; gap: 0; }
        .step {
            display: flex; gap: 16px; align-items: flex-start;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 28px; height: 28px;
            background: rgba(232,160,32,0.1);
            border: 1px solid rgba(232,160,32,0.2);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #e8a020;
            flex-shrink: 0; margin-top: 1px;
        }
        .step-title { font-size: 13px; font-weight: 600; color: #c5bdb5; }
        .step-desc { font-size: 11px; color: #4a4038; margin-top: 2px; line-height: 1.5; }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: #4a4038; font-size: 12px; text-decoration: none;
            transition: color 0.15s;
        }
        .back-link:hover { color: #c5bdb5; }

        .right-panel {
            flex: 1;
            background: #fdf9f5;
            display: flex; align-items: center; justify-content: center;
            padding: 48px 56px;
            overflow-y: auto;
        }
        .form-wrap { width: 100%; max-width: 400px; padding: 20px 0; }

        .form-heading {
            font-family: 'Playfair Display', serif;
            font-size: 34px; font-weight: 900;
            color: #1a1614; letter-spacing: -1px; margin-bottom: 6px;
        }
        .form-sub { color: #9e9189; font-size: 14px; margin-bottom: 32px; }

        .alert-info {
            padding: 12px 16px;
            background: #fffbf0;
            border: 1px solid #f0d896;
            border-left: 3px solid #e8a020;
            border-radius: 8px;
            color: #7a6010;
            font-size: 12px; line-height: 1.6;
            margin-bottom: 28px;
        }
        .alert-error {
            padding: 12px 16px;
            background: #fff5f5;
            border: 1px solid #fca5a5;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            color: #991b1b; font-size: 12px;
            margin-bottom: 24px;
        }

        .field { margin-bottom: 28px; }
        .field label {
            display: block; font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 2px;
            color: #9e9189; margin-bottom: 10px;
        }
        .field-inner { position: relative; }
        .field input, .field select {
            width: 100%; background: transparent;
            border: none; border-bottom: 1.5px solid #d9d0c4;
            padding: 10px 0; font-size: 15px;
            color: #1a1614; font-family: 'Inter', sans-serif;
            outline: none; transition: border-color 0.2s;
            appearance: none;
        }
        .field input::placeholder { color: #c5bdb5; font-size: 14px; }
        .field input:focus, .field select:focus { border-bottom-color: #1a1614; }
        .field-btn {
            position: absolute; right: 0; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #b0a89e; padding: 4px; transition: color 0.15s;
        }
        .field-btn:hover { color: #1a1614; }

        /* Role cards */
        .role-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #9e9189; margin-bottom: 12px; }
        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px; }
        .role-card {
            padding: 14px;
            background: #fff;
            border: 1.5px solid #e8e3da;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }
        .role-card:hover { border-color: #1a1614; }
        .role-card.selected { border-color: #1a1614; background: #faf8f5; }
        .role-card.selected::after {
            content: '✓';
            position: absolute; top: 8px; right: 10px;
            font-size: 10px; font-weight: 700; color: #1a1614;
        }
        .role-card-icon { font-size: 22px; margin-bottom: 8px; display: block; }
        .role-card-name { font-size: 13px; font-weight: 600; color: #1a1614; }
        .role-card-desc { font-size: 10px; color: #9e9189; margin-top: 2px; }
        .role-hint { font-size: 11px; color: #c5bdb5; margin-bottom: 28px; }

        /* Strength bar */
        .strength-wrap { margin-top: 10px; }
        .strength-bars { display: flex; gap: 4px; margin-bottom: 6px; }
        .strength-bar {
            height: 2px; flex: 1; border-radius: 999px;
            background: #e8e3da; transition: background 0.3s;
        }
        .strength-bar.active-1 { background: #ef4444; }
        .strength-bar.active-2 { background: #f59e0b; }
        .strength-bar.active-3 { background: #3b82f6; }
        .strength-bar.active-4 { background: #10b981; }
        .strength-text { font-size: 11px; color: #9e9189; }

        .btn-submit {
            width: 100%; padding: 16px;
            background: #1a1614; color: #fdf9f5;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
            letter-spacing: 0.5px; border: none; border-radius: 10px;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s; margin-top: 8px; margin-bottom: 24px;
        }
        .btn-submit:hover { background: #2a2420; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,22,20,0.15); }
        .btn-submit:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
        .btn-spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .signin-link { text-align: center; font-size: 13px; color: #9e9189; }
        .signin-link a { color: #1a1614; font-weight: 600; text-decoration: none; border-bottom: 1px solid #d9d0c4; padding-bottom: 1px; }

        .footer-note { text-align: center; font-size: 11px; color: #c5bdb5; margin-top: 32px; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { padding: 40px 28px; }
        }
    </style>
</head>
<body x-data="{
    role: '{{ old('role') }}',
    password: '',
    showPwd: false,
    showCon: false,
    loading: false,
    get s() {
        let n=0,p=this.password;
        if(p.length>=8)n++;
        if(/[A-Z]/.test(p))n++;
        if(/[0-9]/.test(p))n++;
        if(/[^A-Za-z0-9]/.test(p))n++;
        return n;
    },
    get sText() { return ['','Weak','Fair','Good','Strong'][this.s]; }
}">

<div class="split">

    <!-- LEFT -->
    <div class="left-panel">
        <div class="bg-text">BP</div>

        <div style="position:relative;z-index:1">
            <div class="logo-mark">🍽️</div>
            <div class="brand-name">BitePlate</div>
        </div>

        <div style="position:relative;z-index:1">
            <h1 class="left-heading">
                Request<br>your<br>
                <em>access.</em>
            </h1>
            <p class="left-desc">Submit your details and a manager will review and activate your account before you can sign in.</p>

            <div class="steps">
                @foreach([
                    ['01','Submit your details','Fill in name, email, role and set a password'],
                    ['02','Manager reviews','A manager will verify and approve your request'],
                    ['03','Account activated','You will be notified when access is granted'],
                ] as [$n,$t,$d])
                <div class="step">
                    <div class="step-num">{{ $n }}</div>
                    <div>
                        <div class="step-title">{{ $t }}</div>
                        <div class="step-desc">{{ $d }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="position:relative;z-index:1">
            <a href="{{ route('login') }}" class="back-link">
                ← Back to sign in
            </a>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
        <div class="form-wrap">

            <h2 class="form-heading">Create account.</h2>
            <p class="form-sub">Your account will require manager approval</p>

            <div class="alert-info">
                ⚠ New accounts are inactive until approved by a manager.
            </div>

            @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" x-on:submit="loading=true">
                @csrf

                <div class="field">
                    <label>Full name</label>
                    <input name="name" type="text" autocomplete="name" required
                        value="{{ old('name') }}" placeholder="Sarah Johnson">
                </div>

                <div class="field">
                    <label>Email address</label>
                    <input name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}" placeholder="you@biteplate.com">
                </div>

                <!-- Role selection -->
                <div style="margin-bottom:28px">
                    <div class="role-label">Select your role</div>
                    <input type="hidden" name="role" x-bind:value="role">
                    <div class="role-grid">
                        @foreach([
                            ['manager','👔','Manager','Full access'],
                            ['waiter','🍽️','Waiter','Orders & tables'],
                            ['chef','👨‍🍳','Chef','Kitchen & queue'],
                            ['cashier','💳','Cashier','Billing & payments'],
                        ] as [$val,$icon,$name,$desc])
                        <div class="role-card" x-bind:class="role==='{{ $val }}'?'selected':''"
                            x-on:click="role='{{ $val }}'">
                            <span class="role-card-icon">{{ $icon }}</span>
                            <div class="role-card-name">{{ $name }}</div>
                            <div class="role-card-desc">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="role-hint" x-show="!role">← Select a role to continue</div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="field-inner">
                        <input name="password" x-bind:type="showPwd?'text':'password'"
                            x-model="password" autocomplete="new-password" required placeholder="Min. 8 characters">
                        <button type="button" class="field-btn" x-on:click="showPwd=!showPwd">
                            <svg x-show="!showPwd" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwd" style="display:none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="strength-wrap" x-show="password.length>0" style="display:none">
                        <div class="strength-bars">
                            <template x-for="i in 4" :key="i">
                                <div class="strength-bar"
                                    x-bind:class="i<=s ? 'active-'+s : ''"></div>
                            </template>
                        </div>
                        <div class="strength-text" x-text="sText"></div>
                    </div>
                </div>

                <div class="field">
                    <label>Confirm password</label>
                    <div class="field-inner">
                        <input name="password_confirmation" x-bind:type="showCon?'text':'password'"
                            autocomplete="new-password" required placeholder="Re-enter password">
                        <button type="button" class="field-btn" x-on:click="showCon=!showCon">
                            <svg x-show="!showCon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showCon" style="display:none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" x-bind:disabled="loading||!role">
                    <span x-show="!loading">Submit request</span>
                    <span x-show="loading" style="display:none;align-items:center;gap:8px">
                        <span class="btn-spinner"></span> Submitting…
                    </span>
                </button>
            </form>

            <p class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>

            <p class="footer-note">
                BitePlate SRMS · Unit 27: Advanced Programming · BTEC Level 5
            </p>
        </div>
    </div>
</div>
</body>
</html>
