@php
    $current = app()->getLocale();
@endphp
<div class="lux-lang" role="navigation" aria-label="{{ __('site.lang.en') }} / {{ __('site.lang.rw') }}">
    <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
       class="lux-lang__btn {{ $current === 'en' ? 'is-active' : '' }}"
       hreflang="en"
       @if($current === 'en') aria-current="true" @endif>EN</a>
    <span class="lux-lang__sep" aria-hidden="true">|</span>
    <a href="{{ route('locale.switch', ['locale' => 'rw']) }}"
       class="lux-lang__btn {{ $current === 'rw' ? 'is-active' : '' }}"
       hreflang="rw"
       @if($current === 'rw') aria-current="true" @endif>RW</a>
</div>
