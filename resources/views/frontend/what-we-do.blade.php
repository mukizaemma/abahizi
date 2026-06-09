@extends('layouts.frontbase')

@section('title', 'What We Do')

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'what_we_do',
        'title' => 'What We Do',
    ])

    @php
        use App\Support\HowItWorks;

        $intro = HowItWorks::parseIntro($about->what_we_do ?? '');
        $steps = HowItWorks::parseSteps($about->how_it_works ?? '');

        if ($steps === []) {
            $steps = HowItWorks::parseSteps($about->what_we_do ?? '');
        }

        if ($steps === []) {
            $steps = HowItWorks::fallbackSteps();
        }
    @endphp

    <section class="what-we-do-intro lux-section" aria-labelledby="what-we-do-intro-title">
        <div class="container">
            <div class="what-we-do-intro__head text-center wow tpfadeUp" data-wow-duration=".85s">
                <p class="what-we-do-intro__eyebrow">{{ __('site.nav.what_we_do') }}</p>
                <h2 id="what-we-do-intro-title" class="what-we-do-intro__title">{{ $intro['title'] }}</h2>
                <p class="what-we-do-intro__lead mx-auto">{{ $intro['lead'] }}</p>
            </div>

            @if(trim(strip_tags($intro['body'])) !== '')
                <article class="what-we-do-intro__body postbox__text wow tpfadeUp" data-wow-duration=".85s" data-wow-delay=".08s">
                    {!! $intro['body'] !!}
                </article>
            @endif
        </div>
    </section>

    <section class="what-we-do-process lux-section grey-bg" aria-labelledby="what-we-do-process-title">
        <div class="container">
            <header class="what-we-do-process__head text-center wow tpfadeUp" data-wow-duration=".85s">
                <p class="what-we-do-process__eyebrow">Our approach</p>
                <h2 id="what-we-do-process-title" class="what-we-do-process__title">How it works</h2>
                <p class="what-we-do-process__subtitle mx-auto">A clear, collaborative path from concept to export-ready production.</p>
            </header>

            <ol class="what-we-do-process__grid">
                @foreach($steps as $i => $step)
                    <li class="what-we-do-process__card wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.07, 2) }}s">
                        <span class="what-we-do-process__index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="what-we-do-process__card-title">{{ $step['title'] }}</h3>
                        @if($step['desc'] !== '')
                            <p class="what-we-do-process__card-desc mb-0">{{ $step['desc'] }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

@endsection
