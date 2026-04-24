@extends('layouts.adminbase')

@section('title', 'Programs')

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
                        <h1>Programs</h1>
                        <p class="text-muted mb-0">Create and manage program content and cover images.</p>
                    </div>
                    <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#programCreateModal">
                        <i class="fa fa-plus me-1"></i> Add Program
                    </button>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Cover</th>
                                        <th>Projects</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $rs)
                                        <tr>
                                            <td class="fw-semibold">{{ $rs->title }}</td>
                                            <td>
                                                <span class="cell-clamp d-inline-block" title="{{ strip_tags($rs->description) }}">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($rs->description), 120) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(!empty($rs->image))
                                                    <img src="{{ asset('storage/' . $rs->image) }}" alt="{{ $rs->title }}" class="rounded border" width="90">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $rs->activities_count }}</span></td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('editProgram', $rs->id) }}" class="btn btn-outline-primary">Edit</a>
                                                    <a href="{{ route('editProgram', $rs->id) }}" class="btn btn-outline-secondary">Gallery</a>
                                                    <form action="{{ route('destroyProgram', $rs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this program?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="border-0">
                                                <div class="admin-empty-state">
                                                    <i class="fas fa-folder-open d-block"></i>
                                                    <p class="mb-0">No programs yet. Create the first one to get started.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade" id="programCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('saveProgram') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Program title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="6" name="description" placeholder="Program description" data-no-editor="true"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Cover image</label>
                            <input type="file" class="form-control" name="image" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Program gallery images (optional)</label>
                            <input type="file" class="form-control" name="gallery_images[]" multiple>
                            <small class="text-muted">You can select multiple images.</small>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
