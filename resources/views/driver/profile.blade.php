@extends('layouts.driver')
@section('title', 'My Profile')

@section('content')
<div class="px-4 py-5 space-y-4">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-white text-xl font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-lg">{{ auth()->user()->name }}</p>
            <p class="text-neutral-500 text-sm">{{ auth()->user()->email }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-100 p-5">
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

    <div class="bg-white rounded-2xl border border-neutral-100 p-5">
        <p class="text-sm text-neutral-600">
            <span class="font-medium">Email:</span> {{ $user->email }}<br>
            <span class="font-medium">Role:</span> {{ ucfirst($user->role) }}<br>
            <span class="font-medium">Account Status:</span>
            <span class="{{ $user->status === 'active' ? 'text-success' : 'text-danger' }} font-medium capitalize">{{ $user->status }}</span>
        </p>
    </div>
</div>
@endsection
