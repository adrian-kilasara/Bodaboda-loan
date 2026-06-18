@extends('layouts.app')
@section('title', 'Contacts')
@section('subtitle', 'Guarantors, next-of-kin, and references')

@section('content')
<div class="py-6 space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('contacts.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Contact
        </a>
    </div>

    @if($contacts->isEmpty())
        <div class="bg-white rounded-xl border border-neutral-100 px-6 py-12 text-center">
            <p class="text-neutral-600 font-medium">No contacts registered yet</p>
            <p class="text-neutral-400 text-sm mt-1">Add guarantors, next-of-kin, or references for your drivers.</p>
            <a href="{{ route('contacts.create') }}" class="btn-primary mt-4 inline-flex">Add first contact</a>
        </div>
    @else
    <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Relationship</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Driver</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Contract</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($contacts as $contact)
                <tr class="hover:bg-neutral-50">
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
                            <button type="submit" class="text-danger text-xs hover:underline">Delete</button>
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
