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
                                    
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
