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
            <div class="container-fluid px-4">
                {{-- <h1 class="mt-4">Dashboard</h1> --}}
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Gallery</li>
                </ol>
                <div class="row">
                    @if(session()->has('success'))
                    <div class="arlert alert-success">
                        <button class="close" type="button" data-dismiss="alert">X</button>
                        {{ session()->get('success') }}
                    </div>

                    @endif
                </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Photo gallery</span>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                <i class="fa fa-plus"></i> Add Image
                            </button>
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
