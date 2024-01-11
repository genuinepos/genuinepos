@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<style>
    .info_area { background: #efefef; padding: 4px; }
</style>

<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Edit Payroll Payment") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_payment_form" action="{{ route('hrm.payroll.payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="current_paid_amount" value="{{ $payment->total_amount }}">
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="row" style="border-right:1px solid rgb(226, 223, 223);">
                            <div class="col-md-12">
                                <table class="display table table-sm">
                                    <tr>
                                        <th colspan="2"><strong>{{ __("Employee Details") }}</strong></th>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Emplayee") }}</td>
                                        <td>: {{ $payment?->payrollRef?->user?->prefix .' '. $payment?->payrollRef?->user?->name .' '. $payment?->payrollRef?->user?->last_name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Employee ID") }}</td>
                                        <td>: {{ $payment?->payrollRef?->user?->emp_id }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Phone") }}</td>
                                        <td>: {{ $payment?->payrollRef?->user?->phone }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Designation") }}</td>
                                        <td>: {{ $payment?->payrollRef?->user?->designation?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Salary") }}</td>
                                        <td>: {{ $payment?->payrollRef?->user?->salary }} / {{ $payment?->payrollRef?->user?->salary_type }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="2"><strong>{{ __("Payroll Details") }}</strong></th>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Payroll") }}</td>
                                        <td>: {{ $payment?->payrollRef->month . ' - ' . $payment?->payrollRef->year }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Payroll Voucher") }}</td>
                                        <td>: {{ $payment?->payrollRef->voucher_no }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Expense Account") }}</td>
                                        <td>: {{ $payment?->payrollRef?->expenseAccount?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Pay Unit") }}</td>
                                        <td>: {{ $payment?->payrollRef->duration_unit }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Amount (As Per Unit)") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payment?->payrollRef->total_amount) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Allowance") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payment?->payrollRef->total_allowance) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Deduction") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payment?->payrollRef->total_deduction) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Gross Amount") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payment?->payrollRef->gross_amount) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    @php
                        $voucherDebitDescription = $payment->voucherDebitDescription;
                        $voucherCreditDescription = $payment->voucherCreditDescription;
                    @endphp

                    <div class="col-md-4">
                        <div class="row" style="border-right:1px solid rgb(226, 223, 223);">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Date") }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="payment_date" data-next="payment_credit_account_id" value="{{ date($dateFormat, strtotime($payment->date)) }}" placeholder="{{ __("Date") }}" autocomplete="off">
                                <span class="error error_payment_date"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Credit A/c") }} <span class="text-danger">*</span> </label>
                                <select required name="credit_account_id" class="form-control select2" id="payment_credit_account_id" data-next="payment_payment_method_id">
                                    <option value="">{{ __("Select Credit/Payment A/c") }}</option>
                                    @foreach ($accounts as $ac)
                                        @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                            @continue
                                        @endif

                                        <option {{ $voucherCreditDescription->account_id == $ac->id ? 'SELECTED' : '' }} value="{{ $ac->id }}">
                                            @php
                                                $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                                $groupName = ' | ' . $ac?->group?->name;
                                            @endphp
                                            {{ $ac->name . $acNo . $bank . $groupName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_payment_credit_account_id"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Type/Method") }} <span class="text-danger">*</span></label>
                                <select required name="payment_method_id" class="form-control" id="payment_payment_method_id" data-next="payment_transaction_no">
                                    @foreach ($methods as $method)
                                        <option {{ $voucherCreditDescription->payment_method_id == $method->id ? 'SELECTED' : '' }} data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_payment_payment_method_id"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Transaction No") }}</label>
                                <input name="transaction_no" class="form-control" id="payment_transaction_no" data-next="payment_cheque_no" value="{{ $voucherCreditDescription->transaction_no }}" placeholder="{{ __("Transaction No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Cheque No") }}</label>
                                <input name="cheque_no" class="form-control" id="payment_cheque_no" data-next="payment_cheque_serial_no" value="{{ $voucherCreditDescription->cheque_no }}" placeholder="{{ __("Cheque No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Cheque Serial No") }}</label>
                                <input name="cheque_serial_no" class="form-control" id="payment_cheque_serial_no" data-next="payment_reference" value="{{ $voucherCreditDescription->cheque_serial_no }}" placeholder="{{ __("Cheque Serial No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Reference") }}</label>
                                <input name="reference" class="form-control" id="payment_reference" data-next="payment_remarks" value="{{ $payment->reference }}" placeholder="{{ __("reference") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Remarks") }}</label>
                                <input name="remarks" class="form-control" id="payment_remarks" data-next="payment_debit_account_id" value="{{ $payment->remarks }}" placeholder="{{ __("Remarks") }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Debit/Expense A/c") }}</label>
                                <select name="debit_account_id" class="form-control select2" id="payment_debit_account_id" data-next="payment_paying_amount">
                                    <option value="">{{ __('Select Debit/Expense A/c') }}</option>
                                    @foreach ($expenseAccounts as $expenseAccount)
                                        <option {{ $voucherDebitDescription->account_id == $expenseAccount->id ? 'SELECTED' : '' }} value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Paying Amount") }}</label>
                                <div class="input-group">
                                    <input required oninput="calculateCurrentBalance(); return false;" type="number" step="any" name="paying_amount" class="form-control fw-bold w-75" id="payment_paying_amount" data-next="save_changes_btn" value="{{ $payment->total_amount }}" placeholder="{{ __("Paying Amount") }}">

                                    <input readonly type="text" class="form-control fw-bold text-danger text-end w-25" id="closing_balance_string" value="{{ App\Utils\Converter::format_in_bdt($payment->payrollRef->due) }}">
                                    <input type="hidden" id="closing_balance_flat_amount" value="{{ $payment->payrollRef->due }}">
                                </div>

                                <span class="error error_payment_paying_amount"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <p class="mt-1 text-uppercase" style="font-size: 11px;"><span class="fw-bold">{{ __("Inword") }} : </span> <span id="inword" class="text-danger fw-bold"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button payment_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
                            <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success payment_submit_button me-2">{{ __("Save Changes") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.payroll_payments.js_partials.edit_js')

