<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Edit Address</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active"><a
                                                href="{{ route('addresses.index') }}">Addresses</a>
                                        </li>
                                        <li class="breadcrumb-item active">Edit Address
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                {{-- @include('layouts.validation.message') --}}
                <!-- form -->
                <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="addressesList">Type<span class="text-danger">*</span></label>
                            <select  onload="taxTypeChange(this)"  name="account_type" id="addType" class="form-select">
                                <option value="" {{ old('account_type', $address->account_type?? "") == "" ? 'selected' : '' }}>Type</option>
                                <option value="individual" {{ old('account_type', $address->account_type ?? " ") == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="business" {{ old('account_type', $address->account_type??" ") == 'business' ? 'selected' : '' }}>Business</option>
                            </select>
                            <x-input-error class="" :messages="$errors->get('account_type')" />
                            {{-- <select id="addressesList" name="addressesList" class="select2 form-select">
                                @foreach ($addresses as $key => $data)
                                    <option value="{{ $data['id'] }}"
                                        {{ $data['id'] == $address->id ? 'selected' : '' }}>
                                        {{ $data['first_name'] ." | ". $data['last_name'] ." | ". $data['email'] ." | ". $data['phone'] ." | ". $data['address'] ." | ". $data['country_id'] ." | ". $data['state_id'] ." | ". $data['zip_code']  }}
                                    </option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <input type="hidden" name="recipinetId" id="recipinetId" value="{{ $address->id ?? '' }}">

                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="first_name">First Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder=""
                                value="{{ $address->first_name ?? old('first_name') }}" />
                            <x-input-error class="" :messages="$errors->get('first_name')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="last_name">Last Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder=""
                                value="{{ $address->last_name ?? old('last_name') }}" />
                            <x-input-error class="" :messages="$errors->get('last_name')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="john.doe@email.com" value="{{ $address->email ?? old('email') }}" />
                            <x-input-error class="" :messages="$errors->get('email')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="phone">Phone<span class="text-danger">*</span>
                                (International Format) i.e +5511992230189 Symbols,letters,spaces not accepted.</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ $address->phone ?? old('phone') }}" />
                            <x-input-error class="" :messages="$errors->get('phone')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="receiverCountry">Country<span
                                    class="text-danger">*</span></label>
                            <select class="select2 w-100" name="country_id" id="receiverCountry">
                                {{-- <option label=""></option>
                            @if (count($countries) == 0)
                                @foreach ($countries as $country)
                                    <option value="{{ $country['id'] }}">{{ $country['name'] }}
                                    </option>
                                @endforeach
                            @endif
                            <option label="Select Country"></option> --}}
                                @if (count($countries) > 0)
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                            {{ $address->country_id == $country['id'] ? 'selected' : '' }}>
                                            {{ $country['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error class="" :messages="$errors->get('country_id')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="receiverState">State<span
                                    class="text-danger">*</span></label>
                            
                            <x-input-error class="" :messages="$errors->get('state_id')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="address">Address<span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control"
                                placeholder="98  Borough bridge Road, New York"
                                value="{{ $address->address ?? old('address') }}" />
                            <x-input-error class="" :messages="$errors->get('address')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="address_2">Address_2<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="address_2" name="address_2" class="form-control"
                                placeholder="98  Borough bridge Road, New York"
                                value="{{ $address->address2 ?? old('address_2') }}" />
                            <x-input-error class="" :messages="$errors->get('address_2')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="city">City<span class="text-danger">*</span></label>
                            <input type="text" name="city" id="city" class="form-control"
                                placeholder="New York" value="{{ $address->city ?? old('city') }}" />
                            <x-input-error class="" :messages="$errors->get('city')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="street_no">Street No<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="street_no" id="street_no" class="form-control"
                                placeholder="" value="{{ $address->street_no ?? old('street_no') }}" />
                            <x-input-error class="" :messages="$errors->get('street_no')" />
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="zipcode">Zipcode<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder=""
                                value="{{ $address->zipcode ?? old('zipcode') }}" />
                            <x-input-error class="" :messages="$errors->get('zipcode')" />
                        </div>
                        @php
                            $accountType = $address->account_type ?? "";
                            $output = ($accountType == "business") ? "CNPJ" : (($accountType == "individual") ? "CPF" : "");
                        @endphp
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="taxId"> <span id="placeType">{{ $output}}</span>  Tax Id<span class="text-danger">*</span></label>
                            <input type="text" name="tax_id" id="taxId" class="form-control" placeholder=""
                                value="{{ $address->tax_id ?? old('tax_id') }}" />
                            <x-input-error class="" :messages="$errors->get('tax_id')" />
                        </div>
                        <div class="content-header-right text-md-end col-md-12 col-12 d-md-block d-none">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-1">Update</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--/ form -->

            </div>
        </div>
    </div>
</x-app-layout>
@include('addresses.partials.address_js')
@include('components.loading')
{{-- @include('components.loading') --}}
