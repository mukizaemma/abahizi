@php
    $pageKey = $pageKey ?? '';
    $label = \App\Support\PageHeaderService::definitions()[$pageKey] ?? 'This page';
    $stored = (array) (\App\Support\PageHeaderService::storedHeaders()[$pageKey] ?? []);
    $storedImage = $stored['image'] ?? null;
@endphp
@if($pageKey !== '')
<form action="{{ route('pageHeader.update') }}" method="POST" enctype="multipart/form-data" class="card mb-4 border">
    @csrf
    <input type="hidden" name="page_header_key" value="{{ $pageKey }}">
    <div class="card-header bg-light fw-semibold">Public page header — {{ $label }}</div>
    <div class="card-body">
        <p class="text-muted small mb-3">Title, caption, and banner at the top of this public page. Leave a field empty to keep the default.</p>
        <div class="row g-3">
            <div class="col-lg-6">
                <label class="form-label">Page title</label>
                <input type="text" class="form-control" name="header_title" value="{{ old('header_title', $stored['title'] ?? '') }}" placeholder="{{ $label }}">
            </div>
            <div class="col-lg-6">
                <label class="form-label">Header image</label>
                <input type="file" class="form-control" name="header_image" accept="image/*">
                @if(!empty($storedImage))
                    <img src="{{ \App\Support\PageHeaderService::imageUrlFromStored($storedImage) }}" alt="" width="160" class="mt-2 rounded border p-1 bg-white">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Caption</label>
                <textarea class="form-control" rows="2" name="header_caption" placeholder="Optional line under the title">{{ old('header_caption', $stored['caption'] ?? '') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save page header</button>
    </div>
</form>
@endif
