<x-guest-layout>
    <style>
     .container, .container-fluid {
      width: 100%;
      margin-right: auto;
      margin-left: auto;
    }
    
    @media (min-width: 576px) {
      .container {
        max-width: 540px;
      }
    }
    @media (min-width: 768px) {
      .container {
        max-width: 720px;
      }
    }
    @media (min-width: 992px) {
      .container {
        max-width: 960px;
      }
    
      #sticky {
        text-align: center;
        padding: 1px;
        font-size: 1.75em;
        color: #FFF;
        z-index: 0;
        height: 130px !important;
      }
      #sticky.stick {
        z-index: 1;
        height: 130px !important;
      }
    
      .header.top-header {
        z-index: 999 !important;
      }
    
      .app-content.page-body {
        margin-top: 9.5rem;
      }
    
      .comb-page .app-content.page-body {
        margin-top: 0;
      }
    }
    @media (min-width: 1280px) {
      .container {
        max-width: 1200px;
      }
    }
    /* .login-background {
      background-color: #313338 !important;
    } */
    .input-box {
        color: #FFF !important;
    }
    .custom-switch-input:checked ~ .custom-switch-indicator {
      background: #007BFF;
    }
    .custom-switch-input:focus ~ .custom-switch-indicator {
      border-color: #007BFF;
    }
    .custom-switch {
      padding-left: 0;
      font-size: 12px;
    }
    
    .custom-switch-input {
      position: absolute;
      z-index: -1;
      opacity: 0;
    }
    
    .custom-switch-indicator {
      display: inline-block;
      height: 1.25rem;
      width: 2.25rem;
      background: #f5f9fc;
      border-radius: 50px;
      position: relative;
      vertical-align: bottom;
      border: 1px solid #ebecf1;
      transition: 0.3s border-color, 0.3s background-color;
    }
    .custom-switch-indicator:before {
      content: "";
      position: absolute;
      height: calc(1.25rem - 4px);
      width: calc(1.25rem - 4px);
      top: 1px;
      left: 1px;
      background: #fff;
      border-radius: 50%;
      transition: 0.3s left;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.4);
    }
    
    .custom-switch-input:checked ~ .custom-switch-indicator:before {
      left: calc(1rem + 1px);
    }
    
    .custom-switch-input:focus ~ .custom-switch-indicator {
      box-shadow: none;
    }
    
    .custom-switch-description {
      margin-left: 0.5rem;
      color: #6e7687;
      transition: 0.3s color;
    }
    
    .custom-switch-input:checked ~ .custom-switch-description {
      color: #1e1e2d;
    }
    .login-main-button {
      width: 50%;
      font-size: 14px !important;
      text-transform: none !important;
    }
    
    #login-footer {
      bottom: 0;
      left: 32.5%;
    }
    .background-special {
      background: #f5f9fc !important;
    }
    .align-middle {
      vertical-align: middle !important;
    }
    #login-background .login-bg {
      background: linear-gradient(230deg, #007bff, #000);
      /* background-size: 300% 300%; */
      -webkit-animation: login-gradient 30s ease infinite;
              animation: login-gradient 30s ease infinite;
      height: 100vh;
      margin: 0 auto;
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    #login-responsive .card-body {
        padding: 5rem 1.5rem !important;
      }
      #login-responsive #login-footer {
        position: relative;
        left: auto !important;
      }
      .mr-auto, .mx-auto {
    margin-right: auto !important;
    }

    .mb-auto, .my-auto {
    margin-bottom: auto !important;
    }

    .ml-auto, .mx-auto {
    margin-left: auto !important;
    }
    .fs-12 {
    font-size: 12px;
    }
    .input-box {
    margin-bottom: 1.5rem;
    }
    .input-box h6 {
    font-family: "Poppins", sans-serif;
    margin-bottom: 0.6rem;
    font-weight: 600;
    font-size: 11px;
    }
    .input-box .form-control {
    font-family: "Poppins", sans-serif;
    font-size: 12px;
    color: #1e1e2d;
    -webkit-appearance: none;
    -moz-appearance: none;
    outline: none;
    appearance: none;
    border-radius: 0.5rem;
    border-width: 1px;
    font-weight: 400;
    line-height: 1rem;
    padding: 0.75rem 1rem;
    width: 100%;
    }
    .input-box input:hover, .input-box input:focus {
    border-color: #007BFF;
    box-shadow: none;
    transition: all 0.2s;
    }
    .input-box textarea {
    font-weight: 400 !important;
    }
    .input-box textarea:hover, .input-box textarea:focus {
    border-color: #007BFF;
    box-shadow: none;
    transition: all 0.2s;
    }
    .font-weight-bold {
    font-weight: bold !important;
    }
</style>
    
        {{-- <form method="POST" action="{{ route('login') }}">
            @csrf
    
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
    
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>
    
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
    
                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form> --}}
        <div class="container-fluid h-100vh ">
            <div class="row login-background justify-content-center">
                <div class="col-md-6 col-sm-12" id="login-responsive"> 
                    <div class="row justify-content-center">
                        <div class="col-lg-7 mx-auto">
                            <div class="card-body pt-10 mt-5">
        
                                <div class="dropdown header-locale" id="frontend-local-login">
                                    {{-- <a class="icon" data-bs-toggle="dropdown">
                                        <span class="fs-12 mr-4"><i class="fa-solid text-black fs-16 mr-2 fa-globe"></i>globe icon</span>
                                    </a> --}}
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                                        <div class="local-menu">
                                        </div>
                                    </div>
                                </div>
        
                                <form method="POST" action="{{ route('login') }}" onsubmit="process()">
                                    @csrf                                       
                
                                    <h3 class="text-center font-weight-bold login-title mb-8 mt-3">{{ __('Welcome Back to') }} <span class="text-info"><a href="">{{ config('app.name') }}</a></span></h3>
                
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-login alert-success"> 
                                            <strong><i class="fa fa-check-circle"></i> {{ $message }}</strong>
                                        </div>
                                        @endif
                
                                        @if ($message = Session::get('error'))
                                        <div class="alert alert-login alert-danger">
                                            <strong><i class="fa fa-exclamation-triangle"></i> {{ $message }}</strong>
                                        </div>
                                    @endif
                                    
                                    
                
                                    <div class="input-box mb-2 mt-3">                             
                                        <label for="email" class="fs-12 font-weight-bold text-md-right text-dark">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off" placeholder="{{ __('Email Address') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                {{ __($message) }}
                                            </span>
                                        @enderror                            
                                    </div>
                
                                    <div class="input-box">                            
                                        <label for="password" class="fs-12 font-weight-bold text-md-right text-dark">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off" placeholder="{{ __('Password') }}" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                {{ __($message) }}
                                            </span>
                                        @enderror                           
                                    </div>
                
                                    <div class="form-group mb-3">  
                                        <div class="d-flex">                        
                                            <label class="custom-switch">
                                                <input type="checkbox" class="custom-switch-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">{{ __('Keep me logged in') }}</span>
                                            </label>   
                
                                            {{-- <div class="ml-auto">
                                                @if (Route::has('password.request'))
                                                    <a class="text-primary fs-12" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                                @endif
                                            </div> --}}
                                        </div>
                                    </div>
                
                                    <input type="hidden" name="recaptcha" id="recaptcha">
                
                                    <div class="text-center">
                                        <div class="form-group mb-0">                        
                                            <button type="submit" class="btn btn-primary font-weight-bold login-main-button" id="sign-in">{{ __('Sign In') }}</button>              
                                        </div>
                    
                                        
                                        <p class="fs-10 text-muted pt-3 mb-0">{{ __('New to ') }} <a href="{{ url('/') }}" class="special-action-sign">{{ config('app.name') }}?</a></p>
                                        <a href="{{ route('register') }}"  class="fs-12 font-weight-bold special-action-sign text-dark">{{ __('Sign Up') }}</a> 
                                        
                                    </div>
                
                                </form>
                            </div>
                        </div>
                    </div>               
                         
                </div>
        
                <div class="col-md-6 col-sm-12 text-center background-special align-middle p-0" id="login-background">
                    <div class="login-bg">
                        <img src="{{ URL::asset('images/link4.jpeg') }}" alt="" style="max-width: 99% !important; max-height: 100% !important;">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- END: Content-->
    </x-guest-layout>
    