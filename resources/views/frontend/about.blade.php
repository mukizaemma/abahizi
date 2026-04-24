@extends('layouts.frontbase')

@section('title', 'About Us')

@section('content')

@include('frontend.includes.page-header', [
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
<section class="about-page-team pt-10 pb-90 grey-bg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tp-team-2__section-title pb-40 text-center">
                    <h4 class="tp-section-title">Our Team</h4>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @forelse($staff as $member)
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <article class="tp-team-2__item text-center h-100">
                        <div class="tp-team-2__thumb">
                            <img src="{{ asset('storage/images/staff') . $member->image }}" alt="{{ $member->names }}">
                        </div>
                        <div class="tp-team-2__content">
                            <div class="tp-team-2__author-info">
                                <h4 class="tp-team-2__author-name">{{ $member->names }}</h4>
                                <span>{{ $member->position }}</span>
                            </div>
                        </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">Team members will appear here once published.</p>
                </div>
            @endforelse
        </div>
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
