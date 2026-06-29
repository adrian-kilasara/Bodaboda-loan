@extends('layouts.app')
@section('title', 'All Users')

@section('content')
<div class="py-6 space-y-4">
    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="card p-4 flex flex-wrap gap-3 items-end animate-slide-up">
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
        <a href="{{ route('admin.users.index') }}" class="btn-ghost">Clear</a>
    </form>

    <div class="table-shell animate-slide-up" style="animation-delay: 60ms">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach($users as $u)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $u->email }}</td>
                    <td class="px-4 py-3 text-neutral-600 text-xs">{{ $u->phone }}</td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $u->role==='admin' ? 'badge-info' : ($u->role==='owner' ? 'badge-warning' : 'badge-pending') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ $u->status==='active' ? 'badge-success' : 'badge-danger' }}">{{ $u->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-neutral-400">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        @if($u->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.toggleStatus', $u) }}">
                            @csrf @method('PUT')
                            <button type="submit"
                                    class="text-xs font-medium {{ $u->status==='active' ? 'text-danger' : 'text-success' }} hover:underline">
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
