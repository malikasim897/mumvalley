<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        button.btn.btn-outline-secondary.dropdown-toggle.show-arrow {
            padding: 0.4em 1.3em;
        }
        /* Hide the default checkbox */
        input[type="checkbox"] {
            display: none;
        }

        /* Custom checkbox appearance */
        .checkbox-wrapper {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            background-color: #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            position: relative;
        }

        /* Checkmark indicator */
        .custom-checkbox::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: #7367F0;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: all 0.3s ease;
        }

        /* Spin animation when checked */
        input[type="checkbox"]:checked + .custom-checkbox {
            animation: spin 0.6s ease;
            background-color: #7367F0;
        }

        input[type="checkbox"]:checked + .custom-checkbox::after {
            transform: translate(-50%, -50%) scale(1);
        }

        /* Spin keyframes */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }


    </style>
    
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row col-12">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Orders</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('orders.index') }}">Orders</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit Invoice
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-left col-md-3 col-12 mb-2 text-end">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="history.back();">
                        Back to List
                    </a>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card table-responsive">
                            @livewire('payment-invoice.edit-invoice', ['invoiceId' => $invoice->id])
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- View Order Modal -->
    <div class="modal fade text-start" id="viewOrderModal" tabindex="-1" aria-labelledby="myModalLabel16"
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
    @include('orders.partials.render_invoice_js')
    @include('components.loading')

</x-app-layout>
