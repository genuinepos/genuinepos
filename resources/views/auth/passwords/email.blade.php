@extends('layout.app')
@section('title', 'Forget Password - ')
@section('content')
    <div class="form-wraper">
        <div class="container">
            <div class="form-content">
                <div class="col-lg-4 col-md-5 col-12">
                    <div class="form-head">
                        <div class="head">
                            <img src="{{ asset('public/assets/images/genuine_pos.png') }}" alt="" class="logo">
                            <span class="head-text">
                                Genuine POS, Point of Sale software by SpeedDigit
                            </span>
                        </div>
                    </div>
                    {{-- Alert --}}
                    <div>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="main-form">
                        <div class="form-title">
                            <p>Forgot Password</p>
                        </div>
                        <form action="{{ route('password.email') }}" method="POST">
                            <div class="left-inner-addon input-container">
                                <i class="fa fa-envelope"></i>
                                <input type="email" class="form-control form-st rounded-bottom
                                            @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="Enter Your Email" required autocomplete="email" autofocus />
                            </div>
                            <button type="submit" class="submit-button">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
