@extends('layouts.adminbase')

@section('title', 'Media Gallery')

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
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1>Media library</h1>
                        <p class="text-muted mb-0">All images stored on the site. This is a file manager, not a public gallery — check where a file is used before replacing or removing it.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mediaLibrary.index') }}" class="btn {{ ! $duplicatesOnly ? 'btn-primary' : 'btn-outline-primary' }}">All images</a>
                        <a href="{{ route('mediaLibrary.index', ['duplicates' => 1]) }}" class="btn {{ $duplicatesOnly ? 'btn-primary' : 'btn-outline-primary' }}">Duplicates</a>
                    </div>
                </div>

                @if($files->isEmpty())
                    <div class="card">
                        <div class="card-body">
                            <div class="admin-empty-state">
                                <i class="fas fa-images d-block"></i>
                                <p class="mb-0">{{ $duplicatesOnly ? 'No duplicate images found.' : 'No images in storage yet.' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="media-gallery-grid">
                        @foreach($files as $file)
                            <article class="media-gallery-card">
                                <button type="button" class="media-gallery-card__thumb" data-media-inspect="{{ $file['path'] }}">
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
                                </button>
                                <div class="media-gallery-card__meta">
                                    <div class="text-truncate fw-semibold" title="{{ $file['name'] }}">{{ $file['name'] }}</div>
                                    <div class="small text-muted">{{ $file['size_label'] }} · {{ $file['modified'] }}</div>
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        <span class="badge bg-light text-dark border">Used {{ $file['usage_count'] }}×</span>
                                        @if(($file['duplicate_count'] ?? 1) > 1)
                                            <span class="badge bg-warning text-dark">{{ $file['duplicate_count'] }} copies</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $files->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade" id="mediaInspectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image uses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="mediaInspectBody">
                <p class="text-muted mb-0">Loading…</p>
            </div>
        </div>
    </div>
</div>
@endsection
