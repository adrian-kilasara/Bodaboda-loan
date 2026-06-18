@extends('layouts.app')
@section('title', 'Add Motorcycle')

@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white rounded-xl border border-neutral-100 p-6">
        <form method="POST" action="{{ route('motorcycles.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="registration_number" class="form-label">Plate / Registration Number *</label>
                    <input id="registration_number" name="registration_number" type="text" required
                           value="{{ old('registration_number') }}"
                           class="form-input @error('registration_number') border-danger @enderror"
                           placeholder="T 123 ABC">
                    @error('registration_number') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="color" class="form-label">Color</label>
                    <input id="color" name="color" type="text"
                           value="{{ old('color') }}"
                           class="form-input" placeholder="e.g. Red">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="make" class="form-label">Make *</label>
                    <input id="make" name="make" type="text" required
                           value="{{ old('make') }}"
                           class="form-input @error('make') border-danger @enderror"
                           placeholder="e.g. Honda">
                    @error('make') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="model" class="form-label">Model *</label>
                    <input id="model" name="model" type="text" required
                           value="{{ old('model') }}"
                           class="form-input @error('model') border-danger @enderror"
                           placeholder="e.g. CG 125">
                    @error('model') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="manufacture_year" class="form-label">Year</label>
                    <input id="manufacture_year" name="manufacture_year" type="number"
                           value="{{ old('manufacture_year') }}"
                           class="form-input" placeholder="{{ date('Y') }}"
                           min="1990" max="{{ date('Y') + 1 }}">
                </div>
                <div>
                    <label for="purchase_price" class="form-label">Purchase Price (TZS) *</label>
                    <input id="purchase_price" name="purchase_price" type="number" required
                           value="{{ old('purchase_price') }}"
                           class="form-input @error('purchase_price') border-danger @enderror"
                           placeholder="2500000" min="1" inputmode="numeric">
                    @error('purchase_price') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="engine_number" class="form-label">Engine Number</label>
                    <input id="engine_number" name="engine_number" type="text"
                           value="{{ old('engine_number') }}"
                           class="form-input" placeholder="Optional">
                </div>
                <div>
                    <label for="chassis_number" class="form-label">Chassis Number</label>
                    <input id="chassis_number" name="chassis_number" type="text"
                           value="{{ old('chassis_number') }}"
                           class="form-input" placeholder="Optional">
                </div>
            </div>

            <div>
                <label for="purchase_date" class="form-label">Purchase Date</label>
                <input id="purchase_date" name="purchase_date" type="date"
                       value="{{ old('purchase_date') }}"
                       class="form-input">
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" rows="2"
                          class="form-input" placeholder="Any additional details…">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Register Motorcycle</button>
                <a href="{{ route('motorcycles.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
