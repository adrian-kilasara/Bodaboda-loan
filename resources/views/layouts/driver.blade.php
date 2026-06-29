<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Loan') — Bodaboda Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-neutral-50 font-sans text-neutral-900 antialiased flex flex-col">

{{-- Top bar --}}
<header class="bg-primary text-white px-4 py-4 flex items-center justify-between sticky top-0 z-20 shadow-sm">
    <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
            <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="font-bold text-sm">Bodaboda Pay</span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-xs text-white/80 px-2.5 py-1.5 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Sign out</button>
    </form>
</header>

{{-- Flash messages --}}
<div x-data="{ items: [] }" x-init="
        @if(session('success')) items.push({ id: 1, type: 'success', message: @js(session('success')) }); @endif
        @if(session('error')) items.push({ id: 2, type: 'error', message: @js(session('error')) }); @endif
        @if($errors->any()) items.push({ id: 3, type: 'error', message: @js($errors->first()) }); @endif
     "
     class="px-4 pt-3 space-y-2">
    <template x-for="item in items" :key="item.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="rounded-xl px-3.5 py-3 text-sm flex items-center gap-2.5"
             :class="item.type === 'success' ? 'bg-success-light text-success' : 'bg-danger-light text-danger'">
            <svg x-show="item.type === 'success'" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <svg x-show="item.type === 'error'" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span x-text="item.message"></span>
        </div>
    </template>
</div>

{{-- Page content --}}
<main class="flex-1 overflow-y-auto pb-24">
    @yield('content')
</main>

{{-- Bottom tab bar --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-200 flex pb-[env(safe-area-inset-bottom)] shadow-[0_-4px_12px_rgba(0,0,0,0.04)]">
    @php
        $tabs = [
            ['route' => 'driver.dashboard', 'match' => 'driver.dashboard', 'label' => 'Home',
             'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'driver.enrol', 'match' => 'driver.enrol*', 'label' => 'My Loan',
             'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['route' => 'driver.profile', 'match' => 'driver.profile*', 'label' => 'Profile',
             'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp
    @foreach($tabs as $tab)
    @php $active = request()->routeIs($tab['match']); @endphp
    <a href="{{ route($tab['route']) }}"
       class="relative flex-1 flex flex-col items-center py-3 text-xs font-medium transition-colors duration-150
              {{ $active ? 'text-primary' : 'text-neutral-400' }}">
        @if($active)
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-10 h-0.5 bg-primary rounded-full"></span>
        @endif
        <svg class="w-5 h-5 mb-1 transition-transform duration-150" :class="{}" style="{{ $active ? 'transform: translateY(-1px)' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
        </svg>
        {{ $tab['label'] }}
    </a>
    @endforeach
</nav>

@stack('scripts')
</body>
</html>
