@extends('layout.master')
@push('stylesheets')
    <style>
       
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_expanse_form" action="{{ route('expanses.update', $expenseId) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Edit Expense</h5>
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
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Category:</label>
                                                <div class="col-8">
                                                    <select name="category_id"  class="form-control" id="category_id">
                                                        <option value="">Select Category</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4">Attachment:</label>
                                                <div class="col-8">
                                                    <input type="file" name="attachment" class="form-control ">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Reference :</label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Date:</label>
                                                <div class="col-8">
                                                    <input type="date" name="date" class="form-control changeable"
                                                        value="{{ date('Y-m-d') }}" id="date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Expanse For :</label>
                                                <div class="col-8">
                                                    <select name="admin_id" class="form-control" id="admin_id">
                                                        <option value="">None</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="inputEmail3">Expense Note :</label>
                                                <textarea class="form-control" name="expanse_note" id="expanse_note" cols="10" rows="3" placeholder="Expanse note"></textarea>
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
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4">Tax :</label>
                                                <div class="col-8">
                                                    <select name="tax" class="form-control" id="tax">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Total : {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input class="form-control add_input" name="total_amount" type="number" data-name="Total amount" id="total_amount" value="" step="any" placeholder="Total amount">
                                                    <span class="error error_total_amount"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Net Total : {{ json_decode($generalSettings->business, true)['currency'] }}</label>
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
                </section>


                <div class="submit_button_area py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        // Set accounts in payment and payment edit form
        function setExpanseCategory(){
            $.ajax({
                url:"{{route('expanses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
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
                    $('#tax').append('<option value="0.00">NoTax</option>');
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
            var tax_percent = $('#tax').val() ? $('#tax').val() : 0;
            var total_amount = $('#total_amount').val() ? $('#total_amount').val() : 0;
            var tax_amount = parseFloat(total_amount) / 100 * parseFloat(tax_percent);
            var netTotalAmount = parseFloat(total_amount) + parseFloat(tax_amount); 
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }

        $('#tax').on('change', function () {
            calculateAmount();
        });

        $('#paying_amount').on('input', function () {
            calculateAmount();
        });

        $('#total_amount').on('input', function () {
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

         //Edit expanse request by ajax
         $('#edit_expanse_form').on('submit', function(e){
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
                toastr.error('Please check again all form fields.','Some thing want wrong.'); 
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

         // Get edit able data
        function getEditableExpanse(){
            $.ajax({
                url:"{{route('expanses.editable.expanse',$expenseId)}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(expanse){
                   $('#branch_id').val(expanse.branch_id);
                   $('#category_id').val(expanse.expanse_category_id);
                   $('#admin_id').val(expanse.admin_id);
                   $('#invoice_id').val(expanse.invoice_id);
                   $('#date').val(expanse.date);
                   $('#tax').val(expanse.tax_percent);
                   $('#total_amount').val(expanse.total_amount);
                   $('#net_total_amount').val(expanse.net_total_amount);
                   $('#expanse_note').val(expanse.note);
                }
            });
        }
        getEditableExpanse();

        $('#payment_method').on('change', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });
    </script>
@endpush
