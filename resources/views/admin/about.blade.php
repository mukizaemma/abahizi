@extends('layouts.adminbase')

@section('title', 'About Us')

@section('sidebar')

    @parent

@endsection

@section('content')
@php
    use App\Support\SectionBackgroundService;
@endphp

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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="section-backgrounds-tab" data-bs-toggle="tab" data-bs-target="#section-backgrounds-pane" type="button" role="tab">Section backgrounds</button>
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
                                    @php
                                        $valuesHtml = old('values', $data->values);
                                        if (trim(strip_tags((string) $valuesHtml)) === '') {
                                            $valuesHtml = \App\Support\CoreValues::listToHtml($data->core_values_list ?? '');
                                        }
                                    @endphp
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Core values</label>
                                            <p class="text-muted small mb-2">Add each value as a list item. They appear as cards on the About Us page.</p>
                                            <textarea rows="8" class="form-control" name="values" data-editor="rich" placeholder="Add each core value as a list item">{!! $valuesHtml !!}</textarea>
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
                                        <div class="col-12">
                                            <p class="text-muted small mb-0">Parallax and full-width section backgrounds are managed under the <strong>Section backgrounds</strong> tab. Page-specific breadcrumb heroes are under <a href="{{ route('settings') }}#page-headers">Settings → Page headers</a>.</p>
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
                                            <label class="form-label">How it works</label>
                                            <p class="text-muted small mb-2">Use one step per line or bullet. Format as <strong>Step title:</strong> short description — these appear as cards on the What We Do page.</p>
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
                                        <div class="col-lg-6 col-xl-3">
                                            <label class="form-label">Handbags Exported</label>
                                            <input type="text" class="form-control" name="handbags_exported" value="{{ $background->handbags_exported }}" placeholder="310,000+">
                                        </div>
                                        <div class="col-lg-6 col-xl-3">
                                            <label class="form-label">Full-Time Factory Employees</label>
                                            <input type="text" class="form-control" name="artisans_count" value="{{ $background->artisans_count }}" placeholder="260+">
                                        </div>
                                        <div class="col-lg-6 col-xl-3">
                                            <label class="form-label">Families Impacted</label>
                                            <input type="text" class="form-control" name="families_impacted" value="{{ $background->families_impacted }}">
                                        </div>
                                        <div class="col-lg-6 col-xl-3">
                                            <label class="form-label">Jobs Created</label>
                                            <input type="text" class="form-control" name="jobs_created" value="{{ $background->jobs_created }}">
                                        </div>
                                        <div class="col-lg-6 col-xl-3">
                                            <label class="form-label">Hours of Vocational Training</label>
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

                            <div class="tab-pane fade" id="section-backgrounds-pane" role="tabpanel" aria-labelledby="section-backgrounds-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <p class="text-muted mb-4">Upload a dedicated image for each full-width or parallax section. If a section has no image, the site uses the listed fallbacks automatically.</p>
                                    <div class="row g-4">
                                        @foreach(SectionBackgroundService::definitions() as $field => $definition)
                                            @php
                                                $storedFile = SectionBackgroundService::storedFilename($background, $field);
                                                $previewUrl = $storedFile
                                                    ? SectionBackgroundService::urlFromFilename($storedFile)
                                                    : SectionBackgroundService::resolve($field, $background);
                                            @endphp
                                            <div class="col-md-6 col-xl-4">
                                                <label class="form-label fw-semibold">{{ $definition['label'] }}</label>
                                                <p class="text-muted small mb-2">{{ $definition['help'] }}</p>
                                                <input type="file" class="form-control" name="{{ $field }}" accept="image/*">
                                                @if($previewUrl)
                                                    <img src="{{ $previewUrl }}" class="admin-preview-img mt-2 rounded border p-1 bg-white" alt="{{ $definition['label'] }} preview">
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save section backgrounds</button>
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
