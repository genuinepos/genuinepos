@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .tab_list_area {padding-bottom: 0px;}
        .card-body { padding: 4px 6px; }
    </style>
@endpush
@section('title', 'Payment Methods - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Payment Methods") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                </a>
            </div>

            <div class="p-1">
                <div class="row g-lg-1 g-1 p-0">
                    <div class="col-12">
                        <div class="card p-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="tab_list_area">
                                            <div class="btn-group">
                                                <a href="#" id="tab_btn" data-show="payment_methods" class="btn btn-sm btn-primary tab_btn tab_active"> <i class="fas fa-th-large"></i> {{ __("Payment Methods") }}</a>

                                                @if (auth()->user()->can('payment_methods_settings'))

                                                    <a href="#" id="tab_btn" data-show="payment_method_settings" class="btn btn-sm btn-primary tab_btn "> <i class="fas fa-code-branch"></i> {{ __("Payment Method Settings") }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-end">
                                        <a href="{{ route('payment.methods.create') }}" class="btn btn-sm btn-primary" id="addPaymentMethod"><i class="fas fa-plus-square"></i> {{ __("Add Payment Method") }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @include('setups.payment_methods.body_partials.payment_method_body')

                        @if (auth()->user()->can('payment_methods_settings'))
                            @include('setups.payment_methods.body_partials.payment_method_settings_body')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.payment_method_settings').hide();

        $(document).on('click', '#tab_btn', function() {

            $('#addPaymentMethod').hide();
            var showing = $(this).data('show');

            if (showing == 'payment_methods') {

                $('#addPaymentMethod').show();
            } else {

                $('#addPaymentMethod').hide();

                setTimeout(function() {

                    $('#account_id').focus();
                }, 100);
            }
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });
    </script>
    @include('setups.payment_methods.js_partials.payment_methods_js_partials')

    @if (auth()->user()->can('payment_methods_settings'))
        @include('setups.payment_methods.js_partials.payment_method_settings_js_partial')
    @endif
@endpush
