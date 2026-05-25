@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-subtitle','Good ' . (date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening')) . ', ' . auth()->user()->name . '!')
@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($stats as $stat)
    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
            </div>
            <div class="text-2xl">{{ $stat['icon'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2"><span>⚡</span> Quick Actions</h3>
        <div class="space-y-2">
            @if(auth()->user()->canAccess('orders'))
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-xl text-blue-700 text-sm font-medium transition-colors">📋 View Orders</a>
            @endif
            @if(auth()->user()->canAccess('kitchen'))
            <a href="{{ route('kitchen.index') }}" class="flex items-center gap-3 px-4 py-3 bg-amber-50 hover:bg-amber-100 rounded-xl text-amber-700 text-sm font-medium transition-colors">👨‍🍳 Kitchen Display</a>
            @endif
            @if(auth()->user()->canAccess('billing'))
            <a href="{{ route('billing.index') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-emerald-700 text-sm font-medium transition-colors">💳 Billing</a>
            @endif
            @if(auth()->user()->canAccess('menu'))
            <a href="{{ route('menu.index') }}" class="flex items-center gap-3 px-4 py-3 bg-violet-50 hover:bg-violet-100 rounded-xl text-violet-700 text-sm font-medium transition-colors">🍴 Manage Menu</a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2"><span>🏗️</span> Design Patterns Active</h3>
        <div class="space-y-2">
            @foreach([['🔁','Singleton','OrderHistoryLog','bg-violet-50 text-violet-700'],['⚡','Command','KitchenQueue','bg-amber-50 text-amber-700'],['🎯','Strategy','PricingEngine','bg-blue-50 text-blue-700'],['👁️','Observer','OrderEvents','bg-green-50 text-green-700'],['📦','Repository','DataLayer','bg-pink-50 text-pink-700'],['🏭','Factory','MenuItemFactory','bg-orange-50 text-orange-700']] as [$icon,$name,$class,$color])
            <div class="flex items-center justify-between px-3 py-2 {{ $color }} rounded-lg text-xs">
                <span>{{ $icon }} <span class="font-semibold">{{ $name }}</span></span>
                <code class="opacity-75">{{ $class }}</code>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2"><span>👤</span> Account</h3>
        <div class="text-center py-4">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3
                @if(auth()->user()->isManager()) bg-violet-100
                @elseif(auth()->user()->isWaiter()) bg-blue-100
                @elseif(auth()->user()->isChef()) bg-amber-100
                @else bg-emerald-100 @endif">
                {{ auth()->user()->getRoleIcon() }}
            </div>
            <p class="font-bold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-gray-500 text-sm mt-0.5">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                @if(auth()->user()->isManager()) bg-violet-100 text-violet-700
                @elseif(auth()->user()->isWaiter()) bg-blue-100 text-blue-700
                @elseif(auth()->user()->isChef()) bg-amber-100 text-amber-700
                @else bg-emerald-100 text-emerald-700 @endif">
                {{ auth()->user()->getRoleLabel() }}
            </span>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 text-center">Access: {{ implode(' · ', auth()->user()->role->permissions()) }}</p>
        </div>
    </div>
</div>
@endsection
