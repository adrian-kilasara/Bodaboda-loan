@extends('layouts.app')
@section('title', 'Loan Contracts')

@section('content')
<div class="py-6 space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-neutral-500 text-sm">{{ $contracts->total() }} contract{{ $contracts->total() !== 1 ? 's' : '' }}</p>
        <a href="{{ route('contracts.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Contract
        </a>
    </div>

    @if($contracts->isEmpty())
        <div class="bg-white rounded-xl border border-neutral-100 px-6 py-12 text-center">
            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-neutral-600 font-medium">No contracts yet</p>
            <a href="{{ route('contracts.create') }}" class="btn-primary mt-4 inline-flex">Create first contract</a>
        </div>
    @else
    <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Contract</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Motorcycle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Driver</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase">Balance</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Next Due</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($contracts as $contract)
                @php
                    $statusMap = [
                        'active'            => 'badge-success',
                        'completed'         => 'badge-success',
                        'pending_enrolment' => 'badge-pending',
                        'defaulted'         => 'badge-danger',
                        'draft'             => 'badge-pending',
                        'terminated'        => 'badge-pending',
                    ];
                    $next = $contract->nextDueInstallment();
                @endphp
                <tr class="hover:bg-neutral-50">
                    <td class="px-4 py-3 font-semibold">{{ $contract->contract_number }}</td>
                    <td class="px-4 py-3 text-neutral-600">{{ $contract->motorcycle->registration_number }}</td>
                    <td class="px-4 py-3 text-neutral-600">{{ $contract->driver?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-right money font-semibold">
                        TZS {{ number_format($contract->balanceRemaining()) }}
                    </td>
                    <td class="px-4 py-3 text-neutral-600">
                        @if($next)
                            <span class="{{ $next->isOverdue() ? 'text-danger font-semibold' : '' }}">
                                {{ $next->due_date->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $statusMap[$contract->status] ?? 'badge-pending' }}">
                            {{ str_replace('_', ' ', $contract->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('contracts.show', $contract) }}" class="text-primary text-xs hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $contracts->links() }}</div>
    @endif
</div>
@endsection
