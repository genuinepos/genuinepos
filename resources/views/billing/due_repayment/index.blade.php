@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
@endpush
@section('title', 'Due Repayment - ')
@section('content')
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
            <div class="card py-2">
                <div class="tab-section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="tab-contents">
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-8 col-md-8">
                                            <div class="table-responsive">
                                                <table class="display table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __("S/L") }}</th>
                                                            <th>{{ __("Purchase Type") }}</th>
                                                            <th>{{ __("Plan") }}</th>
                                                            <th>{{ __("Price") }}</th>
                                                            <th>{{ __("Store Quantity") }}</th>
                                                            <th>{{ __("Multi Store Management System") }}({{ __("Company") }})</th>
                                                            <th>{{ __("Price Period") }}</th>
                                                            <th>{{ __("Net Total") }}</th>
                                                            <th>{{ __("Discount") }}</th>
                                                            <th>{{ __("Total Payable") }}</th>
                                                            <th>{{ __("Paid") }}</th>
                                                            <th>{{ __("Due") }}</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <p>1</p>
                                                            </td>
                                                            <td>
                                                                <p>{{ __('Buy Plan') }}</p>
                                                            </td>
                                                            <td>
                                                                <p>{{ $dueSubscriptionTransaction?->plan?->name }}</p>
                                                            </td>
                                                            <td class="">{{ $dueSubscriptionTransaction?->details?->shop_price }}</td>
                                                            <td>{{ $dueSubscriptionTransaction?->details?->shop_count }}</td>
                                                            <td>
                                                                @if ($dueSubscriptionTransaction?->details?->has_business == 1)
                                                                    <span class="text-success">Yes</span>
                                                                @else
                                                                    <span class="text-danger">No</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $dueSubscriptionTransaction?->details?->shop_price_period }}</td>
                                                            <td class="fw-bold">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->net_total) }}</td>
                                                            <td class="fw-bold">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->discount) }}</td>
                                                            <td class="fw-bold">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->total_payable_amount) }}</td>
                                                            <td class="text-success fw-bold">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->paid) }}</td>
                                                            <td class="text-danger fw-bold">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->due) }}</td>
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
                                                            <h3 class="title">{{ __("Due Amount") }} : <span class="text-danger">{{ \App\Utils\Converter::format_in_bdt($dueSubscriptionTransaction?->due) }}</span></h3>
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
                                                                            {{ __("Credit Card") }}
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
                                                                            {{ __("PayPal") }}
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
                                                                            {{ __("Google Pay") }}
                                                                        </span>
                                                                    </div>
                                                                    <span class="icon">
                                                                        <img src="{{ asset('backend/images/google-pay.png') }}" alt="google-pay">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- <button class="def-btn palce-order tab-next-btn btn-success" id="palceOrder">Payment</button> --}}
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
    {{-- <script src="{{ asset('backend/js/cart.js') }}"></script> --}}
@endpush
