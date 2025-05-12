{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> --}}
<!-- profile -->
<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Profile Details</h4>
    </div>

    <div class="card-body py-2">
        {{-- @include('layouts.validation.message') --}}
        <!-- form -->
        <form method="post" action="{{ route('profile.update') }}" class="" enctype="multipart/form-data">
            @csrf
            @method('patch')


            <!-- header section -->
            <div class="d-flex">
                <a href="#" class="me-25">
                    <img src="{{ $user->image ? asset($user->image->url) : asset('images/portrait/small/avatar-s-11.jpg') }}" id="account-upload-img" class="uploadedImage rounded me-50" alt="profile image" height="100" width="100" />

                </a>
                <!-- upload and reset button -->
                <div class="d-flex align-items-end mt-75 ms-1">
                    <div>
                        <label for="account-upload" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                        <input type="file" id="account-upload" name="image" hidden accept="image/*">
                        <input type="hidden" id="account-current-image" value="{{ asset($user->image()->url ?? '') }}"
                            class="uploadedImageReset" name="current_image" />
                        <button type="button" id="account-reset"
                            class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                        <p class="mb-0">Allowed file size 2048kb.</p>
                        <x-input-error class="" :messages="$errors->get('image')" />
                    </div>
                </div>
                <!--/ upload and reset button -->
            </div>
            <!--/ header section -->

            <div class="row mt-3">

                <input type="hidden" class="form-control" id="userId" name="userId" value="{{ $user->id }}" />

                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="first_ame">First Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="{{ $user->setting->first_name ?? old('first_name') }}" data-msg="Please enter first name"
                        placeholder="John" />
                    <x-input-error class="" :messages="$errors->get('first_name')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="{{ $user->setting->last_name ?? old('last_name') }}" data-msg="Please enter last name"
                        placeholder="Doe" />
                    <x-input-error class="" :messages="$errors->get('last_name')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="userEmail">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="john.doe@email.com" id="userEmail"
                        name="email" value="{{ $user->email }}" />
                    <x-input-error class="" :messages="$errors->get('email')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="userPhone">Phone<span class="text-danger">*</span> (+1234567890)</label>
                    <input type="text" class="form-control" id="userPhone" name="phone" value="{{ $user->phone }}"
                        placeholder="+1298377272" />
                    <x-input-error class="" :messages="$errors->get('phone')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" class="form-control" placeholder="98  Borough bridge Road, Birmingham"
                        id="address" name="address" value="{{ $user->setting->address ?? old('address') }}" />
                    <x-input-error class="" :messages="$errors->get('address')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="country">Country</label>
                    <select id="country" name="country_id" class="select2 form-select">
                        <option value="">Select Country</option>
                        @if ($user->setting == null)
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                            @endforeach
                        @else
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country['id'] }}"
                                    {{ $user->setting->country_id != null && $country['id'] == $user->setting->country_id ? 'selected' : '' }}>
                                    {{ $country['name'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <x-input-error class="" :messages="$errors->get('country_id')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="state">State</label>
                    <input type="text" class="form-control" id="state" name="state"
                        placeholder="Florida (FL)" value="{{ $user->setting->city ?? old('state') }}" />
                    <x-input-error class="" :messages="$errors->get('state')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city"
                        placeholder="Miami" value="{{ $user->setting->city ?? old('city') }}" />
                    <x-input-error class="" :messages="$errors->get('city')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="zipcode">Zip Code</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode"
                        value="{{ $user->setting->zipcode ?? old('zipcode') }}" placeholder="545098" />
                    <x-input-error class="" :messages="$errors->get('zipcode')" />
                </div>
            </div>
            <div class="card">

                <div class="py-2 border-bottom">

                    <div class="alert alert-warning">
                        <div class="alert-body fw-normal">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                            auto-fill="false"  placeholder="············"/>
                            <x-input-error class="" :messages="$errors->get('password')" />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" auto-fill="false" placeholder="············" />
                            <x-input-error class="" :messages="$errors->get('password_confirmation')" />
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--/ form -->
    </div>
</div>

<!--/ profile -->

@include('profile.partials.profile-js')
@include('components.loading')
