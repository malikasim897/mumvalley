<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        /* Hide the default checkbox */
        input[type="checkbox"] {
            display: none;
        }
        
        /* Custom checkbox appearance */
        .custom-checkbox {
            position: relative;
            width: 20px;
            height: 20px;
            background-color: #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        /* Checkmark indicator */
        .custom-checkbox::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: #7367F0;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: all 0.3s ease;
        }
        
        /* Spin animation when checked */
        input[type="checkbox"]:checked + .custom-checkbox {
            animation: spin 0.6s ease;
            background-color: #7367F0;
        }
        
        input[type="checkbox"]:checked + .custom-checkbox::after {
            transform: translate(-50%, -50%) scale(1);
        }
        
        /* Spin keyframes */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('products.index') }}">Products</a>
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
                                <form id="orderDetailsForm"
                                    action="{{ route('product.order.details.edit', $order->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label" for="product_name">Product Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="product_name" id="product_name" class="form-control form-control-sm"
                                                placeholder="" value="{{ $product->product_name }}" readonly/>
                                            <x-input-error class="" :messages="$errors->get('product_name')" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="product_code">Product Unique Code<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="product_code" id="product_code" class="form-control form-control-sm"
                                                placeholder="" value="{{ $product->product_unique_id }}" readonly/>
                                            <x-input-error class="" :messages="$errors->get('product_code')" />
                                        </div>
                                        <div class="col-md-3" style="min-height: 85px;">
                                            <label class="form-label" for="remaining_quantity">Available Units<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="remaining_quantity" id="remaining_quantity" class="form-control form-control-sm"
                                                placeholder="" value="{{ optional(optional($product)->latestconfirmedinventory)->remaining_units + $order->shipped_units }}" readonly/>
                                            <x-input-error class="" :messages="$errors->get('remaining_quantity')" />
                                            <p class="mb-0" id="remainingUnitsText" style="display: none; font-size:11px;">Remaining Units: <strong id="remainingUnitsValue"></strong></p>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label class="form-label" for="shipped_units">Units to be Shipped?<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="shipped_units" id="shipped_units" class="form-control form-control-sm" value="{{ $order->shipped_units }}" required oninput="calculateRemainingUnits()"/>
                                            <x-input-error class="" :messages="$errors->get('shipped_units')" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-2" id="fnsku_quantity_div" style="display:none;">
                                            <label class="form-label" for="fnsku_quantity">Fnsku Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="fnsku_quantity" id="fnsku_quantity" class="form-control form-control-sm" value="{{ $order->fnsku_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('fnsku_quantity')" />
                                        </div>
                                        <div class="mb-1 col-md-2" id="bubblewrap_quantity_div" style="display:none;">
                                            <label class="form-label" for="bubblewrap_quantity">Bubblewrap Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="bubblewrap_quantity" id="bubblewrap_quantity" class="form-control form-control-sm" value="{{ $order->bubblewrap_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('bubblewrap_quantity')" />
                                        </div>
                                        <div class="mb-1 col-md-2" id="polybag_quantity_div" style="display:none;">
                                            <label class="form-label" for="polybag_quantity">Polybag Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="polybag_quantity" id="polybag_quantity" class="form-control form-control-sm" value="{{ $order->polybag_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('polybag_quantity')" />
                                        </div>
                                        <div class="mb-1 col-md-2" id="small_box_quantity_div" style="display:none;">
                                            <label class="form-label" for="small_box_quantity">Small Box Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="small_box_quantity" id="small_box_quantity" class="form-control form-control-sm" value="{{ $order->small_box_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('small_box_quantity')" />
                                        </div>
                                        <div class="mb-1 col-md-2" id="medium_box_quantity_div" style="display:none;">
                                            <label class="form-label" for="medium_box_quantity">Medium Box Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="medium_box_quantity" id="medium_box_quantity" class="form-control form-control-sm" value="{{ $order->medium_box_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('medium_box_quantity')" />
                                        </div>
                                        <div class="mb-1 col-md-2" id="large_box_quantity_div" style="display:none;">
                                            <label class="form-label" for="large_box_quantity">Large Box Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="large_box_quantity" id="large_box_quantity" class="form-control form-control-sm" value="{{ $order->large_box_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('large_box_quantity')" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-2" id="additional_units_quantity_div" style="display:none;">
                                            <label class="form-label" for="additional_units_quantity">Additional Units Qty<span class="text-danger">*</span></label>
                                            <input type="number" name="additional_units_quantity" id="additional_units_quantity" class="form-control form-control-sm" value="{{ $order->additional_units_qty }}" />
                                            <x-input-error class="" :messages="$errors->get('additional_units_quantity')" />
                                        </div>
                                    </div>

                                    <div class="row mt-1 mb-1">
                                        <div class="mb-1 col-md-3">
                                            <label for="fnsku" class="d-flex align-items-center">
                                                <input type="checkbox" name="fnsku" id="fnsku" value="1" {{ $order->fnsku ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Fnsku</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="bubblewrap" class="d-flex align-items-center">
                                                <input type="checkbox" name="bubblewrap" id="bubblewrap" value="1" {{ $order->bubblewrap ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Bubblewrap</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="polybag" class="d-flex align-items-center">
                                                <input type="checkbox" name="polybag" id="polybag" value="1" {{ $order->polybag ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Polybag</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="small_box" class="d-flex align-items-center">
                                                <input type="checkbox" name="small_box" id="small_box" value="1" {{ $order->small_box ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Small Box</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="medium_box" class="d-flex align-items-center">
                                                <input type="checkbox" name="medium_box" id="medium_box" value="1" {{ $order->medium_box ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Medium Box</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="large_box" class="d-flex align-items-center">
                                                <input type="checkbox" name="large_box" id="large_box" value="1" {{ $order->large_box ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Large Box</span>
                                            </label>
                                        </div>
                                        <div class="mb-1 col-md-3">
                                            <label for="additional_units" class="d-flex align-items-center">
                                                <input type="checkbox" name="additional_units" id="additional_units" value="1" {{ $order->additional_units ? 'checked' : '' }} />
                                                <span class="custom-checkbox"></span>
                                                <span class="ms-2">Additional Units</span>
                                            </label>
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
<script>
    function calculateRemainingUnits() {
        var availableUnits = parseInt(document.getElementById('remaining_quantity').value) || 0;
        var shippedUnits = parseInt(document.getElementById('shipped_units').value) || 0;
        var remainingUnits = availableUnits - shippedUnits;

        if (shippedUnits > 0) {
        // Display the remaining units text when there is a value
        document.getElementById('remainingUnitsText').style.display = 'block';
        document.getElementById('remainingUnitsValue').innerText = remainingUnits >= 0 ? remainingUnits : 0;
        } else {
            // Hide the remaining units text when the field is empty
            document.getElementById('remainingUnitsText').style.display = 'none';
        }
    }

    // Call the function on page load if shipped_units has a value
    window.onload = function() {
        if (document.getElementById('shipped_units').value) {
            calculateRemainingUnits();
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        const toggleFields = () => {
            const checkboxes = document.querySelectorAll("input[type='checkbox']");
            
            checkboxes.forEach(checkbox => {
                const fieldId = checkbox.name + "_quantity";
                const field = document.getElementById(fieldId);
                
                if (checkbox.checked) {
                    field.parentElement.style.display = "block"; // Show the field
                    field.setAttribute("required", "required");  // Make it required
                } else {
                    field.parentElement.style.display = "none";  // Hide the field
                    field.removeAttribute("required");  // Remove required attribute
                    field.value = "";  // Clear value when unchecked
                }
            });
        };

        // Initial check to hide/show fields based on the checked state
        toggleFields();

        // Attach event listener to each checkbox to toggle fields when changed
        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
            checkbox.addEventListener("change", toggleFields);
        });
    });
</script>
{{-- @include('parcels.partials.order-js') --}}
@include('components.loading')
