@extends('layouts.frontbase')

@section('title', 'About Us')

@section('content')

@include('frontend.includes.page-header', [
    'pageKey' => 'about',
    'title' => 'About us',
])

<!-- Background section -->
<section class="about-page-intro pt-60 pb-60 grey-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 col-xxl-9">
                <div class="tp-about-4__section-title mb-4">
                    <h4 class="tp-section-title">Background</h4>
                </div>
                <div class="postbox__text about-page-body" style="font-size: 19px; line-height: 1.75; color: #333;">
                    {!! $about->description ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Full-screen mission & vision (same style as home) -->
@include('frontend.includes.programs-dual-cta', ['about' => $about, 'mission' => $mission])

<!-- Core values on white background -->
@php
    $coreValueItems = \App\Support\CoreValues::parseItems($mission->core_values_list ?? null, $mission->values ?? '');
@endphp
<section class="about-page-core-values pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12 text-center">
                <h4 class="tp-section-title mb-0">Our Core Values</h4>
            </div>
        </div>
        @if(count($coreValueItems) > 0)
            <div class="row g-4 justify-content-center">
                @foreach($coreValueItems as $idx => $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="about-core-white-card h-100">
                            <span class="about-core-white-card__index">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <p class="mb-0">{{ $item }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 text-center">
                    <div class="postbox__text">{!! $mission->values ?? '' !!}</div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Team section -->
<section class="about-page-team lux-section grey-bg" aria-labelledby="about-team-heading">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
            <h2 id="about-team-heading" class="lux-section-head__title">Our Team</h2>
            <p class="team-page__lead text-muted mx-auto mb-0">Leadership and staff committed to quality manufacturing and community impact.</p>
        </div>
        <div class="row g-4 g-lg-5 justify-content-center">
            @forelse($staff as $i => $member)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    @include('frontend.includes.team-member-card', ['member' => $member, 'compact' => true, 'memberIndex' => $i])
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">Team members will appear here once published.</p>
                </div>
            @endforelse
        </div>
        @if(($staff ?? collect())->isNotEmpty())
            <div class="text-center mt-4 mt-lg-5">
                <a href="{{ route('team') }}" class="tp-btn tp-btn--outline-dark">Meet the full team <span aria-hidden="true">→</span></a>
            </div>
        @endif
    </div>
</section>

@include('frontend.includes.backImage')

<style>
    .about-core-white-card {
        height: 100%;
        padding: 1.35rem 1.25rem;
        border-radius: 14px;
        border: 1px solid rgba(44, 44, 44, 0.12);
        background: #fff;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.06);
    }

    .about-core-white-card__index {
        display: inline-block;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: var(--brand-primary, #fad200);
    }

    .about-core-white-card p {
        margin: 0;
        font-size: 1.02rem;
        line-height: 1.65;
        color: #2f2f2f;
        font-weight: 600;
    }
</style>

@endsection
