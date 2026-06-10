@extends('layouts.adminbase')

@section('title', 'Edit Team Member')

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
                <div class="admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h1>Edit team member</h1>
                        <p class="text-muted mb-0">{{ $data->names }}</p>
                    </div>
                    <a href="{{ route('staff') }}" class="btn btn-outline-primary">Back to team</a>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('updateStaff', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="edit_names">Full name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_names" name="names" value="{{ old('names', $data->names) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_position">Position <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_position" name="position" value="{{ old('position', $data->position) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="edit_phone">Phone</label>
                                    <input type="text" class="form-control" id="edit_phone" name="phone" value="{{ old('phone', $data->phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="edit_email">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" value="{{ old('email', $data->email) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_facebook">Facebook URL</label>
                                    <input type="url" class="form-control" id="edit_facebook" name="facebook" value="{{ old('facebook', $data->facebook) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_instagram">Instagram URL</label>
                                    <input type="url" class="form-control" id="edit_instagram" name="instagram" value="{{ old('instagram', $data->instagram) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_linkedin">LinkedIn URL</label>
                                    <input type="url" class="form-control" id="edit_linkedin" name="linkedin" value="{{ old('linkedin', $data->linkedin) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_display">Show on website</label>
                                    <select class="form-select" id="edit_display" name="display">
                                        <option value="Yes" @selected(old('display', $data->display ?? 'Yes') === 'Yes')>Yes — visible on About / Team</option>
                                        <option value="No" @selected(old('display', $data->display ?? 'Yes') === 'No')>No — hidden</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_sort_order">Display order</label>
                                    <input type="number" min="1" class="form-control" id="edit_sort_order" name="sort_order" value="{{ old('sort_order', $data->sort_order) }}">
                                    <small class="text-muted">Lower numbers appear first on About and Team pages.</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="edit_created_at">Date added</label>
                                    <input type="datetime-local" class="form-control" id="edit_created_at" name="created_at" value="{{ old('created_at', $data->created_at?->format('Y-m-d\TH:i')) }}">
                                    <small class="text-muted">Used when sorting members with the same display order.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Current photo</label>
                                    @if(!empty($data->image))
                                        <img src="{{ asset('storage/images/staff/' . $data->image) }}" alt="{{ $data->names }}" class="rounded border" width="120" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">No photo uploaded</span>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="edit_image">Replace profile photo</label>
                                    <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                                    <small class="text-muted">Leave empty to keep the current image. Recommended: 270×312 px portrait.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="edit_bio">Biography</label>
                                    <textarea id="edit_bio" rows="8" class="form-control" name="bio" data-editor="rich">{!! old('bio', $data->bio) !!}</textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Save changes
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
@endsection
