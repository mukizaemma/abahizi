@extends('layouts.adminbase')

@section('title', 'Edit Initiative')

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
                        <h1>Edit Initiative</h1>
                        <p class="text-muted mb-0">Update initiative details, assign program, and manage gallery images.</p>
                    </div>
                    <a href="{{ route('getProjects') }}" class="btn btn-outline-primary">Back to Initiatives</a>
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
                                    <label class="form-label">Program</label>
                                    <select name="program_id" class="form-select" required>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ (int)$data->program_id === (int)$program->id ? 'selected' : '' }}>
                                                {{ $program->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Overview (optional)</label>
                                    <textarea rows="6" class="form-control" name="description" data-no-editor="true">{!! $data->description !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">What we do</label>
                                    <textarea rows="5" class="form-control" name="what_we_do" data-no-editor="true">{!! $data->what_we_do !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">How we do it</label>
                                    <textarea rows="5" class="form-control" name="how_we_do_it" data-no-editor="true">{!! $data->how_we_do_it !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Impact</label>
                                    <textarea rows="5" class="form-control" name="impact" data-no-editor="true">{!! $data->impact !!}</textarea>
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
                                <button type="submit" class="btn btn-primary px-4">Save Initiative Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Initiative Gallery ({{ $totalImages }})</span>
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
                <h5 class="modal-title">Upload Initiative Gallery Images</h5>
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
