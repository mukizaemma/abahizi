@php
    $tab = $tab ?? 'products';
@endphp
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'products' ? 'active' : '' }}" href="{{ route('catalogProducts.index') }}">Final products</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'gallery' ? 'active' : '' }}" href="{{ route('catalogProducts.pageGallery') }}">Products page gallery</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'story' ? 'active' : '' }}" href="{{ route('productStory.index') }}">Product story</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'homepage' ? 'active' : '' }}" href="{{ route('catalogProducts.homepage') }}">Homepage cards</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'categories' ? 'active' : '' }}" href="{{ route('productCategories.index') }}">Categories</a>
    </li>
</ul>
