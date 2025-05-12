<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        @media print {
            /* Hide elements that should not be printed */
            .no-print, 
            .header-navbar-shadow, 
            .content-header, 
            .breadcrumb-wrapper, 
            .btn, 
            .card-header, 
            .footer {
                display: none !important;
            }
    
            body, html {
                margin: 0;
                padding: 0;
                width: 100%;
                overflow: hidden; /* Prevent scrollbars in print */
                font-family: 'Arial', sans-serif;
                color: #000; /* Set all text to black */
            }
    
            body * {
                visibility: hidden; /* Hide all elements initially */
            }
    
            #invoice-wrapper, #invoice-wrapper * {
                visibility: visible; /* Show only the invoice content */
            }
    
            #invoice-wrapper {
                position: absolute;
                left: 0;
                top: -30px;
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
            }
    
            /* Ensure table prints properly without scrollbars */
            .table-responsive-md {
                overflow: visible !important;
            }
    
            /* Table appearance */
            table {
                width: 100%;
                font-size: 11px; /* Decrease font size for table rows */
                margin-bottom: 20px;
                margin-left: -26px;
                margin-top: -45px;
                border-collapse: collapse; /* Collapse borders to avoid spacing issues */
            }
    
            thead {
                font-size: 12px !important; /* Decrease font size for table rows */
                border: 1px solid #000; /* Border for thead */
                background-color: #f0f0f0; /* Light grey background for thead */
            }
    
            th, td {
                padding: 10px 15px; /* Add padding */
                text-align: center; /* Center align text in th and td */
                vertical-align: middle; /* Align content in the middle */
                color: #000; /* Ensure text is black */
                /* border: 1px solid #000; */
                font-size: 12px !important; /* Decrease font size for table rows */
                 /* Add border to cells */
            }
    
            th {
                background-color: #f8f9fa; /* Light background for header */
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 12px !important; /* Decrease font size for table rows */
                
            }
    
            /* Add subtle lines between rows */
            tr:not(.total-row) {
                border-bottom: 1px solid #ddd; /* Light line between rows */
            }
    
            /* Styling for total amount row */
            .total-row {
                font-weight: bold;
                border-top: 2px solid #000;
                background-color: #f2f2f2; /* Slightly different background for total row */
            }
    
            .total-row td {
                text-align: center; /* Center align text in total row */
                font-size: 14px; /* Adjust font size for the total row */
                padding-top: 10px;
            }
    
            .total-label {
                text-align: center; /* Center align the label */
                padding-right: 20px;
            }
    
            .total-amount {
                font-size: 14px;
                font-weight: bold;
                color:black !important;
            }
    
            /* Ensure the total amount is visible */
            .invoice-total {
                margin-top: 10px;
                border-top: 2px solid #000;
                width: 100%;
            }
    
            /* General styling for making the table look clean */
            table, th, td {
                /* border: 1px solid #000; */
            }
            table, th {
                border: 1px solid #000;
            }
        }
    
        /* General styles for the web version */
        .invoice-total {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
            padding-top: 10px;
            margin-top: 20px;
            width: 100%;
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
                            <h2 class="content-header-title float-start mb-0">Orders</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                                    <li class="breadcrumb-item active">View</li>
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
                                    <div id="invoice-wrapper" class="wizard print">
                                        <!-- invoice functionality end -->
                                        <!-- invoice page -->
                                        <section class="card invoice-page">
                                            <div id="invoice-template" class="card-body">
                                                <!-- Invoice Company Details -->
                                                <div id="invoice-company-details" class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/logo/logo2.png') }}" alt="SalarLink Logo" style="max-width:150px;">
                                                    </div>
                                                    <div class="text-right">
                                                        <h3>Payment Invoice</h3>
                                                        <div class="invoice-details mt-1">
                                                            <h6 class="mb-0">INVOICE NO.</h6>
                                                            <p>{{ $invoice->uuid }}</p>
                                                            <h6 class="mt-1 mb-0">INVOICE DATE</h6>
                                                            <p>{{ optional($invoice->created_at)->format('d M Y') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/ Invoice Company Details -->

                                                <!-- Invoice Recipient Details -->
                                                <div id="invoice-customer-details" class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="text-left">
                                                        <!-- Add recipient details here if needed -->
                                                    </div>
                                                    <div class="text-right">
                                                        <h6>User Account Information</h6>
                                                        <div class="company-info my-1">
                                                            <i data-feather="user" class="me-50"></i> {{ optional($invoice->user)->name }} {{ optional($invoice->user)->last_name }}  - {{ optional($invoice->user)->po_box_number }} <br>
                                                            <i data-feather="mail" class="me-50"></i> {{ optional($invoice->user)->email }} <br>
                                                            <i data-feather="phone" class="me-50"></i> {{ optional($invoice->user)->phone }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/ Invoice Recipient Details -->

                                                <!-- Invoice Items Details -->
                                                <div id="invoice-items-details" class="pt-1 invoice-items-table">
                                                    <div class="row">
                                                        <div class="table-responsive-md col-12">
                                                            <table class="table table-borderless" id="datatable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Warehouse#</th>
                                                                        <th>Product Image</th>
                                                                        <th>Product Name</th>
                                                                        <th>Product Id</th>
                                                                        <th>Shipped Units</th>
                                                                        <th>Order Date</th>
                                                                        <th>Items Amount</th>
                                                                        <th>Order Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($invoice->orders as $order)
                                                                        <tr class="mb-2" style="border: solid 1px;">
                                                                            <td rowspan="{{ $order->items->count() + 1 }}" style="border: solid 1px;"> <!-- Adjusted rowspan to include Paid and Remaining rows -->
                                                                                <a type="button" class="text-primary">{{ $order->warehouse_number }}</a>
                                                                            </td>
                                                                            @foreach ($order->items as $item)
                                                                                <tr style="border: solid 1px;">
                                                                                    <td>
                                                                                        <img src="{{ $item->productInventory->product_image ? asset('storage/images/products/' . $item->productInventory->product_image) : (optional($item)->product->image ? asset('storage/images/products/' . $item->product->image) : asset('storage/images/products/default.png')) }}" 
                                                                                            alt="Product Image" 
                                                                                            class="product-img">
                                                                                    </td>
                                                                                    <td>{{ $item->product->name }}</td>
                                                                                    <td>{{ $item->product->unique_id }}</td>
                                                                                    <td>{{ $item->shipped_units }}</td>
                                                                                    <td>{{ optional($order->created_at)->format('Y-m-d') }}</td>
                                                                                    <td>₤ {{ number_format($item->total_price, 2) }}</td>
                                                                                    @if ($loop->first) <!-- Only show the total amount for the first item row -->
                                                                                        <td rowspan="{{ $order->items->count() }}" style="border: solid 1px;">₤ {{ number_format($order->total_amount, 2) }}</td>
                                                                                    @endif
                                                                                </tr>
                                                                            @endforeach
                                                                        </tr>
                                                                        
                                                                    @endforeach
                                                                    <!-- Paid Amount Row -->
                                                                    <tr class="mt-2 p-0">
                                                                        <td colspan="7" class="text-end"><strong>Paid Amount:</strong></td>
                                                                        <td>₤ {{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                                                                    </tr>
                                                                    <!-- Remaining Amount Row -->
                                                                    <tr>
                                                                        <td colspan="7" class="text-end"><strong>Remaining Amount:</strong></td>
                                                                        <td>₤ {{ number_format($invoice->differnceAmount(), 2) }}</td>
                                                                    </tr>
                                                                    <tr class="border-top-light">
                                                                        <td colspan="7" class="text-center h4" style="border-top: 1px solid !important;">Total Invoice Amount</td>
                                                                        <td class="h4" style="border-top: 1px solid !important;">
                                                                            ₤ {{ number_format($invoice->differnceAmount() ?: $invoice->orders()->sum('total_amount'), 2) }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="no-print mt-2">
                                                    <div class="row justify-content-end">
                                                        <div class="col-12 col-md-7 text-end">
                                                            <a class="btn btn-primary btn-sm" href="{{ route('payment-invoices.index') }}">Back to List</a>
                                                            <button class="btn btn-info btn-sm mb-1 mb-md-0 waves-effect waves-light" onclick="printInvoice();">
                                                                <i data-feather="printer" class="me-50"></i> Print Invoice
                                                            </button>
                                                            
                                                            {{-- @if (!$invoice->isPrePaid())
                                                                <a href="{{ route('payment-invoices.postpaid.export',$invoice) }}" class="btn btn-primary btn-sm mb-1 mb-md-0 waves-effect waves-light">
                                                                    <i class="feather icon-file"></i> @lang('Export')
                                                                </a>
                                                            @endif --}}
                                                            @if (!$invoice->isPaid())
                                                                <a href="{{ route('payment-invoices.invoice.edit', Crypt::encrypt($invoice->id)) }}" class="btn btn-primary btn-sm mb-1 mb-md-0 waves-effect waves-light">
                                                                    <i data-feather="edit" class="me-50"></i> Edit Invoice
                                                                </a>
                                                                <a href="{{ route('payment-invoices.invoice.checkout.index', Crypt::encrypt($invoice->id)) }}" class="btn btn-success btn-sm mb-1 mb-md-0 waves-effect waves-light">
                                                                    <i data-feather="credit-card" class="me-50"></i> Checkout
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </section>
                                    </div>
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
<script>
    function printInvoice() {
        window.print();
    }
</script>

