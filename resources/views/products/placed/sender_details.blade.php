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
                            <div id="sender-details">
                                <div class="content-header">
                                    {{-- <h5 class="mb-0">Sender Details</h5> --}}
                                </div>
                                <form id="senderDetailsForm"
                                    action="{{ route('parcel.sender.details.store', $parcel->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-1 col-md-8">
                                            <label class="form-label" for="addressesList">Select Address From List</label>
                                            <select id="addressList" data-url="{{route('addresses.sender.list', ['id' => ':id'])}}" name="addressListId" class="select2 form-select">
                                                <option value="">Select Address From List</option>
                                                @foreach ($addresses as $key => $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['first_name'] ." | ". $data['last_name'] ." | ". $data['email'] ." | ". $data['phone'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @php
                                            $firstName = $user->setting ? $user->setting->first_name : null;
                                            $firstName = $parcel->customerSenderDetails->first_name ?? ($firstName ?? old('first_name'));
                                            $lastName = $user->setting ? $user->setting->last_name : null;
                                            $lastName = $parcel->customerSenderDetails->last_name ?? ($lastName ?? old('last_name'));
                                            $email = $user->setting ? $user->email : null;
                                            $email = $parcel->customerSenderDetails->email ?? ($email ?? old('email'));
                                            $phone = $user->setting ? $user->phone : null;
                                            $phone = $parcel->customerSenderDetails->phone ?? ($phone ?? old('phone'));
                                            $address = $user->setting ? $user->setting->address : null;
                                            $address = $parcel->customerSenderDetails->address ?? ($address ?? old('address'));
                                            $city = $user->setting ? $user->setting->city : null;
                                            $city = $parcel->customerSenderDetails->city ?? ($city ?? old('city'));
                                            $zipcode = $user->setting ? $user->setting->zipcode : null;
                                            $zipcode = $parcel->customerSenderDetails->zipcode ?? ($zipcode ?? old('zipcode'));
                                            $country_id = $user->setting ? $user->setting->country_id : null;
                                            $country_id = $parcel->customerSenderDetails->country_id ?? ($country_id ?? old('country_id'));
                                            $state_id = $user->setting ? $user->setting->state_id : null;
                                            $state_id = $parcel->customerSenderDetails->state_id ?? ($state_id ?? old('state_id'));
                                        @endphp
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="first_name">First Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="" value="{{ $firstName }}" />
                                            <x-input-error class="" :messages="$errors->get('first_name')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="last_name">Last Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="" value="{{ $lastName }}" />
                                            <x-input-error class="" :messages="$errors->get('last_name')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="john.doe@email.com" value="{{ $email }}" />
                                            <x-input-error class="" :messages="$errors->get('email')" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="phone">Phone<span
                                                    class="text-danger">*</span> <span>(+1234567890)</span></label>
                                            <input type="text" name="phone" id="phone" class="form-control"
                                                placeholder="" value="{{ $phone }}" />
                                            <x-input-error class="" :messages="$errors->get('phone')" />
                                        </div>
                                        {{-- <div class="mb-1 col-md-6">
                                            <label class="form-label" for="senderCountry">Country</label>
                                            <select class="select2 w-100" name="country_id" id="receiverCountry">
                                                <option label="Select Country"></option>
                                                @if (count($countries) > 0)
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['id'] }}"
                                                            {{ $country_id == $country['id'] ? 'selected' : '' }}>
                                                            {{ $country['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6"> <label class="form-label"
                                                for="senderState">State</label>
                                            <select class="select2 w-100" name="state_id" id="receiverState">
                                                <option label="Select State"></option>
                                                @if ($states != null)
                                                    @foreach ($states as $key => $state)
                                                        <option value="{{ $state['id'] }}"
                                                            {{ $state_id == $state['id'] ? 'selected' : '' }}>
                                                            {{ $state['code'] }}</option>
                                                    @endforeach
                                                @else
                                                    @if ($userStates != null)
                                                        @foreach ($userStates as $key => $state)
                                                            <option value="{{ $state['id'] }}"
                                                                {{ $state_id == $state['id'] ? 'selected' : '' }}>
                                                                {{ $state['code'] }}</option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="address">Address</label>
                                            <input type="text" id="address" name="address" class="form-control"
                                                placeholder="98  Borough bridge Road, Birmingham"
                                                value="{{ $address }}" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="city">City</label>
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="Birmingham" value="{{ $city }}" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="zipcode">Zipcode</label>
                                            <input type="text" name="zipcode" id="zipcode" class="form-control"
                                                placeholder="" value="{{ $zipcode }}" />
                                        </div> --}}
                                        {{-- <div class="mb-1 col-md-6">
                                            <label class="form-label" for="taxId">Tax Id</label>
                                            <input type="text" name="tax_id" id="taxId" class="form-control"
                                                placeholder=""
                                                value="{{ $parcel->customerSenderDetails->tax_id ?? old('tax_id') }}" />
                                        </div> --}}
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
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('orders.index') }}" class="btn btn-success">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </a>
                                        <button class="btn btn-primary btn-next" type="submit">
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
