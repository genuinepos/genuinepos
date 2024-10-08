@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business_or_shop__date_format'];
@endphp
<style>
    .top_card table tbody th {
        line-height: 1;
        height: 17px;
        font-size: 11px;
    }

    .top_card table tbody td {
        line-height: 1;
        height: 17px;
        font-size: 11px;
    }

    .sale-item-sec {
        height: 271px;
    }

    .selected_voucher_list {
        height: 388px !important;
    }

    .info_area {
        background: #efefef;
        padding: 4px;
    }
</style>
<div class="modal-dialog modal-full-display" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Receipt') }} || {{ __('Voucher No') }} : <strong>{{ $receipt->voucher_no }}</strong></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            @php

                $voucherDebitDescription = $receipt->voucherDebitDescription;
                $creditAccount = $receipt->voucherCreditDescription->account;
                $voucherCreditDescription = $receipt->voucherCreditDescription->references()->orderBy('id', 'asc')->get();

                $accountBalanceService = new App\Services\Accounts\AccountBalanceService();

                $branchId = $receipt->branch_id == null ? 'NULL' : $receipt->branch_id;
                $__branchId = $creditAccount->group?->sub_sub_group_number == 6 ? $branchId : '';
                $amounts = $accountBalanceService->accountBalance(accountId: $creditAccount->id, fromDate: null, toDate: null, branchId: $__branchId);
            @endphp
            @if ($account)
                <div class="info_area mb-1">
                    <div class="row">
                        <div class="col-md-4" style="border-right:1px solid #000;">
                            <div class="top_card">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __('Receipt From') }} :</td>
                                            <td class="text-end">{{ $account->name }} | (<span class="fw-bold">{{ $account?->group?->name }}</span>)</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __('Address') }} :</td>
                                            <td class="text-end">{{ $account->address }}</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __('Phone') }} :</td>
                                            <td class="text-end">{{ $account->phone }}</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __('Current Balance') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['closing_balance_string'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4" style="border-right:1px solid #000;">
                            <div class="top_card">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Opening Balance') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['opening_balance_in_flat_amount_string'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Total Sale') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['total_sale_string'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Total Purchase') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['total_purchase_string'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Total Return') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['total_return_string'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="top_card">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Total Received') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['total_received_string'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end fw-bold">{{ __('Total Paid') }} :</td>
                                            <td class="text-end fw-bold">{{ $amounts['total_paid_string'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end fw-bold text-danger">{{ __('Current Balance') }} :</td>
                                            <td class="text-end fw-bold text-danger">{{ $amounts['closing_balance_in_flat_amount_string'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form id="edit_receipt_form" action="{{ route('receipts.update', $receipt->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="current_received_amount" value="{{ $receipt->total_amount }}">
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="row" style="border-right:1px solid black;">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Date') }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="receipt_date" data-next="receipt_payment_method_id" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($receipt->date)) }}" placeholder="{{ __('Date') }}" autocomplete="off">
                                <span class="error error_receipt_date"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Type/Method') }} <span class="text-danger">*</span></label>
                                <select required name="payment_method_id" class="form-control" id="receipt_payment_method_id" data-next="receipt_debit_account_id">
                                    @foreach ($methods as $method)
                                        <option {{ $voucherDebitDescription->payment_method_id == $method->id ? 'SELECTED' : '' }} value="{{ $method->id }}">{{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_receipt_payment_method_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Debit A/c') }} <span class="text-danger">*</span> </label>
                                <select required name="debit_account_id" class="form-control select2" id="receipt_debit_account_id" data-next="receipt_transaction_no">
                                    <option value="">{{ __('Select Debit A/c') }}</option>
                                    @foreach ($accounts as $ac)
                                        @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                            @continue
                                        @endif

                                        @php
                                            $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                            $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                            $groupName = ' | ' . $ac?->group?->name;
                                        @endphp

                                        <option {{ $voucherDebitDescription->account_id == $ac->id ? 'SELECTED' : '' }} value="{{ $ac->id }}">
                                            {{ $ac->name . $acNo . $bank . $groupName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_receipt_debit_account_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Transaction No') }}</label>
                                <input name="transaction_no" class="form-control" id="receipt_transaction_no" data-next="receipt_cheque_no" value="{{ $voucherDebitDescription->transaction_no }}" placeholder="{{ __('Transaction No') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Cheque No') }}</label>
                                <input name="cheque_no" class="form-control" id="receipt_cheque_no" data-next="receipt_cheque_serial_no" value="{{ $voucherDebitDescription->cheque_no }}" placeholder="{{ __('Cheque No') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Cheque Serial No') }}</label>
                                <input name="cheque_serial_no" class="form-control" id="receipt_cheque_serial_no" data-next="receipt_reference" value="{{ $voucherDebitDescription->cheque_serial_no }}" placeholder="{{ __('Cheque Serial No') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Reference') }}</label>
                                <input name="reference" class="form-control" id="receipt_reference" data-next="receipt_remarks" value="{{ $receipt->reference }}" placeholder="{{ __('reference') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Remarks') }}</label>
                                <input name="remarks" class="form-control" id="receipt_remarks" data-next="receipt_credit_account_id" value="{{ $receipt->remarks }}" placeholder="{{ __('Remarks') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            @if ($account)
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Credit A/c') }}</label>
                                    <input readonly class="form-control fw-bold" value="{{ $account->name }}">
                                    <input type="hidden" name="credit_account_id" value="{{ $account->id }}">
                                </div>
                            @else
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Credit A/c') }} <span class="text-danger">*</span></label>
                                    <select required name="credit_account_id" onchange="changeAccount(this); return false;" class="form-control select2" id="receipt_credit_account_id" data-next="receipt_received_amount">
                                        <option value="">{{ __('Select Credit A/c') }}</option>
                                        @foreach ($receivableAccounts as $receivableAccount)
                                            @php
                                                $phoneNo = $receivableAccount->phone ? '/' . $receivableAccount->phone : '';
                                                $groupName = ' | ' . $receivableAccount->group_name;
                                                $subSubGroupNumber = $receivableAccount->sub_sub_group_number;
                                            @endphp
                                            <option data-sub_sub_group_number="{{ $subSubGroupNumber }}" {{ $receipt->voucherCreditDescription->account_id == $receivableAccount->id ? 'SELECTED' : '' }} value="{{ $receivableAccount->id }}">{{ $receivableAccount->name . $phoneNo . $groupName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Receipt Amount') }}</label>
                                <div class="input-group">
                                    <input required oninput="calculateCurrentBalance(); return false;" type="number" step="any" name="received_amount" class="form-control fw-bold w-75" id="receipt_received_amount" data-next="save_changes" value="{{ $receipt->total_amount }}" placeholder="{{ __('Receipt Amount') }}">

                                    @php
                                        $closingBalanceInFlatAmountStr = $amounts['closing_balance_in_flat_amount_string'];
                                        $closingBalanceInFlatAmount = $amounts['closing_balance_in_flat_amount'];
                                        $defaultBalanceType = $amounts['default_balance_type'];
                                    @endphp

                                    <input readonly type="text" class="form-control fw-bold text-danger text-end w-25" id="closing_balance_string" value="{{ $closingBalanceInFlatAmountStr }}" placeholder="{{ __('Current Balance') }}">
                                    <input type="hidden" id="closing_balance_flat_amount" value="{{ $closingBalanceInFlatAmount }}">
                                    <input type="hidden" id="default_balance_type" value="{{ $defaultBalanceType }}">
                                </div>
                                <span class="error error_received_amount"></span>
                            </div>

                            <div class="col-md-12 mt-2">
                                <p class="fw-bold">{{ __('Receipt Against Vouchers') }}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Voucher No') }}</th>
                                                                <th class="text-start">{{ __('V. Type') }}</th>
                                                                <th class="text-start">{{ __('Received') }}</th>
                                                                <th class="text-start">{{ __('Curr. Due') }}</th>
                                                                <th class="text-start">{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="selected_voucher_list">
                                                            @php
                                                                $totalReceiptAgainstVoucher = 0;
                                                            @endphp
                                                            @foreach ($voucherCreditDescription as $reference)
                                                                @php
                                                                    $voucherNo = '';
                                                                    $voucherType = '';
                                                                    $voucherTypeStr = '';
                                                                    $refId = '';
                                                                    $remainingAmount = '';
                                                                    $totalReceiptAgainstVoucher += $reference->amount;
                                                                    if ($reference?->sale) {
                                                                        if ($reference->sale->status == \App\Enums\SaleStatus::Final->value) {
                                                                            $voucherNo = $reference->sale->invoice_id;
                                                                            $voucherType = \App\Enums\DayBookVoucherType::Sales->value;
                                                                            $voucherTypeStr = __('Sales');
                                                                            $refId = $reference->sale->id;
                                                                            $remainingAmount = $reference->sale->due;
                                                                        } elseif ($reference->sale->status == \App\Enums\SaleStatus::Order->value) {
                                                                            $voucherNo = $reference->sale->order_id;
                                                                            $voucherType = \App\Enums\DayBookVoucherType::SalesOrder->value;
                                                                            $voucherTypeStr = __('Sales-Order');
                                                                            $refId = $reference->sale->id;
                                                                            $remainingAmount = $reference->sale->due;
                                                                        }
                                                                    } elseif ($reference?->purchaseReturn) {
                                                                        $voucherNo = $reference->purchaseReturn->voucher_no;
                                                                        $voucherType = \App\Enums\DayBookVoucherType::PurchaseReturn->value;
                                                                        $voucherTypeStr = __('Purchase Return');
                                                                        $refId = $reference->purchaseReturn->id;
                                                                        $remainingAmount = $reference->purchaseReturn->due;
                                                                    }
                                                                @endphp
                                                                <tr id="voucher_tr">
                                                                    <td class="text-start">
                                                                        <input readonly type="text" class="form-control fw-bold" id="voucher_no" value="{{ $voucherNo }}">
                                                                        <input type="hidden" class="{{ $voucherNo }}" id="voucher_id" value="{{ $voucherNo }}">
                                                                        <input type="hidden" name="voucher_types[]" id="voucher_type" value="{{ $voucherType }}">
                                                                        <input type="hidden" id="voucher_type_str" value="{{ $voucherTypeStr }}">

                                                                        @if ($voucherType != \App\Enums\DayBookVoucherType::PurchaseReturn->value)
                                                                            <input type="hidden" name="ref_ids[]" id="ref_id" value="{{ $refId }}">
                                                                        @else
                                                                            <input type="hidden" id="ref_id" value="{{ $refId }}">
                                                                        @endif

                                                                        <input type="hidden" class="unique_id" id="{{ $voucherNo . $refId }}" value="{{ $voucherNo . $refId }}">
                                                                    </td>

                                                                    <td class="text-start"><span id="span_voucher_type">{{ $voucherTypeStr }}</span></td>

                                                                    <td class="text-start">
                                                                        @if ($voucherType != \App\Enums\DayBookVoucherType::PurchaseReturn->value)
                                                                            <input readonly type="number" name="amounts[]" step="any" class="form-control fw-bold" id="amount" value="{{ $reference->amount }}">
                                                                        @else
                                                                            <input readonly type="number" step="any" class="form-control fw-bold" id="amount" value="{{ $reference->amount }}">
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-start text-danger fw-bold">{{ $remainingAmount }}</td>

                                                                    <td class="text-start">
                                                                        <a href="#" id="remove_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p><span class="fw-bold">{{ __('Receipt Against Voucher Amount') }} : </span> <span id="voucher_total_amount" class="fw-bold text-danger">{{ App\Utils\Converter::format_in_bdt($totalReceiptAgainstVoucher) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">

                        <div class="row" style="border-left:1px solid black;">
                            <p class="fw-bold">{{ __('List of Receivable Vouchers') }}</p>
                            <div class="col-md-12">
                                <div class="sale-item-sec selected_voucher_list">
                                    <div class="sale-item-inner">
                                        <div class="table-responsive">
                                            <table class="display data__table table sale-product-table">
                                                <thead class="staky">
                                                    <tr>
                                                        <th class="text-start"></th>
                                                        <th class="text-start">{{ __('Voucher No') }}</th>
                                                        <th class="text-start">{{ __('V. Type') }}</th>
                                                        <th class="text-start">{{ __('Payment Status') }}</th>
                                                        <th class="text-start">{{ __('Due Amount') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="vouchers_list">
                                                    @if (count($vouchers))
                                                        @foreach ($vouchers as $voucher)
                                                            <tr id="selectable_tr">
                                                                <td class="text-start">
                                                                    <input type="checkbox" onchange="selectVoucher(this)" class="select_voucher" id="{{ $voucher['voucherNo'] . $voucher['refId'] }}" value="{{ $voucher['voucherNo'] . $voucher['refId'] }}">
                                                                    <input type="hidden" id="db_voucher_no" value="{{ $voucher['voucherNo'] }}">
                                                                    <input type="hidden" id="db_voucher_type" value="{{ $voucher['voucherType'] }}">
                                                                    <input type="hidden" id="db_voucher_type_str" value="{{ $voucher['voucherTypeStr'] }}">
                                                                    <input type="hidden" id="db_voucher_id" value="{{ $voucher['voucherNo'] }}">
                                                                    <input type="hidden" id="db_ref_id" value="{{ $voucher['refId'] }}">
                                                                    <input type="hidden" id="db_amount" value="{{ $voucher['due'] }}">
                                                                </td>
                                                                <td class="text-start"><a href="#">{{ $voucher['voucherNo'] }}</a></td>
                                                                <td class="text-start">{{ $voucher['voucherTypeStr'] }}</td>
                                                                <td class="text-start {{ $voucher['paymentStatus'] == 'Partial' ? 'text-primary' : 'text-danger' }}">{{ $voucher['paymentStatus'] }}</td>
                                                                <td class="text-start text-danger fw-bold">{{ App\Utils\Converter::format_in_bdt($voucher['due']) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <p class="mt-1 text-uppercase" style="font-size: 11px;"><span class="fw-bold">{{ __('Inword') }} : </span> <span id="inword" class="text-danger fw-bold"></span></p>
                                {{-- <div class="col-md-12">
                                    <label><strong>{{ __("Less Amount") }}</strong></label>
                                    <input name="receipt_less_amount" class="form-control" id="receipt_less_amount" placeholder="{{ __("Less Amount") }}">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button receipt_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __('Loading') }}...</b></button>
                            <button type="submit" id="save_changes" value="save" class="btn btn-sm btn-success receipt_submit_button me-2" value="save">{{ __('Save Changes') }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('accounting.accounting_vouchers.receipts.ajax_view.js_partials.edit_js')
