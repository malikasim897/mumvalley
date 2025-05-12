<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row col-12">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{ route('products.index') }}">Products</a></li>
                                    <li class="breadcrumb-item active">Order</li>
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
                <!-- Horizontal Wizard -->
                <section class="mb-5 min-vh-50">
                    <div class="bs-stepper">
                        @include('parcels.partials.order_header')
                        <div class="bs-stepper-content">
                            <div id="sender-details">
                                <div class="content-header"></div>
                                <form id="orderDetailsForm" action="{{ route('product.order.details.edit', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Shipment Label Upload -->
                                    <div class="row mb-2">
                                        <div class="col-sm-4 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="user_id">Select User<span class="text-danger">*</span></label>
                                                <select id="user_select" name="user_id" class="select2 form-select" required>
                                                    <option value="">Select User</option>
                                                    @foreach ($users as $key => $user)
                                                        <option value="{{ $user['id'] }}" {{ isset($order) && $order->user_id == $user['id'] ? 'selected' : '' }}>
                                                            {{ $user['name'] }} | {{ $user['po_box_number'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error class="" :messages="$errors->get('user_id')" />
                                            </div>
                                        </div>
                                        
                                        {{-- <div class="col-md-4">
                                            <label class="form-label" for="shipment_label">Upload Shipment Label<span class="text-danger">*</span></label>
                                            <input type="file" name="shipment_label" id="shipment_label" class="form-control form-control-md" />
                                            <x-input-error class="" :messages="$errors->get('shipment_label')" />
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            @if($order->shipment_label)
                                                <a href="{{ asset('storage/labels/' . $order->shipment_label) }}" target="_blank">
                                                    <span class="btn btn-sm btn-secondary rounded-pill btn-light-info" style="margin-top:8px;">View Uploaded Label</span>
                                                </a>
                                            @endif
                                        </div> --}}
                                    </div>

                                    <!-- Product Select Dropdown and Quantity Input -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="product_select" class="form-label">Select Product<span class="text-danger">*</span></label>
                                            <select id="product_select" class="form-control select2" name="product">
                                                <option value="">-- Select product --</option>
                                                @foreach($products as $product)
                                                    @if(optional(optional($product)->latestConfirmedInventory)->remaining_units > 0)
                                                        <option value="{{ $product->id }}" data-units="{{ optional(optional($product)->latestConfirmedInventory)->remaining_units }}">
                                                            {{ $product->name }} - ({{ $product->unique_id }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>                                            
                                        </div>
                                        <div class="col-md-2">
                                            <label for="delivered_units" class="form-label">Delivered Units<span class="text-danger">*</span></label>
                                            <input type="number" id="delivered_units" class="form-control form-control-md" name="delivered_units" min="1" />
                                        </div>
                                        <div class="col-md-2">
                                            <label for="returned_units" class="form-label">Returned Units<span class="text-danger">*</span></label>
                                            <input type="number" id="returned_units" class="form-control form-control-md" name="returned_units" value="0"/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="unit_price" class="form-label">Unit Price<span class="text-danger">*</span></label>
                                            <input type="number" id="unit_price" class="form-control form-control-md" name="unit_price"/>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <button type="button" id="addProductBtn" class="btn btn-primary">Add Item</button>
                                        </div>
                                    </div>                                    

                                    <!-- Buttons below the table -->
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-between mt-1">
                                            <a href="{{ route('orders.index') }}" class="btn btn-success">
                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </a>
                                            <button class="btn btn-primary btn-next" type="submit">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Responsive Product Table -->
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="productTable">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>#</th>
                                                            <th style="text-align: left;">Product</th>
                                                            <th>Delivered Units</th>
                                                            <th>Returned Units</th>
                                                            <th>Unit Price (Rs.)</th>
                                                            <th>Total Price (Rs.)</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                        @foreach($order->items as $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td style="text-align: left;">
                                                                    <img src="{{ $item->productInventory->product_image ? asset('storage/images/products/' . $item->productInventory->product_image) : (optional($item)->product->image ? asset('storage/images/products/' . $item->product->image) : asset('storage/images/products/default.png')) }}" 
                                                                        alt="Product Image" 
                                                                        class="product-img">
                                                                    &nbsp; <span class="product-name">{{ $item->product->name }}</span>
                                                                </td>
                                                                <td>{{ $item->product->type === 'pack_of_6' ? $item->delivered_units / 6 : ($item->product->type === 'pack_of_12' ? $item->delivered_units / 12 : $item->delivered_units) }}</td>
                                                                <td>{{ $item->returned_units }}</td>
                                                                <td>{{ $item->unit_price }}</td>
                                                                <td>{{ $item->total_price }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-warning editProductBtn" data-item-id="{{ $item->id }}">Edit</button>
                                                                    <button type="button" class="btn btn-sm btn-danger deleteProductBtn" data-item-id="{{ $item->id }}">Delete</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
    <!-- Edit Item Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Order Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for Editing Product -->
                    <form id="editProductForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="edit_product_name" class="form-label">Product</label>
                            <input type="text" id="edit_product_name" class="form-control" disabled />
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_shipped_units" class="form-label">Delivered Units</label>
                            <input type="number" id="edit_shipped_units" name="shipped_units" class="form-control" min="1" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_returned_units" class="form-label">Returned Units</label>
                            <input type="number" id="edit_returned_units" name="returned_units" class="form-control" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_unit_price" class="form-label">Unit Price (Rs.)</label>
                            <input type="number" id="edit_unit_price" name="unit_price" class="form-control" min="1" required />
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END: Content-->
</x-app-layout>

@include('components.loading')

<!-- Scripts -->
<script>

    $(document).on('click', '.editProductBtn', function() {
        // Get product details from the clicked button's row
        const row = $(this).closest('tr');
        
        // Get product name and shipped units from the row
        const productName = row.find('td:nth-child(2) .product-name').text().trim();  // Assuming product name is in the 2ndd column
        const shippedUnits = row.find('td:nth-child(3)').text(); // Assuming shipped units are in the 3rd column
        const returnedUnits = row.find('td:nth-child(4)').text();// Assuming returned units are in the 4th column
        const unitPrice = row.find('td:nth-child(5)').text(); // Assuming unit price is in the 5th column
        
        // Get the itemId directly from the clicked button
        const itemId = $(this).data('item-id');  // Use $(this) to get data from the clicked button
        
        // Populate modal fields with existing values
        $('#edit_product_name').val(productName);
        $('#edit_shipped_units').val(shippedUnits);
        $('#edit_returned_units').val(returnedUnits);
        $('#edit_unit_price').val(unitPrice);
        
        // Show the modal
        $('#editProductModal').modal('show');
        
        // Handle form submission
        $('#editProductForm').off('submit').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/orders/{{ $order->id }}/edit-item/${itemId}`,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire('Success', response.success, 'success').then(() => {
                        location.reload();  // Reload the page after success to update the order list
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.error, 'error');
                }
            });
        });
    });



    $(document).on('click', '.deleteProductBtn', function() {
        const itemId = $(this).data('item-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be removed from the order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/orders/{{ $order->id }}/delete-item/${itemId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            response.success,
                            'success'
                        );
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the item.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        $('#addProductBtn').click(function() {
        // Get selected product and shipped units values
        const userId = $('#user_select').val();
        const productId = $('#product_select').val();
        const productText = $('#product_select option:selected').text();
        const remainingUnits = $('#product_select option:selected').data('units');
        const shippedUnits = $('#delivered_units').val();
        const unitPrice = $('#unit_price').val();
        const returnedUnits = $('#returned_units').val();
        // Validate the inputs
        if (!productId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a product.'
            });
            return;
        }

        if (!shippedUnits || shippedUnits <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a valid number of delivered units.'
            });
            return;
        }

        if (!unitPrice) {
            Swal.fire({
                icon: 'warning',
                title: 'Product Unit Price',
                text: 'Please enter the product unit price.'
            });
            return;
        }

        if (shippedUnits > remainingUnits) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Delivered units exceed the remaining units (${remainingUnits}).`
            });
            return;
        }

        // Proceed with AJAX submission if validation passes
        $.ajax({
            url: '/orders/{{ $order->id }}/add-item',  // Adjust the URL to your needs
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                user_id: userId,
                product_id: productId,
                delivered_units: shippedUnits,
                unit_price: unitPrice,
                returned_units: returnedUnits,

            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Product added to order successfully!',
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();  // Reload the page after success to update the order list
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong. Please try again.'
                    });
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.error || 'Something went wrong. Please try again.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });

    });

    });



</script>



