@extends('layouts.adminbase')

@section('title', 'Edit Update')

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
                        <h1>Edit Update</h1>
                        <p class="text-muted mb-0">Refine the story, cover, and gallery — then save as a draft or publish.</p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">Back to Updates</a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('updateBlog', $blog->id) }}" method="POST" enctype="multipart/form-data" data-turbo="false">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $blog->title) }}" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Author</label>
                                    <input type="text" name="author" class="form-control" value="{{ old('author', $blog->author) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Story</label>
                                    <textarea class="form-control" rows="10" name="body" data-editor="rich">{!! old('body', $blog->body) !!}</textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Current cover</label>
                                    <div class="mb-2">
                                        @if($blog->coverUrl())
                                            <img src="{{ $blog->coverUrl() }}" alt="{{ $blog->title }}" width="120" class="rounded border">
                                        @else
                                            <span class="text-muted">No cover image</span>
                                        @endif
                                    </div>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Add more gallery images</label>
                                    <input type="file" class="form-control" name="gallery[]" accept="image/*" multiple>
                                </div>
                            </div>
                            <div class="mt-4 d-flex flex-wrap gap-2">
                                <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">Save as Draft</button>
                                <button type="submit" name="status" value="publish" class="btn btn-primary">{{ $blog->published_at ? 'Save & keep published' : 'Publish now' }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Event gallery ({{ $blog->blogimages->count() }})</div>
                    <div class="card-body">
                        @if($blog->blogimages->isEmpty())
                            <div class="admin-empty-state py-4">
                                <i class="fas fa-images d-block"></i>
                                <p class="mb-0">No gallery images yet.</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($blog->blogimages as $image)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-2 h-100">
                                            <img src="{{ $image->imageUrl() }}" class="img-fluid rounded mb-2" alt="{{ $image->caption ?: $blog->title }}">
                                            <a href="{{ route('deleteBlogImage', $image->id) }}" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Delete this gallery image?')">Delete image</a>
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
@endsection
