@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business__date_format'];
@endphp
<style>
    .top_card table tbody th{ line-height: 1; height: 17px; font-size: 11px; }

    .top_card table tbody td{ line-height: 1; height: 17px; font-size: 11px; }

    .sale-item-sec { height: 271px; }

    .selected_voucher_list { height: 388px!important; }

    .info_area { background: #efefef; padding: 4px; }
</style>
<div class="modal-dialog modal-full-display" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Receipt") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            @if ($account)
                <div class="info_area mb-1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="top_card">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Receipt From") }} :</td>
                                            <td class="text-end">{{ $account->name }}</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Address") }} :</td>
                                            <td class="text-end">{{ $account->address }}</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Phone") }} :</td>
                                            <td class="text-end">{{ $account->phone }}</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Current Balance") }} :</td>
                                            <td class="text-end">0.00</td>
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
                                            <td class="fw-bold text-end">{{ __("Opening Balance") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Total Sale") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Total Purchase") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Total Return") }} :</td>
                                            <td class="text-end">0.00</td>
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
                                            <td class="fw-bold text-end">{{ __("Total Received") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Total Paid") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">{{ __("Current Balance") }} :</td>
                                            <td class="text-end">0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form id="add_receipt_form" action="{{ route('receipts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="action">
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="row" style="border-right:1px solid black;">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Date") }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="receipt_date" data-next="receipt_payment_method_id" value="{{ date($generalSettings['business__date_format']) }}" placeholder="{{ __("Date") }}" autocomplete="off">
                                <span class="error error_receipt_date"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Type/Method") }} <span class="text-danger">*</span></label>
                                <select required name="payment_method_id" class="form-control" id="receipt_payment_method_id" data-next="receipt_debit_account_id">
                                    @foreach ($methods as $method)
                                        <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_receipt_payment_method_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Debit A/c") }} <span class="text-danger">*</span> </label>
                                <select required name="debit_account_id" class="form-control select2" id="receipt_debit_account_id" data-next="receipt_transaction_no">
                                    <option value="">{{ __("Select Debit A/c") }}</option>
                                    @foreach ($accounts as $ac)
                                        @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                            @continue
                                        @endif

                                        <option value="{{ $ac->id }}">
                                            @php
                                                $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                            @endphp
                                            {{ $ac->name . $acNo . $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_receipt_debit_account_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Transaction No") }}</label>
                                <input name="transaction_no" class="form-control" id="receipt_transaction_no" data-next="receipt_cheque_no" placeholder="{{ __("Transaction No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque No") }}</label>
                                <input name="cheque_no" class="form-control" id="receipt_cheque_no" data-next="receipt_cheque_serial_no" placeholder="{{ __("Cheque No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque Serial No") }}</label>
                                <input name="cheque_serial_no" class="form-control" id="receipt_cheque_serial_no" data-next="receipt_reference" placeholder="{{ __("Cheque Serial No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Reference") }}</label>
                                <input name="reference" class="form-control" id="receipt_reference" data-next="receipt_remarks" placeholder="{{ __("reference") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Remarks") }}</label>
                                <input name="remarks" class="form-control" id="receipt_remarks" data-next="receipt_credit_account_id" placeholder="{{ __("Remarks") }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            @if ($account)
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __("Credit A/c") }}</label>
                                    <input readonly class="form-control fw-bold" value="{{ $account->name }}">
                                    <input type="hidden" name="credit_account_id" value="{{ $account->id }}">
                                </div>
                            @else
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __("Credit A/c") }}</label>
                                    <select name="credit_account_id" class="form-control select2" id="receipt_credit_account_id" data-next="receipt_received_amount">
                                        <option value="">{{ __('Select Credit A/c') }}</option>
                                        @foreach ($receivableAccounts as $receivableAccount)
                                            @php
                                                $phoneNo = $receivableAccount->phone ?  '/' . $receivableAccount->phone : '';
                                            @endphp
                                            <option value="{{ $receivableAccount->id }}">{{ $receivableAccount->name . $phoneNo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Receipt Amount") }}</label>
                                <input required type="number" step="any" name="received_amount" class="form-control fw-bold" id="receipt_received_amount" data-next="save_and_print" placeholder="{{ __("Receipt Amount") }}">
                                <span class="error error_received_amount"></span>
                            </div>

                            <div class="col-md-12 mt-2">
                                <p class="fw-bold">{{ __("Receipt Against Vouchers") }}</p>
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
                                                                <th class="text-start">{{ __('Amount') }}</th>
                                                                <th class="text-start">{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="selected_voucher_list">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p>
                                    <span class="fw-bold">{{ __("Receipt Against Voucher Amount") }} : </span>
                                    <span id="voucher_total_amount" class="fw-bold text-danger">0.00</span>
                                </p>
                            </div>

                            {{-- <div class="col-md-12">
                                <label><strong>{{ __("Less Amount") }}</strong></label>
                                <input name="receipt_less_amount" class="form-control" id="receipt_less_amount" placeholder="{{ __("Less Amount") }}">
                            </div> --}}
                        </div>
                    </div>

                    <div class="col-md-4">

                        <div class="row" style="border-left:1px solid black;">
                            <p class="fw-bold">{{ __("List Of Receivable Vouchers") }}</p>
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
                                                                    <input type="checkbox" onchange="selectVoucher(this)" class="select_voucher" id="{{ $voucher['voucherId'].$voucher['refId'] }}"  value="{{ $voucher['voucherId'].$voucher['refId'] }}">
                                                                    <input type="hidden" id="db_voucher_no" value="{{ $voucher['voucherNo'] }}">
                                                                    <input type="hidden" id="db_voucher_type" value="{{ $voucher['voucherType'] }}">
                                                                    <input type="hidden" id="db_voucher_type_str" value="{{ $voucher['voucherTypeStr'] }}">
                                                                    <input type="hidden" id="db_voucher_id" value="{{ $voucher['voucherId'] }}">
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
                                <p class="mt-1 text-uppercase" style="font-size: 11px;"><span class="fw-bold">{{ __("Inword") }} : </span> <span id="inword" class="text-danger fw-bold"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button receipt_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
                            <button type="button" id="save_and_print" value="save_and_print" class="btn btn-sm btn-success receipt_submit_button me-2" value="save_and_print">{{ __("Save & Print") }}</button>
                            <button type="button" id="save" value="save" class="btn btn-sm btn-success receipt_submit_button me-2" value="save">{{ __("Save") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('accounting.accounting_vouchers.receipts.ajax_view.js_partials.add_js')

