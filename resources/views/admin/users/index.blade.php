@extends('layouts.erp')

@section('title', 'Users Role')

@section('content')

<style>
.user-card{
    border-radius: 14px;
    border: none;
    overflow: hidden;
}

.filter-box{
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}

.table thead th{
    background: #111827;
    color: white;
    border: none;
}

.badge-role{
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.role-admin{
    background: #ff0000;
    color: white;
}

.role-coordinator{
    background: #e65050;
    color: white;
}

.role-customer{
    background: #dbeafe;
    color: #1d4ed8;
}

.role-staff{
    background: #dcfce7;
    color: #166534;
}
</style>

<div class="container py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Users Management</h3>
            <p class="text-muted mb-0">Manage users and roles</p>
        </div>

        <a href="{{ route('admin.users.trashed') }}"
           class="btn btn-warning rounded-pill px-4">
            View Trashed Users
        </a>
    </div>

    <!-- FILTER + SEARCH -->
    <div class="filter-box mb-4">

        <form method="GET"
              action="{{ route('admin.users.index') }}">

            <div class="row g-3 align-items-end">

                <!-- SEARCH -->
                <div class="col-md-5">
                    <label class="form-label fw-semibold">
                        Search Users
                    </label>

                    <input type="text"
                           name="q"
                           value="{{ $q ?? '' }}"
                           class="form-control"
                           placeholder="Search by name or email">
                </div>

                <!-- ROLE FILTER -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Filter By Role
                    </label>

                    <select name="role" class="form-select">

                        <option value="">
                            All Roles
                        </option>

                        <option value="customer"
                            {{ request('role') == 'customer' ? 'selected' : '' }}>
                            Customer
                        </option>

                        <option value="admin"
                            {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="staff"
                            {{ request('role') == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>

                        <option value="coordinator"
                            {{ request('role') == 'coordinator' ? 'selected' : '' }}>
                            Coordinator
                        </option>

                    </select>
                </div>

                <!-- BUTTONS -->
                <div class="col-md-4">

                    <div class="d-flex gap-2">

                        <button class="btn btn-dark px-4 w-100">
                            Apply Filter
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-outline-secondary w-100">
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- USERS TABLE -->
    <div class="card user-card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $u)

                        <tr>

                            <!-- ID -->
                            <td class="fw-semibold">
                                #{{ $u->id }}
                            </td>

                            <!-- USER -->
                            <td>

                                <div class="fw-semibold">
                                    {{ $u->name }}
                                </div>

                                <div class="text-muted small">
                                    {{ $u->email }}
                                </div>

                            </td>

                            <!-- ROLE -->
                            <td>

                                @if($u->role == 'admin')

                                    <span class="badge-role role-admin">
                                        Admin
                                    </span>

                                @elseif($u->role == 'staff')

                                    <span class="badge-role role-staff">
                                        Staff
                                    </span>

                                @elseif($u->role == 'coordinator')

                                    <span class="badge-role role-coordinator">
                                        Co-Ordinator
                                    </span>

                                @else

                                    <span class="badge-role role-customer">
                                        Customer
                                    </span>

                                @endif

                            </td>

                            <!-- DATE -->
                            <td>
                                {{ $u->created_at->format('M d, Y') }}
                            </td>

                            <!-- STATUS -->
                            <td>

                                @if($u->trashed())

                                    <span class="badge bg-danger">
                                        Deleted
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @endif

                            </td>

                            <!-- ACTIONS -->
                            <td>

                                <div class="d-flex gap-2 flex-wrap">

                                    @if($u->role === 'customer')
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            Change Role
                                        </button>
                                    @else
                                        <a href="{{ route('admin.users.edit', $u->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                            Change Role
                                        </a>
                                    @endif

                                    @if(auth()->id() !== $u->id)

                                        <form action="{{ route('admin.users.destroy', $u->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Remove user access?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger">
                                                Remove
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No users found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>

@endsection