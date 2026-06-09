@php
    $brandPartners = ($partners ?? collect())->take(6);
    $fallbackBrands = ['Kate Spade', 'COACH', 'B-Corp'];
@endphp

<section class="lux-trust" aria-labelledby="lux-trust-heading">
    <div class="container">
        <div class="lux-section-head lux-section-head--solo text-center mb-4">
            <h2 id="lux-trust-heading" class="lux-section-head__title">{{ __('site.trust.eyebrow') }}</h2>
        </div>
        <div class="lux-trust__logos">
            @forelse($brandPartners as $partner)
                @php
                    $logoPath = $partner->image ?? '';
                    $logoUrl = $logoPath !== ''
                        ? asset('storage/images/partners' . (str_starts_with($logoPath, '/') ? '' : '/') . ltrim($logoPath, '/'))
                        : null;
                @endphp
                <div class="lux-trust__item">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $partner->name ?? 'Partner' }}" class="lux-trust__logo" loading="lazy" decoding="async" width="140" height="48">
                    @else
                        <span class="lux-trust__wordmark">{{ $partner->name ?? 'Partner' }}</span>
                    @endif
                </div>
            @empty
                @foreach($fallbackBrands as $brand)
                    <div class="lux-trust__item">
                        <span class="lux-trust__wordmark">{{ $brand }}</span>
                    </div>
                @endforeach
            @endforelse
            <div class="lux-trust__item lux-trust__item--bcorp">
                <span class="lux-trust__bcorp" title="Certified B Corporation">B Corp<br><small>Certified</small></span>
            </div>
        </div>
    </div>
</section>
