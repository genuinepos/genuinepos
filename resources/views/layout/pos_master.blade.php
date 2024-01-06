<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ __('Point Of Sale - GPOSS') }}</title>
    <!-- Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/fontawesome/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{asset('backend/asset/css/bootstrap.min.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="{{ asset('backend/css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/css/body.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/css/reset.css') }}" rel="stylesheet" type="text/css">
    {{-- <link href="{{asset('backend/css/gradient.css') }}" rel="stylesheet" type="text/css"> --}}

    <!-- Calculator -->
    <link rel="stylesheet" href="{{ asset('backend/asset/css/calculator.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/comon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/pos.css') }}">
    <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('backend/asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/pos-theme.css') }}">
    <link href="{{ asset('backend/css/data-table.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <!-- <style> .btn-bg {padding: 2px!important;} </style> -->
    @stack('css')
    <script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
    <!--Toaster.js js link-->
    <script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
    <!--Toaster.js js link end-->

    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }} "></script>
    <script src="{{ asset('assets/plugins/custom/print_this/printThis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/Shortcuts-master/shortcuts.js') }}"></script>
    <!--alert js link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/plugins/custom/digital_clock/digital_clock.js') }}"></script>
    <script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>
    <style>
        .d-hide {
            display: none;
        }
    </style>

    {{-- Harrison Bootstrap-Custom --}}
    <style>
        @media (min-width: 576px) {
            .modal-full-display {
                max-width: 93% !important;
            }

            .four-col-modal {
                max-width: 70% !important;
                margin: 3.8rem auto;
            }

            .five-col-modal {
                max-width: 90% !important;
                margin: 3.8rem auto;
            }

            .col-80-modal {
                max-width: 80% !important;
                margin: 3.8rem auto;
            }

            .double-col-modal {
                max-width: 35% !important;
                margin: 3.8rem auto;
            }

            .col-40-modal {
                max-width: 40% !important;
                margin: 3.8rem auto;
            }

            .col-45-modal {
                max-width: 45% !important;
                margin: 3.8rem auto;
            }

            .col-50-modal {
                max-width: 50% !important;
                margin: 3.8rem auto;
            }

            .col-55-modal {
                max-width: 55% !important;
                margin: 3.8rem auto;
            }

            .col-60-modal {
                max-width: 60% !important;
                margin: 3.8rem auto;
            }

            .col-65-modal {
                max-width: 65% !important;
                margin: 3.8rem auto;
            }
        }

        .modal-middle {
            margin-top: 33%;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #cbe4ee;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            /* background-color: #EBEDF3;*/
            background-color: #cbe4ee;
        }

        /*# sourceMappingURL=bootstrap.min.css.map  background:linear-gradient(#f7f3f3, #c3c0c0);*/

        .widget_content .table-responsive {
            min-height: 80vh !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: inline-block;
            width: 143px;
        }

        .select2-selection:focus {
            box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
        }

        html.sf-js-enabled {
            overflow: hidden;
        }
    </style>
</head>

<body class="{{ $generalSettings['system__theme_color'] ?? 'dark-theme' }}">
    <form id="pos_submit_form" action="{{ route('sales.pos.store') }}" method="POST">
        @csrf
        <div class="pos-body">
            <div class="main-wraper">
                @yield('pos_content')
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade in" id="otherPaymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog col-50-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">{{ __('Choose Payment Method') }}</h6>
                        <a href="#" class="close-btn" id="cancel_pay_mathod" tabindex="-1"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <div class="form-group row single_payment">
                            <div class="col-md-4">
                                <label><strong>{{ __('Payment Method') }} </strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                        @foreach ($methods as $method)
                                            <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label><strong>{{ __('Debit Account') }}</strong> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
                                        @foreach ($accounts as $account)
                                            @if ($account->is_bank_account == 1 && $account->has_bank_access_branch == 0)
                                                @continue
                                            @endif

                                            @if ($account->sub_sub_group_number == 2 && $openedCashRegister->cash_account_id != $account->id)
                                                @continue
                                            @else
                                                <option value="{{ $account->id }}">
                                                    @php
                                                        $acNo = $account->account_number ? ', A/c No : ' . $account->account_number : '';
                                                        $bank = $account?->bank ? ', Bank : ' . $account?->bank?->name : '';
                                                    @endphp
                                                    {{ $account->name . $acNo . $bank }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><strong>{{ __('Payment Note') }}</strong></label>
                            <input name="payment_note" class="form-control form-control-sm" id="payment_note" data-next="choose_method_and_final" placeholder="{{ __('Payment Note') }}">
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                    <button id="choose_method_and_final" value="1" class="btn btn-success pos_submit_btn p-1" tabindex="-1">{{ __('Confirm') }} ({{ __('Ctrl+Enter') }})</button>
                                    <button type="button" class="btn btn-danger p-1" id="cancel_pay_mathod">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Payment modal End-->

        @if ($generalSettings['reward_point_settings__enable_cus_point'] == '1')
            <!--Redeem Point modal-->
            <div class="modal fade" id="pointReedemModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog col-40-modal" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">{{ __('Redeem Point') }}</h6>
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label><b>{{ __('Available Point') }}</b></label>
                                <input readonly type="number" step="any" name="available_point" id="available_point" class="form-control fw-bold" data-next="total_redeem_point" value="0" tabindex="-1">
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><b>{{ __('Redeemed') }}</b></label>
                                    <input type="number" step="any" name="total_redeem_point" id="total_redeem_point" class="form-control fw-bold" data-next="redeem_btn">
                                    <input type="number" step="any" name="pre_redeemed" id="pre_redeemed" class="d-hide" value="0">
                                    <input type="number" step="any" name="pre_redeemed_amount" id="pre_redeemed_amount" class="d-hide" value="0">
                                </div>

                                <div class="col-md-6">
                                    <label><b>{{ __('Redeem Amount') }}</b></label>
                                    <input readonly type="number" step="any" name="redeem_amount" id="redeem_amount" class="form-control fw-bold" tabindex="-1">
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b>{{ __('Loading') }}</b></button>
                                    <a href="#" class="c-btn button-success ms-1 float-end" id="redeem_btn" tabindex="-1">{{ __('Redeem') }}</a>
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--Redeem Point modal-->
    </form>

    <!-- Recent transection list modal-->
    <div class="modal fade" id="recentTransModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Recent transection list modal end-->

    <!-- Hold invoice list modal -->
    <div class="modal fade" id="holdInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Hold invoice list modal End-->

    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    <!--Add Customer Modal-->
    @if (auth()->user()->can('customer_add'))
        <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        </div>
    @endif
    <!--Add Customer Modal-->

    <!-- Suspended sales modal-->
    <div class="modal fade" id="suspendedSalesModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Suspended modal end-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="e_product_name">{{ __('Item Name') }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="update_selling_product">
                        <div class="hidden_fields d-none">
                            <input type="hidden" id="e_unique_id">
                            <input type="hidden" id="e_product_id">
                            <input type="hidden" id="e_variant_id">
                            <input type="hidden" id="e_tax_amount">
                            <input type="hidden" id="e_price_inc_tax">
                            <input type="hidden" id="e_is_show_emi_on_pos">
                        </div>

                        @if (auth()->user()->can('view_product_cost_is_sale_screed'))
                            <p>
                                <span class="btn btn-sm btn-primary d-hide" id="show_cost_section">
                                    <span>{{ $generalSettings['business_or_shop__currency_symbol'] }}</span>
                                    <span id="unit_cost"></span>
                                </span>

                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">{{ __('Cost') }}</span>
                            </p>
                        @endif

                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label> <strong>{{ __('Quantity') }}</strong> : <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control fw-bold w-60" id="e_quantity" placeholder="{{ __('Quantity') }}" value="0.00">
                                    <select id="e_unit_id" class="form-control w-40">
                                        <option value="">{{ __('Unit') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="fw-bold">{{ __('Unit Price (Exc. Tax)') }}</label>
                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                            </div>

                            <div class="col-md-3">
                                <label><strong>{{ __('Unit Discount') }}</strong> </label>
                                <div class="input-group">
                                    <select class="form-control" id="e_unit_discount_type">
                                        <option value="1">{{ __('Fixed') }}</option>
                                        <option value="2">{{ __('Percentage') }}</option>
                                    </select>

                                    <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" class="form-control fw-bold" id="e_unit_discount" value="0.00" />
                                    <input type="hidden" id="e_discount_amount" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label><strong>{{ __('Vat/Tax') }}</strong> </label>
                                <div class="input-group">
                                    <select id="e_tax_ac_id" class="form-control w-50">
                                        <option data-product_tax_percent="0.00" value="">{{ __('NoTax') }}</option>
                                        @foreach ($taxAccounts as $taxAccount)
                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                {{ $taxAccount->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="e_tax_type" class="form-control w-50" tabindex="-1">
                                        <option value="1">{{ __('Exclusive') }}</option>
                                        <option value="2">{{ __('Inclusive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 align-items-end">
                            <div class="col-md-3" id="description_field">
                                <label class="fw-bold">{{ __('IMEI, Serial number or other info.') }}</label>
                                <input type="text" class="form-control" id="e_description" placeholder="{{ __('IMEI, Serial number or other info.') }}" tabindex="-1">
                            </div>

                            <div class="col-md-3">
                                <label class="fw-bold">{{ __('Subtotal') }}</label>
                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                            </div>

                            <div class="col-md-4">
                                <a href="#" class="btn btn-sm btn-success" id="edit_product">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Show stock modal end-->

    <!-- Cash Register Details modal -->
    <div class="modal fade" id="cashRegisterDetailsAndCloseModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Cash Register Details modal End-->

    <!--Quick Cash receive modal-->
    <div class="modal fade in" id="cashReceiveMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content modal-middle">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">{{ __('Quick Cash Receive') }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="form-group row ">
                        <div class="col-md-6">
                            <div class="input-box-4 bg-dark">
                                <label class="text-white big_label"><strong>{{ __('Total Receivable') }}</strong> </label>
                                <input readonly type="text" class="form-control big_field" id="modal_total_receivable" value="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-2 bg-info">
                                <label class="text-white big_label"><strong>{{ __('Change') }}</strong></label>
                                <input readonly type="text" class="form-control big_field text-info" id="modal_change_amount" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-6">
                            <div class="input-box bg-success">
                                <label class="text-white big_label"><strong>{{ __('Cash Receive') }}</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="modal_received_amount" class="form-control text-success big_field m-paying" id="modal_received_amount" data-next="final_and_quick_cash_receive" value="0" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-3 bg-danger">
                                <label class="text-white big_label"><strong>{{ __('Curr. Balance') }}</strong> </label>
                                <input readonly type="text" class="form-control text-danger big_field" id="modal_current_balance" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __('Loading') }}...</b></button>
                            <button class="btn btn-success ms-1 p-1 pos_submit_btn" id="final_and_quick_cash_receive" tabindex="-1">{{ __('Cash') }} ({{ __('Ctrl+Enter') }})</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-danger ms-1 p-1">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Quick Cash receive modal End-->

    <!-- Exchange modal -->
    <div class="modal fade" id="exchangeModal"tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="exchange_body">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('Exchange') }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <div class="form-area">
                        <form id="search_inv_form" action="{{ route('sales.pos.exchange.search.invoice') }}" method="GET">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input required type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Search invoice">
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue m-0">
                                        <button type="submit" class="btn btn-sm btn-primary" tabindex="-1"><i class="fas fa-plus-square"></i> {{ __('Search') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="preloader_area">
                            <div class="data_preloader" id="get_inv_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" id="exchange_invoice_description"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exchange modal End-->

    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="modal-body_shortcuts"></div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <script type="text/javascript" src="{{ asset('backend/asset/cdn/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('') }}assets/plugins/custom/select_li/selectli.js"></script>
    <script src="{{ asset('backend/asset/js/pos.js') }}"></script>
    {{-- <script src="{{ asset('backend/asset/js/pos-amount-calculation.js') }}"></script> --}}
    <script src="{{ asset('') }}/backend/asset/js/sale.exchange.js"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        // Get all pos shortcut menus by ajax
        function allPosShortcutMenus() {
            $.ajax({
                url: "{{ route('pos.short.menus.show') }}",
                type: 'get',
                success: function(data) {
                    $('#pos-shortcut-menus').html(data);
                }
            });
        }
        allPosShortcutMenus();

        $(document).on('click', '#pos_exit_button', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);

            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'content': "{{ __('Are you sure, you want to exit?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            window.location = "{{ route('dashboard.index') }}";
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Deleted canceled.')
                        }
                    }
                }
            });
        });

        //Key shortcut for to the settings
        shortcuts.add('ctrl+q', function() {

            window.location = "{{ route('settings.general.index') }}";
        });

        var scrollContainer = document.querySelector("#pos-shortcut-menus");
        scrollContainer.addEventListener("wheel", (evt) => {

            evt.preventDefault();
            scrollContainer.scrollLeft += evt.deltaY;
        });

        function toggleFullscreen(elem) {

            elem = elem || document.documentElement;

            if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {

                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {

                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {

                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {

                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {

                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }

        document.getElementById('fullscreen').addEventListener('click', function() {
            toggleFullscreen();
        });

        $(window).scroll(function() {
            if ($('.select2').is(':visible')) {
                $('.select2-dropdown').css({
                    "display": "none"
                });
            }
        });

        $(document).on('click', '.select2', function(e) {
            e.preventDefault();
            $('.select2-dropdown').css({
                "display": ""
            });
        });

        $(document).on('select2:open', () => {

            if ($('.select2-search--dropdown .select2-search__field').length > 0) {

                document.querySelector('.select2-search--dropdown .select2-search__field').focus();
            }
        });
    </script>
    @stack('js')
</body>

</html>
