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
                            <h2 class="content-header-title float-start mb-0">Parcels</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Parcels
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
        @include('layouts.validation.message')
                <!-- Basic Tables start -->
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card table-responsive">
                            @livewire('parcels.index')
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- parcel shipping modal -->
    <div class="modal fade" id="parcelShippingInfo" tabindex="-1" aria-labelledby="parcelShippingInfoTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-4">
                    <h1 class="text-center mb-1" id="parcelShippingInfoTitle">Parcel shipping Info</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- / parcel shipping modal -->
    <script>
        function deleteParcel($id) {
            Swal.fire({
                text: 'Are you sure you want to delete this parcel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-2'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $('#delete-form' + $id).submit();
                }
            });
        }
    </script>
</x-app-layout>
