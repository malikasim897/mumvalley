<div>
    @php
    use App\Enums\OrderStatusEnum;
    use Illuminate\Support\Facades\Crypt;
    @endphp
    @if (auth()->user())
    <div style="text-align: end;margin-right: 1%;font-size: large;color: #7367F0">
        {{-- <p class="mt-2 fw-bold">Current Balance ${{ number_format(auth()->user()->current_balance ?: 0, 2, '.', ',') }}</p> --}}
    </div>
    @endif
    <div class="container-fluid text-start mt-2">
        <div class="row mb-2">
            <div class="col-md-3">
                <h6>Total Confirmed Units: <strong>{{ $total_confirmed_units }}</strong></h6>
            </div>
            <div class="col-md-3">
                <h6>Total Delivered Units: <strong>{{ $total_shipped_units }}</strong></h6>
            </div>
            <div class="col-md-3">
                <h6>Remaining Units: <strong>{{ $remaining_units }}</strong></h6>
            </div>
            <div class="col-md-3">
                <h6>Total Orders: <strong>{{ $total_orders }}</strong></h6>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-3 col-md-3">
                        <label for="">Rows</label>
                        <select id="orderPerPage" class="form-control text-start px-2" wire:model.live="orderPerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-9 col-md-9">
                        <label for="">Search</label>
                        <input wire:model.live="search" id="search" type="text" placeholder="Search..." class="form-control mb-1" />
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Status</label>
                        <select id="status" class="form-control text-start px-2" wire:model.live="status">
                            <option value="all">All</option>
                            <option value="in_process">In Process</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Start Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" autocomplete="off">
                    </div>
                    @if(auth()->user()->hasRole('admin') && $status == 'shipped')
                        <div class="col-md-3 mt-2">
                            <button class="btn btn-sm btn-primary" id="generateInvoiceBtn">Generate Invoice</button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- <div class="col-md-6">
                <div class="row">
                    <form action="{{route('export.order')}}" method="post">
                        <div class="row">
                            @csrf
                            <div class="col-md-5">
                                <label for="">Start Date</label>
                                <input type="date" value="{{ date("Y-m-01") }}" class="form-control flatpickr-basic flatpickr-input active" name="started">
                            </div>
                            <div class="col-md-5">
                                <label>End Date</label>
                                <input type="date" value="{{ date("Y-m-d") }}" class="form-control flatpickr-basic flatpickr-input active" name="ended">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" value="Download" class="btn mt-2 btn-primary btn-sm">
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
    <table class="table" id="ordersTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order No.</th>
                @if(auth()->user()->hasRole('admin'))
                    <th>User Name</th>
                @endif
                {{-- <th>Product ID</th>
                <th>Product Name</th>
                <th>Shipped Units</th> --}}
                <th>Amount (Rs.)</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($orders->count() > 0)
            @foreach ($orders as $key => $order)
            <tr wire:key="item-{{ $order->id }}" class="{{ $order->order_status === 'cancelled' ? 'bg-light-danger' : ($order->paymentInvoices->isNotEmpty() ? 'bg-light-success' : '') }}">
                <td>{{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}</td>
                <td>
                    {{-- <a type="button" class="text-primary" data-bs-toggle="modal" data-bs-target="#parcelShippingInfo">{{ $parcel->wr_number }}</a> --}}
                    <a type="button" class="text-primary">{{ $order->order_number }}</a>
                </td>
                @if(auth()->user()->hasRole('admin'))
                    <td>{{ optional($order->user)->name}}</td>
                @endif
                {{-- <td>{{ $order->items->first()->product->unique_id }}</td>
                <td>{{ $order->items->first()->product->name }}</td>
                <td>{{ $order->items->first()->shipped_units }}</td> --}}
                <td>{{ $order->total_amount }}</td>
                <td>
                    @if($order->payment_status === 'unpaid')
                        <span class="badge rounded-pill badge-light-warning me-1">Unpaid</span>
                    @elseif($order->payment_status === 'partial_paid')
                        <span class="badge rounded-pill badge-light-primary me-1">Partial Paid</span>
                    @elseif($order->payment_status === 'paid')
                        <span class="badge rounded-pill badge-light-success me-1">Paid</span>
                    @elseif($order->payment_status === 'cancelled')
                        <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                    @endif
                </td>
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
                    @canany(['order.edit', 'order.delete'])
                    <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $order->id }}" wire:ignore.self>
                        @php
                            $encryptedId = Crypt::encrypt($order->id);
                        @endphp
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow" data-bs-toggle="dropdown">
                            Action
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">

                            @can('order.view')
                                <a class="dropdown-item getInvoice" id="getInvoice" data-id={{ $order->id }}>
                                    <i data-feather="eye" class="me-50"></i>
                                    <span>View</span>
                                </a>
                            @endcan

                            {{-- @if(auth()->user()->hasRole('user')) --}}
                                @if($order->order_status !== 'cancelled' && $order->order_status == 'shipped' && !$order->isPaid() && $order->user->isActive())
                                    @if ($order->paymentInvoices->isNotEmpty())
                                        <a @if(Auth::user()->isActive()) href="{{ route('payment-invoices.invoice.show', Crypt::encrypt($order->paymentInvoices->first()->id)) }}" @else data-toggle="modal" data-target="#hd-modal" data-url="{{ route('modals.user.suspended') }}" @endif class="dropdown-item" title="Pay Invoice">
                                            <i data-feather="dollar-sign" class="me-50"></i> Pay Invoice
                                        </a>
                                    @else
                                        {{-- <a @if(Auth::user()->isActive()) href="{{ route('payment-invoices.orders.index',['order'=>$encryptedId]) }}" @else data-toggle="modal" data-target="#hd-modal" data-url="{{ route('modals.user.suspended') }}" @endif class="dropdown-item" title="Create Invoice">
                                            <i data-feather="dollar-sign" class="me-50"></i> Create Invoice
                                        </a> --}}
                                    @endif
                                @endif
                            {{-- @endif --}}

                            {{-- @if(!$order->payment_status)
                                <a class="dropdown-item" href="{{ route('product.invoice.details', $encryptedId) }}" id="payOrder">
                                    <i data-feather="dollar-sign" class="me-50"></i>
                                    <span>Pay Inovice</span>
                                </a>
                            @endif --}}
                            @if(!count($order->paymentInvoices) && auth()->user()->hasRole('admin'))
                                @if($order->order_status !== 'cancelled' && !$order->payment_status)
                                    @can('order.edit')
                                        <a class="dropdown-item" href="{{ route('product.order.details', $encryptedId) }}">
                                            <i data-feather="edit" class="me-50"></i>
                                            <span>Edit Order</span>
                                        </a>
                                    @endcan
                                @endif
                            @endif

                            {{-- @if(auth()->user()->hasRole('user'))
                                @if(!count($order->paymentInvoices) && $order->order_status !== 'cancelled' && !$order->payment_status)
                                    @can('order.edit')
                                        <a class="dropdown-item" href="{{ route('product.order.details', $encryptedId) }}">
                                            <i data-feather="edit" class="me-50"></i>
                                            <span>Edit Order</span>
                                        </a>
                                    @endcan
                                @endif
                            @endif --}}


                            @if(!count($order->paymentInvoices) && auth()->user()->hasRole('admin'))
                                @can('order.edit')
                                    @if($order->order_status !== 'cancelled' && !$order->payment_status)
                                        <a class="dropdown-item" href="{{ route('order.cancel', $order->id) }}">
                                            <i data-feather="x-circle" class="me-50"></i>
                                            <span>Cancel Order</span>
                                        </a>
                                    @endif
                                @endcan
                            @endif

                            {{-- @if(auth()->user()->hasRole('user'))
                                @can('order.edit')
                                    @if(!count($order->paymentInvoices) && $order->order_status !== 'cancelled' && !$order->payment_status)
                                        <a class="dropdown-item" href="{{ route('order.cancel', $order->id) }}">
                                            <i data-feather="x-circle" class="me-50"></i>
                                            <span>Cancel Order</span>
                                        </a>
                                    @endif
                                @endcan       
                            @endif --}}

                            @if(auth()->user()->hasRole('admin'))
                                @can('order.delete')
                                    @if($order->order_status !== 'cancelled' && !count($order->paymentInvoices))
                                        <a class="dropdown-item" href="#" id="delete-order{{ $order->id }}" onclick="showDeleteConfirmation({{ $order->id }})">
                                            <i data-feather="trash" class="me-50"></i>
                                            <span>Delete</span>
                                        </a>
                                    @endif
                                @endcan
                            @endif
                        </div>
                    </div>
                    @endcanany
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="10" class="text-center">No Record Found</td>
            </tr>
            @endif
        </tbody>
    </table>
    {{ $orders->links() }}
    @include('layouts.livewire.loading')
    @include('components.loading')
        
    @if(session()->has('errors'))
    @foreach(session('errors')['errors'] as $error)
    <script>
        console.log('confirmInvoice event fired');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $error }}'
        });
    </script>
    @endforeach
    @endif
    <script>
        // Listen for any clicks on the document
        document.addEventListener('click', function(event) {
            // Check if the clicked element has the ID of 'generateInvoiceBtn'
            if (event.target && event.target.id === 'generateInvoiceBtn') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to create an invoice against the shipped orders of this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create invoice'
                }).then((result) => {
                    // If the user confirms
                    if (result.isConfirmed) {
                        // Trigger the Livewire method to generate the invoice
                        @this.generateInvoice();
                    }
                });
            }
        });
    
        // Listen for the 'invoiceGenerated' event from Livewire
        document.addEventListener('invoiceGenerated', function(event) {
            // Show success alert
            const successMessage = event.detail[0].message;
            Swal.fire({
                title: 'Success!',
                text: successMessage, // Use the message passed from Livewire
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        });
    
        // Listen for the 'invoiceGenerationFailed' event from Livewire
        document.addEventListener('invoiceGenerationFailed', function(event) {
            // Show error alert
            const errorMessage = event.detail[0].message;
            Swal.fire({
                title: 'Error!',
                text: errorMessage, // Use the error message passed from Livewire
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        });
    </script>
       
</div>
