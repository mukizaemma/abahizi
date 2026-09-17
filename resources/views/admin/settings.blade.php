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
                    <p class="text-muted mb-0">Brand, contact details, colours, product visibility, and headers for pages that appear on the public site. Homepage section titles are edited on those sections (Our Story, Core values, Products, Impact, Homepage hero).</p>
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
                                    <button class="nav-link" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers-pane" type="button" role="tab">Default header</button>
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
                                        <div class="col-lg-6">
                                            <label class="form-label">Phone (also used for WhatsApp)</label>
                                            <input type="text" class="form-control" value="{{ $data->phone }}" name="phone">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Alternate phone <span class="text-muted fw-normal">(optional)</span></label>
                                            <input type="text" class="form-control" value="{{ $data->phone1 }}" name="phone1">
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
                                        <div class="col-lg-6">
                                            <label class="form-label">X / Twitter</label>
                                            <input type="url" class="form-control" value="{{ $data->twitter }}" name="twitter">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Google Map embed code</label>
                                            <textarea class="form-control" rows="4" name="google_map_embed_code" placeholder="Paste iframe embed code">{{ $data->google_map_embed_code }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                            <h6 class="mb-3">Homepage and contact page wording</h6>
                                            @include('admin.includes.landing-copy-fields', ['group' => 'contact', 'data' => $data])
                                        </div>
                                        @php
                                            $contactHeader = (array) ($pageHeaderStore['contact'] ?? []);
                                        @endphp
                                        <div class="col-12">
                                            <hr>
                                            <h6 class="mb-3">Contact page banner</h6>
                                            <p class="text-muted small">Title, caption, and image at the top of the public Get in Touch page.</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Page title</label>
                                            <input type="text" class="form-control" name="page_headers[contact][title]" value="{{ $contactHeader['title'] ?? '' }}" placeholder="Get In Touch">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Header image</label>
                                            <input type="file" class="form-control" name="page_headers[contact][image]" accept="image/*">
                                            @if(!empty($contactHeader['image']))
                                                <img src="{{ PageHeaderService::imageUrlFromStored($contactHeader['image']) }}" alt="" width="160" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Caption</label>
                                            <textarea class="form-control" rows="2" name="page_headers[contact][caption]">{{ $contactHeader['caption'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="colors-pane" role="tabpanel" aria-labelledby="colors-tab">
                                    @php
                                        $bodyFonts = \App\Support\ThemeService::bodyFonts();
                                        $headingFonts = \App\Support\ThemeService::headingFonts();
                                        $currentBodyFont = $data->font_family ?: \App\Support\ThemeService::DEFAULT_BODY_FONT;
                                        $currentHeadingFont = $data->heading_font ?: \App\Support\ThemeService::DEFAULT_HEADING_FONT;
                                        $primaryColor = \App\Support\ThemeService::sanitizeHex($data->primary_color ?? null, \App\Support\ThemeService::DEFAULT_PRIMARY);
                                        $secondaryColor = \App\Support\ThemeService::sanitizeHex($data->secondary_color ?? null, \App\Support\ThemeService::DEFAULT_SECONDARY);
                                        $neutralColor = \App\Support\ThemeService::sanitizeHex($data->neutral_color ?? null, \App\Support\ThemeService::DEFAULT_NEUTRAL);
                                    @endphp
                                    <p class="text-muted mb-4">These colours apply across the public site and the admin panel. The logo defaults are yellow <code>#fad200</code>, black <code>#000000</code>, and grey <code>#9a9a9a</code>.</p>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label" for="primary_color">Accent</label>
                                            <div class="admin-color-field">
                                                <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ $primaryColor }}" title="Accent colour">
                                                <input type="text" class="form-control" value="{{ $primaryColor }}" maxlength="7" spellcheck="false" data-color-hex-for="primary_color" aria-label="Accent hex">
                                            </div>
                                            <div class="form-text">Buttons, highlights, and links.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="secondary_color">Text / dark</label>
                                            <div class="admin-color-field">
                                                <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="{{ $secondaryColor }}" title="Text colour">
                                                <input type="text" class="form-control" value="{{ $secondaryColor }}" maxlength="7" spellcheck="false" data-color-hex-for="secondary_color" aria-label="Text hex">
                                            </div>
                                            <div class="form-text">Headings, body text, and the header.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="neutral_color">Muted</label>
                                            <div class="admin-color-field">
                                                <input type="color" class="form-control form-control-color" id="neutral_color" name="neutral_color" value="{{ $neutralColor }}" title="Muted colour">
                                                <input type="text" class="form-control" value="{{ $neutralColor }}" maxlength="7" spellcheck="false" data-color-hex-for="neutral_color" aria-label="Muted hex">
                                            </div>
                                            <div class="form-text">Secondary text and borders.</div>
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
                                                    Show the products page on the public site
                                                </label>
                                                <div class="form-text">When this is off, Products is hidden from the menu and footer, the “view more bags” button is removed from the homepage, and <code>/products</code> is not available.</div>
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
                                    <p class="text-muted mb-4">Fallback banner used when a page has no header of its own. Edit each page’s title, caption, and image on that page’s admin screen (Our Story, Products, Impact, Team, and so on).</p>

                                    <div class="card admin-cms-card mb-0">
                                        <div class="card-header">
                                            <span class="admin-cms-card__kicker">Public page banner</span>
                                            <strong>Default fallback</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="admin-banner-card">
                                                <div class="admin-banner-card__media">
                                                    <label class="form-label" for="page_header_image">Banner photo</label>
                                                    <input type="file" class="form-control" id="page_header_image" name="page_header_image" accept="image/*" data-media-layout="banner">
                                                    @if(!empty($data->page_header_image))
                                                        <img src="{{ asset('storage/images') . $data->page_header_image }}" alt="Default header" class="admin-preview-img">
                                                    @endif
                                                </div>
                                                <div class="admin-banner-card__copy">
                                                    <div class="admin-banner-card__caption">
                                                        <label class="form-label" for="page_header_caption">Default caption</label>
                                                        <textarea class="form-control" id="page_header_caption" rows="4" name="page_header_caption" placeholder="Used when a page has no custom caption">{{ $data->page_header_caption }}</textarea>
                                                    </div>
                                                    <p class="admin-cms-card__help mb-0">Saved with the rest of Site settings.</p>
                                                </div>
                                            </div>
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

@section('scripts')
<script>
    document.querySelectorAll('[data-color-hex-for]').forEach(function (hexInput) {
        var picker = document.getElementById(hexInput.getAttribute('data-color-hex-for'));
        if (!picker) {
            return;
        }
        picker.addEventListener('input', function () {
            hexInput.value = picker.value;
        });
        hexInput.addEventListener('change', function () {
            var value = hexInput.value.trim();
            if (/^#([A-Fa-f0-9]{6})$/.test(value)) {
                picker.value = value.toLowerCase();
                hexInput.value = picker.value;
            } else {
                hexInput.value = picker.value;
            }
        });
    });
</script>
@endsection
