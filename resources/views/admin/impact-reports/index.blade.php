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
                        <p class="text-muted mb-0">Manage listing page, report landing pages, PDFs, and optional galleries.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReportModal">
                            <i class="fas fa-plus me-1"></i> Add report
                        </button>
                        <a href="{{ route('impactReports') }}" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer">View public page</a>
                    </div>
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
                    <div class="card-header">Impact reports listing page</div>
                    <div class="card-body">
                        <form action="{{ route('impactReports.admin.page') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Page title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Introduction</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $page->description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save page content</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span>Annual reports</span>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addReportModal">
                            <i class="fas fa-plus me-1"></i> Add report
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Sort</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr>
                                            <td class="fw-semibold">{{ $report->heading }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $report->status }}</span></td>
                                            <td>{{ $report->sort_order }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('impactReports.admin.edit', $report->id) }}" class="btn btn-primary">Edit</a>
                                                    <a href="{{ route('impactReportShow', $report->slug) }}" class="btn btn-outline-secondary" target="_blank" rel="noopener noreferrer">View more</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No reports yet. Click <strong>Add report</strong> to create one.</td>
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

<div class="modal fade" id="addReportModal" tabindex="-1" aria-labelledby="addReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReportModalLabel">Add annual report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('impactReports.admin.store') }}" method="POST" enctype="multipart/form-data" id="addReportForm" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">Menu / list title</label>
                        <input type="text" name="heading" class="form-control" value="{{ old('heading') }}" required placeholder="e.g. 2025 Impact Report">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                            <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PDF button label (optional)</label>
                        <input type="text" name="pdf_button_label" class="form-control" value="{{ old('pdf_button_label') }}" placeholder="Read the 2025 Impact Report">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short description (listing page)</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Highlight title (report page)</label>
                        <input type="text" name="highlight_title" class="form-control" value="{{ old('highlight_title') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Highlight message</label>
                        <textarea name="highlight_message" class="form-control" rows="4">{{ old('highlight_message') }}</textarea>
                    </div>
                    <div class="col-12">
                        @include('admin.includes.chunked-pdf-upload', ['required' => true, 'label' => 'PDF'])
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any() && (old('heading') || $errors->has('pdf_upload_id')))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('addReportModal');
                if (el && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        </script>
    @endpush
@endif
@endsection
