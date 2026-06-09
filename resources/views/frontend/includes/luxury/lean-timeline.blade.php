@php
    $steps = [
        ['title' => 'Material Sourcing & Inspection', 'desc' => 'Ethical sourcing with rigorous incoming quality checks.'],
        ['title' => 'Precision Cutting', 'desc' => 'Mechanized cutting for consistency, waste reduction, and scale.'],
        ['title' => 'Hand-Beading & Embellishments', 'desc' => 'Factory employee detailing that elevates every premium piece.'],
        ['title' => 'Assembly & Stitching', 'desc' => 'CMT assembly combining lean flow with master craftsmanship.'],
        ['title' => 'Rigorous Quality Assurance', 'desc' => 'Multi-point inspection before export to global partners.'],
    ];
@endphp

<section class="lux-section lux-timeline grey-bg" id="lean" aria-labelledby="lux-timeline-heading">
    <div class="container">
        <div class="lux-section-head text-center mb-0">
            <p class="lux-section-head__eyebrow">{{ __('site.factory.how_eyebrow') }}</p>
            <h2 id="lux-timeline-heading" class="lux-section-head__title">{{ __('site.manufacturing.lean_title') }}</h2>
        </div>
        <ol class="lux-timeline__track">
            @foreach($steps as $i => $step)
                <li class="lux-timeline__step wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <span class="lux-timeline__index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="lux-timeline__body">
                        <h3 class="lux-timeline__title">{{ $step['title'] }}</h3>
                        <p class="lux-timeline__desc mb-0">{{ $step['desc'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
