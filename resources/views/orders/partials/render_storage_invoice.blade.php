<div class="content-body">
    <!-- Horizontal Wizard -->
    <section class="mb-5 min-vh-50">
        <div class="bs-stepper">
            <div class="bs-stepper-content">
                <div id="shipping-items">
                    <div class="content-header">
                        {{-- <h5 class="mb-0">Shipping & Items</h5> --}}
                    </div>
                    <div class="content-wrapper container-xxl p-0">
                        <div class="content-header row">
                        </div>
                        <div class="content-body">
                            <section class="invoice-preview-wrapper">
                                <div class="row invoice-preview">
                                    <!-- Invoice -->
                                    <div class="col-xl-12 col-md-12 col-12">
                                        <div class="card invoice-preview-card">
                                            <div class="card-body invoice-padding pb-0">
                                                <!-- Header starts -->
                                                <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 mb-0">
                                                    <div class="col-md-9">
                                                        <div class="logo-wrapper">
                                                            <img src="{{ asset('images/logo/logo2.png') }}" alt="SalarLink Logo" style="max-width:120px;">
                                                            {{-- <h3 class="text-primary invoice-logo">GMV Envios</h3> --}}
                                                        </div>
                                                        {{-- <p class="card-text mb-25">{{ $parcel->customerSenderDetails->city }} {{ $parcel->customerSenderDetails->state_code ? ','.$parcel->customerSenderDetails->state_code : '' }} {{ $parcel->customerSenderDetails->zipcode ? ','.$parcel->customerSenderDetails->zipcode : '' }}</p>
                                                        <p class="card-text mb-25">{{ $parcel->customerSenderDetails->address }}</p> --}}
                                                    </div>
                                                    <div class="col-md-3 mt-md-0 mt-2">
                                                        {{-- <h4 class="invoice-title">
                                                            Invoice
                                                            <span class="invoice-number">#{{ $parcel->wr_number }}</span>
                                                        </h4> --}}
                                                        <div class="invoice-date-wrapper">
                                                            <p class="invoice-date">Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m/d/y') }}</p>
                                                        </div>
                                                        <div class="invoice-date-wrapper">
                                                            <p class="invoice-date">No#: {{ $invoice->uuid }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Header ends -->
                                            </div>

                                            <hr class="invoice-spacing mt-0" />

                                            <!-- Address and Contact starts -->
                                            <div class="card-body invoice-padding pt-0">
                                                <div class="row invoice-spacing">
                                                    <div class="col-xl-9 p-0 mt-xl-0 mt-2">
                                                        <h6 class="card-text mb-25"><strong> SALAR LINK LTD</strong></h6>
                                                        <p class="card-text mb-25">WS11 1D Street London, UK</p>
                                                        <p class="card-text mb-25"><i data-feather="phone"></i> +44 123456789</p>
                                                        <p class="card-text mb-0"><i data-feather="mail"></i> salarlinkltd@gmail.com</p>
                                                    </div>
                                                    <div class="col-xl-3 p-0">
                                                        <h6 class="card-text mb-25"><strong>{{ optional($invoice->user)->name }} {{ optional($invoice->user)->last_name }}  - {{ optional($invoice->user)->po_box_number }}</strong></h6>
                                                        <p class="card-text mb-25">{{optional(optional(optional($invoice)->user)->setting)->address}}, {{optional(optional(optional($invoice)->user)->setting)->city}}, {{optional(optional(optional($invoice)->user)->setting)->state}} {{optional(optional(optional($invoice)->user)->setting)->zipcode}}, {{optional(optional(optional($invoice)->user)->country)->iso2}}</p>
                                                        <p class="card-text mb-25"><i data-feather="phone"></i> {{ optional($invoice->user)->phone }}</p>
                                                        <p class="card-text mb-0"><i data-feather="mail"></i> {{ optional($invoice->user)->email }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Address and Contact ends -->
                                            
                                            <div class="card-body invoice-padding pt-0">
                                                    {{-- order info --}}
                                                <div class="col-12 mb-3">
                                                    <table class="table table-bordered">
                                                        <thead class="text-dark">
                                                            <tr>
                                                                <th class="py-1">Invoice Month</th>
                                                                <th class="py-1">Payment Status</th>
                                                                <th class="py-1">Payment Type</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><a type="button" class="text-primary">{{ $invoice->charge_month }} </a></td>
                                                                <td>
                                                                    @if ($invoice->isPaid() )
                                                                        <span class="badge rounded-pill badge-light-success" me-1>
                                                                            Paid
                                                                        </span>
                                                                    @elseif($invoice->cancelled)
                                                                        <span class="badge rounded-pill badge-light-warning" me-1>
                                                                            Cancelled
                                                                        </span>
                                                                    @else
                                                                        <span class="badge rounded-pill badge-light-warning" me-1>
                                                                            Payment Pending
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ( $invoice->payment_type == "direct_transfer")
                                                                        <span class="badge rounded-pill badge-light-dark" me-1>
                                                                            Direct Transfer
                                                                        </span>
                                                                    @elseif( $invoice->payment_type == "stripe")
                                                                        <span class="badge rounded-pill badge-light-dark" me-1>
                                                                            Stripe
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Service -->

                                                {{-- <div class="my-2">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="py-1">Service</th>
                                                                <th class="py-1">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="border-top-light">
                                                                <td>My Packet Standard</td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div> --}}

                                                <!-- Invoice Order Items starts -->
                                                <div class="col-12 mb-2">
                                                    <table class="table table-bordered">
                                                        <thead class="text-dark">
                                                            <tr>
                                                                <th colspan="7" style="font-size: 16px !important;">
                                                                    Invoice Products/Items Detail
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th class="py-1">#</th>
                                                                <th class="py-1">Product</th>
                                                                <th class="py-1">Ship Limit %</th>
                                                                <th class="py-1">Shipped %</th>
                                                                <th class="py-1">Shipped Units</th>
                                                                <th class="py-1">Rem. Units</th>
                                                                <th class="py-1">Charges (₤)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($invoice->products as $product)
                                                                <tr style="font-size: 14px !important;">
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td><img src="{{ $product->product->latestConfirmedInventory->product_image ? asset('storage/images/products/' . $product->product->latestConfirmedInventory->product_image) : ($product->image ? asset('storage/images/products/' . $product->image) : asset('storage/images/products/default.png')) }}" 
                                                                        alt="Product Image" 
                                                                        class="product-img">
                                                                        {{ $product->product->name }} - {{ $product->product->unique_id }}</td>
                                                                    <td>{{ $product->percentage_limit }}</td>
                                                                    <td>{{ $product->shipped_percentage }}</td>
                                                                    <td>{{ $product->shipped_units }}</td>
                                                                    <td>{{ $product->remaining_units }}</td>
                                                                    <td>{{ $product->storage_charges }}</td>
                                                                </tr>
                                                            @endforeach

                                                            <tr class="border-top-light">
                                                                <td colspan="6" class="text-center text-dark" style="font-size: 16px !important;"><b>Invoice Total Amount</b></td>
                                                                <td class="text-dark" style="font-size: 16px !important;">
                                                                    <strong>₤ {{ optional($invoice)->total_amount}}</strong>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{-- <div class="card-body invoice-padding pb-0">
                                                <div class="row invoice-sales-total-wrapper">
                                                    <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                                        <p class="card-text mb-0">
                                                            <span class="fw-bold">Salesperson:</span> <span class="ms-75">Alfie Solomons</span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                                        <div class="invoice-total-wrapper">
                                                            <div class="invoice-total-item">
                                                                <p class="invoice-total-title">Total Amount:</p>
                                                                <p class="invoice-total-amount">{{ number_format(optional($order)->total_amount, 2) }} ₤</p>
                                                            </div>
                                                            <hr class="my-50" />
                                                            <div class="invoice-total-item">
                                                                <p class="invoice-total-title">Total:</p>
                                                                <p class="invoice-total-amount">{{ number_format($order->total_amount, 2) }} ₤</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <!-- Invoice Order Items ends -->

                                            <hr class="invoice-spacing" />

                                            <!-- Invoice Note starts -->
                                            {{-- <div class="card-body invoice-padding pt-0">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="fw-bold">Note:</span>
                                                        <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
                                                            projects. Thank You!</span>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <!-- Invoice Note ends -->
                                        </div>
                                    </div>
                                    <!-- /Invoice -->
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Horizontal Wizard -->
</div>
