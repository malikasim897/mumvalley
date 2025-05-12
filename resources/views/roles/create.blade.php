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
                            <h2 class="content-header-title float-start mb-0">Create Role</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('roles.index') }}">Roles</a>
                                    </li>
                                    <li class="breadcrumb-item active">Create
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
                                <form method="POST" action="{{ route('roles.store') }}">
                                    @csrf
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-1">
                                                <h4 class="card-title mb-1">Role Name</h4>
                                                <input name="name" id="name" value="{{ old('name') }}" class="form-control form-control-lg" type="text" placeholder="name">
                                                <x-input-error class="" :messages="$errors->get('name')" />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="">
                                                <h4 class="card-title">Role Permissions</h4>
                                            </div>
                                            <div class="">
                                                <div class="form-check form-switch form-check-primary"  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Select All" aria-label="Select All">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                                    <label class="form-check-label" for="checkAll">
                                                        <span class="switch-icon-left"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                                        <span class="switch-icon-right"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>
                                                    </label>
                                                    <label class="form-check-label" for="customSwitch1">Select All</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <x-input-error class="px-2" :messages="$errors->get('permissions')" />
                                    </div>
                                </div>
                                <div class="card-body">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Modules</th>
                                                    <th>Create</th>
                                                    <th>Read</th>
                                                    <th>Write</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($permissionsByModule as $module => $permissions)
                                                <tr>
                                                    <td><h6>{{ ucfirst($module) }}</h6></td>
                                                    @foreach($permissions as $key => $permission)
                                                    <td>
                                                        <div class="form-check form-switch form-check-primary">
                                                            <input name="permissions[]" type="checkbox" class="form-check-input permissionCheckbox" id="customSwitch{{$module}}{{$key}}" value="{{ $permission->name }}">
                                                            <label class="form-check-label" for="customSwitch{{$module}}{{$key}}">
                                                                <span class="switch-icon-left"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                                                <span class="switch-icon-right"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>
                                                            </label>
                                                        </div>
                                                        {{-- <div class="form-check form-check-primary form-switch">
                                                            <input name="permissions[]" type="checkbox" class="form-check-input" value="{{ $permission->name }}" id="customSwitch{{$key}}">
                                                        </div> --}}
                                                        {{-- <div class="demo-inline-spacing">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox{{$key}}" value="{{ $permission->name }}">
                                                            </div>
                                                        </div> --}}
                                                    </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-12 mt-5">
                                                <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <script>
        $('#checkAll').change(function() {
            $('.permissionCheckbox').prop('checked', this.checked);
        });
    </script>
    </x-app-layout>
    