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
                            <h2 class="content-header-title float-start mb-0">Update Stock</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('products.index') }}">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active">Stock
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-left col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">
                        Back to List
                    </a>
                </div>
            </div>
            <div class="content-body">
                @if(!$product->lastinventory->status)
                    <section id="multiple-column-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- @include('layouts.validation.message') --}}
                                        <form class="form" action="{{ route('products.update', $product->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                @if (auth()->user()->hasRole('user'))
                                                    <input type="hidden" id="user_id" name="user_id"
                                                        value="{{ auth()->user()->id }}">
                                                @endif
                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="product_name">Product Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="product_name" class="form-control" name="product_name" value="{{ $product->name ?? old('name') }}"  />
                                                        <x-input-error :messages="$errors->get('product_name')" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="purchased_units">Purchased Units<span class="text-danger">*</span></label>
                                                        <input type="number" id="purchased_units" class="form-control" name="purchased_units" value="{{ $product->lastinventory->purchased_units ?? old('dispatched_units') }}"  />
                                                        <x-input-error :messages="$errors->get('purchased_units')" />
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-12 mb-1 mb-sm-0">
                                                    <label for="product_image" class="form-label">Product Image</label>
                                                    <input class="form-control" type="file" id="product_image" name="product_image">
                                                    <x-input-error :messages="$errors->get('product_image')" />
                                                    @if($product->lastinventory->product_image)
                                                        <a href="{{ asset('storage/images/products/' . $product->lastinventory->product_image) }}" class="product-image-link">
                                                            <span class="badge rounded-pill badge-light-primary mt-1">View Uploaded Image</span>
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
                @else
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex p-1 m-0">
                                    <h4>Add New Stock of {{ $product->name }}</h4>
                                </div>
                                <div class="card-body">
                                    {{-- @include('layouts.validation.message') --}}
                                    <form class="form" action="{{ route('product.units.dispatch') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            @if (auth()->user()->hasRole('user'))
                                                <input type="hidden" id="user_id" name="user_id"
                                                    value="{{ auth()->user()->id }}">
                                            @endif
                                            <input type="hidden" id="product_id" name="product_id"
                                                    value="{{ $product->id }}">
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="purchased_units">Total Units<span class="text-danger">*</span></label>
                                                    <input type="number" id="purchased_units" class="form-control" name="purchased_units" value=""  required/>
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

                                            <div class="col-lg-4 col-md-12 mb-1 mb-sm-0">
                                                <label for="product_image" class="form-label">Product Image</label>
                                                <input class="form-control" type="file" id="product_image" name="product_image">
                                                <x-input-error :messages="$errors->get('product_image')" />
                                            </div>

                                            <div class="content-header-right mt-2 text-md-end col-md-12 col-12 d-md-block d-none">
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary me-1">Add Stock</button>
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
                @endif
                <livewire:products.inventorytable :product="$product" />
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
