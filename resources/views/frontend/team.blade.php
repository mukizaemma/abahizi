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
                        <div class="col-md-6 col-lg-4">
                            @include('frontend.includes.team-member-card', ['member' => $member, 'compact' => false, 'memberIndex' => $i])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('frontend.includes.bottom')

@endsection
