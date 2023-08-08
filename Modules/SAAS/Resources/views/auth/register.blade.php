<x-admin::guest-layout title="Register">
    <div class="login-body">
        <div class="top d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="{{ asset('assets/images/logo_white.png') }}" alt="{{ config('app.name') }}">
            </div>
            <a href="/"><i class="fa-duotone fa-house-chimney"></i></a>
        </div>
        <div class="bottom">
            <h3 class="panel-title">{{ __('Registration') }}</h3>
            <form method="POST" action="{{ route('saas.register') }}" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-30">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}">
                </div>
                <div class="input-group mb-30">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}">
                </div>
                <div class="input-group mb-20">
                    <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                    <input type="password" name="password" class="form-control rounded-end" placeholder="{{ __('Password') }}">
                    <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                </div>
                <div class="input-group mb-20">
                    <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control rounded-end" placeholder="{{ __('Confirm Password') }}">
                    <a role="button" class="password-show"><i class="fa-duotone fa-eye"></i></a>
                </div>
                <div class="d-flex justify-content-between mb-30">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="registerCheckbox" required>
                        <label class="form-check-label text-white" for="registerCheckbox">
                            {{ __('I agree') }} {{ config('app.name') }}
                            <a href="#" class="text-white text-decoration-underline">
                                {{ __('Terms & Conditions') }}
                            </a>
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary w-100 login-btn">{{ __('Register') }}</button>
            </form>
            <div class="other-option">
                <p>{{ __('Already have an account? ') }} <a href="{{ route('saas.login') }}">{{ __('Login here') }}</a></p>
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
