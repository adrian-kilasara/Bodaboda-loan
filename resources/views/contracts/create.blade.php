@extends('layouts.app')
@section('title', 'New Loan Contract')

@section('content')
<div class="py-6 max-w-3xl" x-data="contractForm()">
    <div class="card p-6 animate-slide-up">
        <form method="POST" action="{{ route('contracts.store') }}" class="space-y-7">
            @csrf

            {{-- Step 1: Motorcycle --}}
            <div>
                <h2 class="flex items-center gap-2 text-sm font-semibold text-neutral-900 mb-3">
                    <span class="w-5 h-5 rounded-full bg-primary text-white text-[11px] flex items-center justify-center font-bold">1</span>
                    Select Motorcycle
                </h2>
                @if($motorcycles->isEmpty())
                    <div class="bg-accent-light border border-accent rounded-lg px-4 py-3 text-sm text-neutral-700">
                        No available motorcycles.
                        <a href="{{ route('motorcycles.create') }}" class="text-primary font-semibold hover:underline">Add one first →</a>
                    </div>
                @else
                    <select name="motorcycle_id" id="motorcycle_id" required class="form-input max-w-sm">
                        <option value="">— Select motorcycle —</option>
                        @foreach($motorcycles as $bike)
                            <option value="{{ $bike->id }}"
                                {{ old('motorcycle_id', request('motorcycle_id')) == $bike->id ? 'selected' : '' }}>
                                {{ $bike->registration_number }} — {{ $bike->make }} {{ $bike->model }}
                            </option>
                        @endforeach
                    </select>
                    @error('motorcycle_id') <p class="form-error">{{ $message }}</p> @enderror
                @endif
            </div>

            {{-- Step 2: Financial terms --}}
            <div class="pt-6 border-t border-neutral-100">
                <h2 class="flex items-center gap-2 text-sm font-semibold text-neutral-900 mb-3">
                    <span class="w-5 h-5 rounded-full bg-primary text-white text-[11px] flex items-center justify-center font-bold">2</span>
                    Loan Terms
                </h2>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="principal_amount" class="form-label">Principal Amount (TZS) *</label>
                        <input id="principal_amount" name="principal_amount" type="number" required
                               x-model.number="principal"
                               value="{{ old('principal_amount') }}"
                               class="form-input @error('principal_amount') border-danger @enderror"
                               placeholder="2500000" min="1" inputmode="numeric">
                        @error('principal_amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="markup_amount" class="form-label">Markup / Profit (TZS) *</label>
                        <input id="markup_amount" name="markup_amount" type="number" required
                               x-model.number="markup"
                               value="{{ old('markup_amount', 0) }}"
                               class="form-input @error('markup_amount') border-danger @enderror"
                               placeholder="0" min="0" inputmode="numeric">
                        @error('markup_amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="down_payment" class="form-label">Down Payment (TZS)</label>
                        <input id="down_payment" name="down_payment" type="number"
                               x-model.number="downPayment"
                               value="{{ old('down_payment', 0) }}"
                               class="form-input" placeholder="0" min="0" inputmode="numeric">
                    </div>
                    <div class="flex flex-col justify-end">
                        <div class="bg-primary-light rounded-lg px-4 py-3 transition-all duration-200" :class="totalPayable > 0 ? 'animate-pulse-once' : ''">
                            <p class="text-xs text-neutral-500 font-medium">Total Payable</p>
                            <p class="text-xl font-bold money text-primary" x-text="'TZS ' + totalPayable.toLocaleString()"></p>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Financed: <span class="font-medium" x-text="'TZS ' + financed.toLocaleString()"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3: Schedule --}}
            <div class="pt-6 border-t border-neutral-100">
                <h2 class="flex items-center gap-2 text-sm font-semibold text-neutral-900 mb-3">
                    <span class="w-5 h-5 rounded-full bg-primary text-white text-[11px] flex items-center justify-center font-bold">3</span>
                    Installment Schedule
                </h2>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="installment_amount" class="form-label">Installment Amount (TZS) *</label>
                        <input id="installment_amount" name="installment_amount" type="number" required
                               x-model.number="installmentAmount"
                               value="{{ old('installment_amount') }}"
                               class="form-input @error('installment_amount') border-danger @enderror"
                               placeholder="5000" min="1" inputmode="numeric">
                        @error('installment_amount') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="number_of_installments" class="form-label">Number of Installments *</label>
                        <input id="number_of_installments" name="number_of_installments" type="number" required
                               x-model.number="count"
                               value="{{ old('number_of_installments') }}"
                               class="form-input @error('number_of_installments') border-danger @enderror"
                               placeholder="100" min="1" max="1000" inputmode="numeric">
                        @error('number_of_installments') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="installment_frequency" class="form-label">Frequency *</label>
                        <select id="installment_frequency" name="installment_frequency" required
                                x-model="frequency" class="form-input">
                            <option value="daily" {{ old('installment_frequency') === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('installment_frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('installment_frequency', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="form-label">Start Date *</label>
                        <input id="start_date" name="start_date" type="date" required
                               x-model="startDate"
                               value="{{ old('start_date', today()->format('Y-m-d')) }}"
                               class="form-input @error('start_date') border-danger @enderror">
                        @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Schedule preview --}}
                <template x-if="scheduleRows.length > 0">
                    <div class="mt-4 border border-primary/30 rounded-xl overflow-hidden"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-primary-light px-4 py-2.5 flex items-center justify-between">
                            <span class="text-xs font-semibold text-primary uppercase tracking-wide">Schedule Preview (first 10)</span>
                            <span class="text-xs text-neutral-600"
                                  x-text="count + ' installments · ends ' + endDate"></span>
                        </div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-primary/15 bg-white">
                                    <th class="px-4 py-2 text-left text-xs text-neutral-500">#</th>
                                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Due Date</th>
                                    <th class="px-4 py-2 text-right text-xs text-neutral-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <template x-for="row in scheduleRows.slice(0,10)" :key="row.number">
                                    <tr class="border-t border-neutral-50">
                                        <td class="px-4 py-1.5 text-neutral-500 text-xs" x-text="row.number"></td>
                                        <td class="px-4 py-1.5 text-xs" x-text="row.due_date"></td>
                                        <td class="px-4 py-1.5 text-right text-xs money" x-text="'TZS ' + Number(row.amount).toLocaleString()"></td>
                                    </tr>
                                </template>
                                <template x-if="count > 10">
                                    <tr class="border-t border-neutral-50">
                                        <td colspan="3" class="px-4 py-2 text-center text-xs text-neutral-400"
                                            x-text="'... and ' + (count - 10) + ' more installments'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>

            {{-- Step 4: Penalty --}}
            <div class="pt-6 border-t border-neutral-100">
                <h2 class="flex items-center gap-2 text-sm font-semibold text-neutral-900 mb-3">
                    <span class="w-5 h-5 rounded-full bg-neutral-200 text-neutral-600 text-[11px] flex items-center justify-center font-bold">4</span>
                    Penalty <span class="text-neutral-400 font-normal">(optional)</span>
                </h2>
                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <label for="penalty_type" class="form-label">Penalty Type</label>
                        <select id="penalty_type" name="penalty_type" class="form-input">
                            <option value="none" {{ old('penalty_type', 'none') === 'none' ? 'selected' : '' }}>None</option>
                            <option value="fixed" {{ old('penalty_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="percentage" {{ old('penalty_type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                    </div>
                    <div>
                        <label for="penalty_amount" class="form-label">Penalty Amount</label>
                        <input id="penalty_amount" name="penalty_amount" type="number" min="0"
                               value="{{ old('penalty_amount', 0) }}" class="form-input" placeholder="0">
                    </div>
                    <div>
                        <label for="grace_period_days" class="form-label">Grace Period (days)</label>
                        <input id="grace_period_days" name="grace_period_days" type="number" min="0"
                               value="{{ old('grace_period_days', 0) }}" class="form-input" placeholder="0">
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" rows="2" class="form-input"
                          placeholder="Any additional terms or notes…">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Contract &amp; Generate Schedule
                </button>
                <a href="{{ route('contracts.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function contractForm() {
    return {
        principal: {{ old('principal_amount', 0) }},
        markup: {{ old('markup_amount', 0) }},
        downPayment: {{ old('down_payment', 0) }},
        installmentAmount: {{ old('installment_amount', 0) }},
        count: {{ old('number_of_installments', 0) }},
        frequency: '{{ old('installment_frequency', 'monthly') }}',
        startDate: '{{ old('start_date', today()->format('Y-m-d')) }}',

        get totalPayable() {
            return (parseFloat(this.principal) || 0) + (parseFloat(this.markup) || 0);
        },
        get financed() {
            return this.totalPayable - (parseFloat(this.downPayment) || 0);
        },
        get scheduleRows() {
            if (!this.installmentAmount || !this.count || !this.startDate) return [];
            const rows = [];
            let d = new Date(this.startDate);
            const base = parseFloat(this.installmentAmount);
            const n = parseInt(this.count);
            const financed = this.financed;
            const totalBase = base * n;
            const rounding = financed - totalBase;

            for (let i = 1; i <= n; i++) {
                const advance = new Date(d);
                if (this.frequency === 'daily')        advance.setDate(advance.getDate() + i);
                else if (this.frequency === 'weekly')  advance.setDate(advance.getDate() + i * 7);
                else { advance.setMonth(advance.getMonth() + i); }

                const amount = i === n ? (base + rounding) : base;
                rows.push({
                    number: i,
                    due_date: advance.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' }),
                    amount: amount.toFixed(2),
                });
            }
            return rows;
        },
        get endDate() {
            const rows = this.scheduleRows;
            return rows.length ? rows[rows.length - 1].due_date : '—';
        },
    };
}
</script>
@endpush
