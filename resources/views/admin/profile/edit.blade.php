@extends('layouts.adminbase')

@section('title', 'My profile')

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
                    <h1>My profile</h1>
                    <p class="text-muted mb-0">Update your account details and password.</p>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card h-100">
                                <div class="card-header">
                                    <i class="fas fa-user me-2 text-muted"></i>Profile details
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label" for="name">Full name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}" autocomplete="street-address">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="{{ $user->roleLabel() }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card h-100">
                                <div class="card-header">
                                    <i class="fas fa-lock me-2 text-muted"></i>Change password
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Leave the password fields blank if you only want to update your profile.</p>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label" for="current_password">Current password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="password">New password</label>
                                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="password_confirmation">Confirm new password</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Save profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
