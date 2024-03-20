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
