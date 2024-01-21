@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business_or_shop__date_format'];
@endphp

<style>
    .info_area { background: #efefef; padding: 4px; }
</style>

<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Add Payroll Payment") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="add_payment_form" action="{{ route('hrm.payroll.payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="payroll_id" value="{{ $payroll->id }}">
                <input type="hidden" name="print_page_size" id="print_page_size" value="{{ $generalSettings['print_page_size__payroll_payment_voucher_page_size'] }}">
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
                                        <td>: {{ $payroll?->user?->prefix .' '. $payroll?->user?->name .' '.$payroll?->user?->last_name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Employee ID") }}</td>
                                        <td>: {{ $payroll?->user?->emp_id }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Phone") }}</td>
                                        <td>: {{ $payroll?->user?->phone }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Designation") }}</td>
                                        <td>: {{ $payroll?->user?->designation?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">Salary</td>
                                        <td>: {{ $payroll?->user?->salary }} / {{ $payroll?->user?->salary_type }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="2"><strong>{{ __("Payroll Details") }}</strong></th>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Payroll") }}</td>
                                        <td>: {{ $payroll->month .' - ' . $payroll->year }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Payroll Voucher") }}</td>
                                        <td>: {{ $payroll->voucher_no }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Expense Account") }}</td>
                                        <td>: {{ $payroll?->expenseAccount?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Pay Unit") }}</td>
                                        <td>: {{ $payroll->duration_unit }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Amount (As Per Unit)") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Allowance") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Total Deduction") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">{{ __("Gross Amount") }}</td>
                                        <td>: {{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row" style="border-right:1px solid rgb(226, 223, 223);">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Date") }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="payment_date" data-next="payment_credit_account_id" value="{{ date($generalSettings['business_or_shop__date_format']) }}" placeholder="{{ __("Date") }}" autocomplete="off">
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

                                        <option value="{{ $ac->id }}">
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
                                        <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_payment_payment_method_id"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Transaction No") }}</label>
                                <input name="transaction_no" class="form-control" id="payment_transaction_no" data-next="payment_cheque_no" placeholder="{{ __("Transaction No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Cheque No") }}</label>
                                <input name="cheque_no" class="form-control" id="payment_cheque_no" data-next="payment_cheque_serial_no" placeholder="{{ __("Cheque No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Cheque Serial No") }}</label>
                                <input name="cheque_serial_no" class="form-control" id="payment_cheque_serial_no" data-next="payment_reference" placeholder="{{ __("Cheque Serial No") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Reference") }}</label>
                                <input name="reference" class="form-control" id="payment_reference" data-next="payment_remarks" placeholder="{{ __("reference") }}">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Remarks") }}</label>
                                <input name="remarks" class="form-control" id="payment_remarks" data-next="payment_debit_account_id" placeholder="{{ __("Remarks") }}">
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
                                        <option {{ $payroll->expense_account_id == $expenseAccount->id ? 'SELECTED' : '' }} value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">{{ __("Paying Amount") }}</label>
                                <div class="input-group">
                                    <input required oninput="calculateCurrentBalance(); return false;" type="number" step="any" name="paying_amount" class="form-control fw-bold w-75" id="payment_paying_amount" data-next="save_and_print" placeholder="{{ __("Paying Amount") }}">

                                    <input readonly type="text" class="form-control fw-bold text-danger text-end w-25" id="closing_balance_string" value="{{ App\Utils\Converter::format_in_bdt($payroll->due) }}" placeholder="{{ __("Current Balance") }}">
                                    <input type="hidden" id="closing_balance_flat_amount" value="{{ $payroll->due }}">
                                </div>

                                <span class="error error_payment_paying_amount"></span>
                            </div>

                            <div class="col-md-12 mt-1">
                                <p class="mt-1 text-uppercase" style="font-size: 11px;"><span class="fw-bold">{{ __("Inword") }} : </span> <span id="inword" class="text-danger fw-bold"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="m-0 p-0 my-1">

                <div class="form-group d-flex mt-2 justify-content-end" style="gap: 20px;">
                    <div class="input-group" style="width: max-content; align-items: center; gap: 10px;">
                        <label><b>{{ __('Print') }}</b></label>
                        <select id="select_print_page_size" class="form-control">
                            @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                <option {{ $generalSettings['print_page_size__payroll_payment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button payment_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
                            <button type="submit" id="save_and_print" value="save_and_print" class="btn btn-sm btn-success payment_submit_button me-2" value="save_and_print">{{ __("Save & Print") }}</button>
                            <button type="submit" id="save" value="save" class="btn btn-sm btn-success payment_submit_button me-2" value="save">{{ __("Save") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('hrm.payroll_payments.js_partials.add_js')

