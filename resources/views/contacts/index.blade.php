@extends('layouts.app')
@section('title', 'Contacts')
@section('subtitle', 'Guarantors, next-of-kin, and references')

@section('content')
<div class="py-6 space-y-4">
    <div class="flex justify-end animate-slide-up">
        <a href="{{ route('contacts.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Contact
        </a>
    </div>

    @if($contacts->isEmpty())
        <div class="empty-state animate-slide-up" style="animation-delay: 60ms">
            <p class="text-neutral-600 font-medium">No contacts registered yet</p>
            <p class="text-neutral-400 text-sm mt-1">Add guarantors, next-of-kin, or references for your drivers.</p>
            <a href="{{ route('contacts.create') }}" class="btn-primary mt-4 inline-flex">Add first contact</a>
        </div>
    @else
    <div class="table-shell animate-slide-up" style="animation-delay: 60ms">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Relationship</th>
                    <th>Driver</th>
                    <th>Contract</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach($contacts as $contact)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $contact->full_name }}</td>
                    <td class="px-4 py-3 text-neutral-600">{{ $contact->phone }}</td>
                    <td class="px-4 py-3">
                        <span class="badge badge-pending capitalize">{{ str_replace('_', ' ', $contact->relationship) }}</span>
                    </td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $contact->driver?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $contact->contract?->contract_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('contacts.destroy', $contact) }}"
                              onsubmit="return confirm('Delete this contact?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-action icon-action-danger" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $contacts->links() }}</div>
    @endif
</div>
@endsection
