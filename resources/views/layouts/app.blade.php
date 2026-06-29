<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Bodaboda Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-neutral-50 font-sans text-neutral-900 antialiased" x-data="{ sidebarOpen: true, mobileOpen: false }">

<div class="flex h-full">
    {{-- Mobile overlay --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity @click="mobileOpen = false"
         class="fixed inset-0 bg-neutral-900/50 z-30 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside class="bg-white border-r border-neutral-200 flex flex-col transition-all duration-300 ease-out fixed lg:relative z-40 h-full"
           :class="[sidebarOpen ? 'w-64' : 'w-16', mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-4 py-5 border-b border-neutral-200 h-[68px]">
            <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-bold text-neutral-900 text-[15px] whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity>Bodaboda Pay</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2.5 py-4 space-y-0.5 overflow-y-auto">
            @if(auth()->user()->isOwner())
                <a href="{{ route('owner.dashboard') }}"
                   class="nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                </a>
                <a href="{{ route('motorcycles.index') }}"
                   class="nav-item {{ request()->routeIs('motorcycles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Motorcycles</span>
                </a>
                <a href="{{ route('contracts.index') }}"
                   class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Loan Contracts</span>
                </a>
                <a href="{{ route('contacts.index') }}"
                   class="nav-item {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Contacts</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Users</span>
                </a>
                <a href="{{ route('admin.contracts.index') }}"
                   class="nav-item {{ request()->routeIs('admin.contracts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">All Contracts</span>
                </a>
            @endif
        </nav>

        {{-- User card + collapse toggle --}}
        <div class="px-2.5 py-3 border-t border-neutral-200 space-y-1">
            <div class="flex items-center gap-2.5 px-1.5 py-2 rounded-lg" x-show="sidebarOpen" x-transition.opacity>
                <div class="w-8 h-8 rounded-full bg-primary-light text-primary flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-neutral-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-neutral-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                    class="hidden lg:flex w-full items-center justify-center p-2 rounded-lg hover:bg-neutral-100 text-neutral-400 hover:text-neutral-700 transition-colors">
                <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     :style="sidebarOpen ? '' : 'transform: rotate(180deg)'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white/90 backdrop-blur border-b border-neutral-200 px-4 lg:px-6 py-4 flex items-center justify-between h-[68px] flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="mobileOpen = true" class="lg:hidden text-neutral-500 hover:text-neutral-900 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-lg font-bold text-neutral-900 truncate">@yield('title', 'Dashboard')</h1>
                    @hasSection('subtitle')
                        <p class="text-sm text-neutral-500 truncate">@yield('subtitle')</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-neutral-400 capitalize leading-tight">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="icon-action icon-action-danger" title="Sign out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- Enrolment key banner (persistent, not a toast - must be copied) --}}
        @if(session('enrolment_key'))
        <div class="px-4 lg:px-6 pt-4">
            <div class="bg-accent-light border border-accent rounded-xl px-4 py-3 text-sm animate-slide-up flex items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-neutral-900 mb-1">Enrolment Key — copy now, shown once only:</p>
                    <code class="text-xl font-mono font-bold tracking-widest text-primary">{{ session('enrolment_key') }}</code>
                </div>
                <svg class="w-8 h-8 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 11-12 0 6 6 0 0112 0zM2 22l4.586-4.586m0 0a2 2 0 102.828-2.828 2 2 0 00-2.828 2.828z"/>
                </svg>
            </div>
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-6 pb-8" x-data>
            @yield('content')
        </main>
    </div>
</div>

{{-- Toast notifications --}}
<div x-data x-init="
        @if(session('success')) $store.toast.push(@js(session('success')), 'success'); @endif
        @if(session('error')) $store.toast.push(@js(session('error')), 'error'); @endif
     "
     class="fixed top-4 right-4 z-[100] w-80 space-y-2.5 pointer-events-none">
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-6"
             x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-x-3"
             class="pointer-events-auto dialog-panel border overflow-hidden"
             :class="toast.type === 'success' ? 'border-success/20' : 'border-danger/20'">
            <div class="flex items-start gap-3 px-4 py-3.5">
                <svg x-show="toast.type === 'success'" class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <svg x-show="toast.type === 'error'" class="w-5 h-5 text-danger flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-neutral-700 flex-1" x-text="toast.message"></p>
                <button @click="$store.toast.remove(toast.id)" class="text-neutral-300 hover:text-neutral-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>

@stack('scripts')
</body>
</html>
