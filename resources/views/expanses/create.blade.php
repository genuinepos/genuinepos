@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        /* .expense_description_table table tbody tr td {width: 25%;} */
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_expanse_form" action="{{ route('expanses.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Add Expense</h5>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                                        class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Reference No :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Ex Reference No" autofocus>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Date :</b> </label>
                                                    <div class="col-8">
                                                        <input type="date" name="date" class="form-control changeable"
                                                            value="{{ date('Y-m-d') }}" id="date">
                                                    </div>
                                                </div>
                                            </div>
                                     
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Expanse For :</b></label>
                                                    <div class="col-8">
                                                        <select name="admin_id" class="form-control" id="admin_id">
                                                            <option value="">None</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Attachment :</b></label>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control ">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                
                                <div class="form_element m-0">
                                    <div class="heading_area">
                                        <p class="text-primary m-0 p-0 ps-1"><b>Descriptions</b></p>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="expense_description_table">
                                                    <div class="table-responsive">
                                                        <table class="table modal-table table-sm">
                                                            <tbody id="description_body">
                                                                <tr>
                                                                    <td id="index">
                                                                        <b><span class="serial">1</span></b>
                                                                        <input class="index-1" type="hidden" id="index">
                                                                    </td>
                                                                    <td>
                                                                        <select required name="category_ids[]" class="form-control" id="category_id">
                                                                            <option value="">Select Expense Category</option>
                                                                        </select>
                                                                    </td>
        
                                                                    <td>
                                                                        <input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="Amount">
                                                                    </td>
        
                                                                    <td>
                                                                        <div class="btn_30_blue" >
                                                                            <a id="addMore" href=""><i class="fas fa-plus-square"></i></a>
                                                                        </div>
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
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                                    <div class="col-8">
                                                        <input readonly class="form-control add_input" name="total_amount" type="number" data-name="Total amount" id="total_amount" value="0.00" step="any" placeholder="Total amount">
                                                        <span class="error error_total_amount"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Tax :</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax" class="form-control" id="tax">
    
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Net Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</b>  </label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-3">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Paying : ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                                    <div class="col-8">
                                                        <input name="paying_amount" class="form-control" id="paying_amount" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Pay Method :</b></label>
                                                    <div class="col-8">
                                                        <select name="payment_method" class="form-control" id="payment_method">
                                                            <option value="Cash">Cash</option>
                                                            <option value="Advanced">Advanced</option>
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="Card">Card</option>
                                                            <option value="Bank-Transfer">Bank-Transter</option>
                                                            <option value="Other">Other</option>
                                                            <option value="Custom">Custom Field</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Account :</b></label>
                                                    <div class="col-8">
                                                        <select name="account_id" class="form-control" id="account_id">
                                                            <option value="">None</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Total Due :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly name="total_due" type="number" step="any" id="total_due" class="form-control text-danger" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="payment_method d-none" id="Card">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mt-1">
                                                        <label for="inputEmail3" class=" col-4"><b>Card No :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="card_no" id="card_no" placeholder="Card number">
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <div class="input-group mt-1">
                                                        <label for="inputEmail3" class="col-4"><b>Account Holder :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="card_holder_name" id="card_holder_name" placeholder="Card holder name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3 " class="col-4"><b>Transection No</b> :</label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="card_transaction_no" id="card_transaction_no" placeholder="Card transaction no">
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <div class="input-group ">
                                                        <label for="inputEmail3" class=" col-4"><b>Card Type :</b> </label>
                                                        <div class="col-8">
                                                            <select name="card_type" class="form-control"  id="p_card_type">
                                                                <option value="Credit-Card">Credit Card</option>  
                                                                <option value="Debit-Card">Debit Card</option> 
                                                                <option value="Visa">Visa Card</option> 
                                                                <option value="Master-Card">Master Card</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3 mt-1" class=" col-4"><b> Month :</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="month" id="month" placeholder="Month">
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Year :</b></label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="year" id="year" placeholder="Year">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Secure ID :</b></label>
                                                        <div class="col-8">
                                                            <input type="text" class="form-control" name="secure_code" id="secure_code" placeholder="Secure code">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="payment_method d-none" id="Cheque">
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class=" col-2"><b>Cheque Number :</b>  </label>
                                                        <div class="col-10">
                                                            <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="payment_method d-none" id="Bank-Transfer">
                                            <div class="row  mt-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class=" col-2"><b>Account No :</b></label>
                                                        <div class="col-10">
                                                            <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="payment_method d-none" id="Custom">
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <div class="input-group ">
                                                        <label for="inputEmail3" class=" col-2"><b>Transaction No :</b></label>
                                                        <div class="col-10">
                                                            <input type="text" class="form-control " name="transaction_no" id="transaction_no" placeholder="Transaction number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-2"><b>Payment Note :</b></label>
                                                    <div class="col-10">
                                                        <input type="text" name="payment_note" class="form-control form-control-sm" id="payment_note" placeholder="Payment note">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Set accounts in payment and payment edit form
        var ex_categories = '';
        function setExpanseCategory(){
            $.ajax({
                url:"{{route('expanses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
                    ex_categories = categories;
                    $.each(categories, function (key, category) {
                        $('#category_id').append('<option value="'+category.id+'">'+ category.name +' ('+category.code+')'+'</option>');
                    });
                }
            });
        }
        setExpanseCategory();

        // Set accounts in payment and payment edit form
        function setAdmin(){
            $.ajax({
                url:"{{route('expanses.all.admins')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(admins){
                    $.each(admins, function (key, admin) {
                        var role = '';
                        if (admin.role_type == 1) {
                            role = 'Super-Admin';
                        }else if (admin.role_type == 2) {
                            role = 'Admin';
                        }else if (admin.role_type == 3) {
                            role = admin.role.name;
                        }
                        var prefix = admin.prefix != null ? admin.prefix : '';
                        var last_name = admin.last_name != null ? admin.last_name : '';
                        $('#admin_id').append('<option value="'+admin.id+'">'+prefix+' '+admin.name+' '+last_name+' ('+role+')'+'</option>');
                    });
                }
            });
        }
        setAdmin();

        function getTaxes(){
            $.ajax({
                url:"{{route('purchases.get.all.taxes')}}",
                async:false,
                type:'get',
                dataType: 'json',
                success:function(taxes){
                    taxArray = taxes;
                    $('#tax').append('<option value="">NoTax</option>');
                    $.each(taxes, function(key, val){
                        $('#tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                    });
                }
            });
        }
        getTaxes();

        // Set accounts in payment and payment edit form
        function setAccount(){
            $.ajax({
                url:"{{route('accounting.accounts.all.form.account')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(accounts){
                    $.each(accounts, function (key, account) {
                        $('#account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance: '+account.balance+')'+'</option>');
                    });

                    $('#account_id').val({{ auth()->user()->branch ? auth()->user()->branch->default_account_id : '' }});
                }
            });
        }
        setAccount();

         // Calculate amount
         function calculateAmount() {
            var indexs = document.querySelectorAll('#index');
            indexs.forEach(function(index) {
                var className = index.getAttribute("class");
                var rowIndex = $('.' + className).closest('tr').index();
                $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
            });

            var amounts = document.querySelectorAll('#amount');
            totalAmount = 0;
            amounts.forEach(function(amount){
                totalAmount += parseFloat(amount.value ? amount.value : 0);
            });

            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));
            var tax_percent = $('#tax').val() ? $('#tax').val() : 0;
            var tax_amount = parseFloat(totalAmount) / 100 * parseFloat(tax_percent);
            var netTotalAmount = parseFloat(totalAmount) + parseFloat(tax_amount); 
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }

        $(document).on('input', '#amount',function () {
            calculateAmount();
        });

        $('#tax').on('change', function () {
            calculateAmount();
        });

        $('#paying_amount').on('input', function () {
            calculateAmount();
        });

        $(document).on('click', '#remove_btn', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateAmount();
        });

        //Add purchase request by ajax
        $('#add_expanse_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.'); 
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }

                    if(!$.isEmptyObject(data.successMsg)){
                        $('.loading_button').hide();
                        toastr.success(data.successMsg); 
                        window.location = "{{route('expanses.index')}}";
                    }
                }
            });
        });

        var index = 1;
        $(document).on('click', '#addMore', function (e) {
           e.preventDefault();

           var html = '';
           html += '<tr>';
            html += '<td>';
            html += '<b><span class="serial">'+(index + 1)+'</span></b>';
            html += '<input class="index-'+(index + 1)+'" type="hidden" id="index">';
            html += '</td>';
            html += '<td>';
            html += '<select required name="category_ids[]" class="form-control">';
            html += '<option value="">Select Expense Category</option>';
                $.each(ex_categories, function (key, val) {
                    html += '<option value="'+val.id+'">'+val.name+' ('+val.code+')'+'</option>';
                });
            html += '</select>';
            html += '</td>';

            html += '<td>';
            html += '<input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="Amount">';
            html += '</td>';

            html += '<td>';
            html += '<a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash "></span></a>';
            html += '</td>';
            html += '</tr>';
            $('#description_body').append(html);
            calculateAmount();
            index++;
        });

        $('#payment_method').on('change', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });
    </script>
@endpush
