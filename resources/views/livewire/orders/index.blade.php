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
    <div class="container-fluid text-start">
        <div class="row">
            <div class="col-md-6">
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

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <label for="">From Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" type="text" autocomplete="off">
                    </div>
                    <div class="col-md-5">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" type="text" autocomplete="off">
                    </div>
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
                    <th>User</th>
                @endif
                {{-- <th>Product ID</th>
                <th>Product Name</th>
                <th>Shipped Units</th> --}}
                <th>Amount (Rs.)</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Order Invoice</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($orders->count() > 0)
            @foreach ($orders as $key => $order)
            <tr wire:key="item-{{ $order->id }}" class="{{ $order->order_status === 'cancelled' ? 'bg-light-danger' : '' }}">
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
                    @if($order->paymentInvoices->isNotEmpty())
                        <a href="{{ route('invoice.download', $order->id) }}" target="_blank">
                            <span class="badge rounded-pill badge-light-dark me-1"><i data-feather="download" class="me-50"></i>Invoice</span>
                        </a>
                    @endif
                </td>                   
                <td>
                    @canany(['order.edit', 'order.delete'])
                    <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $order->id }}" wire:ignore>
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

                            @if($order->order_status !== 'cancelled' && !$order->isPaid() && $order->user->isActive())
                                @if ($order->paymentInvoices->isNotEmpty())
                                    {{-- <a @if(Auth::user()->isActive()) href="{{ route('payment-invoices.invoice.show', Crypt::encrypt($order->paymentInvoices->first()->id)) }}" @else data-toggle="modal" data-target="#hd-modal" data-url="{{ route('modals.user.suspended') }}" @endif class="dropdown-item" title="Pay Order">
                                        <i data-feather="dollar-sign" class="me-50"></i> Pay Order
                                    </a> --}}
                                @else
                                    <a @if(Auth::user()->isActive()) href="{{ route('payment-invoices.create',['orders'=>$encryptedId]) }}" @else data-toggle="modal" data-target="#hd-modal" data-url="{{ route('modals.user.suspended') }}" @endif class="dropdown-item" title="Create Payment Invoice">
                                        <i data-feather="dollar-sign" class="me-50"></i> Create Invoice
                                    </a>
                                @endif
                            @endif

                            {{-- @if(!$order->payment_status)
                                <a class="dropdown-item" href="{{ route('product.invoice.details', $encryptedId) }}" id="payOrder">
                                    <i data-feather="dollar-sign" class="me-50"></i>
                                    <span>Pay Inovice</span>
                                </a>
                            @endif --}}
                            
                            @if(
                                $order->order_status !== 'cancelled' &&
                                !$order->paymentInvoices->first()?->isPaid() &&
                                !$order->paymentInvoices->first()?->partial_paid
                            )
                                @can('order.edit')
                                    <a class="dropdown-item" href="{{ route('product.order.details', $encryptedId) }}">
                                        <i data-feather="edit" class="me-50"></i>
                                        <span>Edit Order</span>
                                    </a>
                                @endcan
                            @endif


                            @can('order.edit')
                                @if($order->order_status !== 'cancelled' && !$order->payment_status)
                                    <a class="dropdown-item" href="{{ route('order.cancel', $order->id) }}">
                                        <i data-feather="x-circle" class="me-50"></i>
                                        <span>Cancel Order</span>
                                    </a>
                                @endif
                            @endcan

                            @can('order.delete')
                                @if($order->order_status !== 'cancelled' && !count($order->paymentInvoices))
                                    <a class="dropdown-item" href="#" id="delete-order{{ $order->id }}" onclick="showDeleteConfirmation({{ $order->id }})">
                                        <i data-feather="trash" class="me-50"></i>
                                        <span>Delete</span>
                                    </a>
                                @endif
                            @endcan

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

    @if(session()->has('errors'))
    @foreach(session('errors')['errors'] as $error)
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $error }}'
        });
    </script>
    @endforeach
    @endif
</div>
