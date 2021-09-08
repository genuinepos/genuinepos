<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677; padding: 0px 7px;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Customer : </strong>
                        <span class="card_text customer_name">
                            {{ $customer->name }}
                        </span>
                    </li>
                    <li><strong>Phone : </strong>
                        <span class="card_text customer_name">
                            {{ $customer->phone }}
                        </span>
                    </li>
                    <li>
                        <strong>Business : </strong>
                        <span class="card_text customer_business">{{ $customer->business_name }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Total Sale : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale }}
                        </span>
                    </li>
                    <li><strong>Total Paid : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_paid }}
                        </span>
                    </li>
                    <li><strong>Total Due : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale_due }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="money_receipt_form" action="{{ route('money.receipt.voucher.store', $customer->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><b>Receiving Amount :</b> </label>
            <input type="text" name="amount" class="form-control form-control-sm mr_input" id="mr_amount" placeholder="Receiving Amount" data-name="Receiving amount" value=""/>
            <span class="error error_mr_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>Date :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-calendar-week text-dark"></i></span>
                </div>
                <input type="text" name="date" class="form-control datepicker"
                    autocomplete="off" id="mr_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
            </div>
        </div>

        <div class="col-md-4">
            <label><strong>Status :</strong> </strong> <span class="text-danger">*</span> </label>
            <select name="status" class="form-control mr_input" data-name="Money receipt status" id="mr_status">
                <option value="Pending">Pending</option>
            </select>
            <span class="error error_mr_status"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <label><strong>Paper Note :</strong></label>
            <textarea name="note" class="form-control" id="note" cols="30" rows="3"
                placeholder="Paper Note"></textarea>
        </div>
    </div>

    <div class="extra_label">
        <div class="form-group row mt-2">
            <div class="col-md-3">
                <p> <input type="checkbox" name="is_amount" id="is_amount" value="1"> &nbsp; <b>Receiving Amount</b> </p>
            </div>
            
            <div class="col-md-2">
                <p> <input type="checkbox" CHECKED name="is_invoice_id" id="is_date" value="1"> &nbsp; <b>Voucher No</b></p>
            </div>
            
            <div class="col-md-2">
                <p> <input type="checkbox" CHECKED name="is_date" id="is_date" value="1"> &nbsp; <b>Show Date</b></p>
            </div>
            
            <div class="col-md-2">
                <p> <input type="checkbox" name="is_note" id="is_note" value="1"> &nbsp; <b>Paper Note</b></p>
            </div>
        </div>
    </div>

    <div class="extra_label">
        <div class="form-group row mt-2">
            <div class="col-md-3 mt-2">
                <p> <input type="checkbox" name="is_header_less" id="is_header_less" value="1"> &nbsp; <b>Is Header Less For Pad Print?</b> </p>
            </div>

            <div class="col-md-4 gap-from-top-add d-none">
                <label><b>Gap From Top :</b> </label>
                <input type="text" name="gap_from_top" class="form-control" placeholder="Gap From Top"/>
            </div>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>
<script>
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'dd');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    $('.datepicker').datepicker({format: _expectedDateFormat});
</script>
