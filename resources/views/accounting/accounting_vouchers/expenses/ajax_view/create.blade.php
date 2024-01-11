@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business_or_shop__date_format'];
@endphp
<style>
    .sale-item-sec { height: 400px; }

    .select2-selection:focus { box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%); color: #212529; background-color: #fff; border-color: #86b7fe; outline: 0; }

    .select2-container .select2-selection--single .select2-selection__rendered { display: inline-block; width: 143px; }

    .expense_ledgers_table tbody tr td { padding: 4px!important; }
    .expense_ledgers_table thead tr th { padding: 4px!important; }
</style>
<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Expense") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="add_expense_form" action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="action">
                <div class="form-group row">
                    <div class="col-md-5">
                        <p class="fw-bold">{{ __("Credit A/c Details") }}</p>
                        <hr class="p-0 m-0">
                        <div class="row" style="border-right:1px solid black;">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Date") }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="expense_date" data-next="expense_payment_method_id" value="{{ date($generalSettings['business_or_shop__date_format']) }}" placeholder="{{ __("Date") }}" autocomplete="off">
                                <span class="error error_expense_date"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Type/Method") }} <span class="text-danger">*</span></label>
                                <select required name="payment_method_id" class="form-control" id="expense_payment_method_id" data-next="expense_credit_account_id">
                                    @foreach ($methods as $method)
                                        <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_expense_payment_method_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Credit A/c") }} <span class="text-danger">*</span></label>
                                <select required name="credit_account_id" class="form-control select2" id="expense_credit_account_id" data-next="expense_transaction_no">
                                    <option value="">{{ __("Select Credit A/c") }}</option>
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
                                <span class="error error_expense_credit_account_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Transaction No") }}</label>
                                <input name="transaction_no" class="form-control" id="expense_transaction_no" data-next="expense_cheque_no" placeholder="{{ __("Transaction No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque No") }}</label>
                                <input name="cheque_no" class="form-control" id="expense_cheque_no" data-next="expense_cheque_serial_no" placeholder="{{ __("Cheque No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque Serial No") }}</label>
                                <input name="cheque_serial_no" class="form-control" id="expense_cheque_serial_no" data-next="expense_reference" placeholder="{{ __("Cheque Serial No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Reference") }}</label>
                                <input name="reference" class="form-control" id="expense_reference" data-next="expense_remarks" placeholder="{{ __("reference") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Remarks") }}</label>
                                <input name="remarks" class="form-control" id="expense_remarks" data-next="expense_debit_account_id" placeholder="{{ __("Remarks") }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="fw-bold">{{ __("Expense Ledgers") }}</p>
                                <hr class="p-0 m-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec mt-2">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="modal-table data__table table expense_ledgers_table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Expense Ledger') }}</th>
                                                                <th class="text-start">{{ __('Amount') }}</th>
                                                                <th class="text-start">{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="expense_ledger_list">
                                                            <tr>
                                                                <td width="50">
                                                                    <div class="input-group flex-nowrap">
                                                                        <select required name="debit_account_ids[]" class="form-control expense_debit_account_id" id="expense_debit_account_id">
                                                                            <option value="">{{ __("Select Expense Ledger") }}</option>
                                                                            @foreach ($expenseAccounts as $expenseAccount)
                                                                                <option value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    @php
                                                                        $uniqueId = uniqid();
                                                                    @endphp
                                                                    <input type="hidden" class="unique_id-{{ $uniqueId }}" id="unique_id" value="{{ $uniqueId }}">
                                                                </td>

                                                                <td width="25">
                                                                    <input required type="number" step="any" name="amounts[]" class="form-control fw-bold" id="expense_amount" value="0.00">
                                                                </td>

                                                                <td width="25">
                                                                    <select onchange="nextStep(this);" class="form-control" id="expense_next_step">
                                                                        <option value="">{{ __("Next Step") }}</option>
                                                                        <option value="add_more">{{ __("Add More") }}</option>
                                                                        <option value="next_field">{{ __("Next Field") }}</option>
                                                                        <option value="list_end">{{ __("List End") }}</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
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
                                    <span class="fw-bold">{{ __("Total Amount") }} : </span>
                                    <span id="span_expense_total_amount" class="fw-bold text-danger">0.00</span>
                                    <input type="hidden" name="total_amount" id="expense_total_amount">
                                </p>
                            </div>

                            {{-- <div class="col-md-12">
                                <label><strong>{{ __("Less Amount") }}</strong></label>
                                <input name="payment_less_amount" class="form-control" id="payment_less_amount" placeholder="{{ __("Less Amount") }}">
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button expense_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
                            <button type="submit" id="save_and_print" value="save_and_print" class="btn btn-sm btn-success expense_submit_button me-2" value="save_and_print">{{ __("Save & Print") }}</button>
                            <button type="submit" id="save" value="save" class="btn btn-sm btn-success expense_submit_button me-2" value="save">{{ __("Save") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('accounting.accounting_vouchers.expenses.ajax_view.js_partials.add_js')

