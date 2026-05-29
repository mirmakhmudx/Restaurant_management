<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Access — BitePlate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased">

<div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0 px-4">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-6">
        <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center text-2xl mb-3">🍽️</div>
        <span class="text-lg font-semibold text-gray-900">BitePlate SRMS</span>
        <span class="text-xs text-gray-400 mt-0.5">Staff Account Request</span>
    </div>

    <div class="w-full bg-white shadow-md rounded-xl overflow-hidden sm:max-w-md">

        {{-- Notice --}}
        <div class="px-6 pt-5">
            <div class="flex items-start gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                <span class="text-gray-400 text-sm mt-0.5">ℹ</span>
                <p class="text-gray-600 text-sm leading-relaxed">
                    New accounts require <strong class="text-gray-900">manager approval</strong> before sign-in is granted.
                </p>
            </div>
        </div>

        @if($errors->any())
        <div class="px-6 pt-4">
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach($errors->all() as $e)
                <p class="text-red-600 text-sm">{{ $e }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <div class="px-6 py-6" x-data="{
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
            get sText() { return ['','Weak','Fair','Good','Strong'][this.s]; },
            get sColor() { return ['bg-gray-200','bg-red-400','bg-yellow-400','bg-blue-400','bg-green-500'][this.s]; }
        }">

            <h2 class="text-xl font-semibold text-gray-900 mb-1">Create account</h2>
            <p class="text-sm text-gray-500 mb-6">Join your restaurant team</p>

            <form method="POST" action="{{ route('register') }}"
                x-on:submit="loading = true" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full name</label>
                    <input name="name" type="text" autocomplete="name" required
                        value="{{ old('name') }}" placeholder="Sarah Johnson"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                               placeholder-gray-400 outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                    <input name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}" placeholder="you@biteplate.com"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                               placeholder-gray-400 outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your role</label>
                    <input type="hidden" name="role" x-bind:value="role">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['manager', '👔', 'Manager',  'Full system access'],
                            ['waiter',  '🍽️', 'Waiter',   'Orders & tables'],
                            ['chef',    '👨‍🍳', 'Chef',     'Kitchen & queue'],
                            ['cashier', '💳', 'Cashier',  'Billing & payments'],
                        ] as [$val, $icon, $name, $desc])
                        <div x-on:click="role = '{{ $val }}'"
                            x-bind:class="role === '{{ $val }}'
                                ? 'border-gray-900 bg-gray-900'
                                : 'border-gray-200 bg-white hover:border-gray-400'"
                            class="relative p-3 border rounded-lg cursor-pointer transition-all select-none">
                            <span class="text-xl block mb-1">{{ $icon }}</span>
                            <p x-bind:class="role === '{{ $val }}' ? 'text-white' : 'text-gray-900'"
                                class="text-sm font-semibold">{{ $name }}</p>
                            <p x-bind:class="role === '{{ $val }}' ? 'text-gray-300' : 'text-gray-400'"
                                class="text-xs mt-0.5">{{ $desc }}</p>
                            <svg x-show="role === '{{ $val }}'"
                                class="absolute top-2 right-2 w-4 h-4 text-white"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                    <p x-show="!role" class="text-xs text-gray-400 mt-1.5">Select a role to continue</p>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input name="password"
                            x-bind:type="showPwd ? 'text' : 'password'"
                            x-model="password"
                            autocomplete="new-password" required placeholder="Min. 8 characters"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm text-gray-900
                                   placeholder-gray-400 outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        <button type="button" x-on:click="showPwd = !showPwd"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPwd" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Strength --}}
                    <div x-show="password.length > 0" style="display:none" class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <template x-for="i in 4" :key="i">
                                <div class="h-1 flex-1 rounded-full transition-all duration-300"
                                    x-bind:class="i <= s ? sColor : 'bg-gray-200'"></div>
                            </template>
                        </div>
                        <p class="text-xs text-gray-500" x-text="sText"></p>
                    </div>
                </div>

                {{-- Confirm --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password</label>
                    <div class="relative">
                        <input name="password_confirmation"
                            x-bind:type="showCon ? 'text' : 'password'"
                            autocomplete="new-password" required placeholder="Re-enter password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm text-gray-900
                                   placeholder-gray-400 outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                        <button type="button" x-on:click="showCon = !showCon"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                            <svg x-show="!showCon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showCon" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    x-bind:disabled="loading || !role"
                    class="w-full bg-gray-900 hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed
                           text-white font-medium py-2.5 rounded-lg text-sm transition-colors
                           flex items-center justify-center gap-2">
                    <span x-show="!loading">Submit request</span>
                    <span x-show="loading" style="display:none" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Submitting...
                    </span>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Already have an account?
                <a href="{{ route('login') }}" class="text-gray-900 font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>

    <p class="mt-6 text-xs text-gray-400">BitePlate SRMS · Unit 27: Advanced Programming · BTEC Level 5</p>
</div>

</body>
</html>
