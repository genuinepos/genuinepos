<x-saas::guest title="{{ __('Confirm Plan') }}">
    @push('css')
        <style>
            header.cart-header {
                border: 1px solid;
                border-radius: 7px;
                padding: 10px;
            }

            header.cart-header .header-link-area ul li {
                display: inline-block;
            }

            .header-link-area ul li {
                line-height: 43px;
                padding: 1px 8px;
                font-size: 16px;
                font-weight: 500;
            }

            .badge {
                font-size: 2em;
            }

            div#response-message {
                position: fixed;
                top: 69%;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('backend/asset/css/animated-headline.css') }}">
    @endpush
    <header class="cart-header my-3">
        <div class="row">
            <div class="col-md-4">
                <img style="height: 50px;; width:150px;" src="http://gposs.com/wp-content/uploads/2023/05/cropped-GPOSs-logo-b.png" alt="">
            </div>
            <div class="col-md-8">
                <div class="header-link-area">
                    <ul class="list-unstyled">
                        <li>
                            <a href="https://gposs.com">{{ __('Home') }}</a>
                        </li>

                        <li>
                            <a href="https://gposs.com/pricing">{{ __('Pricing') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="row mt-4">
        <div class="col-12">
            <div class="tab-nav">
                <button class="single-nav active stepOneTab" data-tab="stepOneTab">
                    <span class="txt">{{ __('Step One') }}</span>
                    <span class="sl-no">{{ __('01') }}</span>
                </button>

                <button class="single-nav stepTwoTab" data-tab="stepTwoTab">
                    <span class="txt">{{ __('Step Two') }}</span>
                    <span class="sl-no">{{ __('02') }}</span>
                </button>

                <button class="single-nav stepThreeTab" data-tab="stepThreeTab">
                    <span class="txt">{{ __('Step Three') }}</span>
                    <span class="sl-no">{{ __('03') }}</span>
                </button>

                {{-- <button class="single-nav stepFourTab" id="single-nav" data-tab="stepFourTab">
                    <span class="txt">{{ __('Step Four') }}</span>
                    <span class="sl-no">{{ __('02') }}</span>
                </button> --}}
            </div>

            <div class="tab-contents mt-1">

                <form id="planConfirmForm" method="POST" action="{{ route('saas.guest.plan.confirm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_status" value="1">
                    <input type="hidden" name="payment_method_name" value="Cash-On-Delivery">
                    <input type="hidden" name="payment_trans_id" value="N/A">
                    <div class="single-tab active" id="stepOneTab">

                        @include('saas::guest.plan_confirm.partials.view_partials.step_one')
                    </div>

                    <div class="single-tab" id="stepTwoTab">
                        @include('saas::guest.plan_confirm.partials.view_partials.step_two')
                    </div>

                    <div class="single-tab" id="stepThreeTab">
                        @include('saas::guest.plan_confirm.partials.view_partials.step_three')
                    </div>
                </form>

                <div class="single-tab" id="stepFourTab">
                    {{-- <div id="successSection" class="d-none">
                        <div class="check-icon">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                            </svg>
                        </div>

                        <div class="order-complete-msg">
                            <h2>{{ __('App is created successfully') }}</h2>
                        </div>
                    </div> --}}

                    <div class="preloader">
                        <div id="loading" class="loading-screen d-flex align-items-center justify-content-center flex-column ">
                            <div class="o_pyro" ></div>
                            <div class="content d-inline-block text-center position-relative w-100 p-5">
                                <div class="container position-relative py-5" id="preloader-animitation-section">
                                    <div class="o_start_trial_message_container position-relative">
                                        <h1 class="o_start_trial_message text-white animate o_start_first one fadeInUpOne message">
                                            Welcome to GPOS System
                                            <span class="d-block">
                                                <span class="slides fadeInUpOne">
                                                    <span class="slide1">
                                                        <span>Build Your Shop</span>
                                                        <span>Manage Your Inventory</span>
                                                        <span>Mange Your Sales</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </h1>

                                        <h2 class="o_start_trial_message text-white animate o_start_second two fadeInUpTwo" >
                                            <span class="d-block">No. #1</span> Retail POS Software.
                                        </h2>
                                    </div>
                                </div>
                            </div>

                            <div id="response-message" class="mt-3 text-center" style="height: 100px;">
                                <div class="mt-2">
                                    <h6 id="response-message-text" style="color: white;">
                                        {{ __('Creating Your App. please wait...') }}
                                        {{ __('Elapsed Time') }}: <span id="timespan"></span> {{ __('Seconds') }}.

                                        <div class="spinner-border text-dark" role="status">
                                            <span class="visually-hidden" style="color: white!important;">{{ __('Loading') }}...</span>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        {{-- <script src="{{ asset('backend/asset/js/plan_cart.js') }}"></script> --}}
        @include('saas::guest.plan_confirm.partials.js_partials.js')
    @endpush
</x-saas::guest>
