<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Loan') — Bodaboda Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-neutral-100 font-sans text-neutral-900 flex flex-col">

{{-- Top bar --}}
<header class="bg-primary text-white px-4 py-3.5 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-2">
        <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="font-bold text-sm">Bodaboda Pay</span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-xs text-white/80 px-2 py-1 rounded hover:text-white">Sign out</button>
    </form>
</header>

{{-- Flash messages --}}
<div class="px-4 pt-3">
    @if(session('success'))
        <div class="bg-success-light border border-success text-success rounded-lg px-3 py-2.5 text-sm mb-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-danger-light border border-danger text-danger rounded-lg px-3 py-2.5 text-sm mb-3">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-danger-light border border-danger text-danger rounded-lg px-3 py-2.5 text-sm mb-3">
            {{ $errors->first() }}
        </div>
    @endif
</div>

{{-- Page content --}}
<main class="flex-1 overflow-y-auto pb-24">
    @yield('content')
</main>

{{-- Bottom tab bar --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-100 flex">
    <a href="{{ route('driver.dashboard') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs font-medium transition-colors
              {{ request()->routeIs('driver.dashboard') ? 'text-primary' : 'text-neutral-500' }}">
        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Home
    </a>
    <a href="{{ route('driver.enrol') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs font-medium transition-colors
              {{ request()->routeIs('driver.enrol*') ? 'text-primary' : 'text-neutral-500' }}">
        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        My Loan
    </a>
    <a href="{{ route('driver.profile') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs font-medium transition-colors
              {{ request()->routeIs('driver.profile*') ? 'text-primary' : 'text-neutral-500' }}">
        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Profile
    </a>
</nav>

@stack('scripts')
</body>
</html>
