<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        button.btn.btn-outline-secondary.dropdown-toggle.show-arrow {
            padding: 0.4em 1.3em;
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
                            <h2 class="content-header-title float-start mb-0">Invoices</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Transactions
                                    </li>
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
                        <div class="card">
                            <div class="card-header pb-0"><h4>List of All Transactions</h4></div>
                            <div class="table-responsive">
                                @livewire('transactions.index')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- View Invoice Modal -->
    <div class="modal fade text-start" id="viewInvoiceModal" tabindex="-1" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px !important;">
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
    <!--/ View Invoice Modal -->
    <!-- View Storage Inovoice Modal -->
    <div class="modal fade text-start" id="viewStorageInvoiceModal" tabindex="-1" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px !important;">
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
    <!--/ View Storage Inovoice Modal -->
    <!-- View Order Modal -->
    <div class="modal fade text-start" id="viewReceiptModal" tabindex="-1" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px !important;">
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

    <!-- Ship Confirmation Modal -->
    <div class="modal fade" id="shipConfirmationModal" tabindex="-1" aria-labelledby="shipConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shipConfirmationModalLabel">Confirm Shipping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to ship the orders of this transaction? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" id="ship-form" style="display: inline;">
                        @csrf
                        <input type="hidden" name="transaction_id" id="transaction-id">
                        <button type="submit" class="btn btn-primary">Confirm Shipping</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade" id="cancelConfirmationModal" tabindex="-1" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelConfirmationModalLabel">Confirm Cancel Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this transaction? This action cannot be undone and the related invoice will be also cancelled.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" id="cancel-form" style="display: inline;">
                        @csrf
                        <input type="hidden" name="transaction_id" id="transaction-id">
                        <button type="submit" class="btn btn-danger">Cancel Transaction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showShipConfirmation(transactionId) {
            // Set the form action URL dynamically
            const form = document.getElementById('ship-form');
            form.action = '/transaction/' + transactionId + '/ship';

            // Set the hidden input value
            document.getElementById('transaction-id').value = transactionId;

            // Show the confirmation modal
            var myModal = new bootstrap.Modal(document.getElementById('shipConfirmationModal'));
            myModal.show();
        }

        function showCancelfirmation(transactionId) {
            // Set the form action URL dynamically
            const form = document.getElementById('cancel-form');
            form.action = '/transaction/' + transactionId + '/ship';

            // Set the hidden input value
            document.getElementById('transaction-id').value = transactionId;

            // Show the confirmation modal
            var myModal = new bootstrap.Modal(document.getElementById('shipConfirmationModal'));
            myModal.show();
        }
    </script>
    
    @include('orders.partials.render_invoice_js')
    @include('components.loading')

</x-app-layout>
