@extends('layouts.adminbase')

@section('title', 'Factory')

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
                <div class="admin-page-header mb-3">
                    <h1>Factory</h1>
                    <p class="text-muted mb-0">Manage all content shown on factory-related frontend pages.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('factory.admin.overview') }}" class="btn {{ $section === 'overview' ? 'btn-primary' : 'btn-outline-primary' }}">Overview</a>
                    <a href="{{ route('factory.admin.services') }}" class="btn {{ $section === 'services' ? 'btn-primary' : 'btn-outline-primary' }}">Factory Services</a>
                    <a href="{{ route('factory.admin.impact') }}" class="btn {{ $section === 'impact' ? 'btn-primary' : 'btn-outline-primary' }}">Community Impact</a>
                    <a href="{{ route('factory.admin.training') }}" class="btn {{ $section === 'training' ? 'btn-primary' : 'btn-outline-primary' }}">Training Facilities</a>
                    <a href="{{ route('factory.admin.gallery') }}" class="btn {{ $section === 'gallery' ? 'btn-primary' : 'btn-outline-primary' }}">Factory Gallery</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if($section === 'overview')
                            <form action="{{ route('factory.admin.save', 'overview') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Factory page description</label>
                                    <textarea rows="8" class="form-control" name="factory_description" data-editor="rich">{!! $background->factory_description !!}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save overview</button>
                            </form>
                        @endif

                        @if($section === 'services')
                            <form action="{{ route('factory.admin.save', 'services') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Factory services</label>
                                    <textarea rows="7" class="form-control" name="factory_services" data-editor="rich">{!! $background->factory_services !!}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Factory services image</label>
                                    <input type="file" class="form-control" name="factory_services_image" accept="image/*">
                                    @if(!empty($background->factory_services_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_services_image) }}" width="180" class="mt-2 rounded border p-1 bg-white">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub-items</label>
                                    <textarea rows="6" class="form-control" name="factory_services_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_services_subitems', $background->factory_services_subitems ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save services</button>
                            </form>
                        @endif

                        @if($section === 'impact')
                            <form action="{{ route('factory.admin.save', 'impact') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Community impact</label>
                                    <textarea rows="7" class="form-control" name="factory_community_impact" data-editor="rich">{!! $background->factory_community_impact !!}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Community impact image</label>
                                    <input type="file" class="form-control" name="factory_community_impact_image" accept="image/*">
                                    @if(!empty($background->factory_community_impact_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_community_impact_image) }}" width="180" class="mt-2 rounded border p-1 bg-white">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub-items</label>
                                    <textarea rows="6" class="form-control" name="factory_community_impact_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_community_impact_subitems', $background->factory_community_impact_subitems ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save community impact</button>
                            </form>
                        @endif

                        @if($section === 'training')
                            <form action="{{ route('factory.admin.save', 'training') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Training facilities offered</label>
                                    <textarea rows="7" class="form-control" name="factory_training_facilities" data-editor="rich">{!! $background->factory_training_facilities !!}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Training facilities image</label>
                                    <input type="file" class="form-control" name="factory_training_facilities_image" accept="image/*">
                                    @if(!empty($background->factory_training_facilities_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_training_facilities_image) }}" width="180" class="mt-2 rounded border p-1 bg-white">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub-items</label>
                                    <textarea rows="6" class="form-control" name="factory_training_facilities_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_training_facilities_subitems', $background->factory_training_facilities_subitems ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save training facilities</button>
                            </form>
                        @endif

                        @if($section === 'gallery')
                            <p class="text-muted mb-4">Upload photos for the factory page gallery. The six most recent images appear on the public page in two rows of three.</p>

                            <div class="card mb-4 border">
                                <div class="card-header bg-light fw-semibold">Add gallery image</div>
                                <div class="card-body">
                                    <form action="{{ route('factory.admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                                        @csrf
                                        <div class="col-md-5">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="form-control" name="image" accept="image/*" required>
                                            <small class="text-muted">Landscape recommended (1200×800 or similar).</small>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Caption (optional)</label>
                                            <input type="text" class="form-control" name="caption" maxlength="255" placeholder="e.g. Cutting table, Masoro factory">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-plus me-1"></i> Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Caption</th>
                                            <th>Uploaded</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($factoryGalleryImages ?? [] as $galleryImage)
                                            <tr>
                                                <td>
                                                    <img src="{{ \App\Models\FactoryGalleryImage::publicUrl($galleryImage->image) }}" alt="" width="120" class="rounded border">
                                                </td>
                                                <td>{{ $galleryImage->caption ?: '—' }}</td>
                                                <td class="text-muted small">{{ $galleryImage->created_at?->format('M j, Y') }}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFactoryGallery{{ $galleryImage->id }}">Edit</button>
                                                    <a href="{{ route('factory.admin.gallery.destroy', $galleryImage->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this gallery image?')">Delete</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-muted text-center py-4">No factory gallery images yet. Add your first image above.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @foreach($factoryGalleryImages ?? [] as $galleryImage)
                                <div class="modal fade" id="editFactoryGallery{{ $galleryImage->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('factory.admin.gallery.update', $galleryImage->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit gallery image</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Replace image (optional)</label>
                                                        <input type="file" class="form-control" name="image" accept="image/*">
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Caption</label>
                                                        <input type="text" class="form-control" name="caption" value="{{ $galleryImage->caption }}" maxlength="255">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
