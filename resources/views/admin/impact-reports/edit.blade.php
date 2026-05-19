@extends('layouts.adminbase')

@section('title', 'Edit impact report')

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
                        <h1>Edit report</h1>
                        <p class="text-muted mb-0">{{ $report->heading }}</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('impactReportShow', $report->slug) }}" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer">View public page</a>
                        <a href="{{ route('impactReports.admin.index') }}" class="btn btn-outline-secondary">Back to list</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('impactReports.admin.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="card mb-4">
                    @csrf
                    <div class="card-header">Report details</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Menu / list title</label>
                            <input type="text" name="heading" class="form-control" value="{{ old('heading', $report->heading) }}" required>
                            <small class="text-muted">Shown in the header dropdown and reports list.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $report->sort_order) }}" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" @selected(old('status', $report->status) === 'Active')>Active</option>
                                <option value="Inactive" @selected(old('status', $report->status) === 'Inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $report->description) }}</textarea>
                            <small class="text-muted">Used on the main impact reports listing page.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Highlight title</label>
                            <input type="text" name="highlight_title" class="form-control" value="{{ old('highlight_title', $report->highlight_title) }}" placeholder="e.g. A journey of innovation and impact">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Highlight message</label>
                            <textarea name="highlight_message" class="form-control" rows="6" data-editor="rich">{{ old('highlight_message', $report->highlight_message) }}</textarea>
                            <small class="text-muted">Main message on the report landing page.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PDF button label</label>
                            <input type="text" name="pdf_button_label" class="form-control" value="{{ old('pdf_button_label', $report->pdf_button_label) }}" placeholder="Read the 2025 Impact Report">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Replace PDF (optional)</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf">
                            @if($report->pdf)
                                <a href="{{ $report->pdfUrl() }}" class="small d-inline-block mt-1" target="_blank" rel="noopener noreferrer">Current PDF</a>
                            @endif
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save report</button>
                        </div>
                    </div>
                </form>

                <div class="card mb-4">
                    <div class="card-header">Experience gallery (optional)</div>
                    <div class="card-body">
                        <p class="text-muted">Images appear on the report page only when at least one is uploaded.</p>
                        <form action="{{ route('impactReports.admin.gallery.store', $report->id) }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">Upload images</label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary w-100">Add to gallery</button>
                            </div>
                        </form>
                        @if($report->images->isNotEmpty())
                            <div class="row g-3">
                                @foreach($report->images as $image)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="border rounded p-2 bg-white h-100">
                                            <img src="{{ $image->imageUrl() }}" alt="" class="img-fluid rounded mb-2">
                                            <a href="{{ route('impactReports.admin.gallery.destroy', $image->id) }}" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Remove this image?')">Remove</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No gallery images yet.</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('impactReports.admin.destroy', $report->id) }}" class="btn btn-outline-danger" onclick="return confirm('Delete this report permanently?')">Delete report</a>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
