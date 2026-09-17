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
                    <h1>About &amp; homepage</h1>
                    <p class="text-muted mb-0">Mission, values, Our Story, What We Do, and homepage story text. Each tab saves its own content. See the user guide if you are unsure which tab to use.</p>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" id="aboutTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="mission-vision-tab" data-bs-toggle="tab" data-bs-target="#mission-vision-pane" type="button" role="tab">Mission &amp; vision</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="core-values-tab" data-bs-toggle="tab" data-bs-target="#core-values-pane" type="button" role="tab">Core values</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="story-tab" data-bs-toggle="tab" data-bs-target="#story-pane" type="button" role="tab">Our story</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="what-we-do-tab" data-bs-toggle="tab" data-bs-target="#what-we-do-pane" type="button" role="tab">Homepage &amp; what we do</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="impact-tab" data-bs-toggle="tab" data-bs-target="#impact-pane" type="button" role="tab">Impact numbers</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="section-backgrounds-tab" data-bs-toggle="tab" data-bs-target="#section-backgrounds-pane" type="button" role="tab">Section photos</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="aboutTabsContent">
                            <div class="tab-pane fade show active" id="mission-vision-pane" role="tabpanel" aria-labelledby="mission-vision-tab">
                                @include('admin.includes.page-header-form', ['pageKey' => 'mission'])
                                <form action="{{ route('saveAbout', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <p class="text-muted mb-3">Shown on <strong>About → Mission &amp; Vision</strong> and on the Our Story page.</p>
                                    @include('admin.includes.landing-copy-fields', ['group' => 'mission'])
                                    <div class="row g-3 mt-1">
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
                                            <p class="text-muted small mb-2">Homepage <strong>Our Values</strong> heading and the three value cards. The list below also appears on Our Story and Mission &amp; Vision.</p>
                                            @include('admin.includes.landing-copy-fields', ['group' => 'values'])
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Core values list</label>
                                            <p class="text-muted small mb-2">Add each value as a list item. They appear as cards on Our Story and Mission &amp; Vision.</p>
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

                            <div class="tab-pane fade" id="story-pane" role="tabpanel" aria-labelledby="story-tab">
                                @include('admin.includes.page-header-form', ['pageKey' => 'about'])
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <p class="text-muted mb-3">This is the long story on <strong>Our Story</strong> (<code>/about-us</code>). The title and button below appear on the homepage.</p>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            @include('admin.includes.landing-copy-fields', ['group' => 'story'])
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Story</label>
                                            <textarea rows="10" class="form-control" name="description" data-editor="rich">{!! $background->description !!}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="admin-image-card">
                                                <label class="form-label">Story cover image <span class="text-muted fw-normal">(optional)</span></label>
                                                <input type="file" class="form-control" name="image" accept="image/*">
                                                @if(!empty($background->image))
                                                    <img src="{{ asset('storage/images/' . $background->image) }}" class="admin-preview-img" alt="About cover">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save story</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="what-we-do-pane" role="tabpanel" aria-labelledby="what-we-do-tab">
                                @include('admin.includes.page-header-form', ['pageKey' => 'what_we_do'])
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <p class="text-muted mb-3">Homepage story paragraph and What We Do page copy. Homepage <strong>Our Story</strong> titles are on the Our story tab.</p>
                                    @include('admin.includes.landing-copy-fields', ['group' => 'what_we_do'])
                                    <div class="row g-3 mt-1">
                                        <div class="col-12">
                                            <label class="form-label">Homepage story text</label>
                                            <p class="text-muted small mb-2">Short paragraph beside the homepage photo (“From a small group of women…”). Keep it to a few sentences.</p>
                                            <textarea rows="5" class="form-control" name="solution_statement" data-editor="rich">{!! $background->solution_statement !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">What we do</label>
                                            <p class="text-muted small mb-2">Intro on the <strong>What We Do</strong> page.</p>
                                            <textarea rows="6" class="form-control" name="what_we_do" data-editor="rich">{!! $background->what_we_do !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">How it works</label>
                                            <p class="text-muted small mb-2">One step per line or bullet. Format as <strong>Step title:</strong> short description — these appear as cards on What We Do.</p>
                                            <textarea rows="6" class="form-control" name="how_it_works" data-editor="rich">{!! $background->how_it_works !!}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save homepage &amp; what we do</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="impact-pane" role="tabpanel" aria-labelledby="impact-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="return_tab" value="impact">
                                    <p class="text-muted mb-3">These numbers appear on the dark strip under the homepage banner, and can also be used on Impact pages. Rename a label, change a figure, or add another row. Impact pillars (Health, Education, and so on) are a separate list under <a href="{{ route('impacts.index') }}">Impact pillars</a>.</p>
                                    @php
                                        $statRows = \App\Support\ImpactStats::items($background);
                                        if ($statRows === []) {
                                            $statRows = [['value' => '', 'label' => '', 'show_on_bar' => true]];
                                        }
                                    @endphp
                                    <div data-impact-stats>
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 9rem;">Number</th>
                                                        <th>Label</th>
                                                        <th style="width: 11rem;">Homepage bar</th>
                                                        <th style="width: 6rem;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody data-impact-stats-rows>
                                                    @foreach($statRows as $stat)
                                                        <tr data-impact-stats-row>
                                                            <td>
                                                                <input type="text" class="form-control" name="stat_value[]" value="{{ $stat['value'] }}" placeholder="2,000+" maxlength="80">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="stat_label[]" value="{{ $stat['label'] }}" placeholder="Families empowered" maxlength="120">
                                                            </td>
                                                            <td>
                                                                <select class="form-select" name="stat_on_bar[]">
                                                                    <option value="1" {{ !empty($stat['show_on_bar']) ? 'selected' : '' }}>Show on banner</option>
                                                                    <option value="0" {{ empty($stat['show_on_bar']) ? 'selected' : '' }}>Keep off banner</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" data-impact-stats-remove>Remove</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" data-impact-stats-add>
                                            <i class="fa fa-plus me-1"></i> Add another statistic
                                        </button>
                                        <template data-impact-stats-template>
                                            <tr data-impact-stats-row>
                                                <td>
                                                    <input type="text" class="form-control" name="stat_value[]" value="" placeholder="2,000+" maxlength="80">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="stat_label[]" value="" placeholder="Families empowered" maxlength="120">
                                                </td>
                                                <td>
                                                    <select class="form-select" name="stat_on_bar[]">
                                                        <option value="1" selected>Show on banner</option>
                                                        <option value="0">Keep off banner</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" data-impact-stats-remove>Remove</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save impact stats</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="section-backgrounds-pane" role="tabpanel" aria-labelledby="section-backgrounds-tab">
                                <form action="{{ route('saveBackg', $background->id ?? '') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="return_tab" value="section-backgrounds">
                                    <p class="text-muted mb-4">Photos named after the section visitors see. Homepage product cards are on <a href="{{ route('catalogProducts.index') }}">Products</a>. Factory photos are on <a href="{{ route('factory.admin.overview') }}">Our factory</a>.</p>
                                    @foreach(SectionBackgroundService::groupedDefinitions() as $group => $fields)
                                        <h2 class="h6 text-uppercase text-muted mt-2 mb-3">{{ $group }}</h2>
                                        <div class="row g-4 mb-4">
                                            @foreach($fields as $field => $definition)
                                                @php
                                                    $storedFile = SectionBackgroundService::storedFilename($background, $field);
                                                    $previewUrl = $storedFile
                                                        ? SectionBackgroundService::urlFromFilename($storedFile)
                                                        : SectionBackgroundService::resolve($field, $background);
                                                @endphp
                                                <div class="col-md-6">
                                                    <div class="admin-image-card">
                                                        <label class="form-label fw-semibold">{{ $definition['label'] }}</label>
                                                        <p class="text-muted small mb-2">{{ $definition['help'] }}</p>
                                                        <input type="file" class="form-control" name="{{ $field }}" accept="image/*">
                                                        @if($previewUrl)
                                                            <img src="{{ $previewUrl }}" class="admin-preview-img" alt="{{ $definition['label'] }} preview">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save section photos</button>
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

@section('scripts')
<script>
    (function () {
        var hash = (window.location.hash || '').replace('#', '');
        var map = {
            'section-backgrounds': 'section-backgrounds-tab',
            'homepage-photos': 'section-backgrounds-tab',
            'what-we-do': 'what-we-do-tab',
            'impact': 'impact-tab'
        };
        var tabId = map[hash];
        if (!tabId) {
            return;
        }
        var tab = document.getElementById(tabId);
        if (tab && window.bootstrap && bootstrap.Tab) {
            bootstrap.Tab.getOrCreateInstance(tab).show();
        }
    })();

    (function () {
        var root = document.querySelector('[data-impact-stats]');
        if (!root) {
            return;
        }
        var rows = root.querySelector('[data-impact-stats-rows]');
        var template = root.querySelector('[data-impact-stats-template]');
        root.addEventListener('click', function (event) {
            var add = event.target.closest('[data-impact-stats-add]');
            if (add && template && rows) {
                rows.insertAdjacentHTML('beforeend', template.innerHTML);
                return;
            }
            var remove = event.target.closest('[data-impact-stats-remove]');
            if (remove) {
                var row = remove.closest('[data-impact-stats-row]');
                if (row && rows.querySelectorAll('[data-impact-stats-row]').length > 1) {
                    row.remove();
                }
            }
        });
    })();
</script>
@endsection
