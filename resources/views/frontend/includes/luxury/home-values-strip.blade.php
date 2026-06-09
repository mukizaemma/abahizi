@php
    $values = [
        ['icon' => 'fa-bag-shopping', 'title' => 'Custom bag manufacturing', 'desc' => 'Handbags, leather goods, and accessories tailored to your specifications.'],
        ['icon' => 'fa-hands-holding-circle', 'title' => 'Artisan-led production', 'desc' => 'Skilled women artisans bring precision stitching, beading, and finishing to every order.'],
        ['icon' => 'fa-people-group', 'title' => 'Community empowerment', 'desc' => 'Stable jobs, training, and support programs that strengthen families across Rwanda.'],
        ['icon' => 'fa-globe-africa', 'title' => 'Export-ready quality', 'desc' => 'Lean processes and rigorous QC so your products meet international standards.'],
    ];
@endphp

<section class="home-values-strip" aria-labelledby="home-values-strip-heading">
    <div class="container">
        <p id="home-values-strip-heading" class="home-values-strip__eyebrow">{{ __('site.home.values_eyebrow') }}</p>
        <div class="home-values-strip__grid">
            @foreach($values as $i => $item)
                <article class="home-values-strip__item wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <span class="home-values-strip__icon" aria-hidden="true"><i class="fas {{ $item['icon'] }}"></i></span>
                    <h3 class="home-values-strip__title">{{ $item['title'] }}</h3>
                    <p class="home-values-strip__desc mb-0">{{ $item['desc'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
