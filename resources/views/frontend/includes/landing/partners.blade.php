@php
    use App\Support\SectionBackgroundService;

    $partnerList = collect($partners ?? [])->filter(function ($partner) {
        $name = trim((string) ($partner->names ?? $partner->name ?? ''));
        $logo = trim((string) ($partner->image ?? ''));

        return $name !== '' || $logo !== '';
    })->take(8)->values();

    $facilityImage = SectionBackgroundService::partnersFeatureImage($about ?? null);
    $partnersCta = route('contacts');
@endphp

<section class="lh-partners" id="lh-partners" aria-labelledby="lh-partners-title">
    <div class="container">
        <div class="lh-partners__layout{{ $facilityImage ? '' : ' lh-partners__layout--solo' }} lh-reveal">
            <div class="lh-partners__copy">
                <h2 id="lh-partners-title" class="lh-partners__title">{{ __('site.landing.partners_title') }}</h2>
                <p class="lh-partners__lead lh-body">{{ __('site.landing.partners_lead') }}</p>

                @if($partnerList->isNotEmpty())
                    <div class="lh-partners__logos">
                        @foreach($partnerList as $partner)
                            @php
                                $logoPath = trim((string) ($partner->image ?? ''));
                                $logoUrl = $logoPath !== ''
                                    ? asset('storage/images/partners' . (str_starts_with($logoPath, '/') ? '' : '/') . ltrim($logoPath, '/'))
                                    : null;
                                $name = trim((string) ($partner->names ?? $partner->name ?? 'Partner'));
                                $website = trim((string) ($partner->website ?? ''));
                                if ($website !== '' && ! preg_match('#^https?://#i', $website)) {
                                    $website = 'https://' . $website;
                                }
                            @endphp
                            @if($website !== '')
                                <a class="lh-partners__logo" href="{{ $website }}" target="_blank" rel="noopener noreferrer">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $name }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="lh-partners__wordmark">{{ $name }}</span>
                                    @endif
                                </a>
                            @else
                                <div class="lh-partners__logo">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $name }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="lh-partners__wordmark">{{ $name }}</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <a href="{{ $partnersCta }}" class="lh-btn lh-btn--ghost-dark lh-partners__cta">{{ __('site.landing.partners_cta') }}</a>
            </div>

            @if($facilityImage)
                <figure class="lh-partners__media">
                    <img src="{{ $facilityImage }}" alt="{{ __('site.landing.partners_media_alt') }}" loading="lazy" decoding="async">
                    <figcaption class="lh-partners__caption">{{ __('site.landing.partners_caption') }}</figcaption>
                </figure>
            @endif
        </div>
    </div>
</section>
