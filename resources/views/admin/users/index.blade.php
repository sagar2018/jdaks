@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">User Management</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm text-white" style="background:var(--accent-blue);">
        <i class="bi bi-person-plus me-1"></i>Add User
    </a>
</div>

{{-- Filters --}}
<form class="row g-2 mb-3" method="GET">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Search name or email…" value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="role" class="form-select form-select-sm">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <span class="fw-semibold">{{ $user->name }}</span>
                        @if($user->id === auth()->id())
                            <span class="badge bg-secondary ms-1">You</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge" style="background:var(--accent-blue);">
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        @if($user->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '—' }}
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.users.assign-projects', $user) }}" class="btn btn-outline-secondary" title="Assign Projects">
                                <i class="bi bi-diagram-3"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" title="Toggle Status">
                                    <i class="bi bi-{{ $user->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Generate a new random password for {{ addslashes($user->name) }}?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning" title="Reset Password">
                                    <i class="bi bi-key"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
