@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('title', 'Account List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-money-check-alt"></span>
                    <h5>{{ __("Accounts") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row">
                                    @if ($generalSettings['addons__branches'] == 1)
                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && !auth()->user()->branch_id)
                                            <div class="col-md-4">
                                                <label><strong>{{ __("Shop/Business") }} </strong></label>
                                                <select name="branch_id" class="form-control select2" id="f_branch_id" autofocus>
                                                    <option value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">
                                                            @php
                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                $branchCode = '-' . $branch->branch_code;
                                                            @endphp
                                                            {{  $branchName.$areaName.$branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="col-md-4">
                                        <label><strong>{{ __("Account Group") }} </strong></label>
                                        <select name="f_account_group_id" id="f_account_group_id" class="form-control select2">
                                            <option value="">{{ __("All") }}</option>
                                            @foreach ($accountGroups as $group)
                                                @php
                                                    $parentGroup = $group?->parentGroup ? '-('.$group?->parentGroup?->name.')' : '';
                                                @endphp
                                                <option value="{{ $group->id }}">{{ $group->name.$parentGroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
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
                        <a href="{{ route('accounts.create') }}" id="addAccountBtn" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
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
                                    <th class="text-start">{{ __("Group") }}</th>
                                    <th class="text-start">{{ __("Name") }}</th>
                                    <th class="text-start">{{ __("A/c Number") }}</th>
                                    <th class="text-start">{{ __('Bank') }}</th>
                                    <th class="text-start">{{ __("Shop/Business") }}</th>
                                    <th class="text-start">{{ __("Opening Balance") }}</th>
                                    <th class="text-start">{{ __("Debit") }}</th>
                                    <th class="text-start">{{ __("Credit") }}</th>
                                    <th class="text-start">{{ __("Closing Balance") }}</th>
                                    <th class="text-start">{{ __("Action") }}</th>
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

    <!--Add/Edit Account modal-->
    <div class="modal fade" id="accountAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account modal End-->
@endsection
@push('scripts')
    <script src="{{asset('backend/asset/js/select2.min.js') }}"></script>

    <script>
        $('.select2').select2();

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
                "url": "{{ route('accounts.index') }}",
                "data": function(d) {
                    d.branch_id = $('#f_branch_id').val();
                    d.account_group_id = $('#f_account_group_id').val();
                }
            },
            columnDefs: [
                {targets:[5,6,7, 8, 9], orderable:false}
            ],
            columns: [
                {data: 'group', name: 'account_groups.name'},
                {data: 'name', name: 'accounts.name'},
                {data: 'ac_number', name: 'accounts.account_number'},
                {data: 'bank', name: 'banks.name'},
                {data: 'branch', name: 'branches.name', className: 'fw-bold'},
                {data: 'opening_balance', className: 'text-end fw-bold'},
                {data: 'debit', className: 'text-end fw-bold'},
                {data: 'credit', className: 'text-end fw-bold'},
                {data: 'balance', className: 'text-end fw-bold'},
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
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // call jquery method
        $(document).ready(function() {
            // Add account by ajax
            $(document).on('click', '#addAccountBtn', function(e) {
                e.preventDefault();
                var group_id = $(this).data('group_id');
                $('#parent_group_id').val(group_id).trigger('change');
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    async: false,
                    success: function(data) {

                        $('#accountAddOrEditModal .modal-dialog').remove();
                        $('#accountAddOrEditModal').html(data);
                        $('#accountAddOrEditModal').modal('show');

                        setTimeout(function() {

                            $('#account_name').focus();
                        }, 500);

                        $('.data_preloader').hide();

                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#editAccount', function(e) {
                e.preventDefault();

                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#accountAddOrEditModal').empty();
                        $('#accountAddOrEditModal').html(data);
                        $('#accountAddOrEditModal').modal('show');

                        $('.data_preloader').hide();

                        setTimeout(function() {

                            $('#account_name').focus().select();
                        }, 500);
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {

                            toastr.error('Server Error. Please contact to the support team.');
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

        // $(document).on('change', '#account_type', function() {
        //     var account_type = $(this).val();
        //     if (account_type == 2) {
        //         $('.bank_account_field').show();
        //     }else {
        //         $('.bank_account_field').hide();
        //     }
        // });

        // $('#add').on('click', function() {
        //     setTimeout(function () {
        //         $('#name').focus();
        //     }, 500);
        // });

        // document.onkeyup = function () {
        //     var e = e || window.event; // for IE to cover IEs window event-object
        //     //console.log(e);
        //     if(e.ctrlKey && e.which == 13) {
        //         $('#addModal').modal('show');
        //         setTimeout(function () {
        //             $('#name').focus();
        //         }, 500);
        //         //return false;
        //     }
        // }
    </script>
@endpush
