@extends('layouts.app')
@section('title', 'Process Bill')
@section('page-title', 'Process Bill')
@section('page-subtitle', 'Select pricing strategy — Strategy Pattern in action')

@section('content')
<div class="max-w-2xl" x-data="{
    strategy: 'Standard',
    subtotal: {{ $order->subtotal }},
    strategies: @js(collect($strategies)->map(fn($s) => [
        'name'    => $s->getName(),
        'desc'    => $s->getDescription(),
        'pct'     => $s->getDiscountPercent(),
        'icon'    => $s->getIcon(),
    ])->values()),
    get discount() {
        const s = this.strategies.find(x => x.name === this.strategy);
        return s ? Math.round(this.subtotal * (s.pct / 100) * 100) / 100 : 0;
    },
    get total() { return Math.round((this.subtotal - this.discount) * 100) / 100; },
    fmt(n)      { return '£' + n.toFixed(2); }
}">
    {{-- Tax Rate --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
        <input type="number" name="tax_rate" value="20" min="0" max="100" step="0.5"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
    </div>

    {{-- Service Fee --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Service Fee (£)</label>
        <input type="number" name="service_fee" value="0" min="0" step="0.50"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

        {{-- Left: form --}}
        <div class="md:col-span-3 space-y-5">

            {{-- Order summary --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">
                    Order {{ $order->order_number }}
                </h3>
                <div class="space-y-2 mb-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700">{{ $item->quantity }}× {{ $item->name }}</span>
                        <span class="text-gray-900 font-medium">{{ $item->getFormattedSubtotal() }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-2 flex justify-between text-sm font-semibold text-gray-900">
                    <span>Subtotal</span>
                    <span>£{{ number_format($order->subtotal, 2) }}</span>
                </div>
            </div>

            {{-- Strategy selection --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">
                    Pricing Strategy
                    <span class="text-xs text-gray-400 font-normal ml-1">— Strategy Pattern</span>
                </h3>

                <div class="grid grid-cols-2 gap-2">
                    <template x-for="s in strategies" :key="s.name">
                        <label class="flex flex-col p-3 border-2 rounded-xl cursor-pointer transition-all select-none"
                               x-bind:class="strategy === s.name
                                   ? 'border-gray-900 bg-gray-900'
                                   : 'border-gray-200 hover:border-gray-400'">
                            <input type="radio" x-bind:value="s.name" x-model="strategy" class="sr-only">
                            <div class="flex items-center gap-2 mb-1">
                                <span x-text="s.icon" class="text-lg"></span>
                                <span class="font-semibold text-sm"
                                      x-bind:class="strategy === s.name ? 'text-white' : 'text-gray-900'"
                                      x-text="s.name"></span>
                                <span x-show="s.pct > 0"
                                      class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded"
                                      x-bind:class="strategy === s.name ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700'"
                                      x-text="'-' + s.pct + '%'"></span>
                            </div>
                            <span class="text-xs"
                                  x-bind:class="strategy === s.name ? 'text-gray-300' : 'text-gray-500'"
                                  x-text="s.desc"></span>
                        </label>
                    </template>
                </div>
            </div>

            {{-- Payment method --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Method</h3>
                <div class="flex gap-2" x-data="{ method: 'cash' }">
                    @foreach([['cash','💵','Cash'],['card','💳','Card'],['contactless','📱','Contactless']] as [$val,$icon,$label])
                    <label class="flex flex-col items-center p-3 border-2 rounded-xl cursor-pointer transition-all select-none
                                  has-[:checked]:border-gray-900 has-[:checked]:bg-gray-900 border-gray-200 hover:border-gray-400">
                        <input type="radio" name="payment_method_sel" value="{{ $val }}" class="sr-only"
                               {{ $val === 'cash' ? 'checked' : '' }}>
                        <span class="text-2xl mb-1">{{ $icon }}</span>
                        <span class="text-xs font-medium text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: live totals --}}
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl p-5 sticky top-6">
                <h3 class="font-semibold text-gray-900 mb-4">Bill Summary</h3>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900" x-text="fmt(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="discount > 0">
                        <span class="text-green-600">Discount (<span x-text="strategy"></span>)</span>
                        <span class="text-green-600 font-medium" x-text="'-' + fmt(discount)"></span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 flex justify-between">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-gray-900 text-xl" x-text="fmt(total)"></span>
                    </div>
                </div>

                <form method="POST" action="{{ route('billing.store') }}"
                      x-on:submit="
                        $el.querySelector('[name=strategy]').value = strategy;
                        $el.querySelector('[name=payment_method]').value =
                            document.querySelector('[name=payment_method_sel]:checked')?.value || 'cash';
                      ">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="strategy">
                    <input type="hidden" name="payment_method">

                    <button type="submit"
                            class="w-full py-3 bg-gray-900 hover:bg-gray-800 text-white font-semibold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Confirm Payment · <span x-text="fmt(total)"></span>
                    </button>
                </form>

                <a href="{{ route('billing.index') }}"
                   class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
        {{-- Split Bill Option --}}
        <div class="flex-1">
                    <label class="block text-sm font-medium text-amber-800 mb-1">To'lov usuli</label>
                    <select name="payment_method" class="w-full border border-amber-300 rounded-lg px-3 py-2 text-sm bg-white">
                        <option value="cash">Naqd</option>
                        <option value="card">Karta</option>
                    </select>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 text-sm">
                    Bo'lish
                </button>
            </form>

            <p class="text-xs text-amber-600 mt-2">
                Har bir qism: £{{ number_format($order->total / 2, 2) }} (2 ga bo'lganda, tax qo'shiladi)
            </p>
        </div>

    </div>
</div>

{{-- Split Bill --}}
{{-- Split Bill --}}
<div class="mt-4 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm" x-data="{ split: 2 }">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-base">💳</span>
        <div>
            <h4 class="font-semibold text-gray-900 text-sm">Split Bill</h4>
            <p class="text-xs text-gray-400">Hisobni bo'lib to'lash</p>
        </div>
    </div>
    <form method="POST" action="{{ route('billing.split', $order) }}">
        @csrf
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <p class="text-xs font-medium text-gray-600 mb-1.5">Necha kishi?</p>
                <div class="flex gap-1">
                    @foreach([2,3,4,5] as $n)
                        <button type="button"
                                x-on:click="split = {{ $n }}"
                                x-bind:class="split === {{ $n }} ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="flex-1 py-1.5 text-sm font-bold rounded-lg transition-all">
                            {{ $n }}
                        </button>
                    @endforeach
                    <input type="hidden" name="split_count" x-bind:value="split">
                </div>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-600 mb-1.5">To'lov usuli</p>
                <select name="payment_method"
                        class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm bg-white focus:ring-2 focus:ring-gray-900">
                    <option value="cash">💵 Naqd</option>
                    <option value="card">💳 Karta</option>
                    <option value="online">📱 Online</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between bg-gray-50 rounded-xl px-3 py-2 mb-3">
            <span class="text-xs text-gray-500">Har bir qism (tax 20%):</span>
            <span class="font-bold text-gray-900"
                  x-text="'£' + (({{ $order->total }} / split * 1.2)).toFixed(2)"></span>
        </div>

        <button type="submit"
                class="w-full py-2 bg-gray-900 text-white font-semibold rounded-xl text-sm hover:bg-gray-800 transition-colors">
            Bo'lib to'lash
        </button>
    </form>
</div>

@endsection
