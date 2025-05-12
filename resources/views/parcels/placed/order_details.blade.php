<style>
    .orderItems {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        padding: 29px;
        margin: 25px 0px;
        border-radius: 5px;
    }
</style>
<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('products.index') }}">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('orders.index') }}">Order</a>
                                    </li>
                                    <li class="breadcrumb-item active">Details
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Horizontal Wizard -->
                    <div class="bs-stepper">
                        @include('parcels.partials.order_header')
                        <div class="bs-stepper-content">
                                <div class="content-header">
                                    {{-- <h5 class="mb-0">Shipping & Items</h5> --}}
                                </div>
                                <div class="content-wrapper container-xxl p-0">
                                    <div class="content-header row">
                                    </div>
                                    <div class="content-body">
                                            <div class="row invoice-preview">
                                                <!-- Invoice -->
                                                <div class="col-xl-12 col-md-12 col-12">
                                                    <div class="card invoice-preview-card">
                                                        <div class="card-body invoice-padding pb-0">
                                                            <!-- Header starts -->
                                                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 mb-0">
                                                                <div class="col-md-10">
                                                                    <div class="logo-wrapper">
                                                                        <img src="{{ asset('images/logo/logo1.png') }}" alt="Mum Valley Logo" style="max-width:180px;">
                                                                        {{-- <h3 class="text-primary invoice-logo">GMV Envios</h3> --}}
                                                                    </div>
                                                                    {{-- <p class="card-text mb-25">{{ $parcel->customerSenderDetails->city }} {{ $parcel->customerSenderDetails->state_code ? ','.$parcel->customerSenderDetails->state_code : '' }} {{ $parcel->customerSenderDetails->zipcode ? ','.$parcel->customerSenderDetails->zipcode : '' }}</p>
                                                                    <p class="card-text mb-25">{{ $parcel->customerSenderDetails->address }}</p> --}}
                                                                </div>
                                                                <div class="col-md-2 mt-md-0 mt-2">
                                                                    {{-- <h4 class="invoice-title">
                                                                        Invoice
                                                                        <span class="invoice-number">#{{ $parcel->wr_number }}</span>
                                                                    </h4> --}}
                                                                    <div class="invoice-date-wrapper">
                                                                        <p class="invoice-date-title">Order Date:</p>
                                                                        <p class="invoice-date">{{ \Carbon\Carbon::parse($order->date)->format('m/d/y') }}</p>
                                                                    </div>
                                                                    <div class="invoice-date-wrapper">
                                                                        <p class="invoice-date-title">Receipt No#:</p>
                                                                        <p class="invoice-date">{{ 'MV'.str_pad($order->id, 7, '0', STR_PAD_LEFT) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Header ends -->
                                                        </div>

                                                        <hr class="invoice-spacing mt-0" />

                                                        <!-- Address and Contact starts -->
                                                        <div class="card-body invoice-padding pt-0">
                                                            <div class="row invoice-spacing">
                                                                <div class="col-xl-10 p-0 mt-xl-0 mt-2">
                                                                    <h6 class="card-text mb-25"><strong> Mum Valley</strong></h6>
                                                                    <p class="card-text mb-25">Kucha Khuh Road, Near Abdul Hakim Bypass</p>
                                                                    <p class="card-text mb-25"><i data-feather="phone"></i> +92 330 0006860</p>
                                                                    <p class="card-text mb-0"><i data-feather="mail"></i> mumvalley@account.com</p>
                                                                </div>
                                                                <div class="col-xl-2 p-0">
                                                                    <h6 class="card-text mb-25"><strong>{{ optional($order->user)->name }} {{ optional($order->user)->last_name }}  - {{ optional($order->user)->po_box_number }}</strong></h6>
                                                                    <p class="card-text mb-25">{{optional(optional(optional($order)->user)->setting)->address}},  {{optional(optional(optional($order)->user)->setting)->zipcode}}, {{optional(optional(optional($order)->user)->country)->iso2}}</p>
                                                                    <p class="card-text mb-25"><i data-feather="phone"></i> {{ optional($order->user)->phone }}</p>
                                                                    <p class="card-text mb-0"><i data-feather="mail"></i> {{ optional($order->user)->email }}</p>
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
                                                                            <th class="py-1">Order No.</th>
                                                                            <th class="py-1">Payment Status</th>
                                                                            <th class="py-1">Order Status</th>
                                                                            {{-- <th class="py-1">Remaining Customer Units</th>
                                                                            <th class="py-1">Remaining Stock Units</th> --}}
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><a type="button" class="text-primary">{{ $order->order_number }} </a></td>
                                                                            <td>
                                                                                @if($order->payment_status === 'unpaid')
                                                                                    <span class="badge rounded-pill badge-light-warning me-1">Unpaid</span>
                                                                                @elseif($order->payment_status === 'partial_paid')
                                                                                    <span class="badge rounded-pill badge-light-primary me-1">Partial Paid</span>
                                                                                @elseif($order->payment_status === 'paid')
                                                                                    <span class="badge rounded-pill badge-light-success me-1">Paid</span>
                                                                                @elseif($order->payment_status === 'cancelled')
                                                                                    <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($order->order_status === 'cancelled')
                                                                                    <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                                                                                @elseif($order->order_status === 'in_process')
                                                                                    <span class="badge rounded-pill badge-light-secondary me-1">In Process</span>
                                                                                @elseif($order->order_status === 'delivered')
                                                                                    <span class="badge rounded-pill badge-light-primary me-1">Delivered</span>
                                                                                @elseif($order->order_status === 'completed')
                                                                                    <span class="badge rounded-pill badge-light-success me-1">Completed</span>
                                                                                @endif
                                                                            </td>
                                                                            {{-- <td>
                                                                                @if($order->shipment_label)
                                                                                    <a href="{{ asset('storage/labels/' . $order->shipment_label) }}" target="_blank">
                                                                                        <span class="badge rounded-pill badge-light-info">View Label</span>
                                                                                    </a>
                                                                                @endif
                                                                            </td>
                                                                            <td></td> --}}
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
                                                                                Order Items Detail
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="py-1">#</th>
                                                                            <th class="py-1">Product</th>
                                                                            <th class="py-1" style="text-align: center;">Delivered Units</th>
                                                                            <th class="py-1" style="text-align: center;">Returned Units</th>
                                                                            <th class="py-1" style="text-align: center;">Remaining Customer Units</th>
                                                                            <th class="py-1" style="text-align: center;">Unit Price (Rs.)</th>
                                                                            <th class="py-1" style="text-align: center;">Total (Rs.)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($order->items as $item)
                                                                            <tr style="font-size: 14px !important;">
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>
                                                                                    <img src="{{ $item->productInventory->product_image ? asset('storage/images/products/' . $item->productInventory->product_image) : (optional($item)->product->image ? asset('storage/images/products/' . $item->product->image) : asset('storage/images/products/default.png')) }}" 
                                                                                        alt="Product Image" 
                                                                                        class="product-img">
                                                                                        &nbsp; {{ $item->product->name }}
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    {{ $item->product->type === 'pack_of_6' ? $item->delivered_units / 6 : ($item->product->type === 'pack_of_12' ? $item->delivered_units / 12 : $item->delivered_units) }}
                                                                                </td>
                                                                                <td style="text-align: center;">{{ $item->returned_units }}</td>
                                                                                <td style="text-align: center;">{{ $item->remaining_customer_units }}</td>
                                                                                <td style="text-align: center;">{{ $item->unit_price }}</td>
                                                                                <td style="text-align: center;">{{ $item->total_price }}</td>
                                                                            </tr>
                                                                        @endforeach

                                                                        <tr class="border-top-light">
                                                                            <td colspan="6" class="text-center text-dark" style="font-size: 16px !important;"><b>Order Total Amount</b></td>
                                                                            <td class="text-dark" style="font-size: 16px !important; text-align: center;">
                                                                                <strong>Rs. {{ optional($order)->total_amount}}</strong>
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
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    @php
                                        $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($order->id);
                                    @endphp
                                    <a href="{{ route('product.order.details', $encryptedId) }}"
                                        ><button class="btn btn-success btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                    </a>

                                    @if(auth()->user()->hasRole('admin'))
                                        @if( !$order->isPaid() && $order->user->isActive())
                                            @if ($order->paymentInvoices->isNotEmpty())
                                                @if(Auth::user()->isActive())
                                                    <form action="{{ route('payment-invoices.update', ['payment_invoice' => $order->paymentInvoices->first()->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="order_id" value="{{$order->id}}">
                                                        <button type="submit" class="btn btn-primary">
                                                            <span class="align-middle d-sm-inline-block d-none">Update Invoice</span>
                                                            <i data-feather="arrow-right" class="align-middle me-sm-25 me-0"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                        
                                            @else
                                                <a @if(Auth::user()->isActive()) href="{{ route('payment-invoices.create',['orders'=>$encryptedId]) }}" @endif
                                                    class="btn btn-primary">
                                                    <span class="align-middle d-sm-inline-block d-none">Create Invoice</span>
                                                    <i data-feather="arrow-right" class="align-middle me-sm-25 me-0"></i>
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                    </div>
                <!-- /Horizontal Wizard -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    </x-app-layout>
    @include('parcels.partials.order-js')
    @include('components.loading')
    <script>
        localStorage.clear();
    </script>
