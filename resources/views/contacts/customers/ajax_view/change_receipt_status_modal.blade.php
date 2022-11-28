<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Change Money Receipt Voucher Status (Voucher No : {{ $receipt->invoice_id }} )</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="change_voucher_status_form" action="{{ route('money.receipt.voucher.status.change', $receipt->id) }}" method="POST">
                @csrf
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>Received Amount :</b> <span class="text-danger">*</span> </label>
                        <input type="number" step="any" name="amount" class="form-control form-control-sm vcs_input" id="received_amount" data-name="Received amount" placeholder="Received Amount"/>
                        <span class="error error_vcs_received_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Status :</strong> </strong> <span class="text-danger">*</span> </label>
                        <select disabled name="status" class="form-control form-control-sm mr_input" data-name="Money receipt status" id="vcs_status">
                            <option value="Pending">Pending</option>
                            <option selected value="Completed">Completed</option>
                        </select>
                        <span class="error error_vcs_status"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('menu.date') :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-calendar-week text-dark"></i></span>
                            </div>
                            <input type="date" name="date" class="form-control form-control-sm date-picker p_input"
                                autocomplete="off" id="p_date" data-name="Date" value="{{ date('Y-m-d') }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><strong>Payment Method :</strong> </strong></label>
                        <select name="payment_method" class="form-control form-control-sm" id="vcs_status">
                            <option value="Cash">Cash</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Card">Card</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Bank-Transfer">Bank-Transfer</option>
                            <option value="Other">Other</option>
                            <option value="Custom">Custom Field</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label><strong>Account :</strong> </strong> </label>
                        <select name="account_id" class="form-control form-control-sm">
                            <option value="">None</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} (A/C:
                                    {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                        <button type="submit" class="c-btn button-success float-end">Save</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>