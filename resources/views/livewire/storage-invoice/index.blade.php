<div>
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 card-header" >Products Storage Payment Invoices</h4>
            <div class="form-group mb-0" style="margin-right: 1rem;"> <!-- Inline style for margin -->
                <select wire:model.live='pageSize' class="form-control text-start px-2">
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div>
    </div>

    <div class="container-fluid text-start">
        <div class="row mb-2">
            <div class="col-md-2 mt-1">
                <h6>Total Invoices: <strong>{{ $totalInvoices }}</strong></h6>
            </div>
            <div class="col-md-2 mt-1">
                <h6>Paid Invoices: <strong>{{ $paidInvoices }}</strong></h6>
            </div>
            <div class="col-md-2 mt-1">
                <h6>Un-Paid Invoices: <strong>{{ $unpaidInvoices }}</strong></h6>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <label for="">Start Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" autocomplete="off">
                    </div>
                    <div class="col-md-5">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="mb-2">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                @if(auth()->user()->hasRole('admin'))
                <th>User</th>
                @endif
                <th>Invoice Month</th>
                <th>Product Count</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Type</th>
                <th>Action</th>
            </tr>
            <tr>
                <th></th>
                <th>
                    <input type="search" wire:model.live="uuid" class="form-control form-control-sm">
                </th>
                @if(auth()->user()->hasRole('admin'))
                <th>
                    <input type="search" wire:model.live="user" class="form-control form-control-sm">
                </th>
                @endif
                <th>
                    <input type="text" wire:model.live="month" class="form-control form-control-sm">
                </th>
                <th>
                    <input type="text" wire:model.live="count" class="form-control form-control-sm">
                </th>
                <th><input type="text" wire:model.live="amount" class="form-control form-control-sm"></th>
                <th>
                    <select class="form-control form-control-sm" wire:model.live="is_paid">
                        <option value="">All</option>
                        <option value="1">Paid</option>
                        <option value="0">Unpaid</option>
                    </select>
                </th>
                <th>
                    <select class="form-control form-control-sm" wire:model.live="type">
                        <option value="">All</option>
                        <option value="direct_transfer">Direct Transfer</option>
                        <option value="stripe">Stripe / Card</option>
                    </select>
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr class="{{ $invoice->cancelled ? 'bg-light-danger' : '' }}">
                    
                    <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</td>
                    <td><a type="button" class="viewStorageInvoice text-primary" id="viewStorageInvoice" data-type="storage" data-id={{ $invoice->id }}>{{ $invoice->uuid }}</a></td>
                    @if(auth()->user()->hasRole('admin'))
                        <td>{{ optional($invoice->user)->name }}</td>
                    @endif
                    <td>
                        {{ $invoice->charge_month }}
                    </td>
                    <td>
                        {{ round($invoice->products()->count(),2) }}
                    </td>
                    <td>
                        ₤ {{ $invoice->total_amount }}
                    </td>
                
                    <td>
                        @if ($invoice->isPaid() )
                            <span class="badge rounded-pill badge-light-success" me-1>
                                Paid
                            </span>
                        @elseif($invoice->cancelled)
                            <span class="badge rounded-pill badge-light-danger" me-1>
                                Cancelled
                            </span>
                        @else
                            <span class="badge rounded-pill badge-light-warning" me-1>
                                Payment Pending
                            </span>
                        @endif
                    </td>
                
                    <td>
                        @if ( $invoice->payment_type == "direct_transfer")
                            <span class="badge rounded-pill badge-light-dark" me-1>
                                Direct Transfer
                            </span>
                        @elseif( $invoice->payment_type == "stripe")
                            <span class="badge rounded-pill badge-light-dark" me-1>
                                Stripe
                            </span>
                        @endif
                    </td>
                    
                    <td>
                        <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $invoice->id }}" wire:ignore>
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle waves-effect show-arrow" data-bs-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                
                                <a class="dropdown-item viewStorageInvoice" id="viewStorageInvoice" data-type="storage" data-id={{ $invoice->id }} title="View Invoice">
                                    <i data-feather="eye" class="me-50"></i><span>View Invoice</span>
                                </a> 
                
                                {{-- @if (!$invoice->isPaid())
                                    <a class="dropdown-item" href="{{ route('payment-invoices.invoice.edit', \Crypt::encrypt($invoice->id)) }}" title="Edit Invoice">
                                        <i data-feather="edit" class="me-50"></i><span>Edit</span>
                                    </a>
                                @endif --}}

                                @if ($invoice->payment_receipt)
                                    <a class="dropdown-item" href="{{ asset('storage/receipts/' . $invoice->payment_receipt) }}" target="_blank">
                                        <i data-feather="file" class="me-50"></i><span>View Receipt</span>
                                    </a>
                                @endif
                
                                @if (!$invoice->isPaid())
                                    <a class="dropdown-item" href="{{ route('storage-invoices.invoice.checkout.index', \Crypt::encrypt($invoice->id)) }}" title="Pay Invoice">
                                        <i data-feather="dollar-sign" class="me-50"></i><span>Pay</span>
                                    </a>
                                @endif
                
                                @if (!$invoice->isPaid() && auth()->user()->hasRole('admin'))
                                    <a class="dropdown-item" href="#" id="delete-invoice{{ $invoice->id }}" onclick="showDeleteConfirmation('{{ $invoice->id }}')">
                                        <i data-feather="trash" class="me-50"></i>
                                        <span>Delete</span>
                                    </a>
                                @endif
                
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $invoices->links() }}
    @include('layouts.livewire.loading')  

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this invoice? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="delete-form" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for vertical centering (if needed) -->
    <style>
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1.75rem);
        }
    </style>
</div>
<script>
    function showDeleteConfirmation(invoiceId) {
        // Set the form action URL dynamically
        const form = document.getElementById('delete-form');
        form.action = '/storage-invoices/' + invoiceId;

        // Show the confirmation modal
        var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        myModal.show();
    }
</script>
