<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Bodaboda Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans min-h-screen flex items-center justify-center py-8 relative"
      style="background-color:#FAFAFA; background-image: radial-gradient(circle at 15% 20%, rgba(30,111,60,0.07), transparent 40%), radial-gradient(circle at 85% 80%, rgba(242,169,0,0.07), transparent 40%);">
<div class="w-full max-w-sm px-4 relative z-10">
    <div class="text-center mb-6 animate-slide-up">
        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-primary/20">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-neutral-900 tracking-tight">Bodaboda Pay</h1>
    </div>

    <div class="card p-6 animate-slide-up" style="animation-delay: 80ms">
        <h2 class="text-lg font-bold mb-5">Create your account</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Role selector --}}
            <div>
                <label class="form-label">I am a</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="owner"
                               class="sr-only peer" {{ old('role', 'owner') === 'owner' ? 'checked' : '' }}>
                        <div class="border-2 border-neutral-200 rounded-xl p-3.5 text-center transition-all duration-150
                                    peer-checked:border-primary peer-checked:bg-primary-light peer-checked:shadow-sm
                                    hover:border-neutral-300">
                            <svg class="w-6 h-6 mx-auto mb-1.5 text-neutral-400 peer-checked:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-sm font-semibold">Bike Owner</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="driver"
                               class="sr-only peer" {{ old('role') === 'driver' ? 'checked' : '' }}>
                        <div class="border-2 border-neutral-200 rounded-xl p-3.5 text-center transition-all duration-150
                                    peer-checked:border-primary peer-checked:bg-primary-light peer-checked:shadow-sm
                                    hover:border-neutral-300">
                            <svg class="w-6 h-6 mx-auto mb-1.5 text-neutral-400 peer-checked:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-semibold">Driver</span>
                        </div>
                    </label>
                </div>
                @error('role') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="form-label">Full name</label>
                <input id="name" name="name" type="text" required autofocus
                       value="{{ old('name') }}"
                       class="form-input @error('name') border-danger @enderror"
                       placeholder="John Doe">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email address</label>
                <input id="email" name="email" type="email" required
                       value="{{ old('email') }}"
                       class="form-input @error('email') border-danger @enderror"
                       placeholder="you@example.com">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="form-label">Phone number</label>
                <input id="phone" name="phone" type="tel" required
                       value="{{ old('phone') }}"
                       class="form-input @error('phone') border-danger @enderror"
                       placeholder="+255 7XX XXX XXX">
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="form-input @error('password') border-danger @enderror"
                       placeholder="Minimum 8 characters">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="form-input" placeholder="Repeat your password">
            </div>

            <button type="submit" class="btn-primary w-full justify-center py-3">
                Create account
            </button>
        </form>

        <p class="text-center text-sm text-neutral-500 mt-5">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign in</a>
        </p>
    </div>
</div>
</body>
</html>
