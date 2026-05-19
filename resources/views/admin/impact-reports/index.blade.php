@extends('layouts.adminbase')

@section('title', 'Impact Reports')

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
                        <h1>Impact Reports</h1>
                        <p class="text-muted mb-0">Manage the public impact reports page and annual report PDFs.</p>
                    </div>
                    <a href="{{ route('impactReports') }}" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer">View public page</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                <div class="card mb-4">
                    <div class="card-header">Impact reports page</div>
                    <div class="card-body">
                        <form action="{{ route('impactReports.admin.page') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Page title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Introduction</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Short description shown on the impact reports listing page.">{{ old('description', $page->description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save page content</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Add annual report</div>
                    <div class="card-body">
                        <form action="{{ route('impactReports.admin.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Heading</label>
                                <input type="text" name="heading" class="form-control" value="{{ old('heading') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sort order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                    <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Short description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PDF file</label>
                                <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                                <small class="text-muted">Max 20 MB.</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Add report</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Annual reports</div>
                    <div class="card-body p-0">
                        @forelse($reports as $report)
                            <form action="{{ route('impactReports.admin.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="border-bottom p-4 m-0">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Heading</label>
                                        <input type="text" name="heading" class="form-control" value="{{ $report->heading }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">Sort</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ $report->sort_order }}" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Active" @selected($report->status === 'Active')>Active</option>
                                            <option value="Inactive" @selected($report->status === 'Inactive')>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        @if($report->pdf)
                                            <a href="{{ $report->pdfUrl() }}" class="btn btn-sm btn-outline-secondary w-100" target="_blank" rel="noopener noreferrer">View PDF</a>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-muted">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $report->description }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Replace PDF (optional)</label>
                                        <input type="file" name="pdf" class="form-control" accept="application/pdf">
                                    </div>
                                    <div class="col-md-6 d-flex flex-wrap gap-2 align-items-end justify-content-md-end">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        <a href="{{ route('impactReports.admin.destroy', $report->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this annual report?')">Delete</a>
                                    </div>
                                </div>
                            </form>
                        @empty
                            <div class="p-5 text-center text-muted">No annual reports yet. Add one above.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
