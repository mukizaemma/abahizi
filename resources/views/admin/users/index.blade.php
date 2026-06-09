@extends('layouts.adminbase')

@section('title', 'Users')

@section('sidebar')
    @parent
@endsection

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h1>Users</h1>
                        <p class="text-muted mb-0">Create admin panel accounts and assign roles. The super admin account is hidden from this list.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fa fa-plus me-1"></i> Add user
                    </button>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session()->get('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span><i class="fas fa-users me-2 text-muted"></i>Admin panel users</span>
                        <span class="badge bg-light text-dark border">{{ $users->count() }} visible</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Created</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td class="fw-semibold">{{ $user->name }}</td>
                                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                            <td>{{ $user->phone ?: '—' }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $user->roleLabel() }}</span></td>
                                            <td class="text-muted text-nowrap">{{ $user->created_at?->format('M j, Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">Edit</a>
                                                    @if((int) auth()->id() !== (int) $user->id)
                                                        <a href="{{ route('admin.users.destroy', $user) }}" class="btn btn-outline-danger" onclick="return confirm('Delete this user account?')">Delete</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border-0">
                                                <div class="admin-empty-state">
                                                    <i class="fas fa-user-plus d-block"></i>
                                                    <p class="mb-0">No additional users yet. Add an administrator or editor account.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add user</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="add-name">Full name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="add-name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="add-email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="add-email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="add-phone">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="add-phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="add-role">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="add-role" name="role" required>
                                @foreach($roleOptions as $roleId => $roleLabel)
                                    <option value="{{ $roleId }}" @selected((string) old('role', \App\Models\User::ROLE_ADMIN) === (string) $roleId)>{{ $roleLabel }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="add-password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="add-password" name="password" required autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="add-password-confirmation">Confirm password</label>
                            <input type="password" class="form-control" id="add-password-confirmation" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create user</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = document.getElementById('addUserModal');
                if (modal) {
                    new bootstrap.Modal(modal).show();
                }
            });
        </script>
    @endpush
@endif
@endsection
