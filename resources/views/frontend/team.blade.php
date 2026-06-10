@extends('layouts.frontbase')

@section('title', __('site.nav.team'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'team',
        'title' => __('site.nav.team'),
    ])

    <section class="lux-section team-page" aria-labelledby="team-page-heading">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                <h2 id="team-page-heading" class="lux-section-head__title">{{ __('site.nav.team') }}</h2>
                <p class="team-page__lead text-muted mx-auto mb-0">Meet the people behind Abahizi CBC—leadership and staff driving manufacturing excellence and community impact.</p>
            </div>

            @if(($teamMembers ?? collect())->isEmpty())
                <div class="team-page__empty text-center">
                    <p class="text-muted mb-0">Team members will appear here once they are published in the admin panel.</p>
                </div>
            @else
                <div class="row g-4 g-lg-5">
                    @foreach($teamMembers as $i => $member)
                        @php
                            $bioPlain = trim(strip_tags(html_entity_decode($member->bio ?? '')));
                            $bioExcerpt = $bioPlain !== '' ? \Illuminate\Support\Str::limit($bioPlain, 160, '…') : '';
                        @endphp
                        <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format(($i % 3) * 0.08, 2) }}s">
                            <article class="team-page-card h-100">
                                <div class="team-page-card__media">
                                    @if(!empty($member->image))
                                        <img
                                            src="{{ asset('storage/images/staff/' . $member->image) }}"
                                            alt="{{ $member->names }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    @else
                                        <div class="team-page-card__placeholder" aria-hidden="true">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="team-page-card__body">
                                    <h3 class="team-page-card__name">{{ $member->names }}</h3>
                                    <p class="team-page-card__role">{{ $member->position }}</p>
                                    @if($bioExcerpt !== '')
                                        <p class="team-page-card__bio mb-0">{{ $bioExcerpt }}</p>
                                    @endif
                                    @if($bioPlain !== '' && strlen($bioPlain) > 160)
                                        <details class="team-page-card__details mt-2">
                                            <summary>Read full bio</summary>
                                            <div class="team-page-card__bio-full postbox__text mt-2 mb-0">{!! $member->bio !!}</div>
                                        </details>
                                    @elseif($bioPlain !== '' && $bioExcerpt === '')
                                        <div class="team-page-card__bio-full postbox__text mt-2 mb-0">{!! $member->bio !!}</div>
                                    @endif

                                    @if(!empty($member->phone) || !empty($member->email) || !empty($member->facebook) || !empty($member->instagram) || !empty($member->linkedin))
                                        <ul class="team-page-card__contact list-unstyled mb-0 mt-3">
                                            @if(!empty($member->phone))
                                                <li><a href="tel:{{ preg_replace('/\s+/', '', $member->phone) }}"><i class="fas fa-phone" aria-hidden="true"></i> {{ $member->phone }}</a></li>
                                            @endif
                                            @if(!empty($member->email))
                                                <li><a href="mailto:{{ $member->email }}"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $member->email }}</a></li>
                                            @endif
                                            @if(!empty($member->linkedin))
                                                <li><a href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in" aria-hidden="true"></i> LinkedIn</a></li>
                                            @endif
                                            @if(!empty($member->facebook))
                                                <li><a href="{{ $member->facebook }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook</a></li>
                                            @endif
                                            @if(!empty($member->instagram))
                                                <li><a href="{{ $member->instagram }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram" aria-hidden="true"></i> Instagram</a></li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('frontend.includes.bottom')

@endsection
