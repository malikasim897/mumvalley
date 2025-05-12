
{{-- <div class="card-header">
    <p class="h4 text-danger pb-2 border-bottom">WHR# {{ $parcel->wr_number }}</p>
</div> --}}
<div class="bs-stepper-header" role="tablist">
    <div class="step {{ (Route::currentRouteName() == 'product.order.units' || Route::currentRouteName() == 'product.order.details' || Route::currentRouteName() == 'orders.create') ? 'active' : '' }}">
        <button type="button" class="step-trigger">
            <span class="bs-stepper-box">1</span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Units and Product Selection</span>
            </span>
        </button>
    </div>
    <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div>
    {{-- <div class="step {{ Route::currentRouteName() == 'parcel.recipient.details' ? 'active' : '' }}" data-target="#recipient-details" role="tab" id="recipient-details-trigger">
        <button type="button" class="step-trigger">
            <span class="bs-stepper-box">2</span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Recipient Details</span>
            </span>
        </button>
    </div>
    <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div>
    <div class="step {{ Route::currentRouteName() == 'parcel.shipping-items.details' ? 'active' : '' }}" data-target="#shipping-items" role="tab" id="shipping-items-trigger">
        <button type="button" class="step-trigger">
            <span class="bs-stepper-box">3</span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Shipping & Items</span>
            </span>
        </button>
    </div>
    <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div> --}}
    <div class="step {{ Route::currentRouteName() == 'product.invoice.details' ? 'active' : '' }}">
        <button type="button" class="step-trigger">
            <span class="bs-stepper-box">2</span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Order Details & Invoice</span>
            </span>
        </button>
    </div>
    {{-- <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div>
    <div class="step {{ Route::currentRouteName() == 'payment-invoices.orders' ? 'active' : '' }}">
        <button type="button" class="step-trigger">
            <span class="bs-stepper-box">3</span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Invoice and Payments</span>
            </span>
        </button>
    </div> --}}
</div>