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
                    <p class="text-muted mb-0">Each tab matches a section on the public Our Factory page. Empty fields keep the current default copy.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('factory.admin.overview') }}" class="btn {{ $section === 'overview' ? 'btn-primary' : 'btn-outline-primary' }}">Overview</a>
                    <a href="{{ route('factory.admin.services') }}" class="btn {{ $section === 'services' ? 'btn-primary' : 'btn-outline-primary' }}">What we offer</a>
                    <a href="{{ route('factory.admin.impact') }}" class="btn {{ $section === 'impact' ? 'btn-primary' : 'btn-outline-primary' }}">Community impact</a>
                    <a href="{{ route('factory.admin.training') }}" class="btn {{ $section === 'training' ? 'btn-primary' : 'btn-outline-primary' }}">Training</a>
                    <a href="{{ route('factory.admin.gallery') }}" class="btn {{ $section === 'gallery' ? 'btn-primary' : 'btn-outline-primary' }}">Gallery</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if($section === 'overview')
                            <form action="{{ route('factory.admin.save', 'overview') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted mb-4">Shown at the top of Our Factory: the page photo, intro, and production steps.</p>
                                <div class="mb-4">
                                    <label class="form-label">Intro</label>
                                    <textarea rows="5" class="form-control" name="factory_description" placeholder="Who the factory is and what visitors should know first.">{{ old('factory_description', strip_tags(html_entity_decode((string) ($background->factory_description ?? '')))) }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Page photo</label>
                                    <input type="file" class="form-control" name="factory_services_image" accept="image/*">
                                    <small class="text-muted d-block mt-1">Used as the factory page header and the intro image.</small>
                                    @if(!empty($background->factory_services_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_services_image) }}" width="180" class="mt-2 rounded border p-1 bg-white" alt="">
                                    @endif
                                </div>
                                <h2 class="h5 mb-2">How we work</h2>
                                <p class="text-muted small mb-3">These become the numbered production steps on the public page. Leave a row blank to hide it.</p>
                                @foreach($processSteps ?? [] as $i => $step)
                                    @php
                                        $defaultProcess = __('site.factory.process_steps');
                                        $defaultTitle = is_array($defaultProcess) ? ($defaultProcess[$i]['title'] ?? '') : '';
                                        $defaultDesc = is_array($defaultProcess) ? ($defaultProcess[$i]['desc'] ?? '') : '';
                                    @endphp
                                    <div class="border rounded p-3 mb-3">
                                        <label class="form-label">Step {{ $i + 1 }} title</label>
                                        <input type="text" class="form-control mb-2" name="process_steps[{{ $i }}][title]" value="{{ old('process_steps.'.$i.'.title', $step['title'] ?? '') }}" maxlength="160" placeholder="{{ $defaultTitle }}">
                                        <label class="form-label">Step {{ $i + 1 }} description</label>
                                        <textarea rows="2" class="form-control" name="process_steps[{{ $i }}][text]" maxlength="400" placeholder="{{ $defaultDesc }}">{{ old('process_steps.'.$i.'.text', $step['text'] ?? '') }}</textarea>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save overview</button>
                            </form>
                        @endif

                        @if($section === 'services')
                            <form action="{{ route('factory.admin.save', 'services') }}" method="POST">
                                @csrf
                                <p class="text-muted mb-4">These three cards appear under “What we do.” Bullet points also fill the Factory capabilities band further down the page.</p>
                                <div class="mb-4">
                                    <label class="form-label">Section lead <span class="text-muted fw-normal">(optional)</span></label>
                                    <textarea rows="3" class="form-control" name="factory_services" placeholder="A short line above the three offer cards.">{{ old('factory_services', strip_tags(html_entity_decode((string) ($background->factory_services ?? '')))) }}</textarea>
                                </div>
                                <div class="row g-3 mb-4">
                                    @foreach($offerCards ?? [] as $i => $card)
                                        <div class="col-lg-4">
                                            <div class="border rounded p-3 h-100">
                                                <h2 class="h6 mb-3">Offer {{ $i + 1 }}</h2>
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control mb-2" name="offer_cards[{{ $i }}][title]" value="{{ old('offer_cards.'.$i.'.title', $card['title'] ?? '') }}" maxlength="160" placeholder="{{ $i === 0 ? 'CMT handbag production' : ($i === 1 ? 'Custom development' : 'Factory employee finishing') }}">
                                                <label class="form-label">Short description</label>
                                                <textarea rows="4" class="form-control mb-2" name="offer_cards[{{ $i }}][text]" maxlength="600" placeholder="What this offer means for a visitor or buyer.">{{ old('offer_cards.'.$i.'.text', $card['text'] ?? '') }}</textarea>
                                                <label class="form-label">Capability points</label>
                                                <textarea rows="4" class="form-control" name="offer_cards[{{ $i }}][items]" placeholder="One point per line">{{ old('offer_cards.'.$i.'.items', implode("\n", $card['items'] ?? [])) }}</textarea>
                                                <small class="text-muted">Shown in the dark capabilities band.</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save what we offer</button>
                            </form>
                        @endif

                        @if($section === 'impact')
                            <form action="{{ route('factory.admin.save', 'impact') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted mb-4">Appears as the community impact band on Our Factory, with the employment numbers from About → Impact.</p>
                                <div class="mb-3">
                                    <label class="form-label">Impact story</label>
                                    <textarea rows="5" class="form-control" name="factory_community_impact" placeholder="How manufacturing strengthens families and Masoro.">{{ old('factory_community_impact', strip_tags(html_entity_decode((string) ($background->factory_community_impact ?? '')))) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Impact photo</label>
                                    <input type="file" class="form-control" name="factory_community_impact_image" accept="image/*">
                                    @if(!empty($background->factory_community_impact_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_community_impact_image) }}" width="180" class="mt-2 rounded border p-1 bg-white" alt="">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Journey highlights</label>
                                    <textarea rows="5" class="form-control" name="factory_community_impact_subitems" placeholder="One highlight per line, e.g. Vocational training at Masoro">{{ old('factory_community_impact_subitems', implode("\n", \App\Support\FactoryPageContent::lines($background->factory_community_impact_subitems ?? ''))) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save community impact</button>
                            </form>
                        @endif

                        @if($section === 'training')
                            <form action="{{ route('factory.admin.save', 'training') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted mb-4">Shown as the training facilities section on Our Factory.</p>
                                <div class="mb-3">
                                    <label class="form-label">Training story</label>
                                    <textarea rows="5" class="form-control" name="factory_training_facilities" placeholder="What training looks like on the factory floor.">{{ old('factory_training_facilities', strip_tags(html_entity_decode((string) ($background->factory_training_facilities ?? '')))) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Training photo</label>
                                    <input type="file" class="form-control" name="factory_training_facilities_image" accept="image/*">
                                    @if(!empty($background->factory_training_facilities_image))
                                        <img src="{{ asset('storage/images/' . $background->factory_training_facilities_image) }}" width="180" class="mt-2 rounded border p-1 bg-white" alt="">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Facilities and programs</label>
                                    <textarea rows="5" class="form-control" name="factory_training_facilities_subitems" placeholder="One item per line">{{ old('factory_training_facilities_subitems', implode("\n", \App\Support\FactoryPageContent::lines($background->factory_training_facilities_subitems ?? ''))) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save training</button>
                            </form>
                        @endif

                        @if($section === 'gallery')
                            <p class="text-muted mb-4">Photos for the factory gallery mosaic. Landscape images (about 1200×800) work best.</p>

                            <div class="card mb-4 border">
                                <div class="card-header bg-light fw-semibold">Add gallery image</div>
                                <div class="card-body">
                                    <form action="{{ route('factory.admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                                        @csrf
                                        <div class="col-md-5">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="form-control" name="image" accept="image/*" required>
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
