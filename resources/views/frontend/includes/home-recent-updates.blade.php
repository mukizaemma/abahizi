@if(($recentUpdates ?? collect())->isNotEmpty())
    <section class="lux-section home-recent-updates grey-bg" aria-labelledby="home-recent-updates-title">
        <div class="container">
            <div class="row align-items-end justify-content-between g-3 mb-4 mb-lg-5">
                <div class="col-lg-8">
                    <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.updates_eyebrow') }}</p>
                    <h2 id="home-recent-updates-title" class="lux-section-head__title mb-0">{{ __('site.home.updates_title') }}</h2>
                </div>
                <div class="col-lg-auto">
                    <a href="{{ route('posts') }}" class="home-recent-updates__view-all">{{ __('site.home.updates_cta') }} <span aria-hidden="true">→</span></a>
                </div>
            </div>

            <div class="row g-4">
                @foreach($recentUpdates as $update)
                    <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                        <article class="home-recent-updates__card h-100">
                            <a href="{{ route('postSingle', $update->slug) }}" class="home-recent-updates__media d-block">
                                @if(!empty($update->image))
                                    <img src="{{ asset('storage/images/news/' . $update->image) }}" alt="{{ $update->title }}" loading="lazy">
                                @else
                                    <div class="home-recent-updates__placeholder">{{ __('site.home.updates_title') }}</div>
                                @endif
                            </a>
                            <div class="home-recent-updates__body">
                                <time class="home-recent-updates__date" datetime="{{ $update->created_at?->toDateString() }}">
                                    {{ $update->created_at?->format('d M, Y') }}
                                </time>
                                <h3 class="home-recent-updates__title">
                                    <a href="{{ route('postSingle', $update->slug) }}">{{ $update->title }}</a>
                                </h3>
                                <a href="{{ route('postSingle', $update->slug) }}" class="home-recent-updates__link">
                                    {{ __('site.home.updates_read') }} <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
