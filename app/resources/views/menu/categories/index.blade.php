@extends('layouts.app')
@section('title', 'Kategoriyalar')
@section('page-title', 'Menu Kategoriyalar')
@section('page-subtitle', 'Kategorialarni boshqarish')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Yangi kategoriya qo'shish --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Yangi kategoriya</h3>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach($errors->all() as $e)
                        <p class="text-red-600 text-sm">{{ $e }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('menu.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Kategoriya nomi <span class="text-red-500">*</span>
                    </label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           placeholder="masalan: Uzbek Taomlar"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Emoji icon <span class="text-red-500">*</span>
                    </label>
                    <input name="icon" type="text" required value="{{ old('icon', '🍽️') }}"
                           placeholder="🍽️"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                    <p class="text-xs text-gray-400 mt-1">Bitta emoji kiriting</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Tartib raqami</label>
                    <input name="sort_order" type="number" min="1" value="{{ old('sort_order') }}"
                           placeholder="{{ $categories->count() + 1 }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 transition-all">
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Qo'shish
                </button>
            </form>
        </div>

        {{-- Kategoriyalar ro'yxati --}}
        <div class="lg:col-span-2">
            <h3 class="font-bold text-gray-900 text-sm mb-4">
                Kategoriyalar ({{ $categories->count() }} ta)
            </h3>

            @if(session('success'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                @forelse($categories as $cat)
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $cat->icon }}</span>
                            <div>
                                <p class="font-bold text-gray-900">{{ $cat->name }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $cat->menuItems()->count() }} ta taom
                                    · /{{ $cat->slug }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('menu.categories.toggle', $cat) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-xs px-2.5 py-1.5 rounded-lg border font-semibold transition-all
                                {{ $cat->is_active
                                    ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'
                                    : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                                    {{ $cat->is_active ? '● Faol' : '○ Nofaol' }}
                                </button>
                            </form>

                            @if($cat->menuItems()->count() === 0)
                                <form method="POST" action="{{ route('menu.categories.destroy', $cat) }}"
                                      onsubmit="return confirm('{{ $cat->name }} ni o\'chirasizmi?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-300 px-2">O'chirib bo'lmaydi</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-sm">
                        Hali kategoriya yo'q
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                <a href="{{ route('menu.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">
                    ← Menu ga qaytish
                </a>
            </div>
        </div>
    </div>

@endsection
