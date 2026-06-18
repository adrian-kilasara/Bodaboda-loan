@extends('layouts.app')
@section('title', 'Motorcycles')

@section('content')
<div class="py-6 space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-neutral-500 text-sm">{{ $motorcycles->total() }} motorcycle{{ $motorcycles->total() !== 1 ? 's' : '' }} registered</p>
        <a href="{{ route('motorcycles.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Motorcycle
        </a>
    </div>

    @if($motorcycles->isEmpty())
        <div class="bg-white rounded-xl border border-neutral-100 px-6 py-12 text-center">
            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <p class="text-neutral-600 font-medium">No motorcycles yet</p>
            <p class="text-neutral-400 text-sm mt-1">Register your first motorcycle to get started.</p>
            <a href="{{ route('motorcycles.create') }}" class="btn-primary mt-4 inline-flex">Add your first motorcycle</a>
        </div>
    @else
    <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Plate</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Make / Model</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Year</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Purchase Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($motorcycles as $bike)
                <tr class="hover:bg-neutral-50">
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
                    <td class="px-4 py-3 text-right space-x-3">
                        <a href="{{ route('motorcycles.show', $bike) }}" class="text-primary text-xs hover:underline">View</a>
                        <a href="{{ route('motorcycles.edit', $bike) }}" class="text-neutral-500 text-xs hover:underline">Edit</a>
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
