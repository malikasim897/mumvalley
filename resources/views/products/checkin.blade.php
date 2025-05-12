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
                            <h2 class="content-header-title float-start mb-0">Warehouse Check In Product</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('products.index') }}">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active">Check In
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
                                <div class="row card-header"><h4>User Product Information</h4></div>
                                <hr class="mt-0">
                                <div class="card-body">
                                    {{-- @include('layouts.validation.message') --}}
                                    <form class="form" action="{{ route('product.checkInUpdate', $product->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="id" name="id" value="{{ $product->id }}">
                                        <div class="row">
                                            @if (auth()->user()->hasRole('admin'))
                                                <div class="col-sm-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="user_id">Select User<span
                                                                class="text-danger">*</span></label>
                                                        <select id="user_id" name="user_id"
                                                            class="form-select select2" disabled>
                                                            {{-- <option value="">Select User</option> --}}
                                                            @if ($product->user_id != null)
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user['id'] }}"
                                                                        {{ $product->user_id != null && $user['id'] == $product->user_id ? 'selected' : '' }}>
                                                                        {{ $user['name'] }}
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
                                                    <label class="form-label" for="product_name">Product Name<span class="text-danger">*</span></label>
                                                    <input type="text" id="product_name" class="form-control" name="product_name" value="{{ $product->name ?? old('name') }}" readonly />
                                                    <x-input-error :messages="$errors->get('product_name')" />
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="dispatched_units">Dispatched Units<span class="text-danger">*</span></label>
                                                    <input type="number" id="dispatched_units" class="form-control" name="dispatched_units" value="{{ $product->lastinventory->dispatched_units ?? old('dispatched_units') }}" readonly />
                                                    <x-input-error :messages="$errors->get('dispatched_units')" />
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-12 mb-1 mb-sm-0">
                                                <label for="product_image" class="form-label" >Product Image</label>
                                                <input class="form-control" type="file" id="product_image" name="product_image" disabled>
                                                <x-input-error :messages="$errors->get('product_image')" />
                                                @if($product->lastinventory->product_image)
                                                    <a href="{{ asset('storage/images/products/' . $product->lastinventory->product_image) }}" class="product-image-link">
                                                        <span class="badge rounded-pill badge-light-primary mt-1">View Uploaded Image</span>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="row card-header"><h4>Product Warehouse Confirmation</h4></div>
                                            <br/><hr class="mt-0">

                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="received_units">Warehouse Received Units<span class="text-danger">*</span></label>
                                                    <input type="number" id="received_units" class="form-control" name="received_units" value="{{ $product->lastinventory->confirmed_units ?? old('confirmed_units') }}" required @if($product->orders()->exists()) readonly @endif />
                                                    <x-input-error :messages="$errors->get('received_units')" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 mb-1 mb-sm-0">
                                                <label for="warehouse_image" class="form-label" >Warehouse Image</label>
                                                <input class="form-control" type="file" id="warehouse_image" name="warehouse_image">
                                                <x-input-error :messages="$errors->get('warehouse_image')" />
                                                @if($product->lastinventory->warehouse_image)
                                                    <a href="{{ asset('storage/images/products/' . $product->lastinventory->warehouse_image) }}" class="product-image-link">
                                                        <span class="badge rounded-pill badge-light-primary mt-1">View Warehouse Image</span>
                                                    </a>
                                                @endif
                                            </div>

                                            
                                                                                       

                                            <div class="content-header-right mt-2 text-md-end col-md-12 col-12 d-md-block d-none">
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
<!-- Bootstrap Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Product Image" class="img-fluid" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function limitDecimalPlaces(event) {
        let value = event.target.value;
        // Limit input to two decimal places
        if (value.includes('.')) {
            let parts = value.split('.');
            if (parts[1].length > 2) {
                event.target.value = parseFloat(value).toFixed(2);
            }
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageLinks = document.querySelectorAll('.product-image-link');
        imageLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const imageUrl = this.href;
                const modalImage = document.getElementById('modalImage');
                modalImage.src = imageUrl;
                const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
            });
        });
    });
</script>
    <!-- END: Content-->
</x-app-layout>
