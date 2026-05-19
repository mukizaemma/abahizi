@php
    $firstProgram = $ourPrograms->first();
    $whatWeDoIntro = trim(strip_tags(html_entity_decode($about->what_we_do ?? '')));
    if ($whatWeDoIntro === '') {
        $whatWeDoIntro = 'We combine ethical manufacturing with community programs that create lasting opportunity for women and families across Rwanda.';
    } else {
        $whatWeDoIntro = \Illuminate\Support\Str::limit($whatWeDoIntro, 360, '…');
    }
    $firstImageUrl = $firstProgram && !empty($firstProgram->image)
        ? asset('storage/' . $firstProgram->image)
        : asset('assets/img/breadcrumb/breadcrumb-bg-1.jpg');
@endphp

<section class="home-programs-split" aria-labelledby="home-programs-split-title">
    <div class="container-fluid px-0">
        <div class="row g-0 align-items-stretch home-programs-split__row">
            <div class="col-lg-5 col-xl-5 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                <div class="home-programs-split__intro h-100">
                    <div class="home-programs-split__intro-body">
                        <h2 id="home-programs-split-title" class="home-programs-split__title">What we do</h2>
                        <p class="home-programs-split__lead">{{ $whatWeDoIntro }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                @if($firstProgram)
                    <a
                        href="{{ route('programShow', ['slug' => $firstProgram->slug]) }}"
                        class="home-programs-split__feature h-100 d-block"
                        style="background-image: url('{{ $firstImageUrl }}');"
                    >
                        <div class="home-programs-split__feature-footer">
                            <h3 class="home-programs-split__feature-title">{{ $firstProgram->title }}</h3>
                            <span class="home-programs-split__feature-btn-wrap">
                                <span class="tp-btn home-programs-split__feature-btn">View details <span>→</span></span>
                            </span>
                        </div>
                    </a>
                @else
                    <div class="home-programs-split__feature home-programs-split__feature--empty h-100">
                        <p class="mb-0">Add a program in admin to display it here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="home-section-divider" aria-hidden="true"></div>

@include('frontend.includes.programs-dual-cta')
