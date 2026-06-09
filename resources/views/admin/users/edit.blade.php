@extends('layouts.adminbase')

@section('title', 'Edit user')

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
                <div class="admin-page-header">
                    <h1>Edit user</h1>
                    <p class="text-muted mb-0">Update account details for {{ $user->name }}.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label" for="name">Full name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    @foreach($roleOptions as $roleId => $roleLabel)
                                        <option value="{{ $roleId }}" @selected((string) old('role', $user->role) === (string) $roleId)>{{ $roleLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="password">New password</label>
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                <div class="form-text">Leave blank to keep the current password.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="password_confirmation">Confirm new password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save changes</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
