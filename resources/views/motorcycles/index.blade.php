@extends('layouts.app')
@section('title', 'Motorcycles')

@section('content')
<div class="py-6 space-y-4">
    <div class="flex items-center justify-between animate-slide-up">
        <p class="text-neutral-500 text-sm">{{ $motorcycles->total() }} motorcycle{{ $motorcycles->total() !== 1 ? 's' : '' }} registered</p>
        <a href="{{ route('motorcycles.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Motorcycle
        </a>
    </div>

    @if($motorcycles->isEmpty())
        <div class="empty-state animate-slide-up" style="animation-delay: 60ms">
            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <p class="text-neutral-600 font-medium">No motorcycles yet</p>
            <p class="text-neutral-400 text-sm mt-1">Register your first motorcycle to get started.</p>
            <a href="{{ route('motorcycles.create') }}" class="btn-primary mt-4 inline-flex">Add your first motorcycle</a>
        </div>
    @else
    <div class="table-shell animate-slide-up" style="animation-delay: 60ms">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Plate</th>
                    <th>Make / Model</th>
                    <th>Year</th>
                    <th>Purchase Price</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach($motorcycles as $bike)
                <tr>
                    <td class="px-4 py-3 font-semibold">{{ $bike->registration_number }}</td>
                    <td class="px-4 py-3 text-neutral-700">{{ $bike->make }} {{ $bike->model }}</td>
                    <td class="px-4 py-3 text-neutral-500">{{ $bike->manufacture_year ?? '—' }}</td>
                    <td class="px-4 py-3 money">TZS {{ number_format($bike->purchase_price) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $map = [
                                'available'   => 'badge-success',
                                'on_loan'     => 'badge-warning',
                                'repossessed' => 'badge-danger',
                                'sold'        => 'badge-pending',
                                'maintenance' => 'badge-pending',
                            ];
                        @endphp
                        <span class="badge {{ $map[$bike->status] ?? 'badge-pending' }}">
                            {{ str_replace('_', ' ', $bike->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('motorcycles.show', $bike) }}" class="icon-action" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('motorcycles.edit', $bike) }}" class="icon-action" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $motorcycles->links() }}</div>
    @endif
</div>
@endsection
