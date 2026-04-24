@extends('layouts.adminbase')

@section('title', 'Product story section')

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
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                    <div>
                        <h1>Product story</h1>
                        <p class="text-muted mb-0">Heading and bullet points shown below the product grid and on product detail pages (Made in Rwanda story).</p>
                    </div>
                    <a href="{{ route('catalogProducts.index') }}" class="btn btn-outline-primary">Products catalog</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">Section heading</div>
                    <div class="card-body">
                        <form action="{{ route('productStory.heading') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-lg-10">
                                <label class="form-label">Banner title</label>
                                <input type="text" name="heading" class="form-control" value="{{ old('heading', $setting->heading) }}" placeholder="e.g. What goes into our handbags">
                                <small class="text-muted">Displayed in the yellow banner above the bullet list.</small>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary w-100">Save heading</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Add point</div>
                    <div class="card-body">
                        <form action="{{ route('productStory.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Text</label>
                                <textarea name="content" class="form-control" rows="4" required placeholder="Paragraph or bullet text…">{{ old('content') }}</textarea>
                                <small class="text-muted">You can add multiple points at once using commas, new lines, or bullet symbols.</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sort order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Add point</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">All points (newest edits at bottom — order by sort)</div>
                    <div class="card-body p-0">
                        @forelse($points as $point)
                            <form action="{{ route('productStory.update', $point->id) }}" method="POST" class="border-bottom p-3 m-0">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small text-muted mb-1">Point #{{ $point->id }}</label>
                                        <textarea name="content" class="form-control" rows="3" required>{{ $point->content }}</textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Sort</label>
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $point->sort_order }}" min="0">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="pt{{ $point->id }}" {{ $point->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pt{{ $point->id }}">Active</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-md-end d-flex flex-wrap gap-2 justify-content-md-end align-items-end">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        <a href="{{ route('productStory.destroy', $point->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this point?')">Delete</a>
                                    </div>
                                </div>
                            </form>
                        @empty
                            <div class="p-5 text-center text-muted">No points yet. Add content above.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
