@extends('layouts.app')
@section('title', $motorcycle->registration_number)
@section('subtitle', $motorcycle->make . ' ' . $motorcycle->model)

@section('content')
<div class="py-6 space-y-5">
    <div class="flex gap-3">
        <a href="{{ route('motorcycles.edit', $motorcycle) }}" class="btn-secondary btn-sm">Edit</a>
        @if($motorcycle->contracts->where('status', 'active')->isEmpty() && auth()->user()->isOwner())
        <form method="POST" action="{{ route('motorcycles.destroy', $motorcycle) }}"
              onsubmit="return confirm('Delete this motorcycle? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger btn-sm">Delete</button>
        </form>
        @endif
        @if($motorcycle->status === 'available' && auth()->user()->isOwner())
        <a href="{{ route('contracts.create') }}?motorcycle_id={{ $motorcycle->id }}" class="btn-primary btn-sm">
            Create Loan Contract
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Details --}}
        <div class="lg:col-span-1 bg-white rounded-xl border border-neutral-100 p-5 space-y-4">
            <h2 class="font-semibold text-sm uppercase text-neutral-500 tracking-wide">Asset Details</h2>
            @php
                $statusMap = [
                    'available'   => 'badge-success',
                    'on_loan'     => 'badge-warning',
                    'repossessed' => 'badge-danger',
                    'sold'        => 'badge-pending',
                    'maintenance' => 'badge-pending',
                ];
            @endphp
            <div class="flex items-center justify-between">
                <span class="text-neutral-500 text-sm">Status</span>
                <span class="badge {{ $statusMap[$motorcycle->status] ?? 'badge-pending' }}">
                    {{ str_replace('_', ' ', $motorcycle->status) }}
                </span>
            </div>
            @foreach([
                'Plate'           => $motorcycle->registration_number,
                'Make'            => $motorcycle->make,
                'Model'           => $motorcycle->model,
                'Year'            => $motorcycle->manufacture_year ?? '—',
                'Color'           => $motorcycle->color ?? '—',
                'Engine No.'      => $motorcycle->engine_number ?? '—',
                'Chassis No.'     => $motorcycle->chassis_number ?? '—',
                'Purchase Price'  => 'TZS ' . number_format($motorcycle->purchase_price),
                'Purchase Date'   => $motorcycle->purchase_date?->format('d M Y') ?? '—',
            ] as $label => $value)
            <div class="flex items-center justify-between border-t border-neutral-50 pt-3">
                <span class="text-neutral-500 text-sm">{{ $label }}</span>
                <span class="text-sm font-medium">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Contract history --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-neutral-100 p-5">
            <h2 class="font-semibold text-sm uppercase text-neutral-500 tracking-wide mb-4">Loan Contract History</h2>
            @if($motorcycle->contracts->isEmpty())
                <p class="text-neutral-400 text-sm">No contracts yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($motorcycle->contracts as $contract)
                    <div class="border border-neutral-100 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <a href="{{ route('contracts.show', $contract) }}" class="font-semibold text-primary hover:underline text-sm">
                                {{ $contract->contract_number }}
                            </a>
                            @php
                                $cMap = [
                                    'active'            => 'badge-success',
                                    'completed'         => 'badge-success',
                                    'pending_enrolment' => 'badge-pending',
                                    'defaulted'         => 'badge-danger',
                                    'terminated'        => 'badge-pending',
                                    'draft'             => 'badge-pending',
                                ];
                            @endphp
                            <span class="badge {{ $cMap[$contract->status] ?? 'badge-pending' }}">
                                {{ str_replace('_', ' ', $contract->status) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-sm">
                            <div>
                                <p class="text-neutral-400 text-xs">Driver</p>
                                <p class="font-medium">{{ $contract->driver?->name ?? 'No driver yet' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-400 text-xs">Total Payable</p>
                                <p class="font-medium money">TZS {{ number_format($contract->total_payable) }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-400 text-xs">Start Date</p>
                                <p class="font-medium">{{ $contract->start_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
