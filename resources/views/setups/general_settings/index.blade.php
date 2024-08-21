@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }

        label {
            font-size: 12px !important;
        }

        ul.menus_unorder_list {
            list-style: none;
            float: left;
            width: 100%;
        }

        ul.menus_unorder_list .menu_list {
            display: block;
            text-align: center;
            margin-bottom: 5px;
        }

        ul.menus_unorder_list .menu_list:last-child {
            margin-bottom: 0;
        }

        ul.menus_unorder_list .menu_list .menu_btn {
            color: black;
            padding: 5px 1px;
            display: block;
            font-size: 11px;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid transparent;
            border-radius: 5px;
            background: white;
            transition: .2s;
        }

        ul.menus_unorder_list .menu_list .menu_btn.menu_active {
            border-color: var(--dark-color-1);
            color: #504d4d !important;
            font-weight: 600;
        }

        .hide-all {
            display: none;
        }

        .dropify-wrapper {
            height: 100px !important;
        }
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'General Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('General Settings') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>

                {{-- <a href="{{ route('branches.settings.index', 28) }}">Test</a> --}}
            </div>
        </div>
        <div class="p-1">
            <div class="form_element rounded m-0">

                <div class="element-body">
                    <div class="settings_form_area">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="settings_side_menu">
                                    <ul class="menus_unorder_list">
                                        @if (auth()->user()->can('business_or_shop_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn menu_active" data-form="business_settings_form" href="#">{{ __('Company Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('dashboard_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="dashboard_settings_form" href="#">{{ __('Dashboard Settings') }}</a>
                                            </li>
                                        @endif

                                        @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
                                            @if (auth()->user()->can('product_settings'))
                                                <li class="menu_list">
                                                    <a class="menu_btn" data-form="product_settings_form" href="#">{{ __('Product Settings') }}</a>
                                                </li>
                                            @endif
                                        @endif

                                        @if (auth()->user()->can('purchase_settings') && $generalSettings['subscription']->features['purchase'] == \App\Enums\BooleanType::True->value)
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="purchase_settings_form" href="#">{{ __('Purchase Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('manufacturing_settings') && $generalSettings['subscription']->features['manufacturing'] == 1)
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="manufacturing_settings_form" href="#">{{ __('Manufacturing Settings') }}</a>
                                            </li>
                                        @endif

                                        @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                                            @if (auth()->user()->can('add_sale_settings'))
                                                <li class="menu_list">
                                                    <a class="menu_btn" data-form="add_sale_settings_form" href="#">{{ __('Add Sale Settings') }}</a>
                                                </li>
                                            @endif

                                            @if (auth()->user()->can('pos_sale_settings'))
                                                <li class="menu_list">
                                                    <a class="menu_btn" data-form="pos_settings_form" href="#">{{ __('POS Sale Settings') }}</a>
                                                </li>
                                            @endif
                                        @endif

                                        @if (auth()->user()->can('prefix_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="prefix_settings_form" href="#">{{ __('Prefix Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('invoice_layout_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="invoice_layout_settings_form" href="#">{{ __('Invoice Layout Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('print_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="print_page_size_settings_form" href="#">{{ __('Print Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('system_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="system_settings_form" href="#">{{ __('System Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('reward_point_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="point_settings_form" href="#">{{ __('Reward Point Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('module_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="module_settings_form" href="#">{{ __('Modules Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('send_email_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="email_settings_form" href="#">{{ __('Send Email Settings') }}</a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('send_sms_settings'))
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="sms_settings_form" href="#">{{ __('Send SMS Settings') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-9">
                                @if (auth()->user()->can('business_or_shop_settings'))
                                    @include('setups.general_settings.partials.view_partials.business_settings')
                                @endif

                                @if (auth()->user()->can('dashboard_settings'))
                                    @include('setups.general_settings.partials.view_partials.dashboard_settings')
                                @endif

                                @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
                                    @if (auth()->user()->can('product_settings'))
                                        @include('setups.general_settings.partials.view_partials.product_settings')
                                    @endif
                                @endif

                                @if (auth()->user()->can('purchase_settings') && $generalSettings['subscription']->features['purchase'] == \App\Enums\BooleanType::True->value)
                                    @include('setups.general_settings.partials.view_partials.purchase_settings')
                                @endif

                                @if (auth()->user()->can('manufacturing_settings') && $generalSettings['subscription']->features['manufacturing'] == \App\Enums\BooleanType::True->value)
                                    @include('setups.general_settings.partials.view_partials.manufacturing_settings')
                                @endif

                                @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                                    @if (auth()->user()->can('add_sale_settings'))
                                        @include('setups.general_settings.partials.view_partials.add_sale_settings')
                                    @endif

                                    @if (auth()->user()->can('pos_sale_settings'))
                                        @include('setups.general_settings.partials.view_partials.pos_sale_settings')
                                    @endif
                                @endif

                                @if (auth()->user()->can('prefix_settings'))
                                    @include('setups.general_settings.partials.view_partials.prefix_settings')
                                @endif

                                @if (auth()->user()->can('print_settings'))
                                    @include('setups.general_settings.partials.view_partials.print_settings')
                                @endif

                                @if (auth()->user()->can('invoice_layout_settings'))
                                    @include('setups.general_settings.partials.view_partials.invoice_layout_settings')
                                @endif

                                @if (auth()->user()->can('system_settings'))
                                    @include('setups.general_settings.partials.view_partials.system_settings')
                                @endif

                                @if (auth()->user()->can('reward_point_settings'))
                                    @include('setups.general_settings.partials.view_partials.reward_point_settings')
                                @endif

                                @if (auth()->user()->can('module_settings'))
                                    @include('setups.general_settings.partials.view_partials.module_settings')
                                @endif

                                @if (auth()->user()->can('send_email_settings'))
                                    @include('setups.general_settings.partials.view_partials.email_settings')
                                @endif

                                @if (auth()->user()->can('send_sms_settings'))
                                    @include('setups.general_settings.partials.view_partials.sms_settings')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            //$('.setting_form').hide();
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active d-block');
            });
        });
    </script>

    @if (auth()->user()->can('business_or_shop_settings'))
        @include('setups.general_settings.partials.js_partials.business_settings_js')
    @endif

    @include('setups.general_settings.partials.js_partials.dashboard_settings_js')

    @if (auth()->user()->can('prefix_settings'))
        @include('setups.general_settings.partials.js_partials.prefix_settings_js')
    @endif

    @include('setups.general_settings.partials.js_partials.print_settings_js')
    @include('setups.general_settings.partials.js_partials.invoice_layout_settings_js')

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('product_settings'))
            @include('setups.general_settings.partials.js_partials.product_settings_js')
        @endif
    @endif

    @if (auth()->user()->can('purchase_settings') && $generalSettings['subscription']->features['purchase'] == \App\Enums\BooleanType::True->value)
        @include('setups.general_settings.partials.js_partials.purchase_settings_js')
    @endif

    @if (auth()->user()->can('manufacturing_settings') && $generalSettings['subscription']->features['manufacturing'] == \App\Enums\BooleanType::True->value)
        @include('setups.general_settings.partials.js_partials.manufacturing_settings_js')
    @endif

    @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('add_sale_settings'))
            @include('setups.general_settings.partials.js_partials.add_sale_settings_js')
        @endif

        @if (auth()->user()->can('pos_sale_settings'))
            @include('setups.general_settings.partials.js_partials.pos_settings_js')
        @endif
    @endif

    @include('setups.general_settings.partials.js_partials.system_settings_js')
    @include('setups.general_settings.partials.js_partials.reward_point_settings_js')
    @include('setups.general_settings.partials.js_partials.module_settings_js')
    @include('setups.general_settings.partials.js_partials.email_settings_js')
    @include('setups.general_settings.partials.js_partials.sms_settings_js')
@endpush
