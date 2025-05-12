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
                                    <li class="breadcrumb-item active"><a href="{{ route('orders.index') }}">Orders</a></li>
                                    <li class="breadcrumb-item active">Create</li>
                                </ol>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="content-header-left col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">
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
                                        <div class="col-sm-4 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="user_id">Select User<span
                                                        class="text-danger">*</span></label>
                                                <select id="user_select" name="user_id"
                                                    class="select2 form-select" required>
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
                                        {{-- <div class="col-md-4">
                                            <label class="form-label" for="shipment_label">Upload Shipment Label<span class="text-danger">*</span></label>
                                            <input type="file" name="shipment_label" id="shipment_label" class="form-control form-control-md" required />
                                            <x-input-error class="" :messages="$errors->get('shipment_label')" />
                                        </div> --}}
                                    </div>

                                    <!-- Product Select Dropdown and Quantity Input -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="product_select" class="form-label">Select Product<span class="text-danger">*</span></label>
                                            <select id="product_select" class="select2 form-select" name="products[]">
                                                <option value="">Select product</option>
                                                @foreach($products as $product)
                                                    @if(optional(optional($product)->latestConfirmedInventory)->remaining_units > 0)
                                                        <option value="{{ $product->id }}" data-units="{{ optional(optional($product)->latestConfirmedInventory)->remaining_units }}" data-image="{{ $product->image }}">
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
                                                            <th>Product</th>
                                                            <th>Delivered Units</th>
                                                            <th>Unit Price (Rs.)</th>
                                                            <th>Total Price (Rs.)</th>
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
            // console.log(product.image);
            // Loop through products and append to the table
            products.forEach(product => {
                console.log(product.image);
                let imageUrl = product.image
                ? `/storage/images/products/${product.image}`
                : `/storage/images/products/default1.png`;
                let row = `
                    <tr data-product-id="${product.productId}">
                        <td></td>
                        <td>
                            <img src="${imageUrl}" 
                                alt="Product Image" class="product-img" 
                                style="width: 50px; height: 50px; margin-right: 10px;">
                                ${product.productName}
                        </td>
                        <td style="vertical-align: middle;">${product.shippedUnits}</td>
                        <td style="vertical-align: middle;">${product.unitPrice}</td>
                        <td style="vertical-align: middle;">${product.totalPrice}</td>
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
                    value: JSON.stringify({ userId: product.userId, productId: product.productId, shippedUnits: product.shippedUnits, returnedUnits: product.returnedUnits, unitPrice: product.unitPrice, totalPrice:product.totalPrice })
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
            let userId = $('#user_select').val();
            let productId = $('#product_select').val();
            let unitPrice = parseFloat($('#unit_price').val()).toFixed(2);
            let productName = $('#product_select option:selected').text();
            let shippedUnits = parseInt($('#delivered_units').val(), 10);
            let availableUnits = $('#product_select option:selected').data('units');
            let remainingUnits = availableUnits - shippedUnits;
            let returnedUnits = $('#returned_units').val();

            // Check for invalid input
            if (!userId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Selection',
                    text: 'Please select a user to initiate the order.'
                });
                return;
            }

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

            if (!unitPrice) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Product Unit Price',
                    text: 'Please enter the product unit price.'
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

            let totalPrice = (unitPrice * shippedUnits).toFixed(2);

            // Add to products array
            products.push({
                productId: productId,
                productName: productName,
                shippedUnits: shippedUnits,
                remainingUnits: remainingUnits,
                image: $('#product_select option:selected').data('image'),
                totalPrice: totalPrice,
                unitPrice: unitPrice,
                returnedUnits: returnedUnits,
                userId: userId,
            });

            let imageUrl = $('#product_select option:selected').data('image')
            ? `/storage/images/products/${$('#product_select option:selected').data('image')}`
            : `/storage/images/products/default1.png`;

            // Append the product row to the table
            let row = `
                <tr data-product-id="${productId}">
                    <td></td>
                     <td>
                        <img src="${imageUrl}" 
                            alt="Product Image" class="product-img" 
                            style="width: 50px; height: 50px; margin-right: 10px;">
                            ${productName}
                    </td>
                    <td style="vertical-align: middle;">${shippedUnits}</td>
                    <td style="vertical-align: middle;">${unitPrice}</td>
                    <td style="vertical-align: middle;">${totalPrice}</td>
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
                value: JSON.stringify({ userId:userId, productId: productId, shippedUnits: shippedUnits, returnedUnits: returnedUnits, unitPrice: unitPrice, totalPrice: totalPrice })
            }).appendTo('#orderDetailsForm');

            // Save products to localStorage
            localStorage.setItem('products', JSON.stringify(products));

            // Update row numbering
            updateRowNumbering();

            // Reset fields
            $('#delivered_units').val('');
            $('#unit_price').val('');
            $('#returned_units').val(0);
            $('#product_select').val(null).trigger('change');
        });

        // Edit product row
        $(document).on('click', '.editProductBtn', function() {
            let row = $(this).closest('tr');
            let productId = row.data('product-id');
            let product = products.find(p => p.productId == productId);

            // Populate fields with existing values
            $('#product_select').val(product.productId).trigger('change');
            $('#delivered_units').val(product.shippedUnits);
            $('#unit_price').val(product.unitPrice);
            $('#returned_units').val(product.returnedUnits);

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