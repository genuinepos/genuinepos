@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('title', 'Account List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-money-check-alt"></span>
                    <h5>@lang('menu.account')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row">
                                    @if ($addons->branches == 1)
                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                            <div class="col-md-2">
                                                <label><strong>@lang('menu.business_location') :</strong></label>
                                                <select name="branch_id"
                                                    class="form-control submit_able" id="f_branch_id" autofocus>
                                                    <option SELECTED value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</option>
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
                                        <label><strong>@lang('menu.account_types') :</strong></label>
                                        <select name="account_type" id="f_account_type" class="form-control">
                                            <option value="">@lang('menu.all')</option>
                                            @foreach (App\Utils\Util::allAccountTypes(1) as $key => $accountType)
                                                <option value="{{ $key }}">{{ $accountType }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>@lang('menu.all_accounts')</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal" id="add" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.add') (Ctrl+Enter)</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">A/C @lang('menu.type')</th>
                                    <th class="text-start">A/C @lang('menu.name')</th>
                                    <th class="text-start">A/C @lang('menu.number')</th>
                                    <th class="text-start">@lang('menu.bank')</th>
                                    <th class="text-start">@lang('menu.business_location')</th>
                                    <th class="text-start">@lang('menu.opening_balance')</th>
                                    <th class="text-start">@lang('menu.balance')</th>
                                    <th class="text-start">@lang('menu.action')</th>
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

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_account')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_account_form" action="{{ route('accounting.accounts.store') }}">
                        <div class="form-group">
                            <label><strong>@lang('menu.name') :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control add_input" data-name="Name" id="name"
                                placeholder="@lang('menu.account_name')" autocomplete="off" autofocus/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.account_types') : <span class="text-danger">*</span></strong></label>
                            <select name="account_type" class="form-control add_input" data-name="Account Type"
                                id="account_type">
                                <option value="">@lang('menu.select_account_type')</option>
                                @foreach (App\Utils\Util::allAccountTypes(0) as $key => $accountType)
                                    <option value="{{ $key }}">{{ $accountType }}</option>
                                @endforeach
                            </select>
                            <span class="error error_account_type"></span>
                        </div>

                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                            <div class="form-group row mt-1 bank_account_field d-hide">
                                <div class="col-md-12">
                                    <label><strong>@lang('menu.bank_name') :</strong> <span class="text-danger">*</span> </label>
                                    <select name="bank_id" class="form-control add_input" data-name="Bank name" id="bank_id">
                                        <option value="">@lang('menu.select_bank')</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name . ' (' . $bank->branch_name . ')' }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_bank_id"></span>
                                </div>

                                <div class="col-md-12">
                                    <label><strong>@lang('menu.account_number') : </strong><span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control add_input" data-name="Type name" id="account_number" placeholder="@lang('menu.account_number')" />
                                    <span class="error error_account_number"></span>
                                </div>

                                <div class="col-md-12">
                                    <label><strong>@lang('menu.access_business_location') :</strong> <span class="text-danger">*</span></label>
                                    <select name="business_location[]" id="business_location" class="form-control select2" multiple="multiple">
                                        <option {{ $addons->branches == 0 ? 'SELECTED' : '' }} value="NULL">
                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}(HO)
                                        </option>

                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_business_location"></span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.opening_balance') :</strong></label>
                            <input type="number" name="opening_balance" class="form-control" id="opening_balance" value="0.00" step="any"/>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.remarks') :</strong></label>
                            <input type="text" name="remark" class="form-control" id="remarks" placeholder="@lang('menu.remarks')"/>
                        </div>

                        <div class="form-group d-flex justify-content-end py-2">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_account')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_account_form_body"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('backend/asset/js/select2.min.js') }}"></script>

    <script>
        $('.select2').select2({
            placeholder: "Select a access business location",
            allowClear: true
        });

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
                    d.branch_id = $('#f_branch_id').val();
                    d.account_type = $('#f_account_type').val();
                }
            },
            columnDefs: [{
                "targets": [0, 6],
                "orderable": false,
                "searchable": false
            }],

            columns: [
                {data: 'account_type', name: 'account_type'},
                {data: 'name', name: 'accounts.name'},
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
                        $(".select2").select2().val('').trigger('change');
                        $('#addModal').modal('hide');
                        $('#name').focus();
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
                    'title': 'Confirmation',
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

        $(document).on('change', '#account_type', function() {
            var account_type = $(this).val();
            if (account_type == 2) {
                $('.bank_account_field').show();
            }else {
                $('.bank_account_field').hide();
            }
        });

        $('#add').on('click', function() {
            setTimeout(function () {
                $('#name').focus();
            }, 500);
        });

        document.onkeyup = function () {
            var e = e || window.event; // for IE to cover IEs window event-object
            //console.log(e);
            if(e.ctrlKey && e.which == 13) {
                $('#addModal').modal('show');
                setTimeout(function () {
                    $('#name').focus();
                }, 500);
                //return false;
            }
        }
    </script>
@endpush
