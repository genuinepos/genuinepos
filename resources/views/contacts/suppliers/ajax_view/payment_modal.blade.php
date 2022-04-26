<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="modal-dialog col-60-modal" role="document">
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
                    <strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> 
                        <input type="file" name="attachment" class="form-control">
                    </div>
                </div>

                <div class="form-group mt-2">
                <strong> Payment Note :</strong>
                    <textarea name="note" class="form-control" id="note" cols="30" rows="3"
                        placeholder="Note"></textarea>
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
</script>