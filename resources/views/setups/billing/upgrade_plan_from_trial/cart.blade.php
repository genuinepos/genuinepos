<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>Cart - GPOSS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $rtl  = app()->isLocale('ar');
    @endphp

    @if($rtl)
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
                    <div class="tab-nav">
                        <button class="single-nav active" data-tab="cartTab">
                            <span class="txt">Step One</span>
                            <span class="sl-no">01</span>
                        </button>

                        <button class="single-nav" data-tab="checkOutTab" disabled>
                            <span class="txt">Step Two</span>
                            <span class="sl-no">02</span>
                        </button>

                        <button class="single-nav" data-tab="orderCompletedTab" disabled>
                            <span class="txt">Step Three</span>
                            <span class="sl-no">03</span>
                        </button>
                    </div>

                    <div class="tab-contents">
                        <div class="single-tab active" id="cartTab">
                            <div class="table-wrap revel-table">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Package Name</th>
                                                <th>Price</th>
                                                <th>Store Quantity</th>
                                                <th>Total Price</th>
                                                <th>Adjustable Amount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td><p>Previous package (<b>{{ $currentSubscription->plan->name }}</b>) <i class="fa-solid fa-xmark text-danger"></i></p></td>
                                                <td class=""><span class="price-txt">$<span class="main-price">{{ $currentSubscription->plan->price_per_year }}</</span></span></td>
                                                <td>
                                                    1
                                                    {{-- <div class="product-count cart-product-count">
                                                        <div class="quantity rapper-quantity">
                                                            <input class="bg-secondary" type="number" min="1" max="100" step="1" value="1" readonly>
                                                            <div class="quantity-nav">
                                                                <div class="quantity-button-disabled">
                                                                    <i class="fa-solid fa-minus"></i>
                                                                </div>

                                                                <div class="quantity-button-disabled">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    {{-- <input class="bg-secondary" type="number" min="1" max="100" step="1" value="1" readonly> --}}
                                                </td>
                                                <td><span class="price-txt text-danger">$<span class="total-price">{{ $currentSubscription->plan->price_per_year }}</span></span></td>
                                                <td><span>$<span class="adjusted-price">0.00</span></span></td>
                                                <td><span>$<span class="total-price">{{ $currentSubscription->plan->price_per_year }}</</span></span></td>
                                            </tr>

                                            <tr>
                                                <td>{{  $plan->name }}</td>
                                                <td><span class="price-txt">$<span class="main-price">{{ $plan->price_per_year }}</span></span></td>
                                                <td>
                                                    {{-- <div class="product-count cart-product-count">
                                                        <div class="quantity rapper-quantity">
                                                            <input type="number" min="1" max="100" step="1" value="1" readonly>
                                                            <div class="quantity-nav">
                                                                <div class="quantity-button quantity-down">
                                                                    <i class="fa-solid fa-minus"></i>
                                                                </div>
                                                                <div class="quantity-button quantity-up">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    1
                                                </td>
                                                <td><span class="price-txt">$<span class="total-price">{{ $plan->price_per_year }}</span></span></td>
                                                <td>
                                                    <span class="price-txt text-danger">$<span class="adjusted-price">{{ $currentSubscription->plan->is_trial_plan ? 0 : 120  }}</span></span>
                                                </td>
                                                <td><span class="price-txt">$<span class="total-price">{{ $plan->price_per_year }}</span></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="btn-box">
                                    <form action="#" class="cart-coupon-form">
                                        <input type="text" name="cart-coupon-input" id="cart-coupon-input" placeholder="Enter Your Coupon Code">
                                        <button type="submit" class="def-btn coupon-apply-btn">Apply Coupon</button>
                                    </form>
                                </div>
                            </div>

                            <div class="cart-total-panel">
                                <h3 class="title">Cart Total</h3>
                                <div class="panel-body">
                                    <div class="row gy-5">
                                        <div class="col-12">
                                            <div class="calculate-area">
                                                <ul>
                                                    <li>Total Store Quantity <span class="price-txt"><span class="">1</span></span></li>
                                                    <li>Net Total <span class="price-txt">$<span class="sub-total">{{ $plan->price_per_year }}</span></span></li>
                                                    <li>Adjusted Amount <span class="price-txt">$<span class="sub-total">0</span></span></li>
                                                    <li>Tax <span class="price-txt" id="tax"><span class="text-success">Free</span></span></li>
                                                    <li>Discount <span class="price-txt" id="discount"><span>0</span></span></li>
                                                    <li class="total-price-wrap">Total <span class="price-txt">$<span id="totalPrice">{{ $plan->price_per_year }}</span></span></li>
                                                </ul>
                                                <button class="def-btn tab-next-btn" id="proceedToCheckout">Proceed to checkout</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-tab" id="checkOutTab">
                            <div class="row">
                                <div class="col-xl-4 col-lg-5 col-md-6">
                                    <div class="payment-method">
                                        <div class="cart-total-panel">
                                            <h3 class="title">Cart Total</h3>
                                            <div class="panel-body">
                                                <div class="row gy-5">
                                                    <div class="col-12">
                                                        <div class="calculate-area">
                                                            <ul>
                                                                <li>Total Store Quantity <span class="price-txt"><span class="">1</span></span></li>
                                                                <li>Net Total <span class="price-txt">$<span class="sub-total">{{ $plan->price_per_year }}</span></span></li>
                                                                <li>Adjusted Amount <span class="price-txt">$<span class="sub-total">0</span></span></li>
                                                                <li>Tax <span class="price-txt" id="tax"><span class="text-success">Free</span></span></li>
                                                                <li>Discount <span class="price-txt" id="discount"><span>0</span></span></li>
                                                                <li class="total-price-wrap">Total <span class="price-txt">$<span id="totalPrice">{{ $plan->price_per_year }}</span></span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-5 col-md-6">
                                    <div class="payment-method">
                                        <div class="payment-option">
                                            {{-- <div class="single-payment-card">
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
                                                <div class="panel-body">
                                                    <form class="credit-card-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <div class="input-box card-number">
                                                                    <span class="symbol">
                                                                        <img src="{{ asset('backend/images/visa.png') }}" alt="Card Type">
                                                                    </span>
                                                                    <label>Card Number</label>
                                                                    <input type="text" id="creditCardNumber" placeholder="XXXX XXXX XXXX XXXX">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-box">
                                                                    <label>Expiry date</label>
                                                                    <input type="text" id="datepicker" placeholder="MM/YYYY">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-box">
                                                                    <label>Security code</label>
                                                                    <input type="number" id="securityCode" placeholder="e.g. 123">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="input-box">
                                                                    <label>Enter card holder name</label>
                                                                    <input type="text" id="cardHolderName" placeholder="Card holder">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="single-payment-card">
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
                                                <div class="panel-body">
                                                    <form class="paypal-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <label>Email or phone no. that used in paypal</label>
                                                                <input type="email" name="paypal-id" id="paypalId" placeholder="Email or mobile number" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <button type="submit" id="confirmPaypal" class="def-btn">Confirm Paypal</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> --}}

                                            <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="google-pay" value="aamarpay">
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            Aamar Pay
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                    </span>
                                                </div>

                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="google-pay" value="sslcommarze">
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            SSLCOMMMARZE
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                    </span>
                                                </div>

                                                {{-- <div class="panel-body">
                                                    <form class="google-pay-form">
                                                        <div class="row g-lg-4 g-3">
                                                            <div class="col-12">
                                                                <label>Email or phone no. that used in google pay</label>
                                                                <input type="email" name="google-Pay-id" id="googlePayId" placeholder="Email or mobile number" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <button type="submit" id="confirmGooglePay" class="def-btn">Confirm Google Pay</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div> --}}
                                            </div>

                                            {{-- <div class="single-payment-card">
                                                <div class="panel-header">
                                                    <div class="left-wrap">
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="cash-on-delivery" name="cash" type="checkbox" disabled>
                                                            <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                        </div>
                                                        <span class="type">
                                                            Cash on delivery
                                                        </span>
                                                    </div>
                                                    <span class="icon">
                                                        <img src="{{ asset('backend/images/dollar.png') }}" alt="cash">
                                                    </span>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <input type="hidden" id="plan-id" value="{{ $plan->id }}" />
                                        <button class="def-btn palce-order tab-next-btn btn-success" type="button" id="palceOrder">
                                            Place Order <i class="fa-light fa-truck-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-tab" id="orderCompletedTab">
                            <div class="check-icon">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <div class="order-complete-msg">
                                <h2>Your Order Has Been Completed</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------- CART SECTION END --------------------------------->

    <!-- js files -->
    <script src="{{asset('backend/js/jquery-1.7.1.min.js')}}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/cart.js') }}"></script>

    <script>
        $(document).ready( function() {

        });
    </script>
</body>

</html>
