@php
    use App\Support\SectionBackgroundService;

    $aboutRow = $about ?? \App\Models\Background::firstOrEmpty();
    $processBgUrl = SectionBackgroundService::resolve('home_process_background', $aboutRow);

    $steps = [
        ['title' => 'Brief & design', 'desc' => 'Share your specs, materials, and timeline—we align on scope and sampling.'],
        ['title' => 'Sampling', 'desc' => 'Prototype and refine until the product meets your quality standards.'],
        ['title' => 'Production', 'desc' => 'Lean CMT flow with factory employee detailing at every stage of assembly.'],
        ['title' => 'Quality & delivery', 'desc' => 'Multi-point inspection before your order ships to market.'],
    ];
@endphp

<section
    class="home-process home-process--parallax"
    aria-labelledby="home-process-title"
    data-lux-parallax
    style="--home-process-parallax-image: url('{{ $processBgUrl }}');"
>
    <div class="home-process__parallax" data-lux-parallax-layer aria-hidden="true"></div>
    <div class="home-process__content">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5">
                <h2 id="home-process-title" class="home-process__title-main">{{ __('site.home.process_title') }}</h2>
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
        </div>
    </div>
</section>
