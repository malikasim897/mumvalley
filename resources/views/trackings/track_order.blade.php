<!-- BEGIN: Vendor CSS-->
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}"> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/wizard/bs-stepper.min.css') }}"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<!-- END: Vendor CSS-->


<!-- BEGIN: Page CSS-->

<!-- END: Page CSS-->

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .main {
        width: 100%;
        /* height: 100vh; */
        padding: 3px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    ul {
        display: flex;
    }

    ul li {
        list-style: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 30px;
    }

    ul li .icons {
        font-size: 25px;
        color: #1b761b;
        margin: 0 30px;
    }

    ul li .label {
        font-family: sans-serif;
        /* letter-spacing: 1px; */
        font-size: 11px;
        font-weight: bolder;
        color: #9D9AA5;
    }

    ul li .step {
        height: 45px;
        width: 45px;
        border-radius: 50%;
        background-color: #d7d7c3;
        margin: 16px 0;
        display: grid;
        place-items: center;
        color: ghostwhite;
        position: relative;
        cursor: pointer;
    }

    .step::after{
        content: "";
        position: absolute;
        width: 87px;
        height: 7px;
        background-color: #d7d7c3;
        right: 28px;
    }

    .first::after {
        width: 0;
        height: 0;
    }

    .removestep::after{
        content: none;
    }
    ul li .step .awesome {
        display: none;
    }

    ul li .step p {
        font-size: 18px;
    }

    ul li .active {
        background-color: #7367F0;
    }

    li .active::after {
        background-color: #7367F0;

    }

    ul li .active p {
        display: none;
    }

    ul li .active .awesome {
        display: flex;
    }

    ul li .step .uil {
        display: none;
    }

    ul li .active .uil {
        display: flex;
    }
</style>


<x-guest-layout>
    @include('layouts.partials.error_message')
    <!-- BEGIN: Content-->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="d-flex items-center space-x-2">
                                    <span class="brand-logo">
                                        <img src="https://gmvenvios.com/wp-content/uploads/2023/09/WhatsApp-Image-2023-08-31-at-12.57.45.jpg"
                                            alt="logo image" height="26" width="26">
                                    </span>
                                    <h2 class="brand-text me-1">GMV Envios</h2>
                                </div>

                            </div>
                            <div class="content-header-right text-md-end col-sm-6 col-md-6 col-lg-6 col-12 d-md-block">
                                <div class="col-12">
                                    <a type="submit" class="btn btn-primary" href="{{ route('dashboard') }}"><i
                                            data-feather="home" class="me-25"></i><span>Dashboard</span></a>
                                    <a type="submit" class="btn btn-primary me-1"
                                        href="{{ route('dashboard') }}"><span>Go Back</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h1>
       
    </h1>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="POST" action="{{ route('tracking.order.details') }}">
                        @csrf
                        @method('GET')

                        <div class="card-body">
                            <h4>Track Your Order</h4>
                            <div class="row flex items-center justify-center">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 mx-auto">
                                    <div class="mb-1">
                                        <input type="text" name="tracking_order" id="tracking_order"
                                            value=""
                                            placeholder="Enter Tracking Number" class="form-control">
                                        {{-- <x-input-error class="" :messages="$errors->get('tracking_order')" /> --}}
                                    </div>
                                </div>
                                <div
                                    class="content-header-right text-md-end col-sm-6 col-md-6 col-lg-6 col-12 d-md-block">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a type="submit" class="btn btn-primary me-1"
                                            href="#"><span>Download</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <section id="accordion-without-arrow">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                            <div class="card">
                                <div class="card-body">
                                    <div id="accordionIcon" class="accordion accordion-without-arrow">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header text-body d-flex justify-content-between border border-gray-500 border-solid"
                                                id="accordionIconOne">
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                    aria-controls="accordionIcon-1">
                                                    HD WHR#: {{ $order['wr_number'] ?? '' }}
                                                </button>
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                    aria-controls="accordionIcon-1">
                                                    Tracking#: <span>{{ $trackingDetails['hdTrackings'][0]['tracking_code'] }}</span>
                                                </button>
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                    aria-controls="accordionIcon-1">
                                                    Piece: {{ $order['shippment_value'] ?? '' }}
                                                </button>
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                    aria-controls="accordionIcon-1">
                                                    Weight:
                                                    {{ $order['weight'].' '.$order['unit'] ?? ''}}
                                                </button>
                                                <div >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" fill="currentColor" class="bi bi-arrow-down-short text-primary" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4"/>
                                                    </svg>
                                                </div>
                                            </h2>
                                            <div id="accordionIcon-1" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionIcon">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="main">
                                                                <ul>
                                                                   
                                                                @empty($trackingDetails->data->apiTrackings)
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="package"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step  @if ($lastStatusCode >= 70) active @endif  removestep">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Order<br> Placed</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="truck"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                                
                                                                            <div class="step @if ($lastStatusCode>= 72) active @endif">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Freight
                                                                                in <br>transit</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="home"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step @if ($lastStatusCode >= 73 ) active @endif  ">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Received<br>
                                                                                Terminal</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="archive"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step  @if ($lastStatusCode >= 75) active @endif  ">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Processed<br>
                                                                                manifested</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="target"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            {{-- @isset($trackingDetails['hdTrackings'][1]) @if ($trackingDetails['hdTrackings'][1]['status_code']>=70) active @endif  @endisset --}}
                                                                            <div class="step  @if ($lastStatusCode >=80) active @endif">
                                                                                <i data-feather="check" class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Posted
                                                                            <p></p>
                                                                            </p>
                                                                        </li>
                                                                        {{-- Received By Correrios --}}
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="user-check"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Received<br>
                                                                                by Correios</p>
                                                                        </li>

                                                                        <li>
                                                                            <p></p>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="search"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Customs<br>
                                                                                clearance <br> finalized</p>
                                                                        </li>

                                                                        <li>
                                                                            <p></p>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="truck"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">In transit <br>to
                                                                                Sao<br>Paulo
                                                                            </p>
                                                                        </li>

                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="package"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Out for<br>
                                                                                delivery</p>
                                                                        </li>

                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="codesandbox"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">parcels<br> delivered</p>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="package"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step   active removestep">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Order<br> Placed</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="truck"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                                
                                                                            <div class="step  active">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Freight
                                                                                in <br>transit</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="home"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step  active ">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Received<br>
                                                                                Terminal</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="archive"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step   active ">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Processed<br>
                                                                                manifested</p>
                                                                        </li>
                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="target"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            {{-- @isset($trackingDetails['hdTrackings'][1]) @if ($trackingDetails['hdTrackings'][1]['status_code']>=70) active @endif  @endisset --}}
                                                                            <div class="step   active ">
                                                                                <i data-feather="check" class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Posted
                                                                            <p></p>
                                                                            </p>
                                                                        </li>
                                                                        {{-- Received By Correrios --}}

                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="user-check"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step @if( $brazilStatusCode >= 90) active @endif">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Received<br>
                                                                                by Correios</p>
                                                                        </li>

                                                                        <li>
                                                                            <p></p>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="search"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step @if( $brazilStatusCode >= 100) active @endif">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Customs<br>
                                                                                clearance <br> finalized</p>
                                                                        </li>

                                                                        <li>
                                                                            <p></p>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="truck"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step @if( $brazilStatusCode >= 110) active @endif">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">In transit <br>to
                                                                                Sao<br>Paulo
                                                                            </p>
                                                                        </li>

                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="package"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step @if( $brazilStatusCode >= 120) active @endif">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">Out for<br>
                                                                                delivery</p>
                                                                        </li>

                                                                        <li>
                                                                            <div class="avatar bg-light-primary p-50">
                                                                                <span class="avatar-content">
                                                                                    <i data-feather="codesandbox"
                                                                                        class="font-medium-4"></i>
                                                                                </span>
                                                                            </div>
                                                                            <div class="step">
                                                                                <i data-feather="check"
                                                                                    class="uil font-medium-2"></i>
                                                                            </div>
                                                                            <p class="label">parcels<br> delivered</p>
                                                                        </li>
                                                                @endempty
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-responsive w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Country</th>
                                                                        <th>Description</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($trackingDetails['hdTrackings'] as $key => $tracking)
                                                                        <tr>
                                                                            <td>{{ date('Y-m-d H:i:s', strtotime($tracking['created_at'])) }}
                                                                            </td>
                                                                            @if ($tracking['type'] == 'HD')
                                                                                <td>US</td>
                                                                            @else
                                                                                @foreach ($countries as $key => $country)
                                                                                    @if ($country['code'] == $tracking['country'])
                                                                                        <td>{{ $country['name'] }}</td>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                            <td>{{ $tracking['description'] }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        {{-- @endforeach --}}
        <!-- Accordion Without Arrow end -->
    </div>
    <!-- END: Content-->
</x-guest-layout>

<!-- BEGIN: Page Vendor JS-->

<script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

