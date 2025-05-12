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
                    <div class="col-md-4">
                        <label for="">Status</label>
                        <select id="status" class="form-control text-start px-2" wire:model.live="status">
                            <option value="all">All</option>
                            <option value="succeeded">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Start Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" autocomplete="off">
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
                @if(auth()->user()->hasRole('admin'))
                    <th>User Name</th>
                @endif
                <th>Inovice#</th>
                <th>Charge Id</th>
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Payment Status</th>
                {{-- <th>Dispatch Status</th> --}}
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($transactions->count() > 0)
            @foreach ($transactions as $key => $transaction)
            <tr wire:key="item-{{ $transaction->id }}" class="{{ $transaction->status === 'declined' ? 'bg-light-danger' : '' }}">
                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}</td>
                @if(auth()->user()->hasRole('admin'))
                    <td>{{ optional($transaction->user)->name}}</td>
                @endif
                <td>
                    <a type="button" class="viewInvoice text-primary" id="viewInvoice" data-type="{{ $transaction->invoice_type === \App\Models\StorageInvoice::class ? 'storage' : 'payment' }}" data-id="{{ $transaction->invoice->id }}">
                        {{ $transaction->invoice->uuid }}
                    </a>
                </td>                
                <td>{{ $transaction->latest_charge_id }}</td>
                <td>₤ {{ $transaction->amount }}</td>

                <td>
                    @if ( $transaction->payment_type == "direct_transfer")
                        <span class="badge rounded-pill badge-light-dark" me-1>
                            Direct Transfer
                        </span>
                    @elseif( $transaction->payment_type == "stripe")
                        <span class="badge rounded-pill badge-light-dark" me-1>
                            Stripe
                        </span>
                    @endif
                </td>

                <td>
                    @if ($transaction->status === 'succeeded')
                        <span class="badge rounded-pill badge-light-success" me-1>
                            Paid
                        </span>
                    @elseif($transaction->status === 'declined')
                        <span class="badge rounded-pill badge-light-danger" me-1>
                            Declined
                        </span>
                    @else
                        <span class="badge rounded-pill badge-light-warning" me-1>
                            Pending Confirmation
                        </span>
                    @endif
                </td>

                {{-- <td>
                    @if($transaction->invoice_type !=\App\Models\StorageInvoice::class)
                        @if ($transaction->invoice_status === 'shipped')
                            <span class="badge rounded-pill badge-light-primary" me-1>
                                Shipped
                            </span>
                        @elseif($transaction->invoice_status === 'cancelled')
                            <span class="badge rounded-pill badge-light-danger" me-1>
                                Cancelled
                            </span>
                        @else
                            <span class="badge rounded-pill badge-light-warning" me-1>
                                Pending 
                            </span>
                        @endif
                    @endif
                </td> --}}

                <td>
                    @canany(['transaction.edit', 'transaction.delete'])
                    <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $transaction->id }}" wire:ignore>
                        @php
                            $encryptedId = Crypt::encrypt($transaction->id);
                        @endphp
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow" data-bs-toggle="dropdown">
                            Action
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">

                            @if($transaction->payment_type === 'direct_transfer' && $transaction->status === 'pending')
                                @can('transaction.view')
                                    <a class="dropdown-item getInvoice" id="getInvoice" data-id="{{ $transaction->id }}" data-type="{{ $transaction->invoice_type === \App\Models\StorageInvoice::class ? 'storage' : 'payment' }}" data-receipt="{{ asset('storage/receipts/' . $transaction->payment_receipt) }}">
                                        <i data-feather="eye" class="me-50"></i>
                                        <span>View Receipt</span>
                                    </a>
                                @endcan
                            @endif
                            
                            {{-- @if($transaction->invoice_type !=\App\Models\StorageInvoice::class)
                                @if($transaction->status === 'succeeded' && empty($transaction->invoice_status))
                                    @can('transaction.edit')
                                    <a class="dropdown-item" href="#" id="ship-orders{{ $transaction->id }}" onclick="showShipConfirmation({{ $transaction->id }})">
                                        <i data-feather="truck" class="me-50"></i>
                                        <span>Ship Orders</span>
                                    </a>
                                    @endcan
                                @endif
                            @endif --}}

                            {{-- @can('transaction.edit')
                                @if($transaction->status === 'succeeded' && empty($transaction->invoice_status))
                                    <a class="dropdown-item" href="{{ route('order.cancel', $transaction->id) }}">
                                        <i data-feather="x-circle" class="me-50"></i>
                                        <span>Cancel Transaction</span>
                                    </a>
                                @endif
                            @endcan --}}

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
     <!-- View Receipt Modal -->
     <div wire:ignore.self class="modal fade" id="viewReceiptModal" tabindex="-1" aria-labelledby="viewReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewReceiptModalLabel">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="receiptImage" src="" alt="Payment Receipt" class="img-fluid" style="max-height: 500px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="declinePaymentBtn">Decline Payment</button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>
    
    {{ $transactions->links() }}
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
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event listener for the view receipt button
            document.querySelectorAll('.getInvoice').forEach(button => {
                button.addEventListener('click', function () {
                    let transactionId = this.getAttribute('data-id');
                    let receiptImageUrl = this.getAttribute('data-receipt'); // Set this attribute in the button
                    let type = this.getAttribute('data-type');
                    let modal = new bootstrap.Modal(document.getElementById('viewReceiptModal'));
    
                    // Set the image source in the modal
                    document.getElementById('receiptImage').src = receiptImageUrl;
    
                    // Open the modal
                    modal.show();
    
                    // Handle confirm payment button click
                    document.getElementById('confirmPaymentBtn').onclick = function () {
                        confirmPayment(transactionId, type);
                    };

                    // Handle confirm payment button click
                    document.getElementById('declinePaymentBtn').onclick = function () {
                        declinePayment(transactionId, type);
                    };
                });
            });
        });
    
        function confirmPayment(transactionId, type) {
            // Perform the AJAX request to confirm payment
            fetch(`/transactions/confirm/${transactionId}/${type}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Payment confirmed successfully.', 'success');
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000); // 5-second delay
                } else {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to confirm payment.', 'error');
            });
        }

        function declinePayment(transactionId, type) {
            // Perform the AJAX request to confirm payment
            fetch(`/transactions/decline/${transactionId}/${type}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Invoice payment has been declined.', 'success');
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000); // 5-second delay
                } else {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to decline payment.', 'error');
            });
        }
    </script>
    @if(session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}'
        });
    </script>
    @endif
    
</div>


