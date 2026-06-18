<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Bodaboda Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-neutral-100 font-sans flex items-center justify-center min-h-screen">
<div class="w-full max-w-sm px-4">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-neutral-900">Bodaboda Pay</h1>
        <p class="text-neutral-500 text-sm mt-1">Loan management for bodaboda owners & drivers</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
        <h2 class="text-lg font-semibold mb-5">Sign in to your account</h2>

        @if($errors->any())
            <div class="bg-danger-light border border-danger text-danger rounded-lg px-4 py-3 text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-danger-light border border-danger text-danger rounded-lg px-4 py-3 text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="form-label">Email address</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="email"
                       value="{{ old('email') }}"
                       class="form-input @error('email') border-danger @enderror"
                       placeholder="you@example.com">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="form-input @error('password') border-danger @enderror"
                       placeholder="••••••••">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-neutral-600">
                    <input type="checkbox" name="remember" class="rounded border-neutral-300 text-primary">
                    Remember me
                </label>
            </div>
            <button type="submit" class="btn-primary w-full justify-center py-3">
                Sign in
            </button>
        </form>

        <p class="text-center text-sm text-neutral-500 mt-5">
            No account?
            <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Create one</a>
        </p>
    </div>

    {{-- Demo credentials panel --}}
    <div class="bg-white rounded-xl border border-neutral-100 p-4 mt-4">
        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-2">Demo Accounts</p>
        <div class="space-y-2">
            @foreach([
                ['Admin',  'admin@bodaboda.test',  'bg-purple-100 text-purple-700'],
                ['Owner',  'owner@bodaboda.test',  'bg-accent-light text-accent'],
                ['Driver', 'driver@bodaboda.test', 'badge-pending'],
            ] as [$role, $email, $badge])
            <div class="flex items-center justify-between cursor-pointer group"
                 onclick="document.getElementById('email').value='{{ $email }}'; document.getElementById('password').value='password';">
                <div class="flex items-center gap-2">
                    <span class="badge {{ $badge }} text-xs">{{ $role }}</span>
                    <span class="text-xs text-neutral-600 font-mono">{{ $email }}</span>
                </div>
                <span class="text-xs text-primary opacity-0 group-hover:opacity-100 transition-opacity">Fill →</span>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-neutral-400 mt-2 text-center">Password: <code class="font-mono">password</code> — click a row to fill</p>
    </div>
</div>
</body>
</html>
