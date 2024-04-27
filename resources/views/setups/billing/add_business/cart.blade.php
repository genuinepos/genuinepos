@extends('setups.billing.layout.master', ['heading' => 'Add Multi Shop Management System'])
@section('title', 'Add Multi Shop Management System - ')
@push('css')
@endpush
@section('content')
    @php
        $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
    @endphp

    <div class="tab-section py-120 mt-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav single-tab stepOneTab active" data-tab="stepOneTab">
                            <span class="txt">{{ __('Step One') }}</span>
                            <span class="sl-no">{{ __('01') }}</span>
                        </button>

                        <button class="single-nav single-tab stepTwoTab" data-tab="stepTwoTab">
                            <span class="txt">{{ __('Step Two') }}</span>
                            <span class="sl-no">{{ __('02') }}</span>
                        </button>
                    </div>

                    <div class="tab-contents">
                        <form id="add_business_form" action="{{ route('software.service.billing.add.business.confirm') }}" method="post">
                            @csrf
                            <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan->id }}" />
                            <input type="hidden" name="payment_status" value="1">
                            <input type="hidden" name="payment_method_name" value="Cash-On-Delivery">
                            <input type="hidden" name="payment_trans_id" value="N/A">
                            <div class="single-tab active" id="stepOneTab">
                                @include('setups.billing.add_business.partials.cart_partials.step_one')
                            </div>

                            <div class="single-tab" id="stepTwoTab">
                                @include('setups.billing.add_business.partials.cart_partials.step_two')
                            </div>
                        </form>

                        <div class="single-tab" id="stepThreeTab">
                            <div class="check-icon">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <div class="order-complete-msg">
                                <h2>{{ __('Process Has Been Completed') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    @include('setups.billing.add_business.partials.cart_partials.js_partial.js')
@endpush
