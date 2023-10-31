@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business__date_format'];
@endphp
<style>
    .select2-selection:focus { box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%); color: #212529; background-color: #fff; border-color: #86b7fe; outline: 0; }

    .select2-container .select2-selection--single .select2-selection__rendered { display: inline-block; width: 350px; }
</style>
<div class="modal-dialog col-55-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Contra") }} | Voucher No: <strong>{{ $contra->voucher_no }}</strong></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        @php
            $debitDescription = $contra->voucherDebitDescription;
            $creditDescription = $contra->voucherCreditDescription;
        @endphp

        <div class="modal-body">
            <form id="edit_contra_form" action="{{ route('contras.update', $contra->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <p class="fw-bold">{{ __("Credit A/c Details") }}</p>
                        <hr class="p-0 m-0">
                        <div class="row" style="border-right:1px solid black;">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Date") }} <span class="text-danger">*</span></label>
                                <input required name="date" class="form-control" id="contra_date" data-next="contra_credit_account_id" value="{{ date($generalSettings['business__date_format'], strtotime($contra->date)) }}" placeholder="{{ __("Date") }}" placeholder="{{ __("Date") }}" autocomplete="off">
                                <span class="error error_contra_date"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Sender A/c") }} <span class="text-danger">*</span></label>
                                <select required name="credit_account_id" class="form-control select2" id="contra_credit_account_id" data-next="contra_payment_method_id">
                                    <option value="">{{ __("Select Sender A/c") }}</option>
                                    @foreach ($accounts as $ac)
                                        @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                            @continue
                                        @endif

                                        <option {{ $creditDescription->account_id == $ac->id ? 'SELECTED' : '' }} value="{{ $ac->id }}">
                                            @php
                                                $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                            @endphp
                                            {{ $ac->name . $acNo . $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_contra_credit_account_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Type/Method") }} <span class="text-danger">*</span></label>
                                <select required name="payment_method_id" class="form-control" id="contra_payment_method_id" data-next="contra_transaction_no">
                                    @foreach ($methods as $method)
                                        <option {{ $creditDescription->payment_method_id == $method->id ? 'SELECTED' : '' }} data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_contra_payment_method_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Transaction No") }}</label>
                                <input name="transaction_no" class="form-control" id="contra_transaction_no" data-next="contra_cheque_no" value="{{ $creditDescription->transaction_no }}" placeholder="{{ __("Transaction No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque No") }}</label>
                                <input name="cheque_no" class="form-control" id="contra_cheque_no" data-next="contra_cheque_serial_no" value="{{ $creditDescription->cheque_no }}" placeholder="{{ __("Cheque No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Cheque Serial No") }}</label>
                                <input name="cheque_serial_no" class="form-control" id="contra_cheque_serial_no" data-next="contra_reference" value="{{ $creditDescription->cheque_serial_no }}" placeholder="{{ __("Cheque Serial No") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Reference") }}</label>
                                <input name="reference" class="form-control" id="contra_reference" data-next="contra_remarks" value="{{ $contra->reference }}" placeholder="{{ __("reference") }}">
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Remarks") }}</label>
                                <input name="remarks" class="form-control" id="contra_remarks" data-next="contra_debit_account_id" value="{{ $contra->remarks }}" placeholder="{{ __("Remarks") }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <p class="fw-bold">{{ __("Debit A/c Details") }}</p>
                        <hr class="p-0 m-0">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Receiver A/c") }} <span class="text-danger">*</span></label>
                                <select required name="debit_account_id" class="form-control select2" id="contra_debit_account_id" data-next="contra_received_amount">
                                    <option value="">{{ __("Select Sender A/c") }}</option>
                                    @foreach ($accounts as $ac)
                                        @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                            @continue
                                        @endif

                                        <option {{ $debitDescription->account_id == $ac->id ? 'SELECTED' : '' }} value="{{ $ac->id }}">
                                            @php
                                                $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                            @endphp
                                            {{ $ac->name . $acNo . $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_contra_credit_account_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold">{{ __("Received Amount") }}</label>
                                <input type="number" step="any" name="received_amount" class="form-control fw-bold" id="contra_received_amount" data-next="save_changes"  value="{{ $contra->total_amount }}"  placeholder="{{ __("Received Amount") }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button contra_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
                            <button type="submit" id="save_changes" value="save" class="btn btn-sm btn-success contra_submit_button me-2" value="save">{{ __("Save Changes") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('accounting.accounting_vouchers.contras.ajax_view.js_partials.edit_js')
