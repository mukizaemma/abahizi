@extends('layouts.adminbase')

@section('title', 'Settings')

@section('sidebar')

    @parent

@endsection

@section('content')

@php
    use App\Support\PageHeaderService;
    $pageHeaderStore = PageHeaderService::storedHeaders($data);
@endphp

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header">
                    <h1>Site settings</h1>
                    <p class="text-muted mb-0">Manage account details, contact links, brand colors, and page headers.</p>
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
                                        <div class="col-lg-6">
                                            <label class="form-label">Facebook</label>
                                            <input type="url" class="form-control" value="{{ $data->facebook }}" name="facebook">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Instagram</label>
                                            <input type="url" class="form-control" value="{{ $data->instagram }}" name="instagram">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">YouTube</label>
                                            <input type="url" class="form-control" value="{{ $data->youtube }}" name="youtube">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Google Map embed code</label>
                                            <textarea class="form-control" rows="4" name="google_map_embed_code" placeholder="Paste iframe embed code">{{ $data->google_map_embed_code }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="colors-pane" role="tabpanel" aria-labelledby="colors-tab">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">Primary color</label>
                                            <input type="color" class="form-control form-control-color w-100" name="primary_color" value="{{ $data->primary_color ?? '#fad200' }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Secondary color</label>
                                            <input type="color" class="form-control form-control-color w-100" name="secondary_color" value="{{ $data->secondary_color ?? '#2c2c2c' }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Neutral color</label>
                                            <input type="color" class="form-control form-control-color w-100" name="neutral_color" value="{{ $data->neutral_color ?? '#b0b0b0' }}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Font family</label>
                                            <input type="text" class="form-control" name="font_family" value="{{ $data->font_family ?? 'DM Sans' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="visibility-pane" role="tabpanel" aria-labelledby="visibility-tab">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="show_products_publicly" name="show_products_publicly" value="1" {{ ($data->show_products_publicly ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_products_publicly">
                                                    Show product catalog publicly
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="show_products_page" name="show_products_page" value="1" {{ ($data->show_products_page ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_products_page">
                                                    Show products page in navigation
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="accept_order_requests" name="accept_order_requests" value="1" {{ ($data->accept_order_requests ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="accept_order_requests">
                                                    Accept order requests from website
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="headers-pane" role="tabpanel" aria-labelledby="headers-tab">
                                    <p class="text-muted mb-4">All inner pages use a full-screen header image with title and caption. Leave a field empty to use the site default shown on each page.</p>

                                    <div class="card mb-4 border">
                                        <div class="card-header bg-light fw-semibold">Homepage hero defaults</div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">Manage slide images and captions under <strong>Home Slides</strong> in the admin menu. Settings here apply when a slide has no caption, or when no slides exist.</p>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Default hero caption</label>
                                                    <input type="text" class="form-control" name="hero_headline" value="{{ $data->hero_headline }}" placeholder="Premium Custom Handbags. Crafted in Rwanda.">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Hero video URL (optional)</label>
                                                    <input type="url" class="form-control" name="hero_video_url" value="{{ $data->hero_video_url }}" placeholder="https://... (only used when no slides exist)">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Fallback hero image</label>
                                                    <input type="file" class="form-control" name="hero_poster" accept="image/*">
                                                    <small class="text-muted">Used only when no slides are uploaded in Home Slides.</small>
                                                    @if(!empty($data->hero_poster))
                                                        <img src="{{ asset('storage/images/' . ltrim($data->hero_poster, '/')) }}" alt="Hero poster" width="220" class="mt-2 rounded border">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-4 border">
                                        <div class="card-header bg-light fw-semibold">Default fallback (all pages)</div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Default header image</label>
                                                    <input type="file" class="form-control" name="page_header_image" accept="image/*">
                                                    @if(!empty($data->page_header_image))
                                                        <img src="{{ asset('storage/images') . $data->page_header_image }}" alt="Default header" width="180" class="mt-2 rounded border p-1 bg-white">
                                                    @endif
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Default header caption</label>
                                                    <textarea class="form-control" rows="4" name="page_header_caption" placeholder="Used when a page has no custom caption">{{ $data->page_header_caption }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        @foreach(PageHeaderService::definitions() as $pageKey => $pageLabel)
                                            @php
                                                $stored = (array) ($pageHeaderStore[$pageKey] ?? []);
                                                $storedImage = $stored['image'] ?? null;
                                            @endphp
                                            <div class="col-lg-6">
                                                <div class="card h-100 border">
                                                    <div class="card-header bg-light py-2">
                                                        <strong>{{ $pageLabel }}</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <label class="form-label">Caption</label>
                                                        <textarea class="form-control mb-3" rows="3" name="page_headers[{{ $pageKey }}][caption]" placeholder="Optional caption for this page">{{ $stored['caption'] ?? '' }}</textarea>
                                                        <label class="form-label">Header image</label>
                                                        <input type="file" class="form-control" name="page_headers[{{ $pageKey }}][image]" accept="image/*">
                                                        @if(!empty($storedImage))
                                                            <img src="{{ PageHeaderService::imageUrlFromStored($storedImage) }}" alt="{{ $pageLabel }} header" width="180" class="mt-2 rounded border">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
