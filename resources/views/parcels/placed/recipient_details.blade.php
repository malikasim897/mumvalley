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
                            <div id="recipient-details">
                                <div class="content-header">
                                    {{-- <h5 class="mb-0">Recipients Detials</h5> --}}
                                </div>
                                <form id="recipientDetailsForm"
                                    action="{{ route('parcel.recipient.details.store', $parcel->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-1 col-md-8">
                                            <label class="form-label" for="addressesList">Select Address From List</label>
                                            <select id="addressList" data-url="{{ route('addresses.address.list', ['id' => ':id']) }}"" name="addressListId" class="select2 form-select">
                                                <option value="">Select Address From List</option>
                                                @foreach ($addresses as $key => $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['first_name'] ." | ". $data['last_name'] ." | ". $data['email'] ." | ". $data['phone'] ." | ". $data['address'] ." | ". $data['country_id'] ." | ". $data['state_id'] ." | ". $data['zip_code']  }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="mb-1 col-md-3">
                                            <label class="form-label" for="addType">Type <span class="text-danger">*</span></label>
                                            <select onload="taxTypeChange(this)" id="addType" name="account_type" class="form-select">
                                                <option value="" {{ old('account_type', $parcel->customerRecipientDetails->account_type?? "") == "" ? 'selected' : '' }}>Type</option>
                                                <option value="individual" {{ old('account_type', $parcel->customerRecipientDetails->account_type ?? " ") == 'individual' ? 'selected' : '' }}>Individual</option>
                                                <option value="business" {{ old('account_type', $parcel->customerRecipientDetails->account_type??" ") == 'business' ? 'selected' : '' }}>Business</option>
                                            </select>
                                        </div>
                                        <x-input-error class="" :messages="$errors->get('account_type')" />
                                    </div>
                                    {{-- <div class="row">
                                        <div class="mb-1 col-md-8">
                                            <label class="form-label" for="addressList">Select Address From List</label>
                                            <select class="select2 w-100" name="addressListId" id="addressList">
                                                <option value="">Select Address From List</option>
                                                @if (($recipients) != null)
                                                    @foreach ($recipients as $recipient)
                                                        <option value="{{ $recipient['id'] }}" {{ $parcel->customerRecipientDetails && $parcel->customerRecipientDetails->id == $recipient['id'] ? 'selected' : '' }}>{{ $recipient['first_name'] ." | ". $recipient['last_name'] ." | ". $recipient['email'] ." | ". $recipient['phone'] ." | ". $recipient['address'] ." | ". $recipient['country_name'] ." | ". $recipient['state_name'] ." | ". $recipient['zip_code']  }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                    {{-- <input type="hidden" name="recipinetId" id="recipinetId" value="{{ $parcel->customerRecipientDetails->id ?? ''}}"> --}}

                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="first_name">First Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder=""
                                                value="{{ $parcel->customerRecipientDetails->first_name ?? old('first_name') }}" />
                                            <x-input-error class="" :messages="$errors->get('first_name')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="last_name">Last Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder=""
                                                value="{{ $parcel->customerRecipientDetails->last_name ?? old('last_name') }}" />
                                            <x-input-error class="" :messages="$errors->get('last_name')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="john.doe@email.com"
                                                value="{{ $parcel->customerRecipientDetails->email ?? old('email') }}" />
                                            <x-input-error class="" :messages="$errors->get('email')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="phone">Phone<span
                                                    class="text-danger">*</span> (International Format) i.e +5511992230189 Symbols,letters,spaces not accepted.</label>
                                            <input type="text" name="phone" id="phone" class="form-control"
                                                value="{{ $parcel->customerRecipientDetails->phone ?? old('phone') }}" />
                                            <x-input-error class="" :messages="$errors->get('phone')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="receiverCountry">Country<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 w-100" name="country_id" id="receiverCountry">
                                                <option label=""></option>
                                                @if (count($countries) == 0)
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                                <option label="Select Country"></option>
                                                @if (count($countries) > 0)
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['id'] }}"
                                                            {{ $parcel->customerRecipientDetails && $parcel->customerRecipientDetails->country_id == $country['id'] ? 'selected' : '' }}>
                                                            {{ $country['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error class="" :messages="$errors->get('country_id')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="receiverState">State<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 w-100" name="state_id" id="receiverState">
                                                @if (count($states) > 0)
                                                    <option label="Select State"></option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state['id'] }}"
                                                            {{ $parcel->customerRecipientDetails && $parcel->customerRecipientDetails->state_id == $state['id'] ? 'selected' : '' }}>
                                                            {{ $state['iso2'] }}</option>
                                                    @endforeach
                                                @else
                                                    <option label="Select State"></option>
                                                @endif
                                            </select>
                                            <x-input-error class="" :messages="$errors->get('state_id')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="address">Address<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="address" name="address" class="form-control"
                                                placeholder="98  Borough bridge Road, New York"
                                                value="{{ $parcel->customerRecipientDetails->address ?? old('address') }}" />
                                            <x-input-error class="" :messages="$errors->get('address')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="address">Address_2<span
                                                    class="text-danger"></span></label>
                                            <input type="text" id="address_2" name="address_2" class="form-control"
                                                placeholder="98  Borough bridge Road, New York"
                                                value="{{ $parcel->customerRecipientDetails->address_2 ?? old('address_2') }}" />
                                            <x-input-error class="" :messages="$errors->get('address_2')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="city">City<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="New York"
                                                value="{{ $parcel->customerRecipientDetails->city ?? old('city') }}" />
                                            <x-input-error class="" :messages="$errors->get('city')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="street_no">Street No<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="street_no" id="street_no"
                                                class="form-control" placeholder=""
                                                value="{{ $parcel->customerRecipientDetails->street_no ?? old('street_no') }}" />
                                            <x-input-error class="" :messages="$errors->get('street_no')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="zipcode">Zipcode<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="zipcode" id="zipcode" class="form-control"
                                                placeholder=""
                                                value="{{ $parcel->customerRecipientDetails->zipcode ?? old('zipcode') }}" />
                                            <x-input-error class="" :messages="$errors->get('zipcode')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            @php
                                                $accountType = $parcel->customerRecipientDetails->account_type ?? "";
                                                $output = ($accountType == "business") ? "CNPJ" : (($accountType == "individual") ? "CPF" : "");
                                            @endphp
                                            <label class="form-label" for="taxId"> <span id="placeType">{{ $output }}</span> Tax Id<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="tax_id" id="taxId" class="form-control"
                                                placeholder=""
                                                value="{{ $parcel->customerRecipientDetails->tax_id ?? old('tax_id') }}" />
                                            <x-input-error class="" :messages="$errors->get('tax_id')" />
                                        </div>
                                    </div>
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="form-check" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox"
                                                    name="saveAddress" id="saveAddress"/>
                                                <label class="form-check-label font-medium-5" for="saveAddress" style="padding-left: 10px;">
                                                    Save Address
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('parcel.sender.details', $parcel->id) }}"
                                            class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </a>
                                        <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                    </div>
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
