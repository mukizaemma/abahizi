@extends('layouts.frontbase')

@section('title', 'Request an order')

@section('content')

@include('frontend.includes.page-header', [
    'title' => 'Request an order',
    'caption' => 'We produce to order for partners, retailers, and organisations. Share what you need and we will follow up with timelines and pricing.',
])

<section class="py-5 grey-bg site-form-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm site-form-card">
                    <div class="card-body p-4 p-lg-5">
                        @if($product)
                            <div class="d-flex gap-3 align-items-start mb-4 pb-3 border-bottom">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="rounded border" width="88" height="88" style="object-fit: cover;">
                                @endif
                                <div>
                                    <p class="text-uppercase small text-muted mb-1">Referencing</p>
                                    <p class="mb-0 fw-semibold">{{ $product->title }}</p>
                                    <p class="small text-muted mb-0">RWF {{ number_format((float) $product->price, 0) }} — indicative guide price</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('storeOrderRequest') }}" method="POST" class="row g-3">
                            @csrf
                            @if($product)
                                <input type="hidden" name="product_slug" value="{{ $product->slug }}">
                            @endif
                            <div class="col-md-6">
                                <label class="form-label">Full name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" required value="{{ old('full_name') }}" autocomplete="name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" autocomplete="tel">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autocomplete="email">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Describe what you need <span class="text-danger">*</span></label>
                                <textarea name="product_description" class="form-control" rows="6" required placeholder="Product types, quantities, colours, delivery timeline, organisation name (if any)…">{{ old('product_description') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg fw-semibold text-dark site-form-submit">Submit request</button>
                                <a href="{{ route('ourProducts') }}" class="btn btn-outline-secondary btn-lg ms-0 ms-md-2 mt-2 mt-md-0">Browse products</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
