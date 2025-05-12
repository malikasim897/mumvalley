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
                            <h2 class="content-header-title float-start mb-0">Invoices</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Storage Invoices
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-body">
                @if (auth()->user()->hasRole('admin'))
                    <div class="row" id="">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0"><h4>Create Storage Invoice</h4></div>
                                <hr class="mt-0">
                                    <div class="card-body">
                                        {{-- @include('layouts.validation.message') --}}
                                        <form class="form" action="{{ route('storageinvoices.create') }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                @if (auth()->user()->hasRole('admin'))
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="user_id">Select User<span
                                                                    class="text-danger">*</span></label>
                                                            <select id="user" name="user"
                                                                class="form-select select2" required>
                                                                <option value="">Select User</option>
                                                                <option value="all">All</option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->po_box_number }}</option>
                                                                @endforeach()
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (auth()->user()->hasRole('admin'))
                                                    <div class="col-md-2">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="year">Select Year<span class="text-danger">*</span></label>
                                                            <select id="year" name="year" class="form-select" required>
                                                                <option value="">Select Year</option>
                                                                @php
                                                                    $currentYear = now()->year; // Get the current year
                                                                @endphp
                                                                <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (auth()->user()->hasRole('admin'))
                                                    <div class="col-md-2">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="month">Select Month<span class="text-danger">*</span></label>
                                                            <select id="month" name="month" class="form-select" required>
                                                                <option value="">Select Month</option>
                                                                @php
                                                                    $currentMonth = now()->month; // Get the current month
                                                                    $months = [
                                                                        '01' => 'January', '02' => 'February', '03' => 'March',
                                                                        '04' => 'April', '05' => 'May', '06' => 'June',
                                                                        '07' => 'July', '08' => 'August', '09' => 'September',
                                                                        '10' => 'October', '11' => 'November', '12' => 'December'
                                                                    ];
                                                                @endphp
                                                                @foreach($months as $num => $month)
                                                                    @if ($num <= str_pad($currentMonth, 2, '0', STR_PAD_LEFT)) <!-- Show only up to the current month -->
                                                                        <option value="{{ $num }}">{{ $month }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="content-header-right mt-2 text-md-end col-md-12 col-12 d-md-block d-none">
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-primary me-1">Submit</button>
                                                        <button type="reset"
                                                            class="btn btn-outline-secondary">Reset</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card table-responsive">
                            @livewire('storage-invoice.index')
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>  
        </div>
    </div>
    <!-- END: Content-->
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
    @include('orders.partials.render_invoice_js')
    @include('components.loading')

</x-app-layout>
