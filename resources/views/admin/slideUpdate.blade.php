@extends('layouts.adminbase')

@section('title', 'Edit hero slide')

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
                <div class="admin-page-header">
                    <h1>Edit hero slide</h1>
                    <p class="text-muted mb-0">Headline and subheadline appear on the homepage only when sliding images use each slide’s caption.</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <a href="{{ route('slides') }}" class="btn btn-outline-secondary">Back to Homepage hero</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('updateSlide', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-lg-4">
                                    <label class="form-label">Current image</label>
                                    <div>
                                        <img src="{{ \App\Models\Slide::publicImageUrl($data->image) }}" alt="" class="rounded border" width="180">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <label class="form-label">Replace image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted">Landscape recommended (1920×1080 or similar). Leave empty to keep the current photo.</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slide headline</label>
                                <input type="text" class="form-control" value="{{ $data->heading }}" name="heading" placeholder="Large headline for this slide">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slide subheadline</label>
                                <input type="text" class="form-control" value="{{ $data->subheading }}" name="subheading" placeholder="Supporting line for this photo">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save slide
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
