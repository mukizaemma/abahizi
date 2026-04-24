@extends('layouts.adminbase')

@section('title', 'About Us')

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
                    <h1>About</h1>
                    <p class="text-muted mb-0">Manage mission, values, project background, and impact metrics.</p>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" id="aboutTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="mission-vision-tab" data-bs-toggle="tab" data-bs-target="#mission-vision-pane" type="button" role="tab">Mission &amp; Vision</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="core-values-tab" data-bs-toggle="tab" data-bs-target="#core-values-pane" type="button" role="tab">Core values</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="project-background-tab" data-bs-toggle="tab" data-bs-target="#project-background-pane" type="button" role="tab">Project background</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="story-flow-tab" data-bs-toggle="tab" data-bs-target="#story-flow-pane" type="button" role="tab">Problem, solution & manufacturing story</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="impact-tab" data-bs-toggle="tab" data-bs-target="#impact-pane" type="button" role="tab">Impact</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="aboutTabsContent">
                            <div class="tab-pane fade show active" id="mission-vision-pane" role="tabpanel" aria-labelledby="mission-vision-tab">
                                <form action="{{ route('saveAbout', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label">Mission</label>
                                            <textarea rows="6" class="form-control" name="mission" data-editor="rich">{!! $data->mission !!}</textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Vision</label>
                                            <textarea rows="6" class="form-control" name="vision" data-editor="rich">{!! $data->vision !!}</textarea>
                                        </div>
                                        <input type="hidden" name="values" value="{{ $data->values }}">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save mission &amp; vision</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="core-values-pane" role="tabpanel" aria-labelledby="core-values-tab">
                                <form action="{{ route('saveAbout', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Core values — grid list (recommended)</label>
                                            <p class="text-muted small mb-2">Enter <strong>one value per line</strong>. These appear as cards in columns on the About Us page. Leave empty to try auto-detect from the rich text below (bullet list or multiple lines).</p>
                                            <textarea rows="10" class="form-control font-monospace" name="core_values_list" placeholder="One value per line">{{ old('core_values_list', $data->core_values_list ?? '') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Core values — rich text (optional)</label>
                                            <p class="text-muted small mb-2">Used as fallback if the grid list is empty, or for extra formatting where auto-detect does not apply.</p>
                                            <textarea rows="8" class="form-control" name="values" data-editor="rich">{!! $data->values !!}</textarea>
                                        </div>
                                        <input type="hidden" name="mission" value="{{ $data->mission }}">
                                        <input type="hidden" name="vision" value="{{ $data->vision }}">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save core values</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="project-background-pane" role="tabpanel" aria-labelledby="project-background-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Project background details</label>
                                            <textarea rows="8" class="form-control" name="description" data-editor="rich">{!! $background->description !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Our Approach content</label>
                                            <textarea rows="8" class="form-control" name="approach_content" data-editor="rich">{!! $background->approach_content !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Our Model content</label>
                                            <textarea rows="8" class="form-control" name="model_content" data-editor="rich">{!! $background->model_content !!}</textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Our Model image (diagram/photo)</label>
                                            <input type="file" class="form-control" name="model_image">
                                            @if(!empty($background->model_image))
                                                <img src="{{ asset('storage/images/' . $background->model_image) }}" width="220" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">About cover image</label>
                                            <input type="file" class="form-control" name="image">
                                            @if(!empty($background->image))
                                                <img src="{{ asset('storage/images/' . $background->image) }}" width="120" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Home background image</label>
                                            <input type="file" class="form-control" name="image1">
                                            @if(!empty($background->image1))
                                                <img src="{{ asset('storage/images/' . $background->image1) }}" width="120" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Pages header image</label>
                                            <input type="file" class="form-control" name="image2">
                                            @if(!empty($background->image2))
                                                <img src="{{ asset('storage/images/' . $background->image2) }}" width="120" class="mt-2 rounded border p-1 bg-white">
                                            @endif
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Core values section background (About page)</label>
                                            <p class="text-muted small mb-2">Full-width image behind “Our Core Values.” If not set, the pages header image is used.</p>
                                            <input type="file" class="form-control" name="core_values_background" accept="image/*">
                                            @if(!empty($background->core_values_background))
                                                <img src="{{ asset('storage/images/' . $background->core_values_background) }}" width="220" class="mt-2 rounded border p-1 bg-white" alt="Core values background preview">
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <input type="hidden" name="donations" value="{{ $background->donations }}">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save project background</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="story-flow-pane" role="tabpanel" aria-labelledby="story-flow-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Problem statement</label>
                                            <textarea rows="6" class="form-control" name="problem_statement" data-editor="rich">{!! $background->problem_statement !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Solution statement</label>
                                            <textarea rows="6" class="form-control" name="solution_statement" data-editor="rich">{!! $background->solution_statement !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">What we do</label>
                                            <textarea rows="6" class="form-control" name="what_we_do" data-editor="rich">{!! $background->what_we_do !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">How it works (use one step per line or bullet)</label>
                                            <textarea rows="6" class="form-control" name="how_it_works" data-editor="rich">{!! $background->how_it_works !!}</textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Our expertise</label>
                                            <p class="text-muted small mb-2">Format as: intro line, then items separated by new lines, bullets, or commas. End with a closing line if needed.</p>
                                            <textarea rows="6" class="form-control" name="expertise_content" data-editor="rich">{!! $background->expertise_content !!}</textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label">Our impact through manufacturing</label>
                                            <p class="text-muted small mb-2">Use new lines or commas between list items. The website will render them as bullets automatically.</p>
                                            <textarea rows="6" class="form-control" name="manufacturing_impact_content" data-editor="rich">{!! $background->manufacturing_impact_content !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Products intro</label>
                                            <textarea rows="5" class="form-control" name="products_intro" data-editor="rich">{!! $background->products_intro !!}</textarea>
                                        </div>
                                        <input type="hidden" name="description" value="{{ $background->description }}">
                                        <input type="hidden" name="donations" value="{{ $background->donations }}">
                                        <input type="hidden" name="approach_content" value="{{ $background->approach_content }}">
                                        <input type="hidden" name="model_content" value="{{ $background->model_content }}">
                                        <input type="hidden" name="families_impacted" value="{{ $background->families_impacted }}">
                                        <input type="hidden" name="jobs_created" value="{{ $background->jobs_created }}">
                                        <input type="hidden" name="training_hours" value="{{ $background->training_hours }}">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save story flow content</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="impact-pane" role="tabpanel" aria-labelledby="impact-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">Families Impacted</label>
                                            <input type="text" class="form-control" name="families_impacted" value="{{ $background->families_impacted }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Jobs Created</label>
                                            <input type="text" class="form-control" name="jobs_created" value="{{ $background->jobs_created }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Hours of Continuous Vocational Training</label>
                                            <input type="text" class="form-control" name="training_hours" value="{{ $background->training_hours }}">
                                        </div>
                                        <input type="hidden" name="description" value="{{ $background->description }}">
                                        <input type="hidden" name="donations" value="{{ $background->donations }}">
                                        <input type="hidden" name="approach_content" value="{{ $background->approach_content }}">
                                        <input type="hidden" name="model_content" value="{{ $background->model_content }}">
                                        <div class="col-12">
                                            <p class="text-muted mb-2">For item-based impact metrics (title + value), use the <a href="{{ route('impacts.index') }}">Impact Items</a> page.</p>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save impact stats</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

@endsection
