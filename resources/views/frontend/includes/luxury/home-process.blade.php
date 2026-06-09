@php
    $steps = [
        ['title' => 'Brief & design', 'desc' => 'Share your specs, materials, and timeline—we align on scope and sampling.'],
        ['title' => 'Sampling', 'desc' => 'Prototype and refine until the product meets your quality standards.'],
        ['title' => 'Production', 'desc' => 'Lean CMT flow with artisan detailing at every stage of assembly.'],
        ['title' => 'Quality & delivery', 'desc' => 'Multi-point inspection before your order ships to market.'],
    ];
@endphp

<section class="home-process pt-80 pb-80" aria-labelledby="home-process-title">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5">
            <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.process_eyebrow') }}</p>
            <h2 id="home-process-title" class="lux-section-head__title">{{ __('site.home.process_title') }}</h2>
        </div>

        <ol class="home-process__track">
            @foreach($steps as $i => $step)
                <li class="home-process__step wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <span class="home-process__index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="home-process__title">{{ $step['title'] }}</h3>
                    <p class="home-process__desc mb-0">{{ $step['desc'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="text-center mt-4 mt-lg-5">
            <a href="{{ route('contacts') }}" class="tp-btn">{{ __('site.hero.cta_primary') }}</a>
        </div>
    </div>
</section>
