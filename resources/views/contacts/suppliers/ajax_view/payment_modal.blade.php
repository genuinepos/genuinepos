<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}

    .due_all_table {min-height: 200px; max-height: 200px; overflow-x: scroll;}
    .due_purchase_table {min-height: 200px; max-height: 200px; overflow-x: hidden;}
    .due_order_table {min-height: 200px; max-height: 200px; overflow-x: hidden;}
    .seperate_area {border: 1px solid gray; padding: 6px;}
</style>
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Payment <span class="type_name"></span></h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>Supplier : </strong>
                                    <span class="card_text customer_name">{{ $supplier->name }}</span>
                                </li>
                                <li><strong>Business : </strong>
                                    <span class="card_text customer_business">{{ $supplier->business_name }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>Total Purchase : </strong>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    <span class="card_text">
                                        <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</b>
                                    </span>
                                </li>
                                <li><strong>Total Paid : </strong>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    <span class="card_text text-success">
                                        <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}</b>
                                    </span>
                                </li>
                                <li><strong>Total Due : </strong>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    <span class="card_text text-danger">
                                        <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</b> 
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="payment_form" action="{{ route('suppliers.payment.add', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="seperate_area">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group mt-1">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="radio" checked name="payment_against" id="payment_against" class="all"  data-show_table="all_purchase_and_orders_area" value="all"> &nbsp; <b>All</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group mt-1">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="radio" name="payment_against" id="payment_against" class="payment_against"  data-show_table="due_purchase_table_area" value="purchases"> &nbsp; <b>Payment Against Specific Purchase</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group mt-1">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="radio" name="payment_against" id="payment_against" class="payment_against" data-show_table="due_purchase_orders_table_area"  value="purchase_orders"> &nbsp; <b> Payment Against Specific Purchase Orderes</b> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="purchase_and_order_table_area mt-2">
                                <div class="all_purchase_and_orders_area due_table">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="heading_area">
                                                        <p><strong>All </strong></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="due_all_table">
                                                <table class="table modal-table table-sm table-bordered mt-1">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-start">SL</th>
                                                            <th class="text-start">Date</th>
                                                            <th class="text-start">Order/Invoice ID</th>
                                                            <th class="text-start">Status</th>
                                                            <th class="text-start">Due Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($allPurchaseAndOrders as $row)
                                                            <tr>
                                                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                                                <td class="text-start">{{  date('d/m/Y', strtotime($row->date)) }}</td>
                                                                <td class="text-start">{{ $row->invoice_id }}</td>
                                                                <td class="text-start">
                                                                    @if ($row->purchase_status == 1)
                                                                        Purchased
                                                                    @else
                                                                        Order
                                                                    @endif
                                                                </td>
                                                                <td class="text-start">{{ $row->due }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="due_purchase_table_area due_table d-none">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="heading_area">
                                                        <p><strong>Due Purchase Invoice List</strong></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="#" id="close" class="btn btn-sm btn-danger float-end">Cancel</a>
                                                </div>
                                            </div>
                                        
                                            <div class="due_order_table">
                                                <table class="table modal-table table-sm table-bordered mt-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Date</th>
                                                            <th>Invoice ID</th>
                                                            <th>Due Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($purchases as $purchase)
                                                            <tr>
                                                                <td><input type="checkbox" name="purchase_ids[]" value="{{ $purchase->id }}" id="purchase_id" data-due_amount="{{ $purchase->due }}"></td>
                                                                <td>{{ date('d/m/Y', strtotime($purchase->date)) }}</td>
                                                                <td>{{ $purchase->invoice_id }}</td>
                                                                <td>{{ $purchase->due }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="due_purchase_orders_table_area due_table d-none">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="heading_area">
                                                        <p><strong>Due Purchase Order List</strong> </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="#" id="close" class="btn btn-sm btn-danger float-end">Cancel</a>
                                                </div>
                                            </div>
                                        
                                            <div class="due_orders_table">
                                                <table class="table modal-table table-sm table-bordered mt-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Date</th>
                                                            <th>Order ID</th>
                                                            <th>Due Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $order)
                                                            <tr>
                                                                <td><input type="checkbox" name="purchase_ids[]" value="{{ $order->id }}" id="purchase_id" data-due_amount="{{ $order->due }}"></td>
                                                                <td>{{ date('d/m/Y', strtotime($order->date)) }}</td>
                                                                <td>{{ $order->invoice_id }}</td>
                                                                <td>{{ $order->due }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="total_amount_area mt-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Total Amount : </strong> <span id="total_amount">0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-md-8">
                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <strong>Amount :</strong> <span class="text-danger">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark input_i"></i></span>
                                    </div>

                                    <input type="hidden" id="p_available_amount" value="{{ $supplier->total_purchase_due }}">
                                    <input type="number" name="paying_amount" class="form-control p_input" step="any"
                                        data-name="Amount" id="p_paying_amount" value="" autocomplete="off" autofocus/>
                                </div>
                                <span class="error error_p_paying_amount"></span>
                            </div>
        
                            <div class="col-md-4">
                                <strong for="p_date">Date :</strong> <span class="text-danger">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                                    </div>
                                    <input type="text" name="date" class="form-control p_input"
                                        autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                                </div>
                                <span class="error error_p_date"></span>
                            </div>

                            <div class="col-md-4">
                                <label><strong>Reference :</strong> </label>
                                <input type="text" name="reference" class="form-control" step="any" placeholder="Payment Reference" autocomplete="off"/>
                            </div>
                        </div>
        
                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <strong>Credit Account :</strong> 
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark input_i"></i></span>
                                    </div>
                                    <select name="account_id" class="form-control" id="p_account_id">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                @php
                                                    $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                    $bank = $account->bank ? ', BK : '.$account->bank : '';
                                                    $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                                    $balance = ', BL : '.$account->balance;
                                                @endphp
                                                {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error error_p_account_id"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <strong>Payment Method :</strong> <span class="text-danger">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark input_i"></i></span>
                                    </div>
                                    <select required name="payment_method_id" class="form-control" id="p_payment_method_id">
                                        @foreach ($methods as $method)
                                            <option 
                                                data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}" 
                                                value="{{ $method->id }}">
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error error_p_payment_method_id"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> 
                                <input type="file" name="attachment" class="form-control">
                            </div>
        
                            <div class="col-md-8">
                                <strong> Payment Note :</strong>
                                <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="form-group row mt-4">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                        <button name="action" type="submit" value="save" class="c-btn button-success float-end" id="add_supplier_payment">Save</button>
                        <button name="action" value="save_and_print" type="button" class="c-btn button-success float-end" id="add_supplier_payment">Save & Print</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add Supplier payment request by ajax
    $('#payment_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        // var available_amount = $('#p_available_amount').val();
        // var paying_amount = $('#p_paying_amount').val();

        // if (parseFloat(paying_amount)  > parseFloat(available_amount)) {
            
        //     $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
        //     $('.loading_button').hide();
        //     return;
        // }

        var url = $(this).attr('action');
        
        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){

                $('.loading_button').hide();
                $('.error').html('');
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'ERROR');
                }else{

                    $('#paymentModal').modal('hide');
                    toastr.success(data);
                    $('.data_tbl').DataTable().ajax.reload();
                    getSupplier();
                }
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Please Check the connection.'); 
                    return;
                }else if (err.status == 500) {
                    
                    toastr.error('Server Error. Please contact to the support team.'); 
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

    
    $('#p_payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#p_account_id').val(account_id);
        }else if(account_id === ''){

            $('#p_account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#p_payment_method_id').find('option:selected').data('account_id'));

        $(document).on('click', '#payment_against', function() {

        var purchaseIds = document.querySelectorAll('#purchase_id');

        purchaseIds.forEach(function(input){

            $(input).prop('checked', false);
        });

        var show_table = $(this).data('show_table');
        $('.due_table').hide();
        $('.'+show_table).show(300);
        $('#total_amount').html(0.00);
        $('#p_paying_amount').val(parseFloat(0).toFixed(2));
    });

    $(document).on('click', '#purchase_id', function() {

        var purchaseIds = document.querySelectorAll('#purchase_id');

        var total = 0;
        purchaseIds.forEach(function(input){

            if ($(input).is(':CHECKED', true)) {

                total += parseFloat($(input).data('due_amount'));
            }
        });

        $('#total_amount').html(parseFloat(total).toFixed(2));
        $('#p_paying_amount').val(parseFloat(total).toFixed(2));
    });

    $(document).on('click', '#close', function (e) {
        e.preventDefault();

        $('.due_table').hide();
        $('.all_purchase_and_orders_area').show();
        $('.payment_against').prop('checked', false);
        $('.all').prop('checked', true);
        $('#total_amount').html(0.00);
        $('#p_paying_amount').val(0.00);
    });
</script>