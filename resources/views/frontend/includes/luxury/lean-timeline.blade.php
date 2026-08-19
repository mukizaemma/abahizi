@php
    use App\Support\FactoryPageContent;

    $about = $about ?? \App\Models\Background::firstOrEmpty();

    $fallbackSteps = collect(__('site.factory.process_steps'))->map(function ($step) {
        return [
            'title' => $step['title'] ?? '',
            'text' => $step['desc'] ?? ($step['text'] ?? ''),
        ];
    })->all();

    $steps = FactoryPageContent::processSteps($about->factory_process_steps ?? null, $fallbackSteps);
@endphp

@if($steps !== [])
<section class="lux-section lux-timeline grey-bg" id="lean" aria-labelledby="lux-timeline-heading">
    <div class="container">
        <div class="lux-section-head lux-section-head--solo text-center mb-0">
            <h2 id="lux-timeline-heading" class="lux-section-head__title">{{ __('site.manufacturing.lean_title') }}</h2>
        </div>
        <ol class="lux-timeline__track{{ count($steps) < 5 ? ' lux-timeline__track--compact' : '' }}">
            @foreach($steps as $i => $step)
                <li class="lux-timeline__step wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <span class="lux-timeline__index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="lux-timeline__body">
                        <h3 class="lux-timeline__title">{{ $step['title'] }}</h3>
                        <p class="lux-timeline__desc mb-0">{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif
