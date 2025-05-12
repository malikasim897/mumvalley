<x-app-layout>
    <style>
        button.btn.btn-outline-secondary.dropdown-toggle.show-arrow {
            padding: 0.4em 1.3em;
        }
        /* Hide the default checkbox */
        input[type="checkbox"] {
            display: none;
        }

        /* Custom checkbox appearance */
        .checkbox-wrapper {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            background-color: #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            position: relative;
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

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Orders</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card table-responsive">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    Update Invoice
                                </h4>
                                <a href="{{ route('payment-invoices.index') }}" class="btn btn-sm btn-primary">
                                    Back to List
                                </a>
                            </div>
                            <div class="card-content">
                                <div class="">
                                    <hr>
                                    @if( $errors->count() )
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    <form action="{{ route('payment-invoices.invoice.update',$invoice) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <h3 class="mt-1 mb-1">Orders in Invoice</h3>
                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>#</th>
                                                            <th>Product Id</th>
                                                            <th>Product Name</th>
                                                            <th>Shipped Units</th>
                                                            <th>Warehouse No.</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($invoice->orders as $order)
                                                            <tr class="selectable cursor-pointer {{ true? 'bg-light-info' : '' }}">
                                                                <td>
                                                                    <div class="checkbox-wrapper">
                                                                        <input class="form-control" type="checkbox" name="orders[]" id="{{$order->id}}" checked value="{{$order->id}}">
                                                                        <label for="{{ $order->id }}" class="custom-checkbox"></label>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $order->items->first()->product->unique_id }}</td>
                                                                <td>{{ $order->items->first()->product->name }}</td>
                                                                <td>{{ $order->items->first()->shipped_units }}</td>
                                                                <td><a type="button" class="text-primary">{{ $order->warehouse_number }}</a></td>
                                                                <td>₤ {{ $order->total_amount }}</td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td colspan="7">
                                                                <hr class="text-dark">
                                                            </td>
                                                        </tr>

                                                        @foreach ($orders as $order)
                                                            <tr class="selectable cursor-pointer {{ request('order') == $order->id ? 'bg-light-info' : '' }}">
                                                                <td>
                                                                    <div class="checkbox-wrapper">
                                                                        <input class="form-control" type="checkbox" name="orders[]" id="{{$order->id}}" {{ request('order') == $order->id ? 'checked': '' }} value="{{$order->id}}">
                                                                        <label for="{{ $order->id }}" class="custom-checkbox"></label>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $order->items->first()->product->unique_id }}</td>
                                                                <td>{{ $order->items->first()->product->name }}</td>
                                                                <td>{{ $order->items->first()->shipped_units }}</td>
                                                                <td><a type="button" class="text-primary">{{ $order->warehouse_number }}</a></td>
                                                                <td>₤ {{ $order->total_amount }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row col-12">
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button class="btn btn-primary btn-sm mb-1 mb-md-0 waves-effect waves-light me-1">Update Invoice</button>
                                                {{-- <a href="{{ route('payment-invoices.invoice.checkout.index', Crypt::encrypt($invoice->id)) }}" class="btn btn-success btn-sm mb-1 mb-md-0 waves-effect waves-light">Pay Orders</a> --}}
                                            </div>
                                        </div>                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- View Order Modal -->
    <div class="modal fade text-start" id="viewOrderModal" tabindex="-1" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalData"></div>
                </div>
            </div>
        </div>
    </div>
    <!--/ View Order Modal -->

    @include('orders.partials.render_invoice_js')
    @include('components.loading')
   
    <script>
        $(document).ready(function() {
            // Handle checkbox click to toggle row highlight
            $('input[type="checkbox"]').on('click', function(e) {
                e.stopPropagation(); // Prevent the row click event from triggering
    
                const row = $(this).closest('tr');
                
                // Toggle the row highlight based on the checkbox state
                if ($(this).prop('checked')) {
                    row.addClass('bg-light-info');
                } else {
                    row.removeClass('bg-light-info');
                }
            });
    
            // Handle row click to toggle checkbox state and row highlight
            $('tr.selectable').on('click', function(e) {
                // Check if the click is on a checkbox
                if ($(e.target).is('input[type="checkbox"]')) {
                    return;
                }
    
                const checkbox = $(this).find('input[type="checkbox"]');
    
                // Toggle the checkbox state
                checkbox.prop('checked', !checkbox.prop('checked'));
    
                // Toggle the row highlight based on the checkbox state
                if (checkbox.prop('checked')) {
                    $(this).addClass('bg-light-info');
                } else {
                    $(this).removeClass('bg-light-info');
                }
            });
        });
    </script> 
 
</x-app-layout>

