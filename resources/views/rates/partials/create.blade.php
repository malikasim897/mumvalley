<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        button.btn.btn-outline-secondary.dropdown-toggle.show-arrow {
            padding: 0.4em 1.3em;
        }
    </style>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Rate</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('rates.index') }}">Rate</a>
                                    </li>
                                    <li class="breadcrumb-item active">create
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Add Rate</h4>
                            <a href="{{asset('sample/sample.xlsx')}}" download>
                                <button type="submit" class="btn btn-primary mt-1 me-1 btn-sm">Sample Download</button>
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('rates.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col-12 col-sm-4 mb-1">
                                        <label class="form-label" for="shipping_service">User</label>
                                        <select id="user_id" name="user_id" class="select2 form-select">
                                            @foreach ($users as $key => $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} | {{ $user->po_box_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="" :messages="$errors->get('user_id')" />
                                    </div>


                                    <div class="col-12 col-sm-4 mb-1">
                                        <label class="form-label" for="shipping_service">Shipping Service</label>
                                        <select id="shipping_service_id" name="shipping_service_id"
                                            class="select2 form-select">
                                            @foreach ($shippingServices as $key => $shippingService)
                                                <option
                                                    value="{{ $shippingService['id'] . ',' . $shippingService['name'] . ',' . $shippingService['service_sub_class'] }}">
                                                    {{ $shippingService['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="" :messages="$errors->get('shipping_service_id')" />
                                    </div>

                                    <div class="col-12 col-sm-4 mb-1">
                                        <label class="form-label" for="rate_file">Upload Rates File<a href="{{ asset('file.xlsx')}}" download rel="noopener noreferrer" target="_blank">
                                            (Sample file)</a><span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="csv_file" name="csv_file"
                                            value="{{ old('csv_file') }}" data-msg="Please upload csv"
                                            placeholder="csv file" />
                                        <x-input-error class="" :messages="$errors->get('csv_file')" />
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mt-1 me-1">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    @include('components.loading')

</x-app-layout>
