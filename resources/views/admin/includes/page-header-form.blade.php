@php
    $pageKey = $pageKey ?? '';
    $label = \App\Support\PageHeaderService::definitions()[$pageKey] ?? 'This page';
    $stored = (array) (\App\Support\PageHeaderService::storedHeaders()[$pageKey] ?? []);
    $storedImage = $stored['image'] ?? null;
    $previewUrl = ! empty($storedImage) ? \App\Support\PageHeaderService::imageUrlFromStored($storedImage) : null;
@endphp
@if($pageKey !== '')
<form action="{{ route('pageHeader.update') }}" method="POST" enctype="multipart/form-data" class="card admin-cms-card mb-4">
    @csrf
    <input type="hidden" name="page_header_key" value="{{ $pageKey }}">
    <div class="card-header">
        <span class="admin-cms-card__kicker">Public page banner</span>
        <strong>{{ $label }}</strong>
    </div>
    <div class="card-body">
        <p class="admin-cms-card__help">This is the wide title area at the top of the public page. Leave a field empty to keep the default.</p>
        <div class="admin-banner-card">
            <div class="admin-banner-card__media">
                <label class="form-label" for="header_image_{{ $pageKey }}">Banner photo</label>
                <input
                    type="file"
                    class="form-control"
                    id="header_image_{{ $pageKey }}"
                    name="header_image"
                    accept="image/*"
                    data-media-layout="banner"
                >
                @if($previewUrl)
                    <img src="{{ $previewUrl }}" alt="" class="admin-preview-img">
                @endif
            </div>
            <div class="admin-banner-card__copy">
                <div>
                    <label class="form-label" for="header_title_{{ $pageKey }}">Page title</label>
                    <input
                        type="text"
                        class="form-control"
                        id="header_title_{{ $pageKey }}"
                        name="header_title"
                        value="{{ old('header_title', $stored['title'] ?? '') }}"
                        placeholder="{{ $label }}"
                    >
                </div>
                <div class="admin-banner-card__caption">
                    <label class="form-label" for="header_caption_{{ $pageKey }}">Caption</label>
                    <textarea
                        class="form-control"
                        id="header_caption_{{ $pageKey }}"
                        rows="3"
                        name="header_caption"
                        placeholder="Optional line under the title"
                    >{{ old('header_caption', $stored['caption'] ?? '') }}</textarea>
                </div>
                <div class="admin-banner-card__actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Save page header
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
