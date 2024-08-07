<style>
    .payment_top_card {
        background: #d7dfe8;
    }

    .payment_top_card span {
        font-size: 12px;
        font-weight: 400;
    }

    .payment_top_card li {
        font-size: 12px;
    }

    .payment_top_card ul {
        padding: 6px;
        border: 1px solid #dcd1d1;
    }

    .payment_list_table {
        position: relative;
    }

    .payment_details_contant {
        background: azure !important;
    }

    h6.checkbox_input_wrap {
        border: 1px solid #495677;
        padding: 0px 7px;
    }
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Money Receipt Voucher') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">

            <div class="row">
                <div class="col-md-12 text-center">
                    <strong>{{ location_label() }}: </strong> {{ $moneyReceipt?->branch ? $moneyReceipt?->branch->name : $generalSettings['business_or_shop__business_name'] }}
                </div>
            </div>

            @php
                $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
                $amounts = $accountBalanceService->accountBalance(accountId: $moneyReceipt?->contact?->account?->id, fromDate: null, toDate: null, branchId: null);
            @endphp

            <div class="row">
                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ location_label() }}</th>
                                <td class="text-start" style="font-size:11px!important"> :
                                    @if ($moneyReceipt?->contact?->account?->branch)
                                        {{ $moneyReceipt?->contact?->account?->branch?->name }}
                                    @else
                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Customer') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $moneyReceipt?->contact?->name }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Customer') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $moneyReceipt?->contact?->name }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Phone') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $moneyReceipt?->contact?->phone }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Business') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $moneyReceipt?->contact->business_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Opening Balance') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['opening_balance_in_flat_amount_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Sale') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_sale_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Purchase') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_purchase_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Return') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_return_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Received') }}</th>
                                <td class="text-start fw-bold text-success" style="font-size:11px!important"> : {{ $amounts['total_received_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Paid') }}</th>
                                <td class="text-start fw-bold text-danger" style="font-size:11px!important"> : {{ $amounts['total_paid_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start text-danger" style="font-size:11px!important">{{ __('Curr. Balance') }}</th>
                                <td class="text-start fw-bold text-danger" style="font-size:11px!important"> : {{ $amounts['closing_balance_in_flat_amount_string'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <hr>

            <form id="edit_money_receipt_form" action="{{ route('contacts.money.receipts.update', $moneyReceipt->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-3">
                        <label><b>{{ __('Receiving Amount') }}</b> </label>
                        <input type="text" name="amount" class="form-control fw-bold" id="mr_amount" data-next="mr_date" value="{{ $moneyReceipt->amount }}" placeholder="@lang('menu.receiving_amount')" autocomplete="off" />
                        <span class="error error_mr_amount"></span>
                    </div>

                    <div class="col-md-3">
                        <label><strong>{{ __('Date') }}</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                            </div>
                            <input required type="text" name="date" class="form-control" id="mr_date" value="{{ $moneyReceipt->date_ts ? date($generalSettings['business_or_shop__date_format'], strtotime($moneyReceipt->date_ts)) : '' }}" data-next="mr_account_details" autocomplete="off">
                            <span class="error error_mr_date"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label><b>{{ __('Account Details') }}</b></label>
                        <input type="text" name="account_details" class="form-control" id="mr_account_details" value="{{ $moneyReceipt->ac_details }}" data-next="mr_receiver" placeholder="{{ __('Account Details ') }}" />
                    </div>

                    <div class="col-md-3">
                        <label><b>{{ __('Receiver') }}</b></label>
                        <input type="text" name="receiver" class="form-control" id="mr_receiver" value="{{ $moneyReceipt->receiver }}" data-next="mr_note" placeholder="{{ __('Receiver Name') }}" />
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12">
                        <label><b>{{ __('Footer Note') }}</b></label>
                        <input type="text" name="note" class="form-control" id="mr_note" value="{{ $moneyReceipt->note }}" data-next="mr_is_customer_name" placeholder="{{ __('Footer Note') }}">
                    </div>
                </div>

                <div class="extra_label">
                    <div class="form-group row mt-2">
                        <div class="col-md-3">
                            <label><b>{{ __('Show Customer Name') }}</b></label>
                            <select name="is_customer_name" class="form-control" id="mr_is_customer_name" data-next="mr_is_date">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $moneyReceipt->is_customer_name == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><b>{{ __('Show Date') }}</b></label>
                            <select name="is_date" class="form-control" id="mr_is_date" data-next="mr_is_header_less">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $moneyReceipt->is_date == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><b>{{ __('Is Headerless For Pad Print') }}</b></label>
                            <select name="is_header_less" class="form-control" id="mr_is_header_less" data-next="money_receipt_save_changes">
                                <option value="0">{{ __('No') }}</option>
                                <option {{ $moneyReceipt->is_date == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3 gap-from-top-add {{ $moneyReceipt->is_header_less == 0 ? 'd-hide' : '' }}">
                            <label><b>{{ __('Gap From Top (Inches)') }}</b></label>
                            <input type="text" name="gap_from_top" id="mr_gap_from_top" class="form-control" value="{{ $moneyReceipt->gap_from_top }}" data-next="money_receipt_save_changes" placeholder="{{ __('Gap From Top') }}" />
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button mr_loading_button d-hide"><i class="fas fa-spinner"></i><span>{{ __('Loading') }} ...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="money_receipt_save_changes" class="btn btn-sm btn-success monery_receipt_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('contacts.money_receipts.js_partials.edit_js')
