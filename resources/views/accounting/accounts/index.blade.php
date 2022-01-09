@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Account List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-money-check-alt"></span>
                                <h5>Accounts</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>Account Type :</strong></label>
                                                    <select name="account_type" id="f_account_type" class="form-control">
                                                        <option value="">All</option>
                                                        @foreach (App\Utils\Util::allAccountTypes() as $key => $accountType)
                                                            <option value="{{ $key }}">{{ $accountType }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit"
                                                            class="btn text-white btn-sm btn-secondary float-start"><i
                                                                class="fas fa-funnel-dollar"></i> Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All Accounts</h6>
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                class="fas fa-plus-square"></i> Add</a>
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
                                                <th class="text-start">A/C Name</th>
                                                <th class="text-start">A/C Type</th>
                                                <th class="text-start">A/C Number</th>
                                                <th class="text-start">Bank </th>
                                                <th class="text-start">Business Location </th>
                                                <th class="text-start">Opening Balance</th>
                                                <th class="text-start">Balance</th>
                                                <th class="text-start">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Account</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_account_form" action="{{ route('accounting.accounts.store') }}">
                        <div class="form-group">
                            <label><strong>Name :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control add_input" data-name="Type name" id="name"
                                placeholder="Account Name" />
                            <span class="error error_name"></span>
                        </div>
                        
                        <div class="form-group mt-1">
                            <label><strong>Account Type : <span class="text-danger">*</span></strong></label>
                            <select name="account_type" class="form-control add_input" data-name="Account Type"
                                id="account_type">
                                <option value="">Select Account type</option>
                                @foreach (App\Utils\Util::allAccountTypes() as $key => $accountType)
                                    <option value="{{ $key }}">{{ $accountType }}</option>
                                @endforeach
                            </select>
                            <span class="error error_account_type"></span>
                        </div>

                        <div class="form-group row mt-1 bank_account_field d-none">
                            <div class="col-md-12">
                                <label><strong>Bank Name :</strong> <span class="text-danger">*</span> </label>
                                <select name="bank_id" class="form-control add_input" data-name="Bank name" id="bank_id">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name . ' (' . $bank->branch_name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_bank_id"></span>
                            </div>

                            <div class="col-md-12">
                                <label><strong>Account Number : </strong><span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control add_input"
                                    data-name="Type name" id="account_number" placeholder="Account number" />
                                <span class="error error_account_number"></span>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Opening Balance :</strong></label>
                            <input type="number" name="opening_balance" class="form-control" data-name="Type name"
                                id="opening_balance" value="0.00" step="any" />
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Remarks :</strong></label>
                            <input type="text" name="remark" class="form-control" data-name="Remark"
                                id="remarks"/>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue submit_button float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Account</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_account_form_body"></div>
            </div>
        </div>
    </div>

    {{-- <!-- Fund transfer Modal -->
    <div class="modal fade" id="fundTransferModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Fund Transfer</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="fund_transfer_form" action="{{ route('accounting.accounts.fund.transfer') }}">
                        <div class="form-group">
                            <label><strong>Selected Account :</strong> <span class="selected_account"></span></label><br>
                            <label><strong>Balance :</strong> <span class="balance"></span></label>
                            <input type="hidden" name="sender_account_id" id="sender_account_id">
                        </div>

                        <div class="form-group">
                            <label><strong>Transfer To :</strong> <span class="text-danger">*</span></label>
                            <select name="receiver_account_id" class="form-control form-control-sm ft_input"
                                id="receiver_account_id" data-name="Receiver account">
                                <option value="">Select receiver account</option>
                            </select>
                            <span class="error error_receiver_account_id"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-sm ft_input"
                                data-name="Amount" id="amount" value="0.00" step="any" />
                            <span class="error error_amount"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Date :</strong> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-calendar-week text-dark"></i></span>
                                </div>
                                <input type="text" name="date" class="form-control form-control-sm ft_input"
                                    autocomplete="off" id="date" data-name="Date"
                                    value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                            </div>
                            <span class="error error_date"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Note :</strong></label>
                            <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3"
                                placeholder="Note"></textarea>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end submit_button">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Deposit</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="deposit_form" action="{{ route('accounting.accounts.fund.deposit') }}">
                        <div class="form-group">
                            <label><strong>Selected Account :</strong> <span class="selected_account"></span></label><br>
                            <label><strong>Balance :</strong> <span class="balance"></span></label>
                            <input type="hidden" name="receiver_account_id" id="dp_receiver_account_id">
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-sm dp_input"
                                data-name="Amount" id="dp_amount" value="0.00" step="any" />
                            <span class="error error_dp_amount"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Deposti Form :</strong> <span class="text-danger">*</span></label>
                            <select name="sender_account_id" class="form-control form-control-sm"
                                id="dp_sender_account_id">
                                <option value="">Select receiver account</option>
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Date :</strong> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-calendar-week text-dark"></i></span>
                                </div>
                                <input type="text" name="date" class="form-control form-control-sm dp_input"
                                    autocomplete="off" id="dp_date" data-name="Date"
                                    value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}"
                                    autocomplete="off">
                            </div>
                            <span class="error error_dp_date"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Note :</strong></label>
                            <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3"
                                placeholder="Note"></textarea>
                        </div>

                        <div class="form-group text-right py-2">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn me-0 btn_blue float-end submit_button">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var accounts_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10]}},
            ],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('accounting.accounts.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.account_type = $('#f_account_type').val();
                }
            },
            columnDefs: [{
                "targets": [1, 6],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'name', name: 'accounts.name'},
                {data: 'account_type', name: 'account_type'},
                {data: 'ac_number', name: 'accounts.account_number'},
                {data: 'bank', name: 'banks.name'},
                {data: 'branch', name: 'branches.name'},
                {data: 'opening_balance', name: 'accounts.opening_balance', className: 'text-end'},
                {data: 'balance', name: 'accounts.balance', className: 'text-end'},
                {data: 'action'},
             
            ],fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            accounts_table.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        $(document).ready(function() {
            // Add account by ajax
            $('#add_account_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                $('.submit_button').prop('type', 'button');
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        $('.submit_button').prop('type', 'submit');
                        toastr.success(data);
                        $('#add_account_form')[0].reset();
                        $('.loading_button').hide();
                        accounts_table.ajax.reload();
                        $('#addModal').modal('hide');
                    },
                    error: function(err) {
                        $('.submit_button').prop('type', 'submit');
                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }

                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#edit_account_form_body').html(data);
                        $('#editModal').modal('show');
                        $('.data_preloader').hide();
                    },error:function(err){
                        $('.data_preloader').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.'); 
                        }else{
                            toastr.error('Server Error, Please contact to the support team.'); 
                        }
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}
                        },
                        'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
                    }
                });
            });

            //data delete by ajax
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        accounts_table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

        // // Show fund transfer modal with data
        // $(document).on('click', '#fund_transfer', function() {
        //     $('#fund_transfer_form')[0].reset();
        //     var sender_account_id = $(this).data('id');
        //     var sender_account_name = $(this).data('ac_name');
        //     var balance = $(this).data('balance');
        //     $('#sender_account_id').val(sender_account_id);
        //     $('.selected_account').html(sender_account_name);
        //     $('.balance').html(bdFormat(balance));
        //     console.log(accountArray);
        //     $('#receiver_account_id').empty();
        //     $('#receiver_account_id').append('<option value="">Select Receiver Account</option>');
        //     $.each(accountArray, function(key, account) {
        //         if (sender_account_id != account.id) {
        //             $('#receiver_account_id').append('<option value="' + account.id + '">' + account.name +
        //                 ' (A/C: ' + account.account_number + ')' + ' (Balance:' + account.balance +
        //                 ')' + '</option>');
        //         }
        //     });
        //     $('#fundTransferModal').modal('show');
        // });

        // // Show deposit modal with data
        // $(document).on('click', '#deposit', function() {
        //     $('#deposit_form')[0].reset();
        //     var receiver_account_id = $(this).data('id');
        //     var receiver_account_name = $(this).data('ac_name');
        //     var balance = $(this).data('balance');
        //     $('#dp_receiver_account_id').val(receiver_account_id);
        //     $('.selected_account').html(receiver_account_name);
        //     $('.balance').html(bdFormat(balance));
        //     $('#dp_sender_account_id').empty();
        //     $('#dp_sender_account_id').append('<option value="">Select Receiver Account</option>');
        //     $.each(accountArray, function(key, account) {
        //         if (receiver_account_id != account.id) {
        //             $('#dp_sender_account_id').append('<option value="' + account.id + '">' + account.name +
        //                 ' (A/C: ' + account.account_number + ')' + ' (Balance: ' + account.balance +
        //                 ')' + '</option>');
        //         }
        //     });
        //     $('#depositModal').modal('show');
        // });

        // $('#fund_transfer_form').on('submit', function(e) {
        //     e.preventDefault();
        //     $('.loading_button').show();
        //     $('.submit_button').prop('type', 'button');
        //     var url = $(this).attr('action');
        //     var request = $(this).serialize();
        //     var inputs = $('.ft_input');
        //     $('.error').html('');
        //     var countErrorField = 0;
        //     $.each(inputs, function(key, val) {
        //         var inputId = $(val).attr('id');
        //         var idValue = $('#' + inputId).val();
        //         if (idValue == '') {
        //             countErrorField += 1;
        //             var fieldName = $('#' + inputId).data('name');
        //             $('.error_' + inputId).html(fieldName + ' is required.');
        //         }
        //     });

        //     if (countErrorField > 0) {
        //         $('.loading_button').hide();
        //         $('.submit_button').prop('type', 'submit');
        //         return;
        //     }

        //     $.ajax({
        //         url: url,
        //         type: 'post',
        //         data: request,
        //         success: function(data) {
        //             $('.submit_button').prop('type', 'submit');
        //             getAllAccount();
        //             setAccount();
        //             $('.loading_button').hide();
        //             $('#fund_transfer_form')[0].reset();
        //             toastr.success(data);
        //             $('#fundTransferModal').modal('hide');
        //         },
        //         error: function(err) {
        //             $('.submit_button').prop('type', 'submit');
        //             $('.loading_button').hide();
        //             $('.error').html('');
        //             if (err.status == 0) {
        //                 toastr.error('Net Connetion Error. Reload This Page.');
        //                 return;
        //             }
        //         }
        //     });
        // });

        // $('#deposit_form').on('submit', function(e) {
        //     e.preventDefault();
        //     $('.loading_button').show();
        //     $('.submit_button').prop('type', 'button');
        //     var url = $(this).attr('action');
        //     var request = $(this).serialize();
        //     var inputs = $('.dp_input');
        //     $('.error').html('');
        //     var countErrorField = 0;
        //     $.each(inputs, function(key, val) {
        //         var inputId = $(val).attr('id');
        //         var idValue = $('#' + inputId).val();
        //         if (idValue == '') {
        //             countErrorField += 1;
        //             var fieldName = $('#' + inputId).data('name');
        //             console.log(fieldName);
        //             $('.error_' + inputId).html(fieldName + ' is required.');
        //         }
        //     });

        //     if (countErrorField > 0) {
        //         $('.loading_button').hide();
        //         $('.submit_button').prop('type', 'submit');
        //         return;
        //     }

        //     $.ajax({
        //         url: url,
        //         type: 'post',
        //         data: request,
        //         success: function(data) {
        //             $('.submit_button').prop('type', 'submit');
        //             getAllAccount();
        //             setAccount();
        //             toastr.success(data);
        //             $('#fund_transfer_form')[0].reset();
        //             $('.loading_button').hide();
        //             $('#depositModal').modal('hide');
        //         },
        //         error: function(err) {
        //             $('.submit_button').prop('type', 'submit');
        //             $('.loading_button').hide();
        //             $('.error').html('');
        //             if (err.status == 0) {
        //                 toastr.error('Net Connetion Error. Reload This Page.');
        //                 return;
        //             }
        //         }
        //     });
        // });

        $(document).on('change', '#account_type', function() {
            var account_type = $(this).val();
            if (account_type == 2) {
                $('.bank_account_field').show();
            }else {
                $('.bank_account_field').hide();
            }
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('dp_date'),
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
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('date'),
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
