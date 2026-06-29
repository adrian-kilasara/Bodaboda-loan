@extends('layouts.app')
@section('title', 'Add Contact')

@section('content')
<div class="py-6 max-w-2xl">
    <div class="card p-6 animate-slide-up">
        <form method="POST" action="{{ route('contacts.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="full_name" class="form-label">Full Name *</label>
                    <input id="full_name" name="full_name" type="text" required
                           value="{{ old('full_name') }}" class="form-input"
                           placeholder="Jane Doe">
                    @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="relationship" class="form-label">Relationship *</label>
                    <select id="relationship" name="relationship" required class="form-input">
                        <option value="">— Select —</option>
                        @foreach(['guarantor', 'next_of_kin', 'reference', 'other'] as $r)
                        <option value="{{ $r }}" {{ old('relationship')===$r ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $r)) }}
                        </option>
                        @endforeach
                    </select>
                    @error('relationship') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="phone" class="form-label">Phone *</label>
                    <input id="phone" name="phone" type="tel" required
                           value="{{ old('phone') }}" class="form-input" placeholder="+255 7XX XXX XXX">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="alternate_phone" class="form-label">Alternate Phone</label>
                    <input id="alternate_phone" name="alternate_phone" type="tel"
                           value="{{ old('alternate_phone') }}" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="driver_id" class="form-label">Link to Driver</label>
                    <select id="driver_id" name="driver_id" class="form-input">
                        <option value="">— None —</option>
                        @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ old('driver_id')==$d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="contract_id" class="form-label">Link to Contract</label>
                    <select id="contract_id" name="contract_id" class="form-input">
                        <option value="">— None —</option>
                        @foreach($contracts as $c)
                        <option value="{{ $c->id }}" {{ old('contract_id')==$c->id ? 'selected' : '' }}>
                            {{ $c->contract_number }} ({{ $c->motorcycle->registration_number }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="national_id" class="form-label">National ID</label>
                    <input id="national_id" name="national_id" type="text"
                           value="{{ old('national_id') }}" class="form-input">
                </div>
                <div>
                    <label for="physical_address" class="form-label">Address</label>
                    <input id="physical_address" name="physical_address" type="text"
                           value="{{ old('physical_address') }}" class="form-input">
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2 border-t border-neutral-100">
                <button type="submit" class="btn-primary mt-4">Save Contact</button>
                <a href="{{ route('contacts.index') }}" class="btn-secondary mt-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
