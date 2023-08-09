<x-admin::guest-layout>
    <div class="login-body">
        <div class="top d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="{{ asset('modules/saas/images/logo_black.png') }}" alt="Logo">
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
                    <input id="password" type="password" class="form-control rounded-end @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                    <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                {{-- <p>{{ __('Or continue with') }}</p>
                <div class="social-box d-flex justify-content-center gap-20">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div> --}}
            </div>
        </div>
    </div>
</x-admin::guest-layout>
