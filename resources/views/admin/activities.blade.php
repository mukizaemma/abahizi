@extends('layouts.adminbase')

@section('title', 'Initiatives')

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
                        <h1>Initiatives</h1>
                        <p class="text-muted mb-0">Manage initiatives with one clear description, cover image, and optional gallery images.</p>
                    </div>
                    <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#projectCreateModal">
                        <i class="fa fa-plus me-1"></i> Add Initiative
                    </button>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Program</th>
                                        <th>Description</th>
                                        <th>Cover</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $rs)
                                        <tr>
                                            <td class="fw-semibold">{{ $rs->title }}</td>
                                            <td>{{ $rs->program->title ?? 'Unassigned' }}</td>
                                            @php
                                                $desc = \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($rs->description ?? '')), 100, '…');
                                            @endphp
                                            <td>{{ $desc !== '' ? $desc : 'No description' }}</td>
                                            <td>
                                                @if(!empty($rs->image))
                                                    <img src="{{ asset('storage/' . $rs->image) }}" alt="{{ $rs->title }}" class="rounded border" width="90">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('editProject', ['id' => $rs->id]) }}" class="btn btn-outline-primary">Edit & Gallery</a>
                                                    <form action="{{ route('destroyProject', ['id' => $rs->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this initiative?')">
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
                                                    <i class="fas fa-briefcase d-block"></i>
                                                    <p class="mb-0">No initiatives found. Add your first initiative.</p>
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

<div class="modal fade" id="projectCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Initiative</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('saveProject') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label">Initiative title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Program</label>
                            <select name="program_id" class="form-select" required>
                                <option value="" selected disabled>Select program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Initiative details / description</label>
                            <textarea class="form-control" rows="6" name="description" data-no-editor="true" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Cover image</label>
                            <input type="file" class="form-control" name="image" required>
                        </div>
                    </div>
                    <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save Initiative</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
