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
    {{-- @include('layouts.validation.message') --}}
    @include('layouts.partials.error_message')
    <!-- BEGIN: Content-->
    <div class="content-body" style="overflow: hidden">
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
    <div class="content-body" style="overflow: hidden">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <livewire:tracking.tracking>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</x-guest-layout>
<!-- BEGIN Vendor JS-->
<script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
