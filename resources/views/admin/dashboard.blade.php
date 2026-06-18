@extends('layouts.app')
@section('title', 'System Dashboard')
@section('subtitle', 'Platform-wide overview')

@section('content')
<div class="py-6 space-y-6">

    {{-- System stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Owners</p>
            <p class="text-3xl font-bold money mt-1">{{ $stats['total_owners'] }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Drivers</p>
            <p class="text-3xl font-bold money mt-1">{{ $stats['total_drivers'] }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Motorcycles</p>
            <p class="text-3xl font-bold money mt-1">{{ $stats['total_motorcycles'] }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Active Contracts</p>
            <p class="text-3xl font-bold money mt-1 text-success">{{ $stats['active_contracts'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Total Disbursed</p>
            <p class="text-2xl font-bold money mt-1">TZS {{ number_format($stats['total_disbursed']) }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Total Collected</p>
            <p class="text-2xl font-bold money mt-1 text-success">TZS {{ number_format($stats['total_collected']) }}</p>
        </div>
        <div class="stat-tile">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Outstanding</p>
            <p class="text-2xl font-bold money mt-1">TZS {{ number_format($stats['total_outstanding']) }}</p>
        </div>
    </div>

    @if($stats['defaulted'] > 0)
    <div class="bg-danger-light border border-danger rounded-xl px-5 py-3 flex items-center justify-between">
        <p class="text-danger font-semibold text-sm">{{ $stats['defaulted'] }} defaulted contract{{ $stats['defaulted'] !== 1 ? 's' : '' }} require attention.</p>
        <a href="{{ route('admin.contracts.index') }}?status=defaulted" class="text-danger text-xs underline">View →</a>
    </div>
    @endif

    {{-- Quick links --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.users.index') }}" class="btn-primary">Manage Users</a>
        <a href="{{ route('admin.contracts.index') }}" class="btn-secondary">All Contracts</a>
    </div>

    {{-- Recent payments --}}
    <div>
        <h2 class="text-base font-semibold mb-3">Recent Payments</h2>
        <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
            @if($recentPayments->isEmpty())
                <p class="px-5 py-8 text-neutral-400 text-sm text-center">No payments yet.</p>
            @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Contract</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Driver</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Channel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @foreach($recentPayments as $pay)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-2.5 text-xs text-neutral-500">{{ $pay->payment_date->format('d M Y') }}</td>
                        <td class="px-4 py-2.5 text-xs font-medium">{{ $pay->contract->contract_number }}</td>
                        <td class="px-4 py-2.5 text-xs">{{ $pay->driver?->name ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-right text-xs money font-semibold text-success">TZS {{ number_format($pay->amount) }}</td>
                        <td class="px-4 py-2.5 text-xs">{{ $pay->channelLabel() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection
