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
                            <h2 class="content-header-title float-start mb-0">Account</h2>
                            <div class="breadcrumb-wrapper">
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                        <li class="breadcrumb-item active">Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if(auth()->user()->hasRole('user'))
                    @include('livewire.user.partials.user-rate-settings')
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        {{-- <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div> --}}
                        {{-- @if (\Auth::user()->hasRole('admin'))
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        @endif --}}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <script src="{{ asset('js/scripts/pages/page-account-settings-account.js') }}"></script>
</x-app-layout>
