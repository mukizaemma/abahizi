@extends('layouts.adminbase')

@section('title', 'Updates')

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
                        <h1>Updates</h1>
                        <p class="text-muted mb-0">Publish stories from workshops, events, and community visits. Save as a draft or go live immediately.</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBlogModal">
                        <i class="fa fa-plus me-1"></i> New Update
                    </button>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive admin-table-wrap">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Gallery</th>
                                        <th>Cover</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($blogs as $rs)
                                        <tr>
                                            <td class="text-nowrap">{{ optional($rs->displayDate())->format('d M Y') }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $rs->title }}</div>
                                                <small class="text-muted">{{ $rs->excerptText(90) }}</small>
                                            </td>
                                            <td>
                                                @if($rs->published_at)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $rs->blogimages_count }}</span></td>
                                            <td>
                                                @if($rs->coverUrl())
                                                    <img src="{{ $rs->coverUrl() }}" alt="{{ $rs->title }}" width="90" class="rounded border">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('editBlog', $rs->id) }}" class="btn btn-outline-primary">Edit</a>
                                                    @if(!$rs->published_at)
                                                        <a href="{{ route('publishBlog', $rs->id) }}" class="btn btn-outline-success">Publish</a>
                                                    @else
                                                        <a href="{{ route('unpublishBlog', $rs->id) }}" class="btn btn-outline-secondary">Unpublish</a>
                                                    @endif
                                                    <a href="{{ route('deleteBlog', $rs->id) }}" class="btn btn-outline-danger" onclick="return confirm('Delete this update?')">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border-0">
                                                <div class="admin-empty-state">
                                                    <i class="fas fa-newspaper d-block"></i>
                                                    <p class="mb-0">No updates yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $blogs->links() }}
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

<div class="modal fade" id="createBlogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('saveBlog') }}" method="POST" enctype="multipart/form-data" data-turbo="false">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author') }}" placeholder="e.g. Communications Team">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Story</label>
                            <textarea rows="10" class="form-control" name="body" data-editor="rich" data-editor-modal="true" placeholder="Write the update...">{{ old('body') }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Cover image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Event gallery (optional, multiple)</label>
                            <input type="file" class="form-control" name="gallery[]" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">Save as Draft</button>
                        <button type="submit" name="status" value="publish" class="btn btn-primary">Publish now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
