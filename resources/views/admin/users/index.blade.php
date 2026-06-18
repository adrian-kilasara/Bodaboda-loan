@extends('layouts.app')
@section('title', 'All Users')

@section('content')
<div class="py-6 space-y-4">
    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-input w-36">
                <option value="">All roles</option>
                <option value="admin"  {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                <option value="owner"  {{ request('role') === 'owner'  ? 'selected' : '' }}>Owner</option>
                <option value="driver" {{ request('role') === 'driver' ? 'selected' : '' }}>Driver</option>
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input w-36">
                <option value="">All statuses</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="flex-1 min-w-48">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-input" placeholder="Name or email…">
        </div>
        <button type="submit" class="btn-primary">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Clear</a>
    </form>

    <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach($users as $u)
                <tr class="hover:bg-neutral-50">
                    <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $u->email }}</td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $u->phone }}</td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $u->role==='admin' ? 'bg-purple-100 text-purple-700' : ($u->role==='owner' ? 'badge-warning' : 'badge-pending') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $u->status==='active' ? 'badge-success' : 'badge-danger' }}">{{ $u->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-neutral-400">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @if($u->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.toggleStatus', $u) }}">
                            @csrf @method('PUT')
                            <button type="submit"
                                    class="text-xs {{ $u->status==='active' ? 'text-danger' : 'text-success' }} hover:underline">
                                {{ $u->status==='active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $users->links() }}</div>
</div>
@endsection
