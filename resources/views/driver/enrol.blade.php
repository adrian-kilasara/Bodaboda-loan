@extends('layouts.driver')
@section('title', 'My Loan')

@section('content')
<div class="px-4 py-5">

    @if(!$hasContract)
    {{-- Enrolment form --}}
    <div class="bg-white rounded-2xl border border-neutral-100 p-5 mb-5">
        <h2 class="font-semibold text-base mb-1">Enter Enrolment Key</h2>
        <p class="text-neutral-500 text-sm mb-4">Your owner will give you an 8-character key to link your contract.</p>

        <form method="POST" action="{{ route('driver.enrol.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="enrolment_key" class="form-label">Enrolment Key</label>
                <input id="enrolment_key" name="enrolment_key" type="text" required
                       class="form-input text-2xl tracking-[0.3em] font-mono uppercase text-center
                              @error('enrolment_key') border-danger @enderror"
                       placeholder="XXXXXXXX"
                       maxlength="8"
                       autocomplete="off"
                       autofocus>
                @error('enrolment_key')
                    <p class="form-error text-center mt-2">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-primary w-full justify-center py-3">
                Claim My Contract
            </button>
        </form>
    </div>
    @endif

    {{-- Show contract details if they have one --}}
    @php
        $user = auth()->user();
        $contracts = \App\Models\Contract::where('driver_id', $user->id)
            ->with(['motorcycle', 'installments' => fn($q) => $q->orderBy('installment_number')])
            ->get();
    @endphp

    @foreach($contracts as $contract)
    @php
        $balance = $contract->balanceRemaining();
        $percent = $contract->percentPaid();
    @endphp
    <div class="bg-white rounded-2xl border border-neutral-100 overflow-hidden mb-4">
        <div class="bg-primary px-5 py-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">{{ $contract->contract_number }}</p>
                    <p class="text-white/70 text-xs">{{ $contract->motorcycle->registration_number }} · {{ ucfirst($contract->installment_frequency) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-white/70">Balance</p>
                    <p class="font-bold money">TZS {{ number_format($balance) }}</p>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="h-full bg-white rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <p class="text-xs text-white/70 mt-1">{{ $percent }}% paid</p>
            </div>
        </div>

        <div class="px-5 pt-4 pb-2">
            <h3 class="font-semibold text-sm mb-2">Installment Schedule</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-50">
                        <th class="px-4 py-2 text-left text-xs text-neutral-400">#</th>
                        <th class="px-4 py-2 text-left text-xs text-neutral-400">Due</th>
                        <th class="px-4 py-2 text-right text-xs text-neutral-400">Amount</th>
                        <th class="px-4 py-2 text-left text-xs text-neutral-400">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->installments as $inst)
                    @php
                        $iMap = ['paid'=>'badge-success','partial'=>'badge-warning','pending'=>'badge-pending','overdue'=>'badge-danger','waived'=>'badge-pending'];
                    @endphp
                    <tr class="{{ $inst->status==='overdue' ? 'bg-danger-light/30' : 'border-t border-neutral-50' }}">
                        <td class="px-4 py-2 text-xs text-neutral-400">{{ $inst->installment_number }}</td>
                        <td class="px-4 py-2 text-xs">{{ $inst->due_date->format('d M Y') }}</td>
                        <td class="px-4 py-2 text-right text-xs money">TZS {{ number_format($inst->amount_due) }}</td>
                        <td class="px-4 py-2">
                            <span class="badge {{ $iMap[$inst->status]??'badge-pending' }} text-xs">{{ $inst->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

</div>
@endsection
