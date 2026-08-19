@extends('layouts.frontbase')

@section('title', $activity->title)

@section('content')

@include('frontend.includes.initiative-cover')

<section class="activity-main py-4 py-lg-5 bg-white">
    <div class="container">
        <div class="activity-main__copy mx-auto">
            <div class="postbox__text activity-description">{!! $activity->description !!}</div>
            @if(count($activity->normalizedInvolvementWays()) > 0)
                <p class="mt-4 mb-0">
                    <button type="button" class="tp-btn tp-btn--lux" data-bs-toggle="modal" data-bs-target="#getInvolvedModal">
                        {{ __('site.initiative.cta_jump') }} <span aria-hidden="true">→</span>
                    </button>
                </p>
            @endif
        </div>
    </div>
</section>

@include('frontend.includes.initiative-highlights')
@include('frontend.includes.initiative-involve')

@if($relatedActivities->count() > 0)
<section class="related-projects-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
            <h2 class="lux-section-head__title h3 mb-2">{{ __('site.initiative.related_title') }}</h2>
            @if($activity->program)
                <p class="text-muted mb-0">{{ __('site.initiative.related_lead') }} <strong>{{ $activity->program->title }}</strong></p>
            @endif
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($relatedActivities as $rel)
                <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                    <article class="related-project-card h-100 d-flex flex-column bg-white rounded-3 overflow-hidden border shadow-sm">
                        <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="related-project-card__thumb d-block">
                            @if(!empty($rel->image))
                                <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->title }}" class="w-100 object-fit-cover" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">No image</div>
                            @endif
                        </a>
                        <div class="p-3 p-4 flex-grow-1 d-flex flex-column">
                            <h3 class="h6 mb-2">
                                <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="text-dark text-decoration-none">{{ $rel->title }}</a>
                            </h3>
                            @php
                                $ex = Str::limit(strip_tags(html_entity_decode($rel->description ?? '')), 110, '…');
                            @endphp
                            <p class="text-muted small flex-grow-1 mb-3">{{ $ex }}</p>
                            <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="tp-btn align-self-start mt-auto">{{ __('site.initiative.view_more') }}</a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('frontend.includes.backImage')

@if(session('involve_success') || session('involve_open_url'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var title = @json(__('site.initiative.swal_submitted'));
            var text = @json(session('involve_success') ?: __('site.initiative.swal_submitted_text'));
            var url = @json(session('involve_open_url'));
            var color = '#111111';

            function openChannel() {
                if (!url) {
                    return;
                }
                var opened = window.open(url, '_blank', 'noopener,noreferrer');
                if (!opened) {
                    window.location.href = url;
                }
            }

            if (window.Swal) {
                window.Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    confirmButtonText: 'OK',
                    confirmButtonColor: color,
                }).then(openChannel);
            } else {
                openChannel();
            }
        });
    </script>
@elseif($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var color = '#111111';
            function reopenForm() {
                var el = document.getElementById('getInvolvedModal');
                if (el && window.bootstrap && window.bootstrap.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(el).show();
                }
            }
            if (window.Swal) {
                window.Swal.fire({
                    icon: 'error',
                    title: @json(__('site.initiative.swal_failed')),
                    text: @json(__('site.initiative.swal_failed_text')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: color,
                }).then(reopenForm);
            } else {
                reopenForm();
            }
        });
    </script>
@endif

@endsection
