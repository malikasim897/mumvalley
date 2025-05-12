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
                            <h2 class="content-header-title float-start mb-0">Edit Role</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('roles.index') }}">Roles</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-checkbox">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1">
                                                    <h4 class="card-title mb-1">Role Name</h4>
                                                    <input name="name" id="name"
                                                        value="{{ $role->name ?? old('name') }}"
                                                        class="form-control form-control-lg" type="text"
                                                        placeholder="name">
                                                    <x-input-error class="" :messages="$errors->get('name')" />
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="btn btn-primary me-1 waves-effect waves-float waves-light">Update</button>
                                                <button type="reset"
                                                    class="btn btn-outline-secondary waves-effect">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</x-app-layout>
