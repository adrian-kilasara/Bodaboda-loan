@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="py-6 space-y-6">

    {{-- Stat tiles --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="stat-tile animate-slide-up" style="animation-delay: 0ms">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Motorcycles</p>
            <p class="text-3xl font-bold money mt-1" x-data="counter({{ $stats['motorcycles'] }})" x-text="display"></p>
            <a href="{{ route('motorcycles.index') }}" class="text-xs text-primary mt-2 inline-flex items-center gap-1 hover:gap-1.5 transition-all hover:underline">View all <span>→</span></a>
        </div>
        <div class="stat-tile animate-slide-up" style="animation-delay: 40ms">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Active Loans</p>
            <p class="text-3xl font-bold money mt-1" x-data="counter({{ $stats['active_contracts'] }})" x-text="display"></p>
            <a href="{{ route('contracts.index') }}" class="text-xs text-primary mt-2 inline-flex items-center gap-1 hover:gap-1.5 transition-all hover:underline">View all <span>→</span></a>
        </div>
        <div class="stat-tile lg:col-span-1 animate-slide-up" style="animation-delay: 80ms">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Outstanding</p>
            <p class="text-2xl font-bold money mt-1 text-neutral-900" x-data="counter({{ (int) $stats['total_outstanding'] }}, 'TZS ')" x-text="display"></p>
        </div>
        <div class="stat-tile animate-slide-up" style="animation-delay: 120ms">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Collected This Week</p>
            <p class="text-2xl font-bold money mt-1 text-success" x-data="counter({{ (int) $stats['collected_this_week'] }}, 'TZS ')" x-text="display"></p>
        </div>
        <div class="stat-tile animate-slide-up" style="animation-delay: 160ms">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Overdue Loans</p>
            <p class="text-3xl font-bold money mt-1 {{ $stats['overdue_count'] > 0 ? 'text-danger' : 'text-neutral-900' }}"
               x-data="counter({{ $stats['overdue_count'] }})" x-text="display"></p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="flex flex-wrap gap-3 animate-slide-up" style="animation-delay: 200ms">
        <a href="{{ route('motorcycles.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Motorcycle
        </a>
        <a href="{{ route('contracts.create') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Contract
        </a>
    </div>

    {{-- Behind on payments --}}
    @if($behindContracts->isNotEmpty())
    <div class="animate-slide-up" style="animation-delay: 240ms">
        <h2 class="text-base font-semibold text-neutral-900 mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-danger animate-pulse"></span>
            Behind on Payments
        </h2>
        <div class="table-shell">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Contract</th>
                        <th>Motorcycle</th>
                        <th>Driver</th>
                        <th class="text-right">Overdue</th>
                        <th class="text-right">Balance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($behindContracts as $contract)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $contract->contract_number }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $contract->motorcycle->registration_number }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $contract->driver?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-danger font-semibold money">
                            TZS {{ number_format($contract->overdueAmount()) }}
                        </td>
                        <td class="px-4 py-3 text-right money">TZS {{ number_format($contract->balanceRemaining()) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('contracts.show', $contract) }}" class="text-primary text-xs font-medium hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-success-light border border-success rounded-xl px-5 py-4 text-success text-sm font-medium flex items-center gap-2.5 animate-slide-up" style="animation-delay: 240ms">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        All active loans are on track. No overdue payments.
    </div>
    @endif

</div>
@endsection
