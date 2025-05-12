<div>
    <!-- Statistics -->
    <div class="row mb-0 mt-2 pb-0 card-header">
        <div class="col-md-2 mb-1">
            <div class="card text-white bg-primary bg-gradient">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Orders</h5>
                    <p class="card-text">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-1">
            <div class="card text-white bg-success bg-gradient">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Amount</h5>
                    <p class="card-text">{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-1">
            <div class="card text-white bg-info bg-gradient">
                <div class="card-body">
                    <h5 class="card-title text-white">Paid Amount</h5>
                    <p class="card-text">{{ number_format($paidAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-1">
            <div class="card text-white bg-warning bg-gradient">
                <div class="card-body">
                    <h5 class="card-title text-white">Pend. Amount</h5>
                    <p class="card-text">{{ number_format($pendingAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-1">
            <div class="card text-white bg-secondary bg-gradient">
                <div class="card-body">
                    <h5 class="card-title text-white">Return Bottles</h5>
                    <p class="card-text">{{ $returnableBottles }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr style="height: 3px; background-color: black;">

    <!-- Filters -->
    <div class="card-header pb-0 pt-0">
        <h4>Report Filters</h4>
    </div>

    <div class="card-header row mb-0 pb-0 pt-1">
        <!-- Date Range -->
        <div class="col-md-2">
            <label>Date Range</label>
            <select wire:model.live="dateRange" class="form-control">
                <option value="today">Today</option>
                <option value="7days">Last 7 Days</option>
                <option value="14days">Last 14 Days</option>
                <option value="1month">Last 1 Month</option>
                <option value="6months">Last 6 Months</option>
                <option value="1year">Last 1 Year</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>

        <!-- Custom Date Range -->
        @if($dateRange === 'custom')
            <div class="col-md-2">
                <label>Start Date</label>
                <input wire:model.live="customStartDate" class="form-control" type="date">
            </div>
            <div class="col-md-2">
                <label>End Date</label>
                <input wire:model.live="customEndDate" class="form-control" type="date">
            </div>
        @endif

        <!-- User Filter -->
        <div class="col-md-2">
            <label>User</label>
            <div>
                <select wire:model.live="selectedUser" class="form-control" id="selectedUser">
                    <option value="">All</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-md-2">
            <label>Status</label>
            <select wire:model.live="status" class="form-control">
                <option value="">All</option>
                <option value="paid">Paid</option>
                <option value="partial_paid">Partial Paid</option>
                <option value="unpaid">Unpaid</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        
    </div>

    <hr style="height: 3px; background-color: black;">

    <!-- Pagination and Search -->
    <div class="card-header d-flex justify-content-between align-items-center mb-0 pt-0">
        <div class="col-md-1">
            {{-- <label>Rows</label> --}}
            <select wire:model.live="orderPerPage" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Search</label>
            <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
        </div>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table" id="ordersTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Order No.</th>
                    @if(auth()->user()->hasRole('admin'))
                        <th>User</th>
                    @endif
                    <th>Order Amount (Rs.)</th>
                    <th>Paid Amount (Rs.)</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Order Invoice</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr wire:key="item-{{ $order->id }}" class="{{ $order->order_status === 'cancelled' ? 'bg-light-danger' : '' }}">
                        <td>{{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}</td>
                        <td><a class="text-primary">{{ $order->order_number }}</a></td>
                        @if(auth()->user()->hasRole('admin'))
                            <td>{{ optional($order->user)->name }}</td>
                        @endif
                        <td>{{ $order->total_amount }}</td>
                        <td>{{ $order->getPaymentInvoice()?->paid_amount }}</td>
                        <td>
                            <span class="badge rounded-pill badge-light-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'unpaid' ? 'warning' : 'primary') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-light-{{ $order->order_status === 'completed' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </td>
                        <td>
                            @if($order->paymentInvoices->isNotEmpty())
                                <a href="{{ route('invoice.download', $order->id) }}" target="_blank">
                                    <span class="badge rounded-pill badge-light-dark me-1"><i data-feather="download"></i> Invoice</span>
                                </a>
                            @endif
                        </td>
                        <td>
                            @canany(['order.edit', 'order.delete'])
                            <div class="dropdown" wire:key="dropdown-{{ $order->id }}" wire:ignore>
                                @php $encryptedId = Crypt::encrypt($order->id); @endphp
                                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    @can('order.view')
                                        <a class="dropdown-item getInvoice" data-id="{{ $order->id }}">
                                            <i data-feather="eye"></i> View
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            @endcanany
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center">No Record Found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-start mt-3">
        {{ $orders->links() }}
    </div>

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
    @push('scripts')
    <script>
        $(document).ready(function () {
            $('#selectedUser').select2();
            $('#selectedUser').on('change', function (e) {
                @this.set('selectedUser', e.target.value);
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function () {
            $('#selectedUser').select2();
    
            $('#selectedUser').on('change', function (e) {
                @this.set('selectedUser', e.target.value);
            });
        });
    </script>
    @endpush
</div>

