<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ __('Edit Point Of Sale - GPOSS') }}</title>
    <!-- Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    {{-- creat pate link start --}}

    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('backend/asset/css/fontawesome/css/all.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{asset('backend/asset/css/bootstrap.min.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="{{ asset('backend/css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/css/body.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/css/gradient.css') }}" rel="stylesheet" type="text/css">

    <!-- Calculator -->
    <link rel="stylesheet" href="{{ asset('backend/asset/css/calculator.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/comon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/pos.css') }}">
    <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/data-table.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('backend/asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/pos-theme.css') }}">
    <!-- <style> .btn-bg {padding: 2px!important;} </style> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if ($saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value)
        <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/css-toggle-switch@latest/dist/toggle-switch.css" />
    @endif

    @stack('css')
    {{-- creat pate link end --}}

    <style>
        .d-hide {
            display: none;
        }

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
            background-color: #cbe4ee
        }

        .table-striped tbody tr:nth-of-type(odd) {
            /* background-color: #EBEDF3;*/
            background-color: #cbe4ee;
        }

        /*# sourceMappingURL=bootstrap.min.css.map  background:linear-gradient(#f7f3f3, #c3c0c0);*/

        .widget_content .table-responsive {
            min-height: 80vh !important;
        }
    </style>
</head>

<body class="{{ $generalSettings['system__theme_color'] ?? 'dark-theme' }}">
    <form id="pos_submit_form" action="{{ route('sales.pos.update', $sale->id) }}" method="POST">
        @csrf
        <div class="pos-body">
            <div class="main-wraper">
                @yield('pos_content')
            </div>
        </div>

        @if ($saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value)
            <div class="modal fade" id="serviceChecklistModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog double-col-modal" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">{{ __('Servicing Checklist') }}</h6>
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
                        </div>

                        <div class="modal-body">
                            <div class="row gx-2 gy-1 mt-1">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">{{ __('Servicing Checklist: ') }}</span> <small>{{ __('N/A = Not Applicable') }}</small></p>
                                </div>

                                <hr>
                                <div class="row gx-3" id="check_list_area">
                                    @if (isset($sale->jobCard->service_checklist) && is_array($sale->jobCard->service_checklist))
                                        @php
                                            $index = 0;
                                        @endphp
                                        @foreach ($sale->jobCard->service_checklist as $key => $value)
                                            <div class="col-md-4">
                                                <p class="fw-bold text-dark">{{ $key }}</p>
                                                <div class="switch-toggle switch-candy">
                                                    <input type="radio" @checked($value == 'yes') id="{{ $index }}_yes" name="checklist[{{ $key }}]" value="yes">
                                                    <label for="{{ $index }}_yes" class="text-success">✔</label>

                                                    <input type="radio" @checked($value == 'no') id="{{ $index }}_no" name="checklist[{{ $key }}]"  value="no">
                                                    <label for="{{ $index }}_no" class="text-danger">❌</label>

                                                    <input type="radio" @checked($value == 'na') id="{{ $index }}_na" name="checklist[{{ $key }}]" value="na">
                                                    <label for="{{ $index }}_na">N/A</label>
                                                    <a></a>
                                                </div>
                                            </div>
                                            @php
                                                $index++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                                <hr>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12 text-end">
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-danger ms-1 p-1">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
    @if ($generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('customer_add'))
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
                                <span class="d-hide" id="display_unit_cost_section">
                                    <span>{{ $generalSettings['business_or_shop__currency_symbol'] }}</span>
                                    <span class="text-muted" id="display_unit_cost"></span>
                                </span>

                                <span class="btn btn-sm btn-info text-white" id="display_unit_cost_toggle_btn">{{ __('Cost') }}</span>
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
                            <div class="col-md-6" id="description_field">
                                <label class="fw-bold">{{ __('IMEI, Serial number or other info.') }}</label>
                                <input type="text" class="form-control" id="e_description" placeholder="{{ __('IMEI, Serial number or other info.') }}" tabindex="-1">
                            </div>

                            <div class="col-md-3">
                                <label class="fw-bold">{{ __('Subtotal') }}</label>
                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                            </div>

                            <div class="col-md-3">
                                <a href="#" class="btn btn-sm btn-success" id="edit_product">{{ __('Update') }}</a>
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

    <!--Data delete form-->
    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--Data delete form end-->
    <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        $(document).on('click', '#pos_exit_button', function(e) {
            e.preventDefault();
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to exit?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            window.location = "{{ url()->previous() }}";
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
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
