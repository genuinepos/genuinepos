@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Account List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Accounts') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row">
                                    {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && !auth()->user()->branch_id) --}}
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }} </strong></label>
                                            <select name="branch_id" class="form-control select2" id="f_branch_id" autofocus>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="branch_id" id="f_branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}">
                                    @endif

                                    <div class="col-md-4">
                                        <label><strong>{{ __('Account Group') }} </strong></label>
                                        <select name="f_account_group_id" id="f_account_group_id" class="form-control select2">
                                            <option value="">{{ __('All') }}</option>
                                            @foreach ($accountGroups as $group)
                                                @php
                                                    $parentGroup = $group?->parentGroup ? '-(' . $group?->parentGroup?->name . ')' : '';
                                                @endphp
                                                <option value="{{ $group->id }}">{{ $group->name . $parentGroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
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
                        <h6>{{ __('List of Accounts') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('accounts.create', \App\Enums\AccountCreateAndEditType::Others->value) }}" id="addAccountBtn" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Add Account') }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Group') }}</th>
                                    <th class="text-start">{{ __('Name') }}</th>
                                    <th class="text-start">{{ __('A/c Number') }}</th>
                                    <th class="text-start">{{ __('Bank') }}</th>
                                    <th class="text-start">{{ location_label() }}</th>
                                    <th class="text-start">{{ __('Opening Balance') }}</th>
                                    <th class="text-start">{{ __('Debit') }}</th>
                                    <th class="text-start">{{ __('Credit') }}</th>
                                    <th class="text-start">{{ __('Closing Balance') }}</th>
                                    <th class="text-start">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="5" class="text-white text-end">{{ __('Current Total') }} :</th>
                                    <th id="total_opening_balance" class="text-white">0.00 Cr.</th>
                                    <th id="total_debit" class="text-white">0.00</th>
                                    <th id="total_credit" class="text-white">0.00</th>
                                    <th id="total_closing_balance" class="text-white text-start">0.00 Cr.</th>
                                    <th class="text-white text-start">---</th>
                                </tr>
                            </tfoot>
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
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>

    <script>
        $('.select2').select2();

        var accounts_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('accounts.index') }}",
                "data": function(d) {
                    d.branch_id = $('#f_branch_id').val();
                    d.account_group_id = $('#f_account_group_id').val();
                }
            },
            columns: [{
                    data: 'group',
                    name: 'account_groups.name'
                },
                {
                    data: 'name',
                    name: 'accounts.name'
                },
                {
                    data: 'ac_number',
                    name: 'accounts.account_number'
                },
                {
                    data: 'bank',
                    name: 'banks.name'
                },
                {
                    data: 'branch',
                    name: 'branches.name',
                    className: 'fw-bold'
                },
                {
                    data: 'opening_balance',
                    name: 'accounts.opening_balance',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'debit',
                    name: 'accounts.account_number',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'credit',
                    name: 'accounts.account_number',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'closing_balance',
                    name: 'accounts.account_number',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'action',
                    name: 'accounts.account_number'
                },
            ],
            fnDrawCallback: function() {

                var dr_opening_balance = sum_table_col($('.data_tbl'), 'dr_opening_balance');
                var cr_opening_balance = sum_table_col($('.data_tbl'), 'cr_opening_balance');

                var totalOpeningBalance = 0;
                var totalOpeningBalanceSide = 'Dr.';
                if (dr_opening_balance > cr_opening_balance) {

                    totalOpeningBalance = dr_opening_balance - cr_opening_balance;
                    totalOpeningBalanceSide = 'Dr.';
                } else if (cr_opening_balance > dr_opening_balance) {

                    totalOpeningBalance = cr_opening_balance - dr_opening_balance;
                    totalOpeningBalanceSide = 'Cr.';
                }

                $('#total_opening_balance').html(bdFormat(totalOpeningBalance) + ' ' + totalOpeningBalanceSide);

                var total_debit = sum_table_col($('.data_tbl'), 'debit');
                $('#total_debit').html(bdFormat(total_debit));
                var total_credit = sum_table_col($('.data_tbl'), 'credit');
                $('#total_credit').html(bdFormat(total_credit));

                var dr_closing_balance = sum_table_col($('.data_tbl'), 'dr_closing_balance');
                var cr_closing_balance = sum_table_col($('.data_tbl'), 'cr_closing_balance');

                var totalClosingBalance = 0;
                var totalClosingBalanceSide = 'Dr.';
                if (dr_closing_balance > cr_closing_balance) {

                    totalClosingBalance = dr_closing_balance - cr_closing_balance;
                    totalClosingBalanceSide = 'Dr.';
                } else if (cr_closing_balance > dr_closing_balance) {

                    totalClosingBalance = cr_closing_balance - dr_closing_balance;
                    totalClosingBalanceSide = 'Cr.';
                }

                $('#total_closing_balance').html(bdFormat(totalClosingBalance) + ' ' + totalClosingBalanceSide);

                $('.data_preloader').hide();
            }
        });

        function sum_table_col(table, class_name) {

            var sum = 0;

            table.find('tbody').find('tr').each(function() {

                if (parseFloat($(this).find('.' + class_name).data('value'))) {

                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });

            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
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

                            toastr.error("{{ __('Net Connection Error.') }}");
                        } else {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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

                            toastr.error("{{ __('Net Connection Error.') }}");
                        } else {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-modal-primary',
                            'action': function() {
                                console.log('Deleted canceled.');
                            }
                        }
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

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        accounts_table.ajax.reload(null, false);
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connection Error.') }}");
                        } else {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        }
                    }
                });
            });
        });
    </script>
@endpush
