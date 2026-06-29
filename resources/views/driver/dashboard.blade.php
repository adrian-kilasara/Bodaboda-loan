@extends('layouts.driver')
@section('title', 'My Loan')

@section('content')
<div class="px-4 py-5 space-y-4">

    <p class="text-lg font-bold animate-slide-up">Hello, {{ explode(' ', auth()->user()->name)[0] }} 👋</p>

    @if(!$contract)
        {{-- No contract --}}
        <div class="card p-6 text-center animate-slide-up" style="animation-delay: 60ms">
            <svg class="w-14 h-14 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="font-semibold text-neutral-700">No active loan</p>
            <p class="text-neutral-400 text-sm mt-1">Enter the key your owner gave you to link your contract.</p>
            <a href="{{ route('driver.enrol') }}" class="btn-primary mt-4 inline-flex">Enter Enrolment Key</a>
        </div>
    @else
    @php
        $balance = $contract->balanceRemaining();
        $overdue = $contract->overdueAmount();
        $percent = $contract->percentPaid();
        $next = $contract->nextDueInstallment();
    @endphp

        {{-- Overdue banner --}}
        @if(bccomp((string)$overdue, '0', 2) > 0)
        <div class="bg-danger-light border border-danger rounded-xl px-4 py-3 flex items-center gap-3 animate-slide-up" style="animation-delay: 60ms">
            <svg class="w-5 h-5 text-danger flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-danger font-semibold text-sm">Overdue: TZS {{ number_format($overdue) }}</p>
                <p class="text-danger/80 text-xs">Please contact your owner immediately.</p>
            </div>
        </div>
        @endif

        {{-- Balance card --}}
        <div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-5 text-white shadow-lg shadow-primary/20 animate-slide-up" style="animation-delay: 100ms">
            <p class="text-white/70 text-sm font-medium mb-1">Balance Remaining</p>
            <p class="text-4xl font-bold money mb-3" x-data="counter({{ (int) $balance }}, 'TZS ')" x-text="display"></p>

            <div class="mb-3" x-data="{ width: 0 }" x-init="setTimeout(() => width = {{ $percent }}, 150)">
                <div class="flex justify-between text-xs text-white/70 mb-1.5">
                    <span>{{ $percent }}% paid</span>
                    <span>TZS {{ number_format($contract->financed_amount) }} total</span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-2.5 overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out" :style="`width: ${width}%`"></div>
                </div>
            </div>

            @if($next)
            <div class="bg-white/10 rounded-xl px-3 py-2.5 text-sm">
                <span class="text-white/70">Next due: </span>
                <span class="font-semibold">TZS {{ number_format($next->amount_due) }}</span>
                <span class="text-white/70"> on {{ $next->due_date->format('D, d M') }}</span>
            </div>
            @endif
        </div>

        {{-- Loan info --}}
        <div class="card p-4 animate-slide-up" style="animation-delay: 140ms">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-sm">Loan Details</h3>
                <span class="badge badge-success">
                    {{ str_replace('_', ' ', $contract->status) }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-neutral-400 text-xs">Contract</p>
                    <p class="font-semibold">{{ $contract->contract_number }}</p>
                </div>
                <div>
                    <p class="text-neutral-400 text-xs">Motorcycle</p>
                    <p class="font-semibold">{{ $contract->motorcycle->registration_number }}</p>
                </div>
                <div>
                    <p class="text-neutral-400 text-xs">Installment</p>
                    <p class="font-semibold">TZS {{ number_format($contract->installment_amount) }} / {{ $contract->installment_frequency }}</p>
                </div>
                <div>
                    <p class="text-neutral-400 text-xs">Expected End</p>
                    <p class="font-semibold">{{ $contract->expected_end_date?->format('d M Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Recent payments --}}
        <div class="card p-4 animate-slide-up" style="animation-delay: 180ms">
            <h3 class="font-semibold text-sm mb-3">Recent Payments</h3>
            @php $recentPays = $contract->payments->take(5); @endphp
            @if($recentPays->isEmpty())
                <p class="text-neutral-400 text-sm">No payments yet.</p>
            @else
                <div class="space-y-1">
                    @foreach($recentPays as $pay)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                        <div>
                            <p class="text-sm font-medium">TZS {{ number_format($pay->amount) }}</p>
                            <p class="text-xs text-neutral-400">{{ $pay->channelLabel() }} · {{ $pay->payment_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge {{ $pay->status === 'confirmed' ? 'badge-success' : 'badge-warning' }} text-xs">
                            {{ $pay->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a href="{{ route('driver.enrol') }}" class="btn-secondary w-full justify-center animate-slide-up" style="animation-delay: 220ms">View Full Schedule</a>
    @endif
</div>
@endsection
