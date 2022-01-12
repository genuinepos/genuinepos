<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Customer : </strong><span>{{ $payment->sale->customer ? $payment->sale->customer->name : 'Walk-In-Customer' }}</span> </li>
                    <li><strong>Business : </strong><span>{{ $payment->sale->customer ? $payment->sale->customerbusiness_name : '' }}</span> </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> Invoice ID : </strong><span>{{ $payment->sale->invoice_id }}</span> </li>
                    <li><strong>Branch/Business : </strong>
                        <span>
                            @if ($payment->sale->branch)
                                {{ $payment->sale->branch->name.'/'.$payment->sale->branchbranch_code }}
                            @else
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                            @endif
                        </span>  
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="sale_due">
                        <strong>Total Due : {{ json_decode($generalSettings->business, true)['currency'] }} </strong>
                        <span>{{ $payment->sale->due }}</span> 
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="sale_payment_form" action="{{ route('sales.payment.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="far fa-money-bill-alt text-dark input_i"></i>
                    </span>
                </div>
                <input type="hidden" id="available_amount" value="{{ $payment->sale->due+$payment->paid_amount }}">
                <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_paying_amount" value="{{ $payment->paid_amount }}"/>
            </div>
            <span class="error error_p_paying_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>Date :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-calendar-week text-dark input_i"></i>
                    </span>
                </div>
                <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Payment Method :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-money-check text-dark input_i"></i>
                    </span>
                </div>
                <select name="payment_method_id" class="form-control" id="payment_method_id">
                    @foreach ($methods as $method)
                        <option {{ $method->id == $payment->payment_method_id ? 'SELECTED' : '' }} value="{{ $method->id }}">
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
                <span class="error error_p_payment_method"></span>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-7">
            <label><strong>Debit Account :</strong> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-money-check-alt text-dark input_i"></i>
                    </span>
                </div>
                <select name="account_id" class="form-control p_input" id="p_account_id">
                    @foreach ($accounts as $account)
                        <option {{ $payment->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
                            @php
                                $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                $balance = ' BL : '.$account->balance;
                            @endphp
                            {{ $account->name.$accountType.$balance }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-5">
            <label><strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> </label>
            <input type="file" name="attachment" class="form-control" id="attachment" data-name="Date" >
        </div>
    </div>

    <div class="form-group">
        <label><strong> Payment Note :</strong></label>
        <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="Note">{{ $payment->note }}</textarea>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>

<script>
    $('#sale_payment_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var available_amount = $('#available_amount').val();
        var paying_amount = $('#p_paying_amount').val();
        if (parseFloat(paying_amount)  > parseFloat(available_amount)) {
            $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');

        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR');
                    $('.loading_button').hide();
                } else {
                    $('.loading_button').hide();
                    $('#paymentModal').modal('hide');
                    $('#paymentViewModal').modal('hide');
                    sales_table.ajax.reload();
                    toastr.success(data);
                }
            },error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_p_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('p_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>