@extends('layout.app')

@section('title', 'Login - ')

    @push('css')

    @endpush

@section('content')
    {{-- <div class="form-wraper" style="background: #448aff"> --}}
    <div class="form-wraper">
        <div class="container">
            <div class="form-content">
                <div class="inner-div col-lg-7">
                    <div class="border-div">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-head">
                                    <div class="head p-1">
                                        <img src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}" alt="POS" class="logo">
                                        <span class="head-text">
                                            Genuine POS, Point of Sale software by SpeedDigit
                                        </span>
                                    </div>
                                </div>

                                <div class="main-form">
                                    <div class="form-title">
                                        <p>Admin Login</p>
                                    </div>
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="left-inner-addon input-container">
                                            <i class="fa fa-envelope"></i>
                                            <input name="username" class="form-control form-st"
                                                placeholder="Username" required/>
                                        </div>
                                        <div class="left-inner-addon input-container">
                                            <i class="fa fa-key"></i>
                                            <input name="password" type="Password"
                                                class="form-control form-st rounded-bottom" placeholder="Password" required />
                                        </div>
                                        @if (Session::has('errorMsg'))
                                            <div class="bg-danger p-3 mt-4 mx-2">
                                                <p class="text-white">
                                                    {{ session('errorMsg') }}
                                                </p>
                                            </div>
                                        @endif
                                        <button type="submit" class="submit-button">Login</button>
                                    </form>
                                    <div class="login_opt_link">
                                        @if (Route::has('password.request'))
                                            <a class="forget-pw" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                        {{-- <div class="form-group cx-box">
                                            <input type="checkbox" id="remembar" class="form-control">
                                            <label for="remembar">Remembar me</label>
                                        </div> --}}


                                    </div>


                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-head addr">
                                    <div class="head addr-t pt-4">
                                        {{-- <h2>
                                            Genuine Point Of Sale
                                        </h2> --}}
                                        <div class="px-2">
                                            <p class="logo-main-sec"><img src="{{ asset('public/assets/images/genuine_pos.png') }}" alt="POS" class="logo">
                                            </p>
                                            <p class="details"><span>Address:</span> Motijheel Arambagh, Dhaka</p>
                                            <p class="details"><span>Support:</span> support@speeddigit.com</p>
                                            <p class="details"><span>Web Address:</span> www.speeddigit.com</p>

                                            <div class="function-btn">
                                                <span class="btn-fn">P</span>
                                                <span class="btn-fn">O</span>
                                                <span class="btn-fn">S</span>
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
@endsection


@push('js')

@endpush
