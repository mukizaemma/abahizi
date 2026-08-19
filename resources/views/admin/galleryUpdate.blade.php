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

                </ol>
                <div class="row">
                    @if(session()->has('success'))
                    <div class="arlert alert-success">
                        <button class="close" type="button" data-dismiss="alert">X</button>
                        {{ session()->get('success') }}
                    </div>
                    @endif

                    @if(session()->has('warning'))
                    <div class="arlert alert-warning">
                        <button class="close" type="button" data-dismiss="alert">X</button>
                        {{ session()->get('warning') }}
                    </div>
                    @endif
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <a href="{{route('images')}}" class="btn btn-primary">Back</a>
                    </div>
                    <div class="card-body">
                        <form class="form" action="{{ route('updateGallery', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $imagePath = ltrim((string) $data->image, '/');
                            $imageUrl = str_contains($imagePath, '/')
                                ? asset('storage/' . $imagePath)
                                : asset('storage/images/gallery/' . $imagePath);
                        @endphp
                        <div class="form-body">
                            <div class="row mb-4">
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Current image</label>
                                    <div>
                                        <img src="{{ $imageUrl }}" alt="{{ $data->caption ?: 'Gallery image' }}" width="160" class="rounded border">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <label for="image" class="form-label">Replace file <span class="text-muted fw-normal">(optional)</span></label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep the current image.</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-sm-12">
                                    <label for="caption" class="form-label">Caption <span class="text-muted fw-normal">(optional)</span></label>
                                    <input type="text" class="form-control" id="caption" value="{{ $data->caption }}" name="caption" placeholder="Optional caption">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save image
                            </button>
                        </div>
                    </form>
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
