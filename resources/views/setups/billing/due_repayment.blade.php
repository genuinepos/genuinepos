{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>Due Repayment - GPOSS</title>

    @php
        $rtl = app()->isLocale('ar');
    @endphp

    @if ($rtl)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
</head>

<body class="inner">
    <div class="tab-section py-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-contents">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Plan</th>
                                                <th>Price</th>
                                                <th>Store Quantity</th>
                                                <th>Years</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>Enterpiese</p>
                                                </td>
                                                <td class=""><span class="price-txt">$<span class="main-price">460</span></span></td>
                                                <td>1</td>
                                                <td><span class="price-txt text-danger">2</td>
                                                <td><span class="price-txt">$<span class="total-price">460</span></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="payment-method">
                                            <div class="cart-total-panel">
                                                <h3 class="title">Due Amount : TK. 5,600.00</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="payment-method">
                                            <div class="payment-option">
                                                <div class="single-payment-card">
                                                    <div class="panel-header">
                                                        <div class="left-wrap">
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="credit-card" type="checkbox" disabled>
                                                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                            </div>
                                                            <span class="type">
                                                                Credit Card
                                                            </span>
                                                        </div>
                                                        <span class="icon">
                                                            <img src="{{ asset('backend/images/credit-card.png') }}" alt="credit-card">
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="single-payment-card">
                                                    <div class="panel-header">
                                                        <div class="left-wrap">
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="paypal" type="checkbox" disabled>
                                                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                            </div>
                                                            <span class="type">
                                                                PayPal
                                                            </span>
                                                        </div>
                                                        <span class="icon">
                                                            <img src="{{ asset('backend/images/paypal.png') }}" alt="paypal">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="single-payment-card">
                                                    <div class="panel-header">
                                                        <div class="left-wrap">
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="google-pay" type="checkbox" disabled>
                                                                <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                            </div>
                                                            <span class="type">
                                                                Google Pay
                                                            </span>
                                                        </div>
                                                        <span class="icon">
                                                            <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="def-btn palce-order tab-next-btn btn-success" id="palceOrder">Payment</button>
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
    <!--------------------------------- CART SECTION END --------------------------------->

    <!-- js files -->
    <script src="{{ asset('backend/js/jquery-1.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/cart.js') }}"></script>
</body>

</html> --}}
@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Due Repayment - ')
@section('content')
    @push('stylesheets')
        <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Due Repayment') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card p-2">
                <div class="tab-section py-120">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tab-contents">
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-8 col-md-8">
                                            <div class="table-responsive">
                                                <table class="display table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>S/L</th>
                                                            <th>Plan</th>
                                                            <th>Price</th>
                                                            <th>Store Quantity</th>
                                                            <th>Years</th>
                                                            <th>Total Amount</th>
                                                            <th>Due Amount</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <p>1</p>
                                                            </td>
                                                            <td>
                                                                <p>Enterpiese</p>
                                                            </td>
                                                            <td class=""><span class="price-txt">$<span class="main-price">460</span></span></td>
                                                            <td>1</td>
                                                            <td><span class="price-txt text-danger">2</td>
                                                            <td><span class="price-txt">$460</span></td>
                                                            <td><span class="price-txt text-danger">$460</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-5 col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="payment-method">
                                                        <div class="cart-total-panel">
                                                            <h3 class="title">Due Amount : TK. 5,600.00</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="payment-method">
                                                        <div class="payment-option">
                                                            <div class="single-payment-card">
                                                                <div class="panel-header">
                                                                    <div class="left-wrap">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="credit-card" type="checkbox" disabled>
                                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                                        </div>
                                                                        <span class="type">
                                                                            Credit Card
                                                                        </span>
                                                                    </div>
                                                                    <span class="icon">
                                                                        <img src="{{ asset('backend/images/credit-card.png') }}" alt="credit-card">
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="single-payment-card">
                                                                <div class="panel-header">
                                                                    <div class="left-wrap">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="paypal" type="checkbox" disabled>
                                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                                        </div>
                                                                        <span class="type">
                                                                            PayPal
                                                                        </span>
                                                                    </div>
                                                                    <span class="icon">
                                                                        <img src="{{ asset('backend/images/paypal.png') }}" alt="paypal">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="single-payment-card">
                                                                <div class="panel-header">
                                                                    <div class="left-wrap">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="google-pay" type="checkbox" disabled>
                                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                                        </div>
                                                                        <span class="type">
                                                                            Google Pay
                                                                        </span>
                                                                    </div>
                                                                    <span class="icon">
                                                                        <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="def-btn palce-order tab-next-btn btn-success" id="palceOrder">Payment</button>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/js/cart.js') }}"></script>
@endpush
