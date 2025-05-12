<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row col-12">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Product</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('products.index') }}">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active">Create New
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-left col-md-3 col-12 mb-2 text-end">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="history.back();">
                        Back to List
                    </a>
                </div>
            </div>
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    {{-- @include('layouts.validation.message') --}}
                                    <form class="form" action="{{ route('products.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            {{-- @if (auth()->user()->hasRole('admin'))
                                                <div class="col-sm-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="user_id">Select User<span
                                                                class="text-danger">*</span></label>
                                                        <select id="user_id" name="user_id"
                                                            class="select2 form-select">
                                                            <option value="">Select User</option>
                                                            @foreach ($users as $key => $user)
                                                                <option value="{{ $user['id'] }}">
                                                                    {{ $user['name'] }} | {{ $user['po_box_number'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="" :messages="$errors->get('user_id')" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-8"></div>
                                            @endif --}}
                                            @if (auth()->user()->hasRole('user'))
                                                    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ auth()->user()->id }}">
                                            @endif
                                            
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="product_name">New Product Name<span class="text-danger">*</span></label>
                                                    <input type="text" id="product_name" class="form-control" name="product_name" value="{{ old('product_name') }}"  required/>
                                                    <x-input-error :messages="$errors->get('product_name')" />
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="total_units">Total Units<span class="text-danger">*</span></label>
                                                    <input type="number" id="purchased_units" class="form-control" name="purchased_units" value="{{ old('purchased_units') }}" required />
                                                    <x-input-error :messages="$errors->get('purchased_units')" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="unit_price">Unit Price<span class="text-danger">*</span></label>
                                                    <input type="number" id="unit_price" class="form-control" name="unit_price" value="{{ old('unit_price') }}" required />
                                                    <x-input-error :messages="$errors->get('unit_price')" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="type">Product Type<span class="text-danger">*</span></label>
                                                    <select id="type" name="type"
                                                            class="select2 form-select">
                                                            <option value="">Select Type</option>
                                                            <option value="pack_of_1">Pack of 1</option>
                                                            <option value="pack_of_6">Pack of 6</option>
                                                            <option value="pack_of_12">Pack of 12</option>
                                                        </select>
                                                    <x-input-error :messages="$errors->get('type')" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4 col-md-12 mb-1 mb-sm-0">
                                                <label for="product_image" class="form-label">Product Image</label>
                                                <input class="form-control" type="file" id="product_image" name="product_image" required>
                                                <x-input-error :messages="$errors->get('product_image')" />
                                            </div>

                                            <div class="col-md-4 col-12 mb-1 mt-2">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="returnable" name="returnable" value="1">
                                                    <label class="form-check-label" for="returnable">Returnable</label>
                                                </div>
                                            </div>
                                            
                                            <div class="content-header-right mt-2 text-md-end col-md-12 col-12 d-md-block d-none">
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary me-1">Submit</button>
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
