@php
    $orderProduct = $product ?? null;
    $ordersOpen = ($setting->accept_order_requests ?? true) && ($formChannels['channels_ready'] ?? false);
    $formId = $orderFormId ?? 'product-order-form';
@endphp

<section class="product-order-panel" id="{{ $formId }}" aria-labelledby="{{ $formId }}-title">
    <div class="product-order-panel__inner">
        <div class="product-order-panel__head">
            <h2 id="{{ $formId }}-title" class="product-order-panel__title">
                @if($orderProduct)
                    Order this product
                @else
                    Request a product order
                @endif
            </h2>
            <p class="product-order-panel__lead text-muted mb-0">
                Choose WhatsApp or email, send your order in the app that opens, then confirm here so we can record it.
                Use an active phone number and email you can receive replies on.
            </p>
        </div>

        @if(! $ordersOpen)
            <div class="alert alert-warning mb-0">
                @if(! ($setting->accept_order_requests ?? true))
                    Product orders are temporarily unavailable. Please use the <a href="{{ route('contacts') }}">contact page</a> instead.
                @else
                    Orders are unavailable until an administrator configures a valid site email and WhatsApp number in settings.
                @endif
            </div>
        @else
            @if(session('order_success'))
                <div class="alert alert-success">{{ session('order_success') }}</div>
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

            @if($orderProduct)
                <div class="product-order-panel__product card border-0 bg-light mb-4">
                    <div class="card-body d-flex flex-wrap align-items-center gap-3">
                        @if($orderProduct->image)
                            <img src="{{ asset('storage/' . $orderProduct->image) }}" alt="" width="72" height="72" class="rounded object-fit-cover">
                        @endif
                        <div>
                            <div class="fw-semibold">{{ $orderProduct->title }}</div>
                            <div class="text-muted small">RWF {{ number_format((float) $orderProduct->price, 0) }} · indicative guide price</div>
                        </div>
                    </div>
                </div>
            @endif

            <form
                action="{{ route('storeOrderRequest') }}"
                method="POST"
                class="row g-3 site-channel-form site-partner-form product-order-form"
                data-form-type="order"
                novalidate
            >
                @csrf
                <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                @if($orderProduct)
                    <input type="hidden" name="product_id" value="{{ $orderProduct->id }}">
                    <input type="hidden" name="product_slug" value="{{ $orderProduct->slug }}">
                    <input type="hidden" name="product_reference" value="{{ $orderProduct->title }}">
                @endif
                <div class="site-hp-field" aria-hidden="true">
                    <label for="website_order_{{ $formId }}">Website</label>
                    <input type="text" name="website" id="website_order_{{ $formId }}" tabindex="-1" autocomplete="off">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Full name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" required maxlength="255" value="{{ old('full_name') }}" autocomplete="name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control" required minlength="10" maxlength="64" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" pattern="[\d\s\+\-\(\)]{10,}">
                    <small class="text-muted">Active number with at least 10 digits (WhatsApp if selected).</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required maxlength="255" value="{{ old('email') }}" autocomplete="email">
                    <small class="text-muted">Use an inbox you check regularly.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Quantity <span class="text-muted small">(optional)</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" max="99999" value="{{ old('quantity') }}" placeholder="e.g. 500">
                </div>
                <div class="col-12">
                    <label class="form-label">Order details &amp; comments <span class="text-danger">*</span></label>
                    <textarea name="product_description" class="form-control" rows="5" required minlength="10" maxlength="20000" placeholder="Sizes, colours, branding, timeline, delivery notes, or questions…">{{ old('product_description') }}</textarea>
                </div>

                @include('frontend.includes.form-channel-submit', ['formType' => 'order', 'formSource' => 'product'])
            </form>
        @endif
    </div>
</section>
