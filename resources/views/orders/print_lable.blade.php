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
                            <h2 class="content-header-title float-start mb-0">Print Label</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('orders.index') }}">Orders</a>
                                    </li>
                                    <li class="breadcrumb-item active">Label
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="row" id="basic-table">
                    <div class="col-12">
                        <div class="card">
                            {{-- <div class="card-header d-flex justify-content-end">
                                <h4 class="card-title" id="basic-layout-form"></h4>
                                <a href="{{ route('orders.index') }}"
                                    class="btn btn-primary pull-right waves-effect waves-light">
                                    Return to List </a>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div> --}}
                            {{-- <hr> --}}
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="label-wrapper">
                                        @include('orders.partials.render_label')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <script>
        $(document).ready(function () {
            var loadingContainer = $("#loadingContainer");
            // get states for sender country
            $(document).on('click','#reloadLabel', function () {
                    loadingContainer.show(); // Show loading HTML
                    var orderId = $(this).data('id');
                    $.ajax({
                        url: "{{ route('order.reload.label', ['orderId' => ':orderId']) }}".replace(':orderId', orderId),
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            loadingContainer.hide(); // Hide loading HTML
                            $('.label-wrapper').html(data.view);
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            loadingContainer.hide(); // Hide loading HTML
                        }
                    });
            });
        });
    </script>
</x-app-layout>
@include('components.loading')
