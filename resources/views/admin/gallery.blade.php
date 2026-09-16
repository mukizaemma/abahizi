@extends('layouts.adminbase')

@section('title', 'Home Page')

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
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h1>Site gallery</h1>
                        <p class="text-muted mb-0">Photos on the public Gallery page (next to Updates). This is separate from the media library file manager.</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                        <i class="fa fa-plus me-1"></i> Add image
                    </button>
                </div>
                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if(session()->has('warning'))
                    <div class="alert alert-warning">{{ session()->get('warning') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session()->get('error') }}</div>
                @endif

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Public gallery photos</span>
                        </div>

                        <div class="card-body">
                            <table class="table table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Caption</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($images as $rs)
                                        @php
                                            $imagePath = ltrim((string) $rs->image, '/');
                                            $imageUrl = str_contains($imagePath, '/')
                                                ? asset('storage/' . $imagePath)
                                                : asset('storage/images/gallery/' . $imagePath);
                                        @endphp
                                        <tr>
                                            <td>
                                                <img src="{{ $imageUrl }}" alt="{{ $rs->caption ?: 'Gallery image' }}" width="150">
                                            </td>
                                            <td>{{ $rs->caption ?: '—' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('editGallery', $rs->id) }}" class="btn btn-primary text-black">Edit</a>
                                                    <a href="{{ route('destroyGallery', $rs->id) }}" class="btn btn-danger text-black"
                                                        onclick="return confirm('Are you sure to delete this item?')">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted">No gallery images yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Adding New Image</h4>
                                        <button type="button" class="btn-close text-black"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                    <form class="form" action="{{ route('saveGallery') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="image" class="form-label">Select file</label>
                                                    <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                                    <small class="text-muted">Optional size guide: 540×600 pixels. Larger files are compressed automatically.</small>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="caption" class="form-label">Caption <span class="text-muted fw-normal">(optional)</span></label>
                                                    <input type="text" id="caption" name="caption" class="form-control" placeholder="Optional caption">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Add New Image
                                            </button>
                                        </div>
                                    </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-black"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

@section('scripts')

<script src="{{asset('assets')}}/js/summernote.js"></script>

@endsection
