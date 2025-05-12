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
                            <h2 class="content-header-title float-start mb-0">Add Customer</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">Add Customer
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
                            <div class="">
                                <form method="POST" action="{{ route('users.store') }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header border-bottom">
                                                    <h4 class="card-title">Account Registration</h4>
                                                </div>
                                                <div class="card-body">
                                                    <!-- form -->

                                                    <div class="row mt-2">
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="role">Role</label>
                                                            <select class="form-select" id="role" name="role">
                                                                <option value=""> select </option>
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role->name }}">
                                                                        {{ $role->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error class="" :messages="$errors->get('role')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="status">Status</label>
                                                            <select class="form-select" id="status" name="status">
                                                                <option value="1"> Active </option>
                                                                <option value="0"> Inactive </option>
                                                            </select>

                                                            <x-input-error class="" :messages="$errors->get('status')" />
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="name">Name</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" value="{{ old('name') }}"
                                                                data-msg="Please enter name" />
                                                            <x-input-error class="" :messages="$errors->get('name')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="userEmail">Email</label>
                                                            <input type="email" class="form-control" id="userEmail"
                                                                name="email" value="{{ old('email') }}" />
                                                            <x-input-error class="" :messages="$errors->get('email')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="userPhone">Phone<span class="text-danger">*</span> (+923450000000)</label>
                                                            <input type="text" class="form-control" id="userPhone"
                                                                name="phone" value="{{ old('phone') }}" />
                                                            <x-input-error class="" :messages="$errors->get('phone')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="address">Address</label>
                                                            <input type="text" class="form-control" placeholder=""
                                                                id="address" name="address" value="{{ $user->setting->address ?? old('address') }}" />
                                                            <x-input-error class="" :messages="$errors->get('address')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="password">Password</label>
                                                            <input type="password" autocomplete="new-password" class="form-control" id="password"
                                                                name="password" value="{{ old('password') }}" />
                                                            <x-input-error class="" :messages="$errors->get('password')" />
                                                        </div>
                                                        <div class="col-12 col-sm-6 mb-1">
                                                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                                                            <input type="password" class="form-control" id="password_confirmation"
                                                                name="password_confirmation" autocomplete="new-password" value="{{ old('password_confirmation') }}" />
                                                            <x-input-error class="" :messages="$errors->get('password_confirmation')" />
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-1 me-1">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
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
