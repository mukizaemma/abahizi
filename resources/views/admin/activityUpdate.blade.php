@extends('layouts.adminbase')

@section('title', 'Edit Community Initiative')

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
                        <h1>Edit community initiative</h1>
                        <p class="text-muted mb-0">Update title, description, status, cover image, and gallery images for Impact → Community.</p>
                    </div>
                    <a href="{{ route('communityImpact.admin.index') }}" class="btn btn-outline-primary">Back to community impact</a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('updateProject', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Initiative title</label>
                                    <input type="text" class="form-control" name="title" value="{{ $data->title }}" required>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Active" {{ ($data->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active — visible on Impact → Community</option>
                                        <option value="Inactive" {{ ($data->status ?? 'Active') === 'Inactive' ? 'selected' : '' }}>Inactive — hidden from public page</option>
                                    </select>
                                </div>
                                @if(($programs ?? collect())->isNotEmpty())
                                    <div class="col-12">
                                        <label class="form-label">Program <span class="text-muted small">(optional)</span></label>
                                        <select name="program_id" class="form-select">
                                            <option value="">None</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}" {{ (int)$data->program_id === (int)$program->id ? 'selected' : '' }}>
                                                    {{ $program->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <label class="form-label">Initiative details / description</label>
                                    <textarea rows="7" class="form-control" name="description" data-no-editor="true" required>{!! $data->description !!}</textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Current cover</label>
                                    <div>
                                        @if(!empty($data->image))
                                            <img src="{{ asset('storage/' . $data->image) }}" alt="{{ $data->title }}" class="rounded border" width="120">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Change cover image</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Gallery images ({{ $totalImages }})</span>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#projectImageModal">
                            <i class="fa fa-plus me-1"></i> Add Images
                        </button>
                    </div>
                    <div class="card-body">
                        @if($images->isEmpty())
                            <div class="admin-empty-state py-4">
                                <i class="fas fa-images d-block"></i>
                                <p class="mb-0">No initiative gallery images added yet.</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($images as $image)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-2 h-100">
                                            <img src="{{ asset('storage/' . $image->image) }}" class="img-fluid rounded mb-2" alt="Project image">
                                            <form action="{{ route('deleteProjectImage', $image->id) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete image</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade" id="projectImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload gallery images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('addProjectImage') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $data->id }}">
                    <div class="mb-3">
                        <label class="form-label">Select one or more images</label>
                        <input type="file" class="form-control" name="image[]" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Images</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
