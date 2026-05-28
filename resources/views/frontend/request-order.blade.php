@extends('layouts.frontbase')

@section('title', 'Enquiry')

@section('content')

@include('frontend.includes.page-header', [
    'title' => 'Enquiry',
    'caption' => 'Tell us how you would like to partner with Abahizi Rwanda — manufacturing, community initiatives, or collaboration.',
])

<section class="py-5 grey-bg site-form-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(($ordersClosed ?? false) === true)
                    <div class="alert alert-info mb-4">
                        Enquiries are currently closed. Please contact us and we will respond with the right next step.
                    </div>
                    <div class="text-center">
                        <a href="{{ route('contacts') }}" class="tp-btn">Contact us</a>
                    </div>
                @else
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

                        <form action="{{ route('storeOrderRequest') }}" method="POST" class="row g-3 site-partner-form site-channel-form" data-form-type="order">
                            @csrf
                            <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                            <div class="site-hp-field" aria-hidden="true">
                                <label for="website_order">Website</label>
                                <input type="text" name="website" id="website_order" tabindex="-1" autocomplete="off">
                            </div>
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
                                <label class="form-label">Your enquiry <span class="text-danger">*</span></label>
                                <textarea name="product_description" class="form-control" rows="6" required placeholder="Manufacturing: product types, materials, quantities, delivery timeline.\nCommunity initiatives: program area, goals, timeline.\nPartnership: what you want to explore and how we can help.">{{ old('product_description') }}</textarea>
                            </div>
                            @if($product)
                                <input type="hidden" name="product_reference" value="{{ $product->title }}">
                            @endif
                            @include('frontend.includes.form-channel-submit', ['formType' => 'order'])
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
