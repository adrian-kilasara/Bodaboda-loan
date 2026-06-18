@extends('layouts.app')
@section('title', $contract->contract_number)
@section('subtitle', $contract->motorcycle->registration_number . ' — ' . $contract->motorcycle->make . ' ' . $contract->motorcycle->model)

@section('content')
<div class="py-6 space-y-5">
@php
    $statusMap = [
        'active'            => 'badge-success',
        'completed'         => 'badge-success',
        'pending_enrolment' => 'badge-pending',
        'defaulted'         => 'badge-danger',
        'draft'             => 'badge-pending',
        'terminated'        => 'badge-pending',
    ];
    $paid = $contract->amountPaidTotal();
    $balance = $contract->balanceRemaining();
    $overdue = $contract->overdueAmount();
    $percent = $contract->percentPaid();
    $next = $contract->nextDueInstallment();
@endphp

{{-- Summary bar --}}
<div class="bg-white rounded-xl border border-neutral-100 p-5">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h2 class="font-bold text-lg">{{ $contract->contract_number }}</h2>
            <p class="text-neutral-500 text-sm">
                Driver: <span class="font-medium text-neutral-900">{{ $contract->driver?->name ?? 'Not assigned yet' }}</span>
                &nbsp;·&nbsp;
                Started {{ $contract->start_date->format('d M Y') }}
            </p>
        </div>
        <span class="badge {{ $statusMap[$contract->status] ?? 'badge-pending' }} text-sm px-3 py-1">
            {{ str_replace('_', ' ', $contract->status) }}
        </span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div>
            <p class="text-xs text-neutral-500 font-medium">Total Payable</p>
            <p class="font-bold money text-lg">TZS {{ number_format($contract->total_payable) }}</p>
        </div>
        <div>
            <p class="text-xs text-neutral-500 font-medium">Amount Paid</p>
            <p class="font-bold money text-lg text-success">TZS {{ number_format($paid) }}</p>
        </div>
        <div>
            <p class="text-xs text-neutral-500 font-medium">Balance</p>
            <p class="font-bold money text-lg">TZS {{ number_format($balance) }}</p>
        </div>
        <div>
            <p class="text-xs text-neutral-500 font-medium">Overdue</p>
            <p class="font-bold money text-lg {{ bccomp((string)$overdue, '0', 2) > 0 ? 'text-danger' : 'text-neutral-900' }}">
                TZS {{ number_format($overdue) }}
            </p>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mb-3">
        <div class="flex justify-between text-xs text-neutral-500 mb-1">
            <span>Progress</span>
            <span>{{ $percent }}% paid</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $percent }}%"></div>
        </div>
    </div>

    @if($next)
    <div class="text-sm {{ $next->isOverdue() ? 'text-danger' : 'text-neutral-600' }}">
        Next due:
        <span class="font-semibold">TZS {{ number_format($next->amount_due) }}</span>
        on {{ $next->due_date->format('D, d M Y') }}
        @if($next->isOverdue()) &nbsp;<span class="badge badge-danger">OVERDUE</span> @endif
    </div>
    @endif
</div>

{{-- Action buttons --}}
<div class="flex flex-wrap gap-3">
    @if(in_array($contract->status, ['active', 'defaulted']))
    <button onclick="document.getElementById('paymentModal').classList.remove('hidden')"
            class="btn-primary">
        Record Payment
    </button>
    @endif

    @if($contract->status === 'pending_enrolment')
    <form method="POST" action="{{ route('contracts.generateKey', $contract) }}">
        @csrf
        <button type="submit" class="btn-secondary">
            {{ $contract->hasValidEnrolmentKey() ? 'Regenerate Enrolment Key' : 'Generate Enrolment Key' }}
            @if($contract->hasValidEnrolmentKey())
                <span class="text-xs font-normal">(expires {{ $contract->enrolment_key_expires_at->format('d M') }})</span>
            @endif
        </button>
    </form>
    @endif
</div>

{{-- Loan terms --}}
<div class="bg-white rounded-xl border border-neutral-100 p-5">
    <h3 class="font-semibold text-sm uppercase text-neutral-500 tracking-wide mb-3">Loan Terms</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        @foreach([
            'Principal'    => 'TZS ' . number_format($contract->principal_amount),
            'Markup'       => 'TZS ' . number_format($contract->markup_amount),
            'Down Payment' => 'TZS ' . number_format($contract->down_payment),
            'Financed'     => 'TZS ' . number_format($contract->financed_amount),
            'Installment'  => 'TZS ' . number_format($contract->installment_amount),
            'Frequency'    => ucfirst($contract->installment_frequency),
            'Count'        => $contract->number_of_installments . ' installments',
            'End Date'     => $contract->expected_end_date?->format('d M Y') ?? '—',
        ] as $label => $value)
        <div>
            <p class="text-neutral-400 text-xs mb-0.5">{{ $label }}</p>
            <p class="font-semibold">{{ $value }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Installment schedule --}}
<div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-neutral-100 flex items-center justify-between">
        <h3 class="font-semibold text-sm">Installment Schedule</h3>
        <span class="text-xs text-neutral-500">{{ $contract->installments->count() }} installments</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-50">
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">#</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Due Date</th>
                    <th class="px-4 py-2 text-right text-xs text-neutral-500">Due</th>
                    <th class="px-4 py-2 text-right text-xs text-neutral-500">Paid</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($contract->installments as $inst)
                @php
                    $iMap = [
                        'paid'    => 'badge-success',
                        'partial' => 'badge-warning',
                        'pending' => 'badge-pending',
                        'overdue' => 'badge-danger',
                        'waived'  => 'badge-pending',
                    ];
                @endphp
                <tr class="{{ $inst->status === 'overdue' ? 'bg-danger-light/40' : 'hover:bg-neutral-50' }}">
                    <td class="px-4 py-2 text-neutral-400 text-xs">{{ $inst->installment_number }}</td>
                    <td class="px-4 py-2 text-xs">{{ $inst->due_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-right text-xs money">TZS {{ number_format($inst->amount_due) }}</td>
                    <td class="px-4 py-2 text-right text-xs money {{ $inst->amount_paid > 0 ? 'text-success' : '' }}">
                        TZS {{ number_format($inst->amount_paid) }}
                    </td>
                    <td class="px-4 py-2">
                        <span class="badge {{ $iMap[$inst->status] ?? 'badge-pending' }} text-xs">{{ $inst->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Payment history --}}
<div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-neutral-100">
        <h3 class="font-semibold text-sm">Payment History</h3>
    </div>
    @if($contract->payments->isEmpty())
        <p class="px-5 py-6 text-neutral-400 text-sm text-center">No payments recorded yet.</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-50">
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Date</th>
                    <th class="px-4 py-2 text-right text-xs text-neutral-500">Amount</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Channel</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Reference</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Recorded By</th>
                    <th class="px-4 py-2 text-left text-xs text-neutral-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($contract->payments as $pay)
                <tr class="hover:bg-neutral-50">
                    <td class="px-4 py-2 text-xs">{{ $pay->payment_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-right text-xs money font-semibold text-success">TZS {{ number_format($pay->amount) }}</td>
                    <td class="px-4 py-2 text-xs">{{ $pay->channelLabel() }}</td>
                    <td class="px-4 py-2 text-xs text-neutral-500">{{ $pay->external_reference ?? '—' }}</td>
                    <td class="px-4 py-2 text-xs text-neutral-500">{{ $pay->recorder?->name ?? '—' }}</td>
                    <td class="px-4 py-2">
                        <span class="badge {{ $pay->status === 'confirmed' ? 'badge-success' : ($pay->status === 'rejected' ? 'badge-danger' : 'badge-warning') }} text-xs">
                            {{ $pay->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

</div>

{{-- Record Payment Modal --}}
<div id="paymentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold">Record Payment</h3>
            <button onclick="document.getElementById('paymentModal').classList.add('hidden')"
                    class="text-neutral-400 hover:text-neutral-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('contracts.payments.store', $contract) }}" class="space-y-4">
            @csrf
            <div>
                <label for="p_amount" class="form-label">Amount (TZS) *</label>
                <input id="p_amount" name="amount" type="number" required min="1"
                       class="form-input" placeholder="5000" inputmode="numeric">
            </div>
            <div>
                <label for="p_date" class="form-label">Payment Date *</label>
                <input id="p_date" name="payment_date" type="date" required
                       value="{{ today()->format('Y-m-d') }}" class="form-input">
            </div>
            <div>
                <label for="p_channel" class="form-label">Channel *</label>
                <select id="p_channel" name="channel" required class="form-input">
                    <option value="cash">Cash</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="tigopesa">Tigo Pesa</option>
                    <option value="airtel">Airtel Money</option>
                    <option value="halopesa">Halo Pesa</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label for="p_ref" class="form-label">Mobile Money Ref. / Notes</label>
                <input id="p_ref" name="external_reference" type="text"
                       class="form-input" placeholder="e.g. QA12BCDE34">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-primary flex-1 justify-center">Record Payment</button>
                <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Show modal if validation failed on payment --}}
@if($errors->any())
<script>document.getElementById('paymentModal').classList.remove('hidden');</script>
@endif
@endsection
