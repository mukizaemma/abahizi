@props([
    'href',
    'icon' => 'fa-circle',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'nav-link d-flex align-items-center' . ($active ? ' active' : ''),
    ]) }}
>
    <div class="sb-nav-link-icon"><i class="fa {{ $icon }}"></i></div>
    <span>{{ $slot }}</span>
</a>
