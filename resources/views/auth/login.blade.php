@extends('layout.app')
@section('title', 'Login - ')
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
@endpush
@section('content')
    <div class="form-wraper user_login">
        <div class="container">
            <div class="form-content">
                <div class="inner-divsssssss col-lg-7">
                    <div class="border-divsssssssss">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6 col-12">
                                {{-- <div class="form-head">
                                    <div class="head p-1">
                                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">
                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                            </span>
                                        @endif
                                        <span class="head-text">
                                            {{ config('speeddigit.name') }}, {{ config('speeddigit.slogan')}}
                                        </span>
                                    </div>
                                </div> --}}

                                <div class="inner-div">
                                    <div class="border-div">

                                        <div class="main-form">
                                            <div class="form_inner">
                                                <div class="form-title">
                                                    <p>{{ __('User Login') }}</p>
                                                </div>
                                                <form action="{{ route('login') }}" method="POST">
                                                    @csrf
                                                    <div class="left-inner-addon input-container">
                                                        <i class="fa fa-user"></i>
                                                        <input required type="text" name="username_or_email" class="form-control form-st" value="{{ old('username_or_email') }}" placeholder="{{ __('Username Or Email Address') }}" />
                                                    </div>

                                                    <div class="left-inner-addon input-container">
                                                        <i class="fa fa-key"></i>
                                                        <input required name="password" type="Password" class="form-control form-st rounded-bottom" placeholder="{{ __('Password') }}" />
                                                    </div>

                                                    @if (Session::has('errorMsg'))
                                                        <div class="bg-danger p-3 mt-4">
                                                            <p class="text-white">
                                                                {{ session('errorMsg') }}
                                                            </p>
                                                        </div>
                                                    @endif

                                                    <button type="submit" class="submit-button">{{ __('Login') }}</button>
                                                    <div class="login_opt_link">
                                                        @if (Route::has('password.request'))
                                                            <a class="forget-pw" href="{{ route('password.request') }}"> &nbsp;
                                                                {{ __('Forgot Your Password?') }}</a>
                                                        @endif
                                                        <div class="form-group cx-box">
                                                            <input type="checkbox" id="remembar" class="form-control">
                                                            <label for="remembar">{{ __('Remember Me') }}</label>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12 d-none">
                                <div class="form-head addr">
                                    <div class="head addr-t">

                                        <div class="px-2">
                                            {{-- Static --}}
                                            {{-- <p class="logo-main-sec">
                                                <img src="{{ asset(config('speeddigit.app_logo')) }}" class="logo" alt="{{ config('speeddigit.app_logo_alt') }}">
                                                <p class="version"><span>@lang('menu.version') :</span>{{ config('speeddigit.version') }}</p>
                                            </p> --}}
                                            {{-- Static End--}}

                                            <p class="logo-main-sec">
                                                @if ($businessInfo['business_or_shop__business_logo'])
                                                    <img src="{{ asset('uploads/business_logo/' . $businessInfo['business_or_shop__business_logo']) }}" class="logo" alt="Accotix Erp Logo">
                                                @else
                                                    <h4 class="text-white mb-1">{{ $businessInfo['business_or_shop__business_name'] }}</h4>
                                                @endif

                                                <p class="version"><span>@lang('menu.version') :</span>{{ config('speeddigit.version') }}</p>
                                            </p>

                                            <table class="text-white">
                                                <tbody>
                                                    <tr class="details">
                                                        <td>{{ __('Address') }} :</td>
                                                        <td>{{ config('speeddigit.address') }}</td>
                                                    </tr>
                                                    <tr class="details">
                                                        <td>{{ __('Support') }} :</td>
                                                        <td>{{ config('speeddigit.support_email') }}</td>
                                                    </tr>
                                                    <tr class="details">
                                                        <td>{{ __('Website') }} :</td>
                                                        <td>{{ config('speeddigit.website') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="function-btn">
                                                <a href="{{ config('speeddigit.facebook') }}" target="_blank"><span class="btn-fn facebook"><i class="fab fa-facebook"></i></span></a>
                                                <a href="{{ config('speeddigit.twitter') }}" target="_blank"><span class="btn-fn twitter"><i class="fab fa-twitter"></i></span></a>
                                                <a href="{{ config('speeddigit.youtube') }}" target="_blank"><span class="btn-fn youtube"><i class="fab fa-youtube"></i></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="back_btn_wrapper">
        <div class="back_btn">
            <a href="#" class="btn">@lang('menu.back')</a>
        </div>
    </div> --}}
@endsection

<style>
    .head {
        padding-top: 10px !important;
    }

    .back_btn_wrapper {
        position: fixed;
        top: 10px;
        right: 10px;
        padding: 6px;
        border-radius: 3px;
        box-shadow: -1px 0px 10px 1px #0a0a0a52;
    }

    .back_btn_wrapper .back_btn {
        border: 1px solid #0f76b673;
        padding: 0px 5px;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 0 5px #666;
        box-shadow: inner 0 0 5px #666;
    }

    .back_btn_wrapper .back_btn a {
        color: white;
        font-size: 13px;
    }

    .back_btn_wrapper .back_btn a:focus {
        outline: unset;
        box-shadow: unset;
    }

    .user_login .form-title {
        background: unset;
        -webkit-box-shadow: unset;
        margin-top: -10px;
    }

    .user_login input.form-control.form-st {
        background: unset;
        border: 1px solid #ffffff69;
        border-radius: 4px;
        color: white;
    }

    .user_login .left-inner-addon.input-container {
        margin-bottom: 3px;
    }

    .main-form {
        padding: 6px;
        border-radius: 3px;
        box-shadow: -1px 0px 10px 1px #0a0a0a52;
    }

    .user_login .form_inner {
        border: 1px solid #0f76b673;
        padding: 12px 5px;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 0 5px #666;
        box-shadow: inner 0 0 5px #666;
    }

    .left-inner-addon i {
        color: #f5f5f5 !important;
    }

    .btn-fn {
        width: 30px !important;
        height: 30px !important;
    }

    .btn-fn a {
        color: white;
    }

    .btn-fn a:hover {
        color: white;
    }

    .btn-fn.facebook {
        background: #3A5794;
    }

    .btn-fn.twitter {
        background: #1C9CEA;
    }

    .btn-fn.youtube {
        background: #F70000;
    }

    .version {
        margin-bottom: 5px;
        color: white;
        font-weight: 400;
        font-size: 10px
    }

    .logo-main-sec {
        margin-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .function-btn {
        margin-top: 9px !important;
    }

    .login_opt_link .form-group input {
        display: inline-block;
    }

    table {
        width: min-content;
        margin: auto !important;
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }

    table td:first-child {
        min-width: 60px;
    }
</style>

@push('js')
@endpush
