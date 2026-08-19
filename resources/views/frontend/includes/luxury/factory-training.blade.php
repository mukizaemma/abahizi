@php
    use App\Support\FactoryPageContent;

    $trainingLead = FactoryPageContent::plainLead($about->factory_training_facilities ?? '');
    if ($trainingLead === '') {
        $trainingLead = __('site.factory.training_lead');
    }

    $trainingImage = ! empty($about->factory_training_facilities_image)
        ? asset('storage/images/' . ltrim((string) $about->factory_training_facilities_image, '/'))
        : '';

    $trainingItems = FactoryPageContent::lines($about->factory_training_facilities_subitems ?? '');
    if ($trainingItems === []) {
        $trainingItems = __('site.factory.training_items');
        if (! is_array($trainingItems)) {
            $trainingItems = [];
        }
    }
@endphp

<section class="lux-section factory-training" aria-labelledby="factory-training-title">
    <div class="container">
        <div class="row align-items-center g-4 g-xl-5">
            <div class="{{ $trainingImage !== '' ? 'col-lg-6 order-lg-2' : 'col-lg-10 col-xl-9 mx-auto' }}">
                <div class="lux-section-head lux-section-head--solo {{ $trainingImage === '' ? 'text-center' : '' }}">
                    <h2 id="factory-training-title" class="lux-section-head__title mb-3">{{ __('site.factory.training_title') }}</h2>
                    <p class="lux-lead mb-4">{{ $trainingLead }}</p>
                </div>
                @if($trainingItems !== [])
                    <ul class="factory-story__list">
                        @foreach($trainingItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @if($trainingImage !== '')
                <div class="col-lg-6 order-lg-1">
                    <figure class="factory-story__visual mb-0">
                        <img src="{{ $trainingImage }}" alt="{{ __('site.factory.training_title') }}" loading="lazy" decoding="async">
                    </figure>
                </div>
            @endif
        </div>
    </div>
</section>
