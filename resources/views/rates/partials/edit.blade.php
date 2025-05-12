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
                            <h2 class="content-header-title float-start mb-0">Rates</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('rates.index') }}">Rates</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('rates.index') }}">{{ $rate->user->name }}</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('rates.index') }}">{{ $rate->shipping_service_name }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">View
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <a href="{{ route('rates.create') }}" class="btn btn-primary">Update Rate</a>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12 mt-5">
                        {{-- <div class="col-12 d-flex justify-content-end my-1">
                            <a href="{{ route('rates.create') }}" class="btn btn-primary mt-1 me-1">Update Rate</a>
                        </div> --}}
                        <div class="card table-responsive">
                            <livewire:rates.edit.index :rate="$rate" />
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
    @include('components.loading')
</x-app-layout>
