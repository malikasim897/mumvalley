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
                            <h2 class="content-header-title float-start mb-0">Products</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Products
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
        @include('layouts.validation.message')
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0"><h4>List of All Products</h4>
                                @if (auth()->user()->hasRole('admin'))
                                    <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                                        <div class="">
                                            <a href="{{ route('products.create') }}"
                                                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">+ New Product</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                @livewire('products.index')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- Price Modal -->
    <div class="modal fade" id="editPriceModal" tabindex="-1" aria-labelledby="editPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPriceModalLabel">Update Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="priceForm">
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Per Unit Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productPrice" name="price" step="0.01">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePriceButton">Save Price</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Open modal and fetch product price
            $('.edit-price-btn').on('click', function(e) {
                e.preventDefault();

                var productId = $(this).data('id');
                var productName = $(this).data('name');
                var productUniqueId = $(this).data('unique');

                // Set modal title to include Product Name and ID
                $('#editPriceModalLabel').text('Update Price of ' + productName + ' | ' + productUniqueId);

                $('#editPriceModal').modal('show');

                // Store the product ID in the save button
                $('#savePriceButton').data('id', productId);

                // Fetch the price
                $.ajax({
                    url: '/product/' + productId + '/price',
                    method: 'GET',
                    success: function(response) {
                        $('#productPrice').val(response.price || '');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                });
            });
    
            // Save the updated price
            $('#savePriceButton').on('click', function() {
                var productId = $(this).data('id'); // Get the product ID from the save button
                var price = $('#productPrice').val();
    
                $.ajax({
                    url: '/product/' + productId + '/price',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        price: price
                    },
                    success: function(response) {
                        $('#editPriceModal').modal('hide');
    
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Something went wrong!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    
    <!-- parcel shipping modal -->
    <div class="modal fade" id="parcelShippingInfo" tabindex="-1" aria-labelledby="parcelShippingInfoTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                    <h1 class="text-center mb-1" id="parcelShippingInfoTitle">Parcel shipping Info</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- / parcel shipping modal -->
    <script>
        function deleteParcel($id) {
            Swal.fire({
                text: 'Are you sure you want to delete this parcel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-2'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $('#delete-form' + $id).submit();
                }
            });
        }
    </script>
</x-app-layout>
