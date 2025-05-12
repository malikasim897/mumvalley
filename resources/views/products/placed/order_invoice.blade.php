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
                                    <li class="breadcrumb-item active"><a href="{{ route('parcels.index') }}">Parcels</a>
                                    </li>
                                    <li class="breadcrumb-item active">Order
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Horizontal Wizard -->
                <section class="mb-5 min-vh-50">
                    <div class="bs-stepper">
                        @include('parcels.partials.order_header')
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
                                                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                                                <div>
                                                                    <div class="logo-wrapper">
                                                                        <img src="https://gmvenvios.com/wp-content/uploads/2023/09/WhatsApp-Image-2023-08-31-at-12.57.45.jpg"
                                                                        alt="logo image"  style="height: 50px; width:50px;">
                                                                        {{-- <h3 class="text-primary invoice-logo">GMV Envios</h3> --}}
                                                                    </div>
                                                                    {{-- <p class="card-text mb-25">{{ $parcel->customerSenderDetails->city }} {{ $parcel->customerSenderDetails->state_code ? ','.$parcel->customerSenderDetails->state_code : '' }} {{ $parcel->customerSenderDetails->zipcode ? ','.$parcel->customerSenderDetails->zipcode : '' }}</p>
                                                                    <p class="card-text mb-25">{{ $parcel->customerSenderDetails->address }}</p> --}}
                                                                </div>
                                                                <div class="mt-md-0 mt-2">
                                                                    {{-- <h4 class="invoice-title">
                                                                        Invoice
                                                                        <span class="invoice-number">#{{ $parcel->wr_number }}</span>
                                                                    </h4> --}}
                                                                    <div class="invoice-date-wrapper">
                                                                        <p class="invoice-date-title">Invoice Date:</p>
                                                                        <p class="invoice-date">{{ \Carbon\Carbon::parse($parcel->updated_at)->format('m/d/y') }}</p>
                                                                    </div>
                                                                    <div class="invoice-date-wrapper">
                                                                        <p class="invoice-date-title">Invoice No#:</p>
                                                                        <p class="invoice-date">{{ 'G'.str_pad($parcel->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Header ends -->
                                                        </div>

                                                        <hr class="invoice-spacing" />

                                                        <!-- Address and Contact starts -->
                                                        <div class="card-body invoice-padding pt-0">
                                                            <div class="row invoice-spacing">
                                                                <div class="col-xl-9 p-0 mt-xl-0 mt-2">
                                                                    <h6 class="mb-2">Recipient:</h6>
                                                                    <h6 class="mb-25">{{ $parcel->customerRecipientDetails->first_name }} {{$parcel->customerRecipientDetails->last_name }}</h6>
                                                                    <p class="card-text mb-25">{{ $parcel->customerRecipientDetails->address }} <strong>,</strong> {{ $parcel->customerRecipientDetails->street_no }} <strong>,</strong> {{ $parcel->customerRecipientDetails->address_2 }}</p>
                                                                    {{-- <p class="card-text mb-25">{{ $parcel->customerRecipientDetails->city }} {{ $parcel->customerRecipientDetails->state_code ? ','.$parcel->customerRecipientDetails->state_code : '' }} {{ $parcel->customerRecipientDetails->zipcode ? ','.$parcel->customerRecipientDetails->zipcode : '' }}</p> --}}
                                                                    <p class="card-text mb-25">{{ $parcel->customerRecipientDetails->zipcode }} <strong>,</strong>{{ $parcel->customerRecipientDetails->city }} <b>,</b> {{ $stateName }}</p>
                                                                    <p class="card-text mb-25"></p>
                                                                    {{-- <p class="card-text mb-25"> , </p> --}}
                                                                    {{-- <p class="card-text mb-25"></p> --}}
                                                                    <p class="card-text mb-25"><i data-feather="phone"></i> {{ $parcel->customerRecipientDetails->phone }}</p>
                                                                    <p class="card-text mb-0"><i data-feather="mail"></i> {{ $parcel->customerRecipientDetails->email }}</p>
                                                                </div>
                                                                <div class="col-xl-3 p-0">
                                                                    <h6 class="mb-2">Sender:</h6>
                                                                    <h6 class="mb-25">{{ $parcel->customerSenderDetails->first_name }} {{$parcel->customerSenderDetails->last_name }}</h6>
                                                                    <p class="card-text mb-25">{{ $parcel->customerSenderDetails->city }} {{ $parcel->customerSenderDetails->state_code ? ','.$parcel->customerSenderDetails->state_code : '' }} {{ $parcel->customerSenderDetails->zipcode ? ','.$parcel->customerSenderDetails->zipcode : '' }}</p>
                                                                    <p class="card-text mb-25">{{ $parcel->customerSenderDetails->address }}</p>
                                                                    <p class="card-text mb-25">{{ $parcel->customerSenderDetails->country_name }}</p>
                                                                    <p class="card-text mb-25"><i data-feather="phone"></i> {{ $parcel->customerSenderDetails->phone }}</p>
                                                                    <p class="card-text mb-0"><i data-feather="mail"></i> {{ $parcel->customerSenderDetails->email }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Address and Contact ends -->
                                                        <div class="card-body invoice-padding pt-0">
                                                                {{-- order info --}}
                                                            <div class="col-12">
                                                                <table class="table table-bordered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th>Merchant</th>
                                                                            <th>Carrier</th>
                                                                            <th>Carrier Tracking</th>
                                                                            <th>Warehouse Number</th>
                                                                            <th>Customer Reference</th>
                                                                            <th>Tracking Code</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>{{ $parcel->merchant }}</td>
                                                                            <td>{{ $parcel->carrier }}</td>
                                                                            <td>{{ $parcel->tracking_id }}</td>
                                                                            <td>{{ $parcel->wr_number }}</td>
                                                                            <td>{{ $parcel->additional_reference }} </td>
                                                                            <td>{{ $parcel->tracking_code }} </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Length</th>
                                                                            <th>Width</th>
                                                                            <th>Height</th>
                                                                            <th>Weight</th>
                                                                            <th colspan="2">Unit</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>{{ $parcel->length }} {{ $parcel->unit == 'lbs/in' ? 'in' : 'cm' }}</td>
                                                                            <td>{{ $parcel->width }} {{ $parcel->unit == 'lbs/in' ? 'in' : 'cm' }}</td>
                                                                            <td>{{ $parcel->height }} {{ $parcel->unit == 'lbs/in' ? 'in' : 'cm' }}</td>
                                                                            <td>
                                                                                Weight: {{ $parcel->weight_other }} {{ $parcel->unit == 'lbs/in' ? 'Kg' : 'lbs' }} ( {{ $parcel->weight }} {{ $parcel->unit == 'lbs/in' ? 'lbs' : 'kg' }} ) <br>
                                                                                {{-- Vol. Weight: {{ $parcel->weight_other }} {{ $parcel->unit == 'lbs/in' ? 'Kg' : 'lbs' }} ( {{ $parcel->weight }} {{ $parcel->unit == 'lbs/in' ? 'lbs' : 'kg' }} ) <br> --}}
                                                                            </td>
                                                                            <td colspan="2">{{ $parcel->unit }} </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- Service -->
                                                            <div class="my-2">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="py-1">Service</th>
                                                                            <th class="py-1">Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="border-top-light">
                                                                            <td>{{ $parcel->service_name }}</td>
                                                                            <td>
                                                                                {{ number_format($parcel->shippment_value, 2) }} USD
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- Invoice Order Items starts -->
                                                            <div class="">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="6">
                                                                                <h4>Order Items/Customs Declaration</h4>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="py-1">ShCode</th>
                                                                            <th class="py-1">Description</th>
                                                                            <th class="py-1">Quantity</th>
                                                                            <th class="py-1">Unit Value</th>
                                                                            <th class="py-1">Total</th>
                                                                            <th class="py-1">Battery/Perfume/Flameable</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($parcel->orderItems as $item)
                                                                        @php
                                                                            $goods = '';
                                                                            if($item->contain_goods == 'is_battery')
                                                                            {
                                                                                $goods = 'Battery';
                                                                            } else if($item->contain_goods == 'is_perfume')
                                                                            {
                                                                                $goods = 'Perfume';
                                                                            } else if($item->contain_goods == 'is_flameable')
                                                                            {
                                                                                $goods = 'Flameable';
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="py-1">
                                                                                    {{ $item->sh_code }}
                                                                            </td>
                                                                            <td class="py-1 text-wrap">
                                                                               {{ $item->description }}
                                                                            </td>
                                                                            <td class="py-1 text-center">
                                                                                <span class="fw-bold">{{ $item->quantity }}</span>
                                                                            </td>
                                                                            <td class="py-1 text-center">
                                                                                <span class="fw-bold">{{ number_format($item->value, 2) }} USD</span>
                                                                            </td>
                                                                            <td class="py-1">
                                                                                <span class="fw-bold">{{ number_format($item->total, 2) }} USD</span>
                                                                            </td>
                                                                            <td class="py-1">
                                                                                <span class="fw-bold">{{ $goods}}</span>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                        <tr class="border-top-light">
                                                                            <td colspan="4" class="text-center h4">Order Value</td>
                                                                            <td class="h4">
                                                                                {{ number_format($parcel->orderItems->sum('total'), 2) }} USD
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>

                                                                        <tr class="border-top-light">
                                                                            <td colspan="4" class="text-center h4">Freight Declared to Custom</td>
                                                                            <td class="h4">
                                                                                {{ optional($parcel)->freight_rate}} USD 
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="card-body invoice-padding pb-0">
                                                            <div class="row invoice-sales-total-wrapper">
                                                                <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                                                    <p class="card-text mb-0">
                                                                        {{-- <span class="fw-bold">Salesperson:</span> <span class="ms-75">Alfie Solomons</span> --}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                                                    <div class="invoice-total-wrapper">
                                                                        <div class="invoice-total-item">
                                                                            <p class="invoice-total-title">Shipping:</p>
                                                                            <p class="invoice-total-amount">{{ number_format(optional($parcel)->shippment_value, 2) }} USD</p>
                                                                        </div>
                                                                        <hr class="my-50" />
                                                                        <div class="invoice-total-item">
                                                                            <p class="invoice-total-title">Total:</p>
                                                                            <p class="invoice-total-amount">{{ number_format($parcel->shippment_value, 2) }} USD</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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




                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('parcel.shipping-items.details', $parcel->id) }}"
                                        class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </a>
                                    <div>

                                             @if($parcel->need_to_pay==0)
                                                    @if ($parcel->parcel_id)
                                                        <a class="btn btn-success" href="{{ route('order.print.label', $parcel->id) }}"
                                                            id="printPostLable">
                                                            <i data-feather="printer" class="me-50"></i>
                                                            <span>Print Post Label</span>
                                                        </a>
                                                    @else 
                                                        <a href="{{ route('order.register', $parcel->id) }}"  class="btn btn-success">
                                                        <i data-feather="plus" class="me-50"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Register Order</span>
                                                    </a>
                                                    @endif
                                            @elseif($parcel->need_to_pay>0)                                                
                                                    <a href="{{ route('order.pay', $parcel->id) }}"
                                                        class="btn btn-success">
                                                        <span class="align-middle d-sm-inline-block d-none">Pay {{$parcel->need_to_pay}} USD</span>
                                                    </a>
                                            @elseif($parcel->need_to_pay<0)
                                                <a class="btn btn-success" href="{{ route('order.pay', $parcel->id) }}" id="printPostLable">
                                                        <i data-feather="dollar-sign" class="me-50"></i>
                                                <span>Refund {{ abs($parcel->need_to_pay)}} USD</span>
                                                </a>
                                            @endif 

                                        <a href="{{ route('orders.index') }}"
                                            class="btn btn-primary">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle me-sm-25 me-0"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /Horizontal Wizard -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    </x-app-layout>
    @include('parcels.partials.order-js')
    @include('components.loading')
