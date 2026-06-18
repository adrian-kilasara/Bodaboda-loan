@extends('layouts.app')
@section('title', 'Edit — ' . $motorcycle->registration_number)

@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white rounded-xl border border-neutral-100 p-6">
        <form method="POST" action="{{ route('motorcycles.update', $motorcycle) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="registration_number" class="form-label">Plate / Registration Number *</label>
                    <input id="registration_number" name="registration_number" type="text" required
                           value="{{ old('registration_number', $motorcycle->registration_number) }}"
                           class="form-input @error('registration_number') border-danger @enderror">
                    @error('registration_number') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="form-label">Status *</label>
                    <select id="status" name="status" class="form-input">
                        @foreach(['available', 'on_loan', 'repossessed', 'sold', 'maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $motorcycle->status) === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="make" class="form-label">Make *</label>
                    <input id="make" name="make" type="text" required
                           value="{{ old('make', $motorcycle->make) }}" class="form-input">
                </div>
                <div>
                    <label for="model" class="form-label">Model *</label>
                    <input id="model" name="model" type="text" required
                           value="{{ old('model', $motorcycle->model) }}" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="manufacture_year" class="form-label">Year</label>
                    <input id="manufacture_year" name="manufacture_year" type="number"
                           value="{{ old('manufacture_year', $motorcycle->manufacture_year) }}" class="form-input">
                </div>
                <div>
                    <label for="purchase_price" class="form-label">Purchase Price (TZS) *</label>
                    <input id="purchase_price" name="purchase_price" type="number" required
                           value="{{ old('purchase_price', $motorcycle->purchase_price) }}" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="color" class="form-label">Color</label>
                    <input id="color" name="color" type="text"
                           value="{{ old('color', $motorcycle->color) }}" class="form-input">
                </div>
                <div>
                    <label for="purchase_date" class="form-label">Purchase Date</label>
                    <input id="purchase_date" name="purchase_date" type="date"
                           value="{{ old('purchase_date', $motorcycle->purchase_date?->format('Y-m-d')) }}" class="form-input">
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" rows="2" class="form-input">{{ old('notes', $motorcycle->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
