<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Customer Profile Settings</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Customers</a>
                                        </li>
                                        <li class="breadcrumb-item active">Settings
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                {{-- @include('layouts.validation.message') --}}
                <!-- form -->
                @include('livewire.user.partials.user-rate-settings')

                <form method="post" action="{{ route('users.update', $user->id) }}" class="validate-form mt-2">
                    @csrf
                    @method('patch')

                    @include('livewire.user.partials.user-role-settings')                  
                    <livewire:token-generator :user-id="$user->id"/>
                    @include('livewire.user.partials.user-profile-settings')
                </form>
                <!--/ form -->

            </div>
        </div>
    </div>
</x-app-layout>
