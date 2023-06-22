<style>
    .cash_receive_input{
      background-color: var(--white-color);
      border: 1px solid #ced4da;
      letter-spacing: -3px!important;
      padding: 0px 3px 0px 0px!important;
      font-weight: 700!important;
    }
    #net_total_amount[value] {
        font-weight: 700;
    }
</style>

<div class="col-lg-3">
    <div class="pos-right-inner">
        <div class="check-out-wraper">
            <div class="function-sec">
                <div class="row g-xxl-3 g-xl-2 g-lg-1">
                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if ($generalSettings['pos__is_enabled_draft'] == '1')
                                    data-button_type="0"
                                    data-action_id="2"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating draft is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.draft')<p>F2</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if ($generalSettings['pos__is_enabled_quotation'] == '1')
                                    data-action_id="4"
                                    data-button_type="0"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating quotaion is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.quotation')<p>F4</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="exchange_btn" data-bs-toggle="modal" data-bs-target="#exchangeModal" tabindex="-1">
                                @lang('menu.exchange')<p>F6</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="show_stock" tabindex="-1">@lang('menu.stock')<p>Alt+C</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
                                    data-button_type="0"
                                    data-action_id="5"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.hold_invoices')<p>F8</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
                                    id="pick_hold_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">{{ __('Pick Hold') }} <p>F9</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="function-card" tabindex="-1">
                                Setup <p>Ctrl+Q</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if ($generalSettings['pos__is_enabled_suspend'] == '1')
                                    data-button_type="0"
                                    data-action_id="6"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Suspend is disabled in POS.');
                                    "
                                @endif
                                class="function-card function-card-danger" tabindex="-1">{{ __('Suspend') }}<p>Alt+A</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card function-card-danger" onclick="cancel(); return false;" tabindex="-1">
                                Cancel <p>Ctrl+M</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper_input_btn">
                <div class="checkout-input-sec">
                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end"><b>@lang('menu.net_total')</b></label>
                        <div class="col-sm-7">
                            <strong>
                                <input readonly type="number" class="form-control pos-amounts" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                            </strong>
                        </div>
                    </div>

                    @if ($generalSettings['pos__is_enabled_discount'] == '1')
                        <div class="row">
                            <label class="col-sm-5 col-form-label text-white text-end">@lang('menu.discount')</label>
                            <div class="col-sm-7">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="order_discount_type" id="order_discount_type" class="form-control pos-amounts">
                                            <option value="1">@lang('menu.fixed')(0.00)</option>
                                            <option value="2">Percent(%)</option>
                                        </select>
                                        {{-- <input name="order_discount_type" class="form-control" id="order_discount_type" value="1"> --}}
                                    </div>

                                    <div class="col-6">
                                        <input name="order_discount" type="number" step="any" class="form-control pos-amounts fw-bold" id="order_discount" value="0.00">
                                    </div>
                                </div>

                                <input name="order_discount_amount" type="number" class="d-hide" id="order_discount_amount"
                                    value="0.00" tabindex="-1">
                            </div>
                        </div>
                    @else
                        <input name="order_discount" type="hidden" id="order_discount" value="0.00" tabindex="-1">
                        <input name="order_discount_amount" type="number" class="d-hide" id="order_discount_amount"
                            value="0.00" tabindex="-1">
                        <input name="order_discount_type" class="d-hide" id="order_discount_type" value="1">
                    @endif

                    @if ($generalSettings['pos__is_enabled_order_tax'] == '1')
                        <div class="row">
                            <label class="col-sm-5 col-form-label text-white text-end">{{ __('Vat/Tax') }}</label>
                            <div class="col-sm-7">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="order_tax" class="form-control pos-amounts" id="order_tax"></select>
                                    </div>

                                    <div class="col-6">
                                        <input type="number" class="form-control pos-amounts fw-bold" name="order_tax_amount" id="order_tax_amount"
                                        value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <input name="order_tax" type="hidden" id="order_tax" value="0.00" tabindex="-1">
                        <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00" tabindex="-1">
                    @endif

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">{{ __('Previous Due') }}</label>
                        <div class="col-sm-7">
                            <input readonly class="form-control pos-amounts fw-bold" type="number" step="any" name="previous_due" id="previous_due" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">@lang('menu.receivable')</label>
                        <div class="col-sm-7">
                            <input readonly class="form-control pos-amounts fw-bold" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">
                            <input class="d-hide" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">@lang('menu.cash_receive')</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-text cash_receive_input">>></span>
                                <input type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control pos-amounts input_i fw-bold" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-5 col-form-label text-white text-end">@lang('menu.change_amount')</label>
                        <div class="col-sm-7 col-7">
                            <input readonly type="text" name="change_amount" id="change_amount" value="0.00"
                                class="form-control pos-amounts fw-bold" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-5 col-form-label text-danger text-end"><b>@lang('menu.due') </b></label>
                        <div class="col-sm-7 col-7">
                            <input type="text" readonly name="total_due" id="total_due" value="0.00"
                                class="form-control pos-amounts text-danger fw-bold" tabindex="-1">
                        </div>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row g-xxl-3 g-1">
                        <div class="col-lg-4 col-6 m-order-2">
                            <div class="btn-bg mb-xxl-1 mb-xl-1">
                                <a href="#" class=" btn-pos"
                                    @if ($generalSettings['pos__is_enabled_credit_full_sale'] == '1')
                                        data-button_type="0"
                                        id="full_due_button"
                                    @else
                                        onclick="
                                            event.preventDefault();
                                            toastr.error('Full credit sale is disabled.');
                                        "
                                    @endif
                                    tabindex="-1">@lang('menu.credit_sale')</a>
                            </div>

                            <div class="btn-bg">
                                <a href="#" class="btn-pos" id="reedem_point_button" tabindex="-1">@lang('menu.reedem_oint')</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 btn-bottom m-order-3">
                            <div class="btn-bg">
                                <a href="#" class="function-card other_payment_method" tabindex="-1">
                                    <span>@lang('menu.other_method')</span>
                                    <p>Ctrl+B</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 btn-bottom">
                            <div class="btn-bg">
                                <a href="#" class="function-card cash-btn" id="submit_btn" data-button_type="1"
                                    data-action_id="1" tabindex="-1">
                                    <span>@lang('menu.cash') </span>
                                    <p>F10</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
