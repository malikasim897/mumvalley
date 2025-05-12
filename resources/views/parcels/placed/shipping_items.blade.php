<style>
    .orderItems {
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
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
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('parcels.index') }}">Parcels</a>
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
                                {{-- show api error if order not created --}}
                                @if (session('error_message'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach (session('error_message') as $field => $fieldErrors)
                                                @foreach ($fieldErrors as $error)
                                                    <li style="list-style: inside;padding-left: 10px;">
                                                        {{ $error }}</li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form id="shippingItemsForm" action="{{ route('parcel.shipping.items.store', $parcel->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="customer_reference">Customer
                                                    Reference</label>
                                                {{-- @if (auth()->check())
                                                    @auth
                                                        @if (Auth::user()->id != null)
                                                            <input type="text" name="customer_reference"
                                                                class="form-control customer_reference"
                                                                id="customer_reference" readonly
                                                                value="{{ Auth::user()->name . ' - ' . str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) ?? old('customer_reference') }}"
                                                                maxlength="20" oninput="restrictInputLength(this, 100)">
                                                        @else
                                                            <input type="text" name="customer_reference"
                                                                class="form-control customer_reference"
                                                                id="customer_reference" readonly
                                                                value="{{ Auth::user()->name . ' - ' . str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) ?? old('customer_reference') }}"
                                                                maxlength="20" oninput="restrictInputLength(this, 100)">
                                                        @endif
                                                    @endauth
                                                @endif --}}
                                                <input type="text" name="customer_reference"
                                                    class="form-control customer_reference" id="customer_reference"
                                                    value="{{ $parcel->tracking_id ?? old('customer_reference') }}"
                                                    maxlength="20" oninput="restrictInputLength(this, 100)">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="wr_number">WR Numnber</label>
                                                <input type="text" name="wr_number" class="form-control wr_number" id="wr_number" readonly="readonly" value="{{ $parcel->wr_number ?? '' }}">
                                            </div>
                                        </div> --}}
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="shippingServices">Select Shipping
                                                Service<span class="text-danger">*</span></label>
                                            <select class="select2 w-100" name="service_id" id="shippingServices">
                                                <option label=""></option>
                                                @if ($shippingServices['data'] && count($shippingServices['data']) > 0)
                                                    @foreach ($shippingServices['data'] as $service)
                                                        <option value="{{ $service['id'] }}"
                                                            {{ $parcel->service_id == $service['id'] ? 'selected' : '' }}
                                                            data-service-cost="{{ $service['cost'] }}">
                                                            {{-- . $service['cost'] --}}
                                                            {{ $service['shippingServices'] . ' '  }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error class="" :messages="$errors->get('service_id')" />
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="shippment_value">Freight</label>
                                                <input type="float" value="{{ old('shippment_value',$parcel->freight_rate) }}" name="shippment_value" id="freight_value" class="form-control" >
                                                <x-input-error class="" :messages="$errors->get('shippment_value')" />
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="tax_modality">
                                                Tax Modality<span class="text-danger">*</span></label>
                                            <select class="select2 w-100" name="tax_modality" id="tax_modality">
                                                <option label=""></option>
                                                <option value="ddu" {{ old("tax_modality", $parcel->tax_modality) == "ddu" ? 'selected' : '' }}>DDU</option>
                                                <option value="ddp" {{ old("tax_modality", $parcel->tax_modality) == "ddp" ? 'selected' : '' }}>DDP</option>
                                            </select>
                                            <x-input-error class="" :messages="$errors->get('tax_modality')" />
                                        </div>
                                    </div>
                                    <div class="row mt-2 mb-2">
                                        <div>
                                          <div class="form-check" style="display: flex; align-items: center;">
                                            <input class="form-check-input" type="radio" value="1" name="return_parcel" id="return"  @if($parcel->return_parcel=="1") checked @endif/>
                                            <label class="form-check-label font-medium-5 me-5" for="return" style="padding-left: 10px;"> Return All Parcel On My Account </label>
                                            <input class="form-check-input" type="radio" value="2" name="return_parcel" id="disposal" @if($parcel->return_parcel=="2") checked @endif/>
                                            <label class="form-check-label font-medium-5" for="disposal" style="padding-left: 10px;"  > Disposal All Authorized </label>
                                          </div>
                                          <div class="text-center col-6">
                                          </div>
                                            <x-input-error class="" :messages="$errors->get('return_parcel')" />
                                        </div>
                                      </div>
                                    <div class="divider">
                                        <div class="divider-text">Order Items</div>
                                    </div>
                                    <div action="#" class="order-repeater">
                                        <div class="row">
                                            <div class="col-12 mb-1">
                                                <button class="btn btn-icon btn-success" type="button" id="addNewItem"
                                                    data-repeater-create>
                                                    <i data-feather="plus" class="me-25"></i>
                                                    <span>Add New</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div data-repeater-list="shippingItems">
                                            @if (count($parcel->orderItems) > 0)
                                                @include('parcels.partials.store_order_items')
                                            @else
                                                @include('parcels.partials.order_item')
                                            @endif
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <div class="divider-text">Disclaimer</div>
                                    </div>
                                    <div class="alert alert-warning my-2">
                                        <h4 class="alert-heading">
                                            By clicking on save order you agree that you have read and confirm that all
                                            information in this statement has been filled out by me and is correct and I
                                            am 100% responsible for the information contained here in.
                                        </h4>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('parcel.recipient.details', $parcel->id) }}"
                                            class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </a>
                                        <button class="btn btn-success btn-submit">Save Order</button>
                                        {{-- <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button> --}}
                                    </div>
                                    <input type="hidden" name="removed_item_ids" id="removedItemIds" value="">
                                    <input type="hidden" name="service_name" id="service_name">
                                </form>
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

