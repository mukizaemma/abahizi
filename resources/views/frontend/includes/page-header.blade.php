@php
    use App\Support\PageHeaderService;

    $resolved = PageHeaderService::resolve(
        $pageKey ?? null,
        $title ?? null,
        $caption ?? null,
        $image ?? null,
        $about ?? null,
        $titleLocked ?? false,
    );

    $headerTitle = $resolved['title'];
    $headerCaption = $resolved['caption'];
    $headerImageUrl = $resolved['image'];
@endphp

<section
    class="tp-breadcrumb__area tp-breadcrumb-height tp-breadcrumb__area--fullscreen tp-breadcrumb__area--no-shapes p-relative fix"
    @if($headerImageUrl) data-background="{{ $headerImageUrl }}" @endif
    aria-label="{{ $headerTitle }}"
>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tp-breadcrumb__content z-index-5 text-center">
                    <h1 class="tp-breadcrumb__title text-center mb-0">{{ $headerTitle }}</h1>
                    @if(!empty($headerCaption))
                        <p class="tp-breadcrumb__caption text-center mb-0 mt-3">{{ $headerCaption }}</p>
                    @endif
                    @if(!empty($extraHtml))
                        {!! $extraHtml !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
