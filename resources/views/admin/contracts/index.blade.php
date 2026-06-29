@extends('layouts.app')
@section('title', 'All Contracts')

@section('content')
<div class="py-6 space-y-4">
    <form method="GET" action="{{ route('admin.contracts.index') }}" class="card p-4 flex gap-3 items-end animate-slide-up">
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input w-44">
                <option value="">All statuses</option>
                @foreach(['active','pending_enrolment','completed','defaulted','terminated','draft'] as $s)
                <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">Filter</button>
        <a href="{{ route('admin.contracts.index') }}" class="btn-ghost">Clear</a>
    </form>

    <div class="table-shell animate-slide-up" style="animation-delay: 60ms">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Contract</th>
                    <th>Motorcycle</th>
                    <th>Owner</th>
                    <th>Driver</th>
                    <th class="text-right">Total Payable</th>
                    <th>Status</th>
                    <th>Started</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach($contracts as $contract)
                @php
                    $map=['active'=>'badge-success','completed'=>'badge-success','pending_enrolment'=>'badge-pending','defaulted'=>'badge-danger','terminated'=>'badge-pending','draft'=>'badge-pending'];
                @endphp
                <tr>
                    <td class="px-4 py-3 font-semibold text-xs">{{ $contract->contract_number }}</td>
                    <td class="px-4 py-3 text-xs">{{ $contract->motorcycle->registration_number }}</td>
                    <td class="px-4 py-3 text-xs">{{ $contract->owner?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs">{{ $contract->driver?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-xs money">TZS {{ number_format($contract->total_payable) }}</td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $map[$contract->status]??'badge-pending' }} text-xs">{{ str_replace('_',' ',$contract->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-neutral-400">{{ $contract->start_date->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $contracts->links() }}</div>
</div>
@endsection
