@props([
    'href',
    'icon' => 'fa-circle',
    'active' => false,
    'badge' => null,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'nav-link d-flex align-items-center' . ($active ? ' active' : ''),
    ]) }}
>
    <div class="sb-nav-link-icon"><i class="fa {{ $icon }}"></i></div>
    <span>{{ $slot }}</span>
    @if($badge !== null && $badge !== '' && (int) $badge > 0)
        <span class="admin-nav-badge ms-auto">{{ $badge }}</span>
    @endif
</a>
