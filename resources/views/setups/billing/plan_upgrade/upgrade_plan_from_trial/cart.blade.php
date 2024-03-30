<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>{{ __("Upgrade Plan Cart") }} - GPOS</title>
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
    <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="inner">
    <div class="tab-section py-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav active" data-tab="cartTab">
                            <span class="txt">{{ __("Step One") }}</span>
                            <span class="sl-no">{{ __("01") }}</span>
                        </button>

                        <button class="single-nav" data-tab="checkOutTab" disabled>
                            <span class="txt">{{ __("Step Two") }}</span>
                            <span class="sl-no">{{ __("02") }}</span>
                        </button>

                        {{-- <button class="single-nav" data-tab="orderCompletedTab" disabled>
                            <span class="txt">{{ __("Step Three") }}</span>
                            <span class="sl-no">{{ __("03") }}</span>
                        </button> --}}
                    </div>

                    <div class="tab-contents">
                        <form id="plan_upgrade_form" action="{{ route('software.service.billing.upgrade.plan.confirm') }}" method="POST">
                            @csrf
                            <div class="single-tab active" id="cartTab">
                                @include('setups.billing.plan_upgrade.upgrade_plan_from_trial.partials.cart_partials.step_one')
                            </div>

                            <div class="single-tab" id="checkOutTab">
                                @include('setups.billing.plan_upgrade.upgrade_plan_from_trial.partials.cart_partials.step_two')
                            </div>
                        </form>

                        <div class="single-tab" id="orderCompletedTab">
                            <div class="check-icon">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <div class="order-complete-msg">
                                <h2>{{ __("Plan is upgraded successfully.") }}</h2>
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
    <script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/js/cart.js') }}"></script> --}}
    @include('setups.billing.plan_upgrade.upgrade_plan_from_trial.js_partial.js')
</body>

</html>
