{{-- <section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section> --}}


<!-- deactivate account  -->
<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Deactivate Account</h4>
    </div>
    <div class="card-body py-2 my-25">
        <div class="alert alert-warning">
            <h4 class="alert-heading">Are you sure you want to deactivate your account?</h4>
        </div>

        <form id="formAccountDeactivation" class="validate-form" method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="col-12 col-sm-6 mb-1">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" />
                @error('current_password')
                    <span class="text-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                {{-- <x-input-error :messages="$errors->updatePassword->get('password')" class="" /> --}}
                <x-input-error :messages="$errors->userDeletion->get('password')" class="" />
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation"
                    data-msg="Please confirm you want to delete account" />
                <label class="form-check-label font-small-3" for="accountActivation">
                    I confirm my account deactivation
                </label>
            </div>
            <div>
                <button type="button" class="btn btn-danger deactivate-account mt-1">Deactivate Account</button>
            </div>
        </form>
    </div>
</div>
