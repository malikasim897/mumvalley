<x-app-layout>
    <!-- BEGIN: Content-->  
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Orders</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Inovices
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
                        <div class="card table-responsive">
                            @livewire('payment-invoice.table')
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
    <!-- View Order Modal -->
    <div class="modal fade text-start" id="viewOrderModal" tabindex="-1" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
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
