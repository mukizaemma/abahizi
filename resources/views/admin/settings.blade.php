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
                                    @php
                                        $bodyFonts = \App\Support\ThemeService::bodyFonts();
                                        $headingFonts = \App\Support\ThemeService::headingFonts();
                                        $currentBodyFont = $data->font_family ?: \App\Support\ThemeService::DEFAULT_BODY_FONT;
                                        $currentHeadingFont = $data->heading_font ?: \App\Support\ThemeService::DEFAULT_HEADING_FONT;
                                    @endphp
                                    <p class="text-muted mb-4">The public site uses the logo palette only: yellow, black, and white. Fonts still apply across the public site and the admin panel.</p>
                                    <div class="row g-3 mb-4">
                                        <div class="col-12">
                                            <label class="form-label d-block">Logo colors</label>
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <span class="d-inline-flex align-items-center gap-2">
                                                    <span style="display:inline-block;width:2rem;height:2rem;background:#fad200;border:1px solid #000;"></span>
                                                    <span>Yellow <code>#fad200</code></span>
                                                </span>
                                                <span class="d-inline-flex align-items-center gap-2">
                                                    <span style="display:inline-block;width:2rem;height:2rem;background:#000;"></span>
                                                    <span class="text-dark">Black <code>#000000</code></span>
                                                </span>
                                                <span class="d-inline-flex align-items-center gap-2">
                                                    <span style="display:inline-block;width:2rem;height:2rem;background:#fff;border:1px solid #000;"></span>
                                                    <span>White <code>#ffffff</code></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label">Body font (Google Fonts)</label>
                                            <select class="form-select" name="font_family">
                                                @foreach($bodyFonts as $font)
                                                    <option value="{{ $font }}" {{ $currentBodyFont === $font ? 'selected' : '' }} style="font-family: '{{ $font }}', sans-serif;">{{ $font }}</option>
                                                @endforeach
                                                @if($currentBodyFont && ! isset($bodyFonts[$currentBodyFont]))
                                                    <option value="{{ $currentBodyFont }}" selected>{{ $currentBodyFont }} (custom)</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Heading font (Google Fonts)</label>
                                            <select class="form-select" name="heading_font">
                                                @foreach($headingFonts as $font)
                                                    <option value="{{ $font }}" {{ $currentHeadingFont === $font ? 'selected' : '' }}>{{ $font }}</option>
                                                @endforeach
                                                @if($currentHeadingFont && ! isset($headingFonts[$currentHeadingFont]))
                                                    <option value="{{ $currentHeadingFont }}" selected>{{ $currentHeadingFont }} (custom)</option>
                                                @endif
                                            </select>
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
                                                <div class="form-text">Product pages with descriptions, materials, and order requests. The homepage photo gallery still appears either way.</div>
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
                                    <p class="text-muted mb-4">Set the title, caption, and header image for each public page. Leave a field empty to keep the site default for that page.</p>

                                    <div class="card mb-4 border">
                                        <div class="card-header bg-light fw-semibold">Homepage hero defaults</div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">Hero media (slideshow, banner, or video) is managed under <strong>Homepage hero</strong> in the admin menu. The text here is the default headline when a slide has no caption.</p>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Default hero caption</label>
                                                    <input type="text" class="form-control" name="hero_headline" value="{{ $data->hero_headline }}" placeholder="Premium Custom Handbags. Crafted in Rwanda.">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Default hero subheadline</label>
                                                    <input type="text" class="form-control" name="hero_subheadline" value="{{ $data->hero_subheadline }}" placeholder="Ethical bag manufacturing that strengthens families…">
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
                                                        <label class="form-label">Page title</label>
                                                        <input type="text" class="form-control mb-3" name="page_headers[{{ $pageKey }}][title]" value="{{ $stored['title'] ?? '' }}" placeholder="{{ $pageLabel }}">
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
