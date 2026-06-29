@extends('layouts.driver')
@section('title', 'My Profile')

@section('content')
<div class="px-4 py-5 space-y-4">
    <div class="flex items-center gap-4 animate-slide-up">
        <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white text-xl font-bold shadow-md shadow-primary/20">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-lg">{{ auth()->user()->name }}</p>
            <p class="text-neutral-500 text-sm">{{ auth()->user()->email }}</p>
        </div>
    </div>

    <div class="card p-5 animate-slide-up" style="animation-delay: 60ms">
        <h2 class="font-semibold text-sm mb-4">Edit Profile</h2>
        <form method="POST" action="{{ route('driver.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label for="name" class="form-label">Full Name *</label>
                <input id="name" name="name" type="text" required
                       value="{{ old('name', $user->name) }}" class="form-input">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="form-label">Phone Number *</label>
                <input id="phone" name="phone" type="tel" required
                       value="{{ old('phone', $user->phone) }}" class="form-input">
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="driving_license_number" class="form-label">Driving License Number</label>
                <input id="driving_license_number" name="driving_license_number" type="text"
                       value="{{ old('driving_license_number', $profile?->driving_license_number) }}" class="form-input">
            </div>
            <div>
                <label for="national_id" class="form-label">National ID</label>
                <input id="national_id" name="national_id" type="text"
                       value="{{ old('national_id', $profile?->national_id) }}" class="form-input">
            </div>
            <div>
                <label for="physical_address" class="form-label">Physical Address</label>
                <textarea id="physical_address" name="physical_address" rows="2" class="form-input"
                          placeholder="Street, area, city">{{ old('physical_address', $profile?->physical_address) }}</textarea>
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Save Profile</button>
        </form>
    </div>

    <div class="card p-5 animate-slide-up" style="animation-delay: 100ms">
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between">
                <span class="text-neutral-400">Email</span>
                <span class="font-medium">{{ $user->email }}</span>
            </div>
            <div class="flex justify-between border-t border-neutral-100 pt-2.5">
                <span class="text-neutral-400">Role</span>
                <span class="font-medium">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="flex justify-between border-t border-neutral-100 pt-2.5">
                <span class="text-neutral-400">Account Status</span>
                <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ $user->status }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
