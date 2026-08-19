@extends('layouts.adminbase')

@section('title', 'Homepage hero')

@section('sidebar')
    @parent
@endsection

@section('content')

@php
    $heroType = $setting->resolvedHeroMediaType();
    $posterUrl = $setting->heroPosterPublicUrl();
    $videoUrl = $setting->heroVideoPublicUrl();
@endphp

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header">
                    <h1>Homepage hero</h1>
                    <p class="text-muted mb-0">Choose sliding images, a single banner, or a video for the top of the homepage.</p>
                </div>

                <form action="{{ route('saveHero') }}" method="POST" enctype="multipart/form-data" class="card mb-4">
                    @csrf
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <label class="form-label">Headline</label>
                                <input type="text" class="form-control" name="hero_headline" value="{{ $setting->hero_headline }}" placeholder="Premium Custom Handbags. Crafted in Rwanda.">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Subheadline</label>
                                <input type="text" class="form-control" name="hero_subheadline" value="{{ $setting->hero_subheadline }}" placeholder="Ethical bag manufacturing that strengthens families…">
                            </div>
                        </div>

                        <label class="form-label d-block mb-2">Hero media</label>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="hero-type-card">
                                    <input type="radio" name="hero_media_type" value="slideshow" {{ $heroType === 'slideshow' ? 'checked' : '' }}>
                                    <span>
                                        <strong>Sliding images</strong>
                                        <small>Rotate through uploaded slides.</small>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="hero-type-card">
                                    <input type="radio" name="hero_media_type" value="image" {{ $heroType === 'image' ? 'checked' : '' }}>
                                    <span>
                                        <strong>One image banner</strong>
                                        <small>A single still image behind the text.</small>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="hero-type-card">
                                    <input type="radio" name="hero_media_type" value="video" {{ $heroType === 'video' ? 'checked' : '' }}>
                                    <span>
                                        <strong>Video</strong>
                                        <small>Upload a file or paste a video URL.</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div data-hero-panel="slideshow" class="hero-type-panel mb-3">
                            <p class="text-muted mb-0">Add or edit slides in the list below. Use two or more images for a slideshow.</p>
                        </div>

                        <div data-hero-panel="image" class="hero-type-panel mb-3">
                            <label class="form-label">Banner image</label>
                            <input type="file" class="form-control" name="hero_banner" accept="image/*">
                            <small class="text-muted d-block mt-1">Landscape recommended (1920×1080 or similar). Leave empty to keep the current banner.</small>
                            @if($posterUrl)
                                <img src="{{ $posterUrl }}" alt="Current banner" width="280" class="mt-2 rounded border">
                            @endif
                        </div>

                        <div data-hero-panel="video" class="hero-type-panel mb-3">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Upload video</label>
                                    <input type="file" class="form-control" name="hero_video" accept="video/mp4,video/webm,video/ogg" data-no-media-picker="true">
                                    <small class="text-muted">MP4 or WebM, up to 50 MB. A new upload replaces the current video.</small>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Or video URL</label>
                                    <input type="text" class="form-control" name="hero_video_url" value="{{ $setting->hero_video_url }}" placeholder="https://… or YouTube link">
                                    @if($videoUrl)
                                        <small class="text-muted d-block mt-1">A video is already set. Save after uploading a file or pasting a new URL to replace it.</small>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Poster image <span class="text-muted fw-normal">(optional)</span></label>
                                    <input type="file" class="form-control" name="hero_poster" accept="image/*">
                                    <small class="text-muted">Shown before the video starts, or if the video cannot play.</small>
                                    @if($posterUrl)
                                        <img src="{{ $posterUrl }}" alt="Current poster" width="220" class="mt-2 rounded border">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save hero
                        </button>
                    </div>
                </form>

                <div class="card mb-4" data-hero-panel="slideshow">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <strong>Slideshow images</strong>
                            <p class="text-muted small mb-0 mt-1">Used when hero media is set to sliding images.</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" type="button">
                            <i class="fa fa-plus"></i> Add slide
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hero caption</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slides as $rs)
                                    <tr>
                                        <td>{{ $rs->heading ?: '—' }}</td>
                                        <td>
                                            <img src="{{ \App\Models\Slide::publicImageUrl($rs->image) }}" alt="" width="150">
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('editSlide', $rs->id) }}" class="btn btn-primary">Edit</a>
                                                <a href="{{ route('destroySlide', $rs->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure to delete this item?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">No slides yet. Add at least one image for the slideshow.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add homepage hero slide</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form class="form" action="{{ route('saveSlide') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Hero image</label>
                                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                        <small class="text-muted">Landscape recommended (1920×1080 or similar)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Slide caption <span class="text-muted fw-normal">(optional)</span></label>
                                        <input type="text" class="form-control" placeholder="e.g. Premium Custom Handbags. Crafted in Rwanda." name="heading">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Add slide
                                    </button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection

@section('scripts')
<style>
    .hero-type-card {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        height: 100%;
        margin: 0;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        cursor: pointer;
        background: #fff;
    }
    .hero-type-card input {
        margin-top: 0.2rem;
    }
    .hero-type-card strong {
        display: block;
    }
    .hero-type-card small {
        display: block;
        color: #64748b;
        margin-top: 0.2rem;
    }
    .hero-type-card:has(input:checked) {
        border-color: var(--brand-primary, #fad200);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--brand-primary, #fad200) 28%, transparent);
    }
    .hero-type-panel[hidden] {
        display: none !important;
    }
</style>
<script>
    (function () {
        function syncHeroPanels() {
            var selected = document.querySelector('input[name="hero_media_type"]:checked');
            var type = selected ? selected.value : 'slideshow';
            document.querySelectorAll('[data-hero-panel]').forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-hero-panel') !== type;
            });
        }
        document.querySelectorAll('input[name="hero_media_type"]').forEach(function (input) {
            input.addEventListener('change', syncHeroPanels);
        });
        syncHeroPanels();
    })();
</script>
@endsection
