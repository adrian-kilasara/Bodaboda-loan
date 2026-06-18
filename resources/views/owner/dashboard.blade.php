@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="py-6 space-y-6">

    {{-- Stat tiles --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Motorcycles</p>
            <p class="text-3xl font-bold money mt-1">{{ $stats['motorcycles'] }}</p>
            <a href="{{ route('motorcycles.index') }}" class="text-xs text-primary mt-2 inline-block hover:underline">View all →</a>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Active Loans</p>
            <p class="text-3xl font-bold money mt-1">{{ $stats['active_contracts'] }}</p>
            <a href="{{ route('contracts.index') }}" class="text-xs text-primary mt-2 inline-block hover:underline">View all →</a>
        </div>
        <div class="stat-tile lg:col-span-1">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Outstanding</p>
            <p class="text-2xl font-bold money mt-1 text-neutral-900">TZS {{ number_format($stats['total_outstanding']) }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Collected This Week</p>
            <p class="text-2xl font-bold money mt-1 text-success">TZS {{ number_format($stats['collected_this_week']) }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Overdue Loans</p>
            <p class="text-3xl font-bold money mt-1 {{ $stats['overdue_count'] > 0 ? 'text-danger' : 'text-neutral-900' }}">
                {{ $stats['overdue_count'] }}
            </p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="flex flex-wrap gap-3">
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
    <div>
        <h2 class="text-base font-semibold text-neutral-900 mb-3">Behind on Payments</h2>
        <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Contract</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Motorcycle</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Driver</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase">Overdue</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase">Balance</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @foreach($behindContracts as $contract)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-3 font-medium">{{ $contract->contract_number }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $contract->motorcycle->registration_number }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $contract->driver?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-danger font-semibold money">
                            TZS {{ number_format($contract->overdueAmount()) }}
                        </td>
                        <td class="px-4 py-3 text-right money">TZS {{ number_format($contract->balanceRemaining()) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('contracts.show', $contract) }}" class="text-primary text-xs hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-success-light border border-success rounded-xl px-5 py-4 text-success text-sm font-medium">
        All active loans are on track. No overdue payments.
    </div>
    @endif

</div>
@endsection
