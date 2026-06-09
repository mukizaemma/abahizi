@php
    $tabs = [
        'capacity' => [
            'label' => 'Capacity',
            'items' => [
                '14,000+ units per season (scalable)',
                'Dedicated CMT lines for handbags & accessories',
                'Seasonal planning aligned with global brand calendars',
            ],
        ],
        'machinery' => [
            'label' => 'Technical capabilities',
            'items' => [
                'Industrial cutting, skiving, and stitching equipment',
                'Specialized beading, embroidery, and leather finishing',
                'Custom hardware application and quality control stations',
            ],
        ],
        'benefits' => [
            'label' => 'Worker benefits',
            'items' => [
                'Full health insurance for employees and families',
                'Paid maternity leave, sick days, and vacation',
                'Vocational training, financial literacy, and employee ownership',
            ],
        ],
    ];
@endphp

<section class="lux-section lux-specs" aria-labelledby="lux-specs-heading">
    <div class="container">
        <div class="lux-section-head text-center">
            <p class="lux-section-head__eyebrow">{{ __('site.factory.specs_eyebrow') }}</p>
            <h2 id="lux-specs-heading" class="lux-section-head__title">{{ __('site.manufacturing.specs_title') }}</h2>
        </div>

        <div class="lux-specs__panel" x-data="{ tab: 'capacity' }">
            <div class="lux-specs__tabs" role="tablist">
                @foreach($tabs as $key => $tab)
                    <button type="button"
                            class="lux-specs__tab"
                            :class="{ 'is-active': tab === '{{ $key }}' }"
                            @click="tab = '{{ $key }}'"
                            role="tab"
                            :aria-selected="tab === '{{ $key }}'">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
            @foreach($tabs as $key => $tab)
                <div class="lux-specs__content"
                     x-show="tab === '{{ $key }}'"
                     x-transition.opacity.duration.200ms
                     role="tabpanel">
                    <ul class="lux-specs__list">
                        @foreach($tab['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
