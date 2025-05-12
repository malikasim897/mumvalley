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
                            <h2 class="content-header-title float-start mb-0">Edit Parcel</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('parcels.index') }}">Parcels</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('layouts.validation.message')
                                    <form class="form" action="{{ route('orders.update', $parcel->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            @if (auth()->user()->hasRole('admin'))
                                                <div class="col-sm-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="user_id">Select User<span
                                                                class="text-danger">*</span></label>
                                                        <select id="user_id" name="user_id"
                                                            class="form-select select2">
                                                            {{-- <option value="">Select User</option> --}}
                                                            @if ($parcel->user_id != null)
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user['id'] }}"
                                                                        {{ $parcel->user_id != null && $user['id'] == $parcel->user_id ? 'selected' : '' }}>
                                                                        {{ $user['name'] }} | {{ $user['po_box_number'] }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <x-input-error class="" :messages="$errors->get('user_id')" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-8"></div>
                                            @endif
                                            @if (auth()->user()->hasRole('user'))
                                                <input type="hidden" id="user_id" name="user_id"
                                                    value="{{ auth()->user()->id }}">
                                            @endif
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="merchant">Sender’s Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="merchant" class="form-control"
                                                        name="merchant"
                                                        value="{{ $parcel->merchant ?? old('merchant') }}" />
                                                    <x-input-error class="" :messages="$errors->get('merchant')" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="carrier">Your customer nick name or
                                                        their code<span class="text-danger">*</span></label>
                                                    <input type="text" id="carrier" class="form-control"
                                                        name="carrier"
                                                        value="{{ $parcel->carrier ?? old('carrier') }}" />
                                                    <x-input-error class="" :messages="$errors->get('carrier')" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="tracking_id">Your reference or order
                                                        number <span class="text-danger">*</span></label>
                                                    <input type="text" id="tracking_id" class="form-control"
                                                        name="tracking_id"
                                                        value="{{ $parcel->tracking_id ?? old('tracking_id') }}" />
                                                    <x-input-error class="" :messages="$errors->get('tracking_id')" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="additional_reference">Additional
                                                        reference</label>
                                                    <input type="text" id="additional_reference" class="form-control"
                                                        name="additional_reference"
                                                        value="{{ $parcel->additional_reference ?? old('additional_reference') }}" />
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
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mb-1 position-relative">
                                                <label class="form-label" for="date">Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="date" id="date"
                                                    class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                    value="{{ $parcel->date ?? old('merchant') }}" />
                                                <x-input-error class="" :messages="$errors->get('date')" />
                                            </div>
                                            <div class="col-lg-4 col-md-12 mb-1 mb-sm-0">
                                                <label for="formFile" class="form-label">Invoice</label>
                                                <input class="form-control" type="file" id="formFile"
                                                    name="image">
                                                <x-input-error class="" :messages="$errors->get('image')" />
                                                @if ($parcel->image())
                                                    <div class="">
                                                        <a target="_blank" href="{{ asset($parcel->image()->url) }}">
                                                            {{ $parcel->image()->name }} </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <livewire:parcels.shipment :order="$parcel" />
                                            <div
                                                class="content-header-right text-md-end col-md-12 col-12 d-md-block d-none">
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary me-1">Update</button>
                                                    <button type="reset"
                                                        class="btn btn-outline-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</x-app-layout>
