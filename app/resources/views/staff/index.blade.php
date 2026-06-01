@extends('layouts.app')
@section('title', 'Staff')
@section('page-title', 'Staff Management')
@section('page-subtitle', 'Manage team members and access control')

@section('content')

{{-- Pending approval alert --}}
@if($pending > 0)
<div class="mb-5 flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg">
    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <p class="text-amber-700 text-sm">
        <strong>{{ $pending }} staff member{{ $pending > 1 ? 's' : '' }}</strong> awaiting approval.
        <a href="{{ route('staff.index', ['filter'=>'pending']) }}" class="underline font-semibold">Review now</a>
    </p>
</div>
@endif

{{-- Filter tabs --}}
<div class="flex items-center gap-1 mb-5 bg-gray-100 p-1 rounded-lg w-fit">
    <a href="{{ route('staff.index') }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium transition-all
       {{ $filter === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        All ({{ \App\Models\User::count() }})
    </a>
    <a href="{{ route('staff.shifts') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Navbatlar
    </a>
    <a href="{{ route('staff.index', ['filter'=>'pending']) }}"
       class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition-all
       {{ $filter === 'pending' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        Pending
        @if($pending > 0)
        <span class="w-4 h-4 bg-amber-500 text-white text-xs rounded-full flex items-center justify-center leading-none">
            {{ $pending }}
        </span>
        @endif
    </a>
    @foreach($roles as $role)
    <a href="{{ route('staff.index', ['filter'=>$role->value]) }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium transition-all
       {{ $filter === $role->value ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        {{ $role->label() }}
    </a>
    @endforeach
</div>

{{-- Staff table --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Member</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Role</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Joined</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($staff as $member)
            <tr class="hover:bg-gray-50 transition-colors {{ !$member->is_active ? 'opacity-60' : '' }}">
                {{-- Name + avatar --}}
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                            {{ $member->is_active ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-500' }}">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $member->name }}
                                @if($member->id === auth()->id())
                                <span class="text-xs text-gray-400">(you)</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $member->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Role + change --}}
                <td class="px-5 py-3.5">
                    @if($member->id !== auth()->id())
                    <form method="POST" action="{{ route('staff.role', $member) }}" class="flex items-center gap-1">
                        @csrf @method('PATCH')
                        <select name="role" x-on:change="$el.closest('form').submit()"
                                class="text-xs border border-gray-200 rounded-lg px-2 py-1 text-gray-700 bg-white focus:outline-none focus:border-gray-900">
                            @foreach(\App\Enums\StaffRole::cases() as $r)
                            <option value="{{ $r->value }}" {{ $member->role === $r ? 'selected' : '' }}>
                                {{ $r->icon() }} {{ $r->label() }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full">
                        {{ $member->role->icon() }} {{ $member->role->label() }}
                    </span>
                    @endif
                </td>

                {{-- Status --}}
                <td class="px-5 py-3.5">
                    @if($member->is_active)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Active
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Pending
                    </span>
                    @endif
                </td>

                {{-- Joined --}}
                <td class="px-5 py-3.5 text-xs text-gray-500">
                    {{ $member->created_at->format('d M Y') }}
                </td>

                {{-- Actions --}}
                <td class="px-5 py-3.5 text-right">
                    @if($member->id !== auth()->id())
                    <form method="POST" action="{{ route('staff.toggle', $member) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="text-xs px-3 py-1.5 rounded-lg border font-medium transition-colors
                            {{ $member->is_active
                                ? 'border-red-200 text-red-600 hover:bg-red-50'
                                : 'border-green-200 text-green-700 bg-green-50 hover:bg-green-100' }}">
                            {{ $member->is_active ? 'Deactivate' : 'Approve' }}
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No staff found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
