@extends('layouts.adminbase')

@section('title', 'Homepage product cards')

@section('sidebar')
    @parent
@endsection

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header mb-3">
                    <h1>Products</h1>
                    <p class="text-muted mb-0">The three bag photos on the homepage, plus the Our Craft title beside them.</p>
                </div>
                @include('admin.includes.products-tabs', ['tab' => 'homepage'])
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @include('admin.includes.landing-copy-form', ['group' => 'craft', 'title' => 'Our Craft titles (homepage)'])
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('catalogProducts.homepageCards') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                @foreach($homepageSlots as $slot)
                                    <div class="col-md-4">
                                        <div class="admin-image-card h-100">
                                            <label class="form-label fw-semibold">Card {{ $slot['slot'] }}</label>
                                            <input type="text" class="form-control mb-2" name="{{ $slot['title_field'] }}" value="{{ old($slot['title_field'], $slot['title']) }}" placeholder="{{ $slot['placeholder'] }}" maxlength="80">
                                            <input type="file" class="form-control" name="{{ $slot['image_field'] }}" accept="image/*">
                                            @if($slot['src'])
                                                <img src="{{ $slot['src'] }}" class="admin-preview-img mt-2" alt="Homepage card {{ $slot['slot'] }}">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input" name="clear_{{ $slot['image_field'] }}" value="1" id="clear_card_{{ $slot['slot'] }}">
                                                    <label class="form-check-label small" for="clear_card_{{ $slot['slot'] }}">Remove this photo</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save homepage cards</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
