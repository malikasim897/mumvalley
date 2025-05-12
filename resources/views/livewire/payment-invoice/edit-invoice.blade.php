<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="mb-1">Update Payment Invoice ({{ $invoice->uuid }})</h4>
                </div>
                <div class="container-fluid text-start">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-2 col-md-2">
                                    <label for="">Rows</label>
                                    <select id="orderPerPage" class="form-control text-start px-2" wire:model.live="orderPerPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="col-5 col-md-5">
                                    <label for="">From Date</label>
                                    <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" type="text" autocomplete="off">
                                </div>
                                <div class="col-5 col-md-5">
                                    <label>End Date</label>
                                    <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" type="text" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <hr class="mt-0">
                        @if($errors->count())
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
                            <h4 class="mt-1 mb-1">Orders Added in the Invoice</h4>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label class="checkbox-wrapper">
                                                        <input class="form-control order-select-all" type="checkbox" id="checkAll">
                                                        <span class="custom-checkbox"></span>
                                                    </label>
                                                </th>
                                                <th>#</th>
                                                <th>Order Date</th>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <th>User Name</th>
                                                @endif
                                                <th>Order NO.</th>
                                                <th>Delivered Units</th>
                                                <th>AMOUNT (Rs.)</th>
                                                <th>Status</th>
                                                <th>Details</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <th>
                                                        <input type="text" wire:model.live="user_name" class="form-control form-control-sm">
                                                    </th>
                                                @endif
                                                <th>
                                                    <input type="text" wire:model.live="warehouse_number" class="form-control form-control-sm">
                                                </th>
                                                <th>
                                                    <input type="text" wire:model.live="shipped_units" class="form-control form-control-sm">
                                                </th>
                                                <th>
                                                    <input type="text" wire:model.live="amount" class="form-control form-control-sm">
                                                </th>
                                                <th></th>
                                                <th></th>
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
                                                    <td>{{ $order->date }}</td>
                                                    @if(auth()->user()->hasRole('admin'))
                                                        <td>{{ $order->user->name }}</td>
                                                    @endif
                                                    <td><a type="button" class="text-primary">{{ $order->order_number }}</a></td>
                                                    <td>{{ $order->items->sum('delivered_units') }}</td>
                                                    <td>{{ $order->total_amount }}</td>
                                                    <td>
                                                        @if($order->order_status === 'cancelled')
                                                            <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                                                        @elseif($order->order_status === 'in_process')
                                                            <span class="badge rounded-pill badge-light-secondary me-1">In Process</span>
                                                        @elseif($order->order_status === 'delivered')
                                                            <span class="badge rounded-pill badge-light-primary me-1">Delivered</span>
                                                        @elseif($order->order_status === 'completed')
                                                            <span class="badge rounded-pill badge-light-success me-1">Completed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="getInvoice" id="getInvoice" data-id={{ $order->id }}>
                                                            <span class="badge rounded-pill badge-light-dark me-1">View</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="9">
                                                    <hr class="text-dark">
                                                    <h4 class="mt-1 mb-1">Available Orders</h4>
                                                </td>
                                            </tr>

                                            @foreach($orders as $order)
                                            <tr class="{{ in_array($order->id, $selectedOrders) ? 'bg-light-info' : '' }}">
                                                <td>
                                                    <label class="checkbox-wrapper">
                                                        <input class="order-select" type="checkbox" name="orders[]" id="{{ $order->id }}" 
                                                            wire:click="toggleOrderSelection({{ $order->id }})"
                                                            {{ in_array($order->id, $selectedOrders) ? 'checked' : '' }} value="{{ $order->id }}">
                                                        <span class="custom-checkbox"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->date }}</td>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <td>{{ $order->user->name }}</td>
                                                @endif

                                                <td><a type="button" class="text-primary">{{ $order->order_number }}</a></td>
                                                <td>{{ $order->items->sum('delivered_units') }}</td>
                                                <td>{{ $order->total_amount }}</td>
                                                <td>
                                                    @if($order->order_status === 'cancelled')
                                                        <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                                                    @elseif($order->order_status === 'in_process')
                                                        <span class="badge rounded-pill badge-light-secondary me-1">In Process</span>
                                                    @elseif($order->order_status === 'delivered')
                                                        <span class="badge rounded-pill badge-light-primary me-1">Delivered</span>
                                                    @elseif($order->order_status === 'completed')
                                                        <span class="badge rounded-pill badge-light-success me-1">Completed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="getInvoice" id="getInvoice" data-id={{ $order->id }}>
                                                        <span class="badge rounded-pill badge-light-dark me-1">View</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-sm btn-primary float-right">Update Invoice</button>
                                </div>
                            </div>
                            <div class="float-right mt-3">
                                {{ $orders->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.livewire.loading')

    <script>
        $(document).ready(function () {
            function initializeOrderSelection() {
                // Handle the "checkAll" functionality
                $('#checkAll').on('change', function() {
                    let isChecked = $(this).is(':checked');
                    $('.order-select').each(function() {
                        $(this).prop('checked', isChecked);
                        $(this).closest('tr').toggleClass('bg-light-info', isChecked);
                    });
                });

                // Handle individual row selection
                $('.order-select').on('change', function() {
                    let isChecked = $(this).is(':checked');
                    $(this).closest('tr').toggleClass('bg-light-info', isChecked);
                });

                // Highlight initial selections based on the selected orders
                @foreach ($selectedOrders as $selectedOrder)
                    $('#{{ $selectedOrder }}').prop('checked', true).closest('tr').addClass('bg-light-info');
                @endforeach
            }

            // Initialize after document is ready
            initializeOrderSelection();

            // Reinitialize after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                initializeOrderSelection();
            });

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
        });
    </script>
</div>
