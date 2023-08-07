{{-- <x-saas::guest title="Login">
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                <h1 class="">Login to {{config('app.name')}}</h1>
            </div>
            <div class="card-body">
                <div class="">
                    <form method="POST" action="{{ route('saas.login') }}">
                        @csrf

                        <div class="form-group  mb-2 row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  mb-2 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  mb-2 row mb-0">
                            <div class="ms-2">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                                <div class="col-md-6 offset-md-4 mt-2">
                                    <p>{{ __('Don\'t have an account? ') }} <a href="{{ route('saas.register') }}">{{ __('Register here') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-saas::guest> --}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('modules/saas/images/favicon.png') }}">
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('modules/saas') }}/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">

    @stack('css')
</head>

<body class="dark-theme">
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->

    <!-- theme color hidden button -->
    <button class="header-btn theme-color-btn d-none"><i class="fa-light fa-sun-bright"></i></button>
    <!-- theme color hidden button -->

    <!-- main content start -->
    <div class="main-content login-panel">
        <div class="login-body">
            <div class="top d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="{{ asset('modules/saas/images/logo_white.png') }}" alt="Logo">
                </div>
                <a href="/"><i class="fa-duotone fa-house-chimney"></i></a>
            </div>
            <div class="bottom">
                <h3 class="panel-title">{{ __('Login') }}</h3>
                <form method="POST" action="{{ route('saas.login') }}">
                    @csrf
                    <div class="input-group mb-30">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                    </div>
                    <div class="d-flex justify-content-between mb-30">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="loginCheckbox">
                            <label class="form-check-label text-white" for="loginCheckbox">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        <a href="#" class="text-white fs-14">{{ __('Forgot Password?') }}</a>
                    </div>
                    <button class="btn btn-primary w-100 login-btn">{{ __('Sign in') }}</button>
                </form>
                <div class="other-option">
                    <p>{{ __('Don\'t have an account? ') }} <a href="{{ route('saas.register') }}">{{ __('Register here') }}</a></p>
                    <p>{{ __('Or continue with') }}</p>
                    <div class="social-box d-flex justify-content-center gap-20">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-google"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <x-saas::admin-footer />
    </div>
    <!-- main content end -->
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('modules/saas') }}/js/main.js"></script>
</body>

</html>
