<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased">

<div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-6">
        <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center text-2xl mb-3">
            🍽️
        </div>
        <span class="text-lg font-semibold text-gray-900">BitePlate SRMS</span>
        <span class="text-xs text-gray-400 mt-0.5">Smart Restaurant Management</span>
    </div>

    {{-- Card --}}
    <div class="w-full bg-white shadow-md rounded-xl overflow-hidden sm:max-w-md">

        {{-- Pending message --}}
        @if(session('pending'))
        <div class="px-6 pt-5">
            <div class="flex items-start gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg">
                <span class="text-amber-500 text-sm mt-0.5">⏳</span>
                <p class="text-amber-700 text-sm">{{ session('pending') }}</p>
            </div>
        </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
        <div class="px-6 pt-5">
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach($errors->all() as $e)
                <p class="text-red-600 text-sm">{{ $e }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <div class="px-6 py-6" x-data="{ showPwd: false, loading: false }">

            <h2 class="text-xl font-semibold text-gray-900 mb-1">Welcome back</h2>
            <p class="text-sm text-gray-500 mb-6">Sign in to your staff account</p>

            <form method="POST" action="{{ route('login') }}" x-on:submit="loading = true" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="email">
                        Email address
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email"
                        value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                               placeholder-gray-400 outline-none transition-all
                               focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                        placeholder="you@biteplate.com">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" name="password"
                            x-bind:type="showPwd ? 'text' : 'password'"
                            autocomplete="current-password" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm
                                   text-gray-900 placeholder-gray-400 outline-none transition-all
                                   focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                            placeholder="••••••••">
                        <button type="button" x-on:click="showPwd = !showPwd"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPwd" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                        <span class="text-sm text-gray-600">Keep me signed in</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" x-bind:disabled="loading"
                    class="w-full bg-gray-900 hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed
                           text-white font-medium py-2.5 rounded-lg text-sm transition-colors
                           flex items-center justify-center gap-2">
                    <span x-show="!loading">Sign in</span>
                    <span x-show="loading" style="display:none" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                New team member?
                <a href="{{ route('register') }}" class="text-gray-900 font-semibold hover:underline">Request access</a>
            </p>
        </div>

        {{-- Demo accounts --}}
        <div class="px-6 pb-6 border-t border-gray-100 pt-5" x-data>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider text-center mb-3">
                Demo accounts · password: <code class="normal-case font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">password</code>
            </p>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['👔', 'Manager',  'manager@biteplate.com'],
                    ['🍽️', 'Waiter',   'waiter@biteplate.com'],
                    ['👨‍🍳', 'Chef',     'chef@biteplate.com'],
                    ['💳', 'Cashier',  'cashier@biteplate.com'],
                ] as [$icon, $role, $email])
                <button type="button"
                    onclick="document.getElementById('email').value='{{ $email }}';document.getElementById('password').value='password'"
                    class="flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 rounded-lg
                           hover:border-gray-900 hover:bg-gray-50 transition-all text-left group">
                    <span class="text-lg leading-none">{{ $icon }}</span>
                    <div>
                        <p class="text-xs font-semibold text-gray-900">{{ $role }}</p>
                        <p class="text-gray-400 truncate" style="font-size:10px">{{ $email }}</p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <p class="mt-6 text-xs text-gray-400">
        BitePlate SRMS · Unit 27: Advanced Programming · BTEC Level 5
    </p>
</div>

</body>
</html>
