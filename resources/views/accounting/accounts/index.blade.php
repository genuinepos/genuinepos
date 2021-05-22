@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Accounts</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
                                        <form id="filter_account" action="{{ route('accounting.accounts.filter') }}" method="get" class="px-2">
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><b>Status</b> : </label>
                                                    <select name="status" class="form-control submit_able" id="status" autofocus>
                                                        <option value="1"><strong>Active</strong></option>  
                                                        <option value="0">Closed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>All Accounts</h6>
                                    </div>
                                  
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">Name</th>
                                                    <th class="text-start">Account Number</th>
                                                    <th class="text-start">Bank Name</th>
                                                    <th class="text-start">Account Type</th>
                                                    <th class="text-start">Remark</th>
                                                    <th class="text-start">Balance</th>
                                                    <th class="text-start">Created By</th>
                                                    <th class="text-start">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Account</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_account_form" action="{{ route('accounting.accounts.store') }}">
                        <div class="form-group">
                            <label><strong>Name :</strong>  <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm add_input" data-name="Type name" id="name" placeholder="account name"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Account Number : </strong><span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control form-control-sm add_input" data-name="Type name" id="account_number" placeholder="Account number"/>
                            <span class="error error_account_number"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Bank Name :</strong> <span class="text-danger">*</span> </label>
                            <select name="bank_id" class="form-control form-control-sm add_input" data-name="Bank name" id="bank_id">
                                <option value="">Select Bank</option>
                            </select>
                            <span class="error error_bank_id"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Account Type :</strong></label>
                            <select name="account_type_id"  class="form-control form-control-sm" title="Select Type"  id="account_type_id">
                                <option value="">Select Account type</option>  
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Opening Balance :</strong></label>
                            <input type="number" name="opening_balance" class="form-control form-control-sm" data-name="Type name" id="opening_balance" value="0.00" step="any"/>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Remark :</strong></label>
                            <input type="text" name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark Type"/>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Account</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_account_form" action="{{ route('accounting.accounts.update') }}" method="POST">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><strong>Name :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm edit_input" data-name="Type name" id="e_name" placeholder="Account name"/>
                            <span class="error error_e_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Account Number :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control form-control-sm edit_input" data-name="Type name" id="e_account_number" placeholder="Account number"/>
                            <span class="error error_e_account_number"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Bank Name :</strong> <span class="text-danger">*</span> </label>
                            <select name="bank_id" class="form-control form-control-sm edit_input" data-name="Bank name" id="e_bank_id">
                                <option value="">Select Bank</option>    
                            </select>
                            <span class="error error_e_bank_id"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Account Type : </strong></label>
                            <select name="account_type_id"  class="form-control form-control-sm" title="Select Type"  id="e_account_type_id">
                                <option value="">Select Account type</option> 
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Remark :</strong></label>
                            <input type="text" name="remark" id="e_remark" class="form-control form-control-sm" placeholder="Remark Type"/>
                        </div>

                        <div class="form-group text-end py-2">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end">Update</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <!-- Fund transfer Modal -->
    <div class="modal fade" id="fundTransferModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Fund Transfer</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="fund_transfer_form" action="{{ route('accounting.accounts.fund.transfer') }}">
                        <div class="form-group">
                            <label><strong>Selected Account :</strong>  <span class="selected_account"></span></label><br>
                            <label><strong>Balance :</strong>  <span class="balance"></span></label>
                            <input type="hidden" name="sender_account_id" id="sender_account_id">
                        </div>

                        <div class="form-group">
                            <label><strong>Transfer To :</strong> <span class="text-danger">*</span></label>
                            <select name="receiver_account_id" class="form-control form-control-sm ft_input" id="receiver_account_id" data-name="Receiver account">
                                <option value="">Select receiver account</option>  
                            </select>
                            <span class="error error_receiver_account_id"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-sm ft_input" data-name="Amount" id="amount" value="0.00" step="any"/>
                            <span class="error error_amount"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Date :</strong> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark"></i></span>
                                </div>
                                <input type="date" name="date" class="form-control form-control-sm date-picker ft_input" autocomplete="off" id="date" data-name="Date" value="{{ date('Y-m-d') }}">
                            </div>
                            <span class="error error_date"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Note :</strong></label>
                            <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

     <!-- Deposit Modal -->
     <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Deposit</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="deposit_form" action="{{ route('accounting.accounts.fund.deposit') }}">
                        <div class="form-group">
                            <label><strong>Selected Account :</strong>  <span class="selected_account"></span></label><br>
                            <label><strong>Balance :</strong>  <span class="balance"></span></label>
                            <input type="hidden" name="receiver_account_id" id="dp_receiver_account_id">
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-sm dp_input" data-name="Amount" id="dp_amount" value="0.00" step="any"/>
                            <span class="error error_dp_amount"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Deposti Form :</strong> <span class="text-danger">*</span></label>
                            <select name="sender_account_id" class="form-control form-control-sm"  id="dp_sender_account_id">
                                <option value="">Select receiver account</option>  
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Date :</strong> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark"></i></span>
                                </div>
                                <input type="date" name="date" class="form-control form-control-sm date-picker dp_input" autocomplete="off" id="dp_date" data-name="Date" value="{{ date('Y-m-d') }}">
                            </div>
                            <span class="error error_dp_date"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Note :</strong></label>
                            <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Set all banks into modal form
    function setBanks(){
        $.ajax({
            url:"{{route('accounting.accounts.all.banks')}}",
            type:'get',
            dataType: 'json',
            success:function(banks){
                $.each(banks, function(key, val){
                    $('#bank_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.branch_name+')'+'</option>');
                    $('#e_bank_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.branch_name+')'+'</option>');
                });
            }
        });
    }
    setBanks();

    var accountArray = '';
    function setAccount(){
        $.ajax({
            url:"{{route('accounting.accounts.all.form.account')}}",
            type:'get',
            dataType: 'json',
            success:function(accounts){
                accountArray = accounts;
            }
        });
    }
    setAccount();

    // Set account types into modal form
    function setAccoutTypes(){
        $.ajax({
            url:"{{route('accounting.accounts.all.account.types')}}",
            type:'get',
            dataType: 'json',
            success:function(types){
                $.each(types, function(key, val){
                    $('#account_type_id').append('<option value="'+val.id+'">'+ val.name +'</option>');
                    $('#e_account_type_id').append('<option value="'+val.id+'">'+ val.name +'</option>');
                });
            }
        });
    }
    setAccoutTypes();

    // Get all account by ajax
    function getAllAccount(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('accounting.accounts.all.account') }}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllAccount();

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method 
    $(document).ready(function(){
        // Add account by ajax
        $('#add_account_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
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
            console.log(countErrorField);
            if(countErrorField > 0){
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data, 'Succeed');
                    $('#add_account_form')[0].reset();
                    $('.loading_button').hide();
                    getAllAccount();
                    $('#addModal').modal('hide');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var account = $(this).closest('tr').data('info');
            console.log(account);
            $('#id').val(account.id);
            $('#e_name').val(account.name);
            $('#e_account_number').val(account.account_number);
            $('#e_bank_id').val(account.bank_id);
            $('#e_account_type_id').val(account.account_type_id);
            $('#e_remark').val(account.remark);
            $('#editModal').modal('show');
        });

        // edit account type by ajax
        $('#edit_account_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
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
                return;
            }
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    console.log(data);
                    toastr.success(data, 'Succeed');
                    $('.loading_button').hide();
                    getAllAccount();
                    $('#editModal').modal('hide'); 
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) { 
                    $('#deleted_form').submit();
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
        });
        
        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    getAllAccount();
                    toastr.success(data, 'Succeed');
                    $('#deleted_form')[0].reset();
                }
            });
        });

        $(document).on('click', '#change_status',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    getAllAccount();
                    toastr.success(data, 'Succeed');
                }
            });
        });

         //Submit filter form by select input changing
         $(document).on('change', '.submit_able', function () {
            $('#filter_account').submit();
        });

         //Send account filter request
         $('#filter_account').on('submit', function (e) {
           e.preventDefault();
           $('.data_preloader').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            console.log(request);
            $.ajax({
                url:url,
                type:'get',
                data: request,
                success:function(data){
                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            }); 
        });
    });

    // Show fund transfer modal with data
    $(document).on('click', '#fund_transfer',function () {
        $('#fund_transfer_form')[0].reset();
        var sender_account_id = $(this).data('id');
        var sender_account_name = $(this).data('ac_name'); 
        var balance = $(this).data('balance'); 
        $('#sender_account_id').val(sender_account_id);
        $('.selected_account').html(sender_account_name);
        $('.balance').html(balance);
        console.log(accountArray);
        $('#receiver_account_id').empty();
        $('#receiver_account_id').append('<option value="">Select Receiver Account</option>');
        $.each(accountArray, function (key, account) {
            if (sender_account_id != account.id) {
                $('#receiver_account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance:'+account.balance+')'+'</option>');
            }
        });
        $('#fundTransferModal').modal('show');
    });

    // Show deposit modal with data
    $(document).on('click', '#deposit',function () {
        $('#deposit_form')[0].reset();
        var receiver_account_id = $(this).data('id');
        var receiver_account_name = $(this).data('ac_name'); 
        var balance = $(this).data('balance'); 
        $('#dp_receiver_account_id').val(receiver_account_id);
        $('.selected_account').html(receiver_account_name);
        $('.balance').html(balance);
        $('#dp_sender_account_id').empty();
        $('#dp_sender_account_id').append('<option value="">Select Receiver Account</option>');
        $.each(accountArray, function (key, account) {
            if (receiver_account_id != account.id) {
                    $('#dp_sender_account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance: '+account.balance+')'+'</option>');
            }
        });
        $('#depositModal').modal('show');
    });

    $('#fund_transfer_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.ft_input');
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

        console.log(countErrorField);
        if(countErrorField > 0){
            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                getAllAccount();
                setAccount();
                $('.loading_button').hide();
                $('#fund_transfer_form')[0].reset();
                toastr.success(data, 'Succeed');
                $('#fundTransferModal').modal('hide');
            }
        });
    });

    $('#deposit_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.dp_input');
            $('.error').html('');  
            var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                console.log(fieldName);
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        console.log(countErrorField);
        if(countErrorField > 0){
            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                getAllAccount();
                setAccount();
                toastr.success(data, 'Succeed');
                $('#fund_transfer_form')[0].reset();
                $('.loading_button').hide();
                $('#depositModal').modal('hide');
            }
        });
    });
</script>
@endpush
