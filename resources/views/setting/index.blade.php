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
                            <h2 class="content-header-title float-start mb-0">Setting</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Settings
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row" id="">
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-12">
                                    @if (\Auth::user()->hasRole('admin'))
                                        <div class="card">
                                            <form method="POST" action="{{ route('settings.store') }}">
                                                @csrf

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 mx-auto">
                                                            <h1>Mode</h1>
                                                            <div class="my-1" style="display: flex; justify-content: flex-start;" >
                                                                <div class="form-check mb-1" >
                                                                    <input class="form-check-input mode-radio" value="live" name="mode_type" type="radio"  id="live" >
                                                                    <label class="form-check-label" for="dev">
                                                                      Live
                                                                    </label>
                                                                  </div>
                                                                  <div class="form-check" style="margin-left: 5%;">
                                                                    <input class="form-check-input mode-radio"  value="dev" name="mode_type" type="radio"  id="dev">
                                                                    <label class="form-check-label" for="dev">
                                                                        Dev
                                                                    </label>
                                                                </div>
                                                            </div>
                                                           <div id="token_show" class="d-none">
                                                                <h4>Token <span id="mode_text">{{optional($token)->mode}}</span></h4>
                                                                <div class="mb-1">
                                                                    <textarea name="token" id="token" value="{{ $token->token ?? old('token') }}" cols="30" rows="4"
                                                                        class="form-control form-control-lg">{{ $token->token ?? old('token') }}</textarea>
                                                                    <x-input-error class="" :messages="$errors->get('token')" />
                                                                </div>
                                                           </div>
                                                        </div>
                                                        <div
                                                            class="content-header-right text-md-end col-md-12 col-12 d-md-block">
                                                            <div class="col-12">
                                                                <button type="submit"
                                                                    class="btn btn-primary me-1">Save</button>
                                                                <button type="reset"
                                                                    class="btn btn-outline-secondary">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</x-app-layout>
@include('setting.partials.setting-js')