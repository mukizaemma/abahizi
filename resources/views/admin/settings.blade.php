@extends('layouts.adminbase')

@section('title', 'Settings')

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
                    <h1>Site settings</h1>
                    <p class="text-muted mb-0">Manage account details, contact links, and brand colors.</p>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{ route('saveSetting', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <ul class="nav nav-tabs mb-4" id="siteSettingsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-pane" type="button" role="tab">Account settings</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts-pane" type="button" role="tab">Contacts</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="colors-tab" data-bs-toggle="tab" data-bs-target="#colors-pane" type="button" role="tab">Colors</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="visibility-tab" data-bs-toggle="tab" data-bs-target="#visibility-pane" type="button" role="tab">Visibility</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers-pane" type="button" role="tab">Page headers</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="siteSettingsTabsContent">
                                <div class="tab-pane fade show active" id="account-pane" role="tabpanel" aria-labelledby="account-tab">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label">Company Name</label>
                                            <input type="text" class="form-control" value="{{ $data->company }}" name="company">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Company Logo</label>
                                            <input type="file" class="form-control" name="logo">
                                            @if(!empty($data->logo))
                                                <img src="{{ asset('storage/images') . $data->logo }}" alt="Logo" width="130" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>

                                        @if ((Auth::user()->email ?? null) === 'admin@iremetech.com')
                                            <div class="col-12 mt-2">
                                                <hr>
                                                <h6 class="mb-1">Change admin password</h6>
                                                <small class="text-muted">Only available for admin@iremetech.com</small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label">New Password</label>
                                                <input type="password" class="form-control" name="new_password" autocomplete="new-password" placeholder="Enter new password">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label">Confirm New Password</label>
                                                <input type="password" class="form-control" name="new_password_confirmation" autocomplete="new-password" placeholder="Confirm new password">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="contacts-pane" role="tabpanel" aria-labelledby="contacts-tab">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" value="{{ $data->address }}" name="address">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $data->email }}" name="email">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" value="{{ $data->phone }}" name="phone">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Phone 2</label>
                                            <input type="text" class="form-control" value="{{ $data->phone1 }}" name="phone1">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Phone 3</label>
                                            <input type="text" class="form-control" value="{{ $data->phone2 }}" name="phone2">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Facebook</label>
                                            <input type="text" class="form-control" value="{{ $data->facebook }}" name="facebook">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Instagram</label>
                                            <input type="text" class="form-control" value="{{ $data->instagram }}" name="instagram">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">YouTube</label>
                                            <input type="text" class="form-control" value="{{ $data->youtube }}" name="youtube">
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="colors-pane" role="tabpanel" aria-labelledby="colors-tab">
                                    <div class="row g-3">
                                        <div class="col-lg-3">
                                            <label class="form-label d-block">Primary color</label>
                                            <input type="color" class="form-control form-control-color" name="primary_color" value="{{ $data->primary_color ?? '#fad200' }}">
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="form-label d-block">Secondary color</label>
                                            <input type="color" class="form-control form-control-color" name="secondary_color" value="{{ $data->secondary_color ?? '#2c2c2c' }}">
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="form-label d-block">Neutral color</label>
                                            <input type="color" class="form-control form-control-color" name="neutral_color" value="{{ $data->neutral_color ?? '#b0b0b0' }}">
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="form-label">Google font family</label>
                                            <input type="text" class="form-control" name="font_family" placeholder="e.g. Poppins, Roboto" value="{{ $data->font_family ?? 'Poppins' }}">
                                            <small class="text-muted">Copy the family name from <a href="https://fonts.google.com" target="_blank" rel="noopener">Google Fonts</a>.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="visibility-pane" role="tabpanel" aria-labelledby="visibility-tab">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="show_products_publicly" name="show_products_publicly" value="1" {{ ($data->show_products_publicly ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_products_publicly">
                                                    Show products publicly on website
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                When off, product links/cards/pages are hidden from public visitors.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="headers-pane" role="tabpanel" aria-labelledby="headers-tab">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label">Default page header image</label>
                                            <input type="file" class="form-control" name="page_header_image">
                                            <small class="text-muted d-block mt-1">Used by all breadcrumb headers unless a page provides its own image.</small>
                                            @if(!empty($data->page_header_image))
                                                <img src="{{ asset('storage/images') . $data->page_header_image }}" alt="Page header image" width="180" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Default header caption</label>
                                            <textarea class="form-control" rows="4" name="page_header_caption" placeholder="Short caption shown below each page title">{{ $data->page_header_caption }}</textarea>
                                            <small class="text-muted d-block mt-1">This keeps page hero sections consistent site-wide.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                    <i class="fa fa-save me-1"></i> Save Site Settings
                                </button>
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
