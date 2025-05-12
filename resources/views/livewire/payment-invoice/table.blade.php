<div>
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 card-header" >Order Payment Invoices</h4>
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
                <h6>Partial Paid Invoices: <strong>{{ $partialPaidInvoices }}</strong></h6>
            </div>
            <div class="col-md-2 mt-1">
                <h6>Un-Paid Invoices: <strong>{{ $unpaidInvoices }}</strong></h6>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Start Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date">
                    </div>
                    <div class="col-md-6">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date">
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
                <th>Orders Count</th>
                <th>Amount (Rs.)</th>
                <th>Status</th>
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
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                @include('payment-invoices.components.table-row')
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
        form.action = '/payment-invoices/' + invoiceId;

        // Show the confirmation modal
        var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        myModal.show();
    }
</script>
