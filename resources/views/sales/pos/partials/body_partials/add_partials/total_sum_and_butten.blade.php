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
                            @if ($generalSettings['pos__is_enabled_draft'] == '1')
                                <button type="button" id="draft" value="{{ App\Enums\SaleStatus::Draft->value }}" class="function-card pos_submit_btn btn" tabindex="-1">
                                   <span class="d-block">{{ __("Draft") }}</span>
                                   <span class="d-block">{{ __("F2") }}</span>
                                </button>
                            @else
                                <a href="#" id="draft_disabled" onclick="event.preventDefault(); toastr.error('Creating draft is disabled in POS.');"  class="function-card">{{ __("Draft") }}<p>{{ __("F2") }}</p>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            @if ($generalSettings['pos__is_enabled_quotation'] == '1')
                                <button type="button" id="quotation" value="{{ App\Enums\SaleStatus::Quotation->value }}" class="function-card pos_submit_btn btn" tabindex="-1">
                                    <span class="d-block">{{ __("Quotation") }}</span>
                                     <span class="d-block">{{ __("Alt+Q") }}</span>  {{--F4 --}}
                                </button>
                            @else
                                <a href="#" id="quotation_disabled" onclick="event.preventDefault(); toastr.error('Creating quotaion is disabled in POS');"  class="function-card">{{ __("Quotation") }}<p>{{ __("Alt+Q") }}</p>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="exchange" data-bs-toggle="modal" data-bs-target="#exchangeModal" tabindex="-1">{{ __("Exchange") }}<p>{{ __("F6") }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="show_stock" tabindex="-1">{{ __("Stock") }}<p>{{ __("Alt+C") }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            @if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
                                <button type="button" id="hold_invoice" value="{{ App\Enums\SaleStatus::Hold->value }}" class="function-card pos_submit_btn btn" tabindex="-1">
                                    <span class="d-block">{{ __("Hold Invoice") }}</span>
                                    <span class="d-block">{{ __("F8") }}</span>
                                </button>
                            @else
                                <a href="#" id="hold_invoice_disabled" onclick=" event.preventDefault(); toastr.error('Hold invoice is disabled in POS.');"  class="function-card">{{ __("Hold Invoice") }}<p>{{ __("F8") }}</p>
                                </a>
                            @endif
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
                                class="function-card" tabindex="-1">{{ __('Pick Hold') }}<p>{{ __("F9") }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="function-card" tabindex="-1">
                                {{ __("Setup") }} <p>{{ __("Ctrl+Q") }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            @if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
                                <button type="button" id="suspend" value="{{ App\Enums\SaleStatus::Suspended->value }}" class="function-card pos_submit_btn btn" tabindex="-1">
                                    <span class="d-block">{{ __("Suspend") }}</span>
                                    <span class="d-block">{{ __("F6") }}</span>
                                </button>
                            @else
                                <a href="#" id="suspend_disabled" onclick=" event.preventDefault(); toastr.error('Suspend is disabled in POS.');"  class="function-card">{{ __("Suspend") }}<p>{{ __("F6") }}</p>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card function-card-danger" onclick="cancel(); return false;" tabindex="-1">
                                {{ __("Cancel") }} <p>{{ __("Ctrl+M") }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper_input_btn">
                <div class="checkout-input-sec">
                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end"><b>{{ __("Net Total") }}</b></label>
                        <div class="col-sm-7">
                            <strong>
                                <input readonly type="number" class="form-control pos-amounts" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                            </strong>
                        </div>
                    </div>

                    @if ($generalSettings['pos__is_enabled_discount'] == '1')
                        <div class="row">
                            <label class="col-sm-5 col-form-label text-white text-end">{{ __("Sale Discount") }}</label>
                            <div class="col-sm-7">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="order_discount_type" id="order_discount_type" class="form-control pos-amounts" data-next="order_discount">
                                            <option value="1">{{ __("Fixed") }}(0.00)</option>
                                            <option value="2">{{ __("Percentage") }}(%)</option>
                                        </select>
                                        {{-- <input name="order_discount_type" class="form-control" id="order_discount_type" value="1"> --}}
                                    </div>

                                    <div class="col-6">
                                        <input name="order_discount" type="number" step="any" class="form-control pos-amounts fw-bold" id="order_discount" data-next="sale_tax_ac_id" value="0.00">
                                    </div>
                                </div>

                                <input name="order_discount_amount" type="number" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="order_discount" id="order_discount" value="0.00">
                        <input type="hidden" name="order_discount_amount" id="order_discount_amount" value="0.00">
                        <input type="hidden" name="order_discount_type" id="order_discount_type" value="1">
                    @endif

                    @if ($generalSettings['pos__is_enabled_order_tax'] == '1')
                        <div class="row">
                            <label class="col-sm-5 col-form-label text-white text-end">{{ __('Vat/Tax') }}</label>
                            <div class="col-sm-7">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <select name="sale_tax_ac_id" class="form-control" id="sale_tax_ac_id" data-next="received_amount">
                                            <option data-order_tax_percent="0.00" value="">{{ __("No Vat/Tax") }}</option>
                                            @foreach ($taxAccounts as $taxAccount)
                                                <option {{ $generalSettings['sale__default_tax_id'] == $taxAccount->id ? 'SELECTED' : '' }} data-order_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                    {{ $taxAccount->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" step="any" class="d-none" name="order_tax_percent" id="order_tax_percent" value="0.00">
                                        <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="sale_tax_ac_id" id="sale_tax_ac_id" value="">
                        <input type="hidden" name="order_tax_percent" id="order_tax_percent" value="0.00">
                        <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00">
                    @endif

                    <div class="row">
                        <label class="col-sm-5 col-5 col-form-label text-danger text-end"><b>{{ __('Previous Due') }}</b></label>
                        <div class="col-sm-7">
                            <input readonly type="number" step="any" name="previous_due" class="form-control pos-amounts fw-bold text-danger" id="previous_due" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">{{ __("Invoice Amount") }}</label>
                        <div class="col-sm-7">
                            <input readonly type="number" step="any" name="total_invoice_amount" class="form-control fw-bold" id="total_invoice_amount" value="0.00" tabindex="-1">
                            <input type="number" step="any" class="d-none" name="sales_ledger_amount" id="sales_ledger_amount" value="0.00">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">{{ __("Receivable") }}</label>
                        <div class="col-sm-7">
                            <input readonly type="number" step="any" name="total_receivable_amount" class="form-control pos-amounts fw-bold" id="total_receivable_amount" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-form-label text-white text-end">{{ __("Cash Receive") }}</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-text cash_receive_input">>></span>
                                <input type="number" step="any" name="received_amount" id="received_amount"
                                class="form-control pos-amounts input_i fw-bold" value="0" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-5 col-form-label text-white text-end">{{ __("Change Amount") }}</label>
                        <div class="col-sm-7 col-7">
                            <input readonly type="text" name="change_amount" id="change_amount" class="form-control pos-amounts fw-bold" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-5 col-5 col-form-label text-danger text-end"><b>{{ __("Curr. Balance") }}</b></label>
                        <div class="col-sm-7 col-7">
                            <input type="text" readonly name="current_balance" id="current_balance" class="form-control pos-amounts text-danger fw-bold" value="0.00" tabindex="-1">
                        </div>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row g-xxl-3 g-1">
                        <div class="col-lg-4 col-6 m-order-2">
                            <div class="btn-bg mb-xxl-1 mb-xl-1">
                                @if ($generalSettings['pos__is_enabled_credit_full_sale'] == '1')
                                    <button type="button" id="credit_and_final" value="{{ App\Enums\SaleStatus::Final->value }}" class="btn-pos pos_submit_btn btn bg-danger btn-pos" tabindex="-1">
                                        {{ __("Credit Sale") }} {{ __("Alt+A") }}
                                    </button>
                                @else
                                    <a href="#" id="credit_sale_disabled" onclick="event.preventDefault(); toastr.error('Full credit sale is disabled.');" class="function-card">{{ __("Credit Sale") }}<p>{{ __("Alt+A") }}</p>
                                    </a>
                                @endif
                            </div>

                            <div class="btn-bg">
                                <a href="#" class="btn-pos" id="reedem_point_button" tabindex="-1">{{ __("Reedem Point") }}</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 btn-bottom m-order-3">
                            <div class="btn-bg">
                                <a href="#" class="function-card other_payment_method" tabindex="-1">
                                    <span>{{ __("Other Method") }}</span>
                                    <p>{{ __("Ctrl+B") }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 btn-bottom">
                            <div class="btn-bg">
                                <button type="button" id="final" value="{{ App\Enums\SaleStatus::Final->value }}" class="function-card cash-btn pos_submit_btn btn" tabindex="-1">
                                    <span>{{ __("Cash") }}</span>
                                    <p>{{ __("Ctrl+Enter") }}</p>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
