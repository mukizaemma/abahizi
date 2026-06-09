@php
    $capabilities = [
        [
            'icon' => 'fa-gem',
            'title' => 'Premium craftsmanship',
            'desc' => 'Hand stitching, beading, leather work, and custom finishes on every piece.',
        ],
        [
            'icon' => 'fa-industry',
            'title' => 'Scalable production',
            'desc' => 'Lean manufacturing flow built for consistent quality at volume.',
        ],
        [
            'icon' => 'fa-pen-ruler',
            'title' => 'Custom development',
            'desc' => 'From sampling to bulk orders—bags tailored to your brand specifications.',
        ],
        [
            'icon' => 'fa-heart',
            'title' => 'Ethical impact',
            'desc' => 'Fair wages, skills training, and community programs woven into every order.',
        ],
    ];
@endphp

<section class="home-capabilities grey-bg pt-80 pb-80" aria-labelledby="home-capabilities-title">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5">
            <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.capabilities_eyebrow') }}</p>
            <h2 id="home-capabilities-title" class="lux-section-head__title">{{ __('site.home.capabilities_title') }}</h2>
        </div>

        <div class="row g-4">
            @foreach($capabilities as $i => $cap)
                <div class="col-sm-6 col-lg-3 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <article class="home-capabilities__card h-100">
                        <span class="home-capabilities__icon" aria-hidden="true"><i class="fas {{ $cap['icon'] }}"></i></span>
                        <h3 class="home-capabilities__title">{{ $cap['title'] }}</h3>
                        <p class="home-capabilities__desc mb-0">{{ $cap['desc'] }}</p>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
