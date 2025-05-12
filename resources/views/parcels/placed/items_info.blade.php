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
                            <div class="breadcrumb-wrapper d-flex justify-content-between align-items-center">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{ route('products.index') }}">Products</a></li>
                                    <li class="breadcrumb-item active">Order</li>
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
                <!-- Horizontal Wizard -->
                <section class="mb-5 min-vh-50">
                    <div class="bs-stepper">
                        @include('parcels.partials.order_header')
                        <div class="bs-stepper-content">
                            <div id="sender-details">
                                <div class="content-header"></div>
                                <form id="orderDetailsForm" action="{{ route('product.order.details.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Shipment Label Upload -->
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label" for="shipment_label">Upload Shipment Label<span class="text-danger">*</span></label>
                                            <input type="file" name="shipment_label" id="shipment_label" class="form-control form-control-md" required />
                                            <x-input-error class="" :messages="$errors->get('shipment_label')" />
                                        </div>
                                    </div>

                                    <!-- Product Select Dropdown and Quantity Input -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="product_select" class="form-label">Select Product<span class="text-danger">*</span></label>
                                            <select id="product_select" class="form-control select2" name="products[]">
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
                                        <div class="col-md-3">
                                            <label for="shipped_units" class="form-label">Shipped Units<span class="text-danger">*</span></label>
                                            <input type="number" id="shipped_units" class="form-control form-control-md" name="shipped_units" min="1" />
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <button type="button" id="addProductBtn" class="btn btn-secondary">Add Item</button>
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
                                                            <th>Product Image</th>
                                                            <th>Product Name</th>
                                                            <th>Shipped Units</th>
                                                            <th>Remaining Units</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                        <!-- Product rows will be appended here dynamically -->
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
    <!-- END: Content-->
</x-app-layout>

@include('components.loading')

<!-- Scripts -->
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#product_select').select2();

        // Load existing products from localStorage
        let products = JSON.parse(localStorage.getItem('products')) || [];

        // Function to load products into the table and hidden inputs
        function loadProducts() {
            // Clear existing rows
            $('#productTable tbody').empty();

            // Loop through products and append to the table
            products.forEach(product => {
                let row = `
                    <tr data-product-id="${product.productId}">
                        <td></td>
                        <td>
                            <img src="{{ asset('storage/images/products/' . ($product->latestConfirmedInventory->product_image ?? optional($product)->image ?? 'default.png')) }}"
                                 alt="Product Image" class="product-img" style="width: 50px; height: 50px; margin-right: 10px;">
                        </td>
                        <td style="vertical-align: middle;">${product.productName}</td>
                        <td style="vertical-align: middle;">${product.shippedUnits}</td>
                        <td style="vertical-align: middle;">${product.remainingUnits}</td>
                        <td style="vertical-align: middle;">
                            <button type="button" class="btn btn-sm btn-warning editProductBtn">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger deleteProductBtn">Delete</button>
                        </td>
                    </tr>
                `;
                $('#productTable tbody').append(row);

                // Create hidden inputs for the product
                $('<input>').attr({
                    type: 'hidden',
                    name: 'products[]',
                    value: JSON.stringify({ productId: product.productId, shippedUnits: product.shippedUnits })
                }).appendTo('#orderDetailsForm');
            });
            updateRowNumbering();
        }

        // Load existing products into the table on page load
        loadProducts();

        // Add numbering to rows
        function updateRowNumbering() {
            $('#productTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Add product row
        $('#addProductBtn').on('click', function() {
            let productId = $('#product_select').val();
            let productName = $('#product_select option:selected').text();
            let shippedUnits = parseInt($('#shipped_units').val(), 10);
            let availableUnits = $('#product_select option:selected').data('units');
            let remainingUnits = availableUnits - shippedUnits;

            // Check for invalid input
            if (!productId || !shippedUnits || shippedUnits <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: 'Please select a valid product and enter a valid shipped unit quantity.'
                });
                return;
            }

            // Check if shipped units exceed remaining units
            if (shippedUnits > availableUnits) {
                Swal.fire({
                    icon: 'error',
                    title: 'Exceeds Remaining Units',
                    text: 'The shipped units cannot be greater than the available units (' + availableUnits + ').'
                });
                return;
            }

            // Prevent duplicate product entries
            if (products.find(p => p.productId == productId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Product already added!',
                    text: 'This product is already added to the order.'
                });
                return;
            }

            // Add to products array
            products.push({
                productId: productId,
                productName: productName,
                shippedUnits: shippedUnits,
                remainingUnits: remainingUnits
            });

            // Append the product row to the table
            let row = `
                <tr data-product-id="${productId}">
                    <td></td>
                    <td>
                        <img src="{{ $product->latestConfirmedInventory->product_image ? asset('storage/images/products/' . $product->latestConfirmedInventory->product_image) : ($product->image ? asset('storage/images/products/' . $product->image) : asset('storage/images/products/default.png')) }}" 
                                     alt="Product Image" 
                                     class="product-img">
                    </td>
                    <td style="vertical-align: middle;">${productName}</td>
                    <td style="vertical-align: middle;">${shippedUnits}</td>
                    <td style="vertical-align: middle;">${remainingUnits}</td>
                    <td style="vertical-align: middle;">
                        <button type="button" class="btn btn-sm btn-warning editProductBtn">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger deleteProductBtn">Delete</button>
                    </td>
                </tr>
            `;
            $('#productTable tbody').append(row);

            // Create hidden inputs for the product
            $('<input>').attr({
                type: 'hidden',
                name: 'products[]',
                value: JSON.stringify({ productId: productId, shippedUnits: shippedUnits })
            }).appendTo('#orderDetailsForm');

            // Save products to localStorage
            localStorage.setItem('products', JSON.stringify(products));

            // Update row numbering
            updateRowNumbering();

            // Reset fields
            $('#shipped_units').val('');
            $('#product_select').val(null).trigger('change');
        });

        // Edit product row
        $(document).on('click', '.editProductBtn', function() {
            let row = $(this).closest('tr');
            let productId = row.data('product-id');
            let product = products.find(p => p.productId == productId);

            // Populate fields with existing values
            $('#product_select').val(product.productId).trigger('change');
            $('#shipped_units').val(product.shippedUnits);

            // Remove the row
            row.remove();
            products = products.filter(p => p.productId != productId); // Remove from array

            // Remove the hidden input associated with the deleted product
            $('input[name="products[]"]').each(function() {
                let hiddenInputValue = JSON.parse($(this).val());
                if (hiddenInputValue.productId == productId) {
                    $(this).remove(); // Remove the hidden input
                }
            });
            updateRowNumbering();
            localStorage.setItem('products', JSON.stringify(products)); // Update localStorage
        });

        // Delete product row
        $(document).on('click', '.deleteProductBtn', function() {
            let row = $(this).closest('tr');
            let productId = row.data('product-id');

            // Confirmation for product deletion
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will remove the product from the order!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove row from table and product array
                    products = products.filter(p => p.productId != productId);
                    row.remove();

                    // Remove the hidden input associated with the deleted product
                    $('input[name="products[]"]').each(function() {
                        let hiddenInputValue = JSON.parse($(this).val());
                        if (hiddenInputValue.productId == productId) {
                            $(this).remove(); // Remove the hidden input
                        }
                    });

                    // Update localStorage
                    localStorage.setItem('products', JSON.stringify(products));

                    // Update row numbering
                    updateRowNumbering();

                    Swal.fire('Deleted!', 'Product has been removed.', 'success');
                }
            });
        });
    });
</script>



