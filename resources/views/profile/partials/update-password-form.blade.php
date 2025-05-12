{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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

<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Update Password</h4>
    </div>
    <div class="card-body py-2 my-25">
        <div class="alert alert-warning">
            <div class="alert-body fw-normal">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </div>
        </div>
        <!-- form -->
        <form method="post" action="{{ route('password.update') }}" class="validate-form mt-2 pt-50">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" />
                </div>
                <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="" />
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                </div>
            </div>
        </form>
        <!--/ form -->
    </div>
</div>
