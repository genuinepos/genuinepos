@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Payment List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Payments') }}</h5>
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
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Shop/Business') }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="f_branch_id" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Paid To') }}</strong></label>
                                                    <select name="debit_account_id" class="form-control select2" id="f_debit_account_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($debitAccounts as $debitAccount)
                                                            <option data-credit_account_name="{{ $debitAccount->name . '/' . $debitAccount->phone }}" value="{{ $debitAccount->id }}">{{ $debitAccount->name . '/' . $debitAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="f_from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="f_to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                            <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                        </button>
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
                                <div class="col-10">
                                    <h6>{{ __('List Of Payments') }}</h6>
                                </div>

                                @if (auth()->user()->can('purchase_add'))
                                    <div class="col-2 d-flex justify-content-end">
                                        <a href="{{ route('payments.create') }}" class="btn btn-sm btn-primary" id="addPayment"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Voucher') }}</th>
                                                <th>{{ __('Shop/Business') }}</th>
                                                <th>{{ __('Reference') }}</th>
                                                <th>{{ __('Remarks') }}</th>
                                                <th>{{ __('Paid To') }}</th>
                                                <th>{{ __('Paid From') }}</th>
                                                <th>{{ __('Type/Method') }}</th>
                                                <th>{{ __('Trans. No') }}</th>
                                                <th>{{ __('Cheque No') }}</th>
                                                {{-- <th>{{ __("Cheque S/L No") }}</th> --}}
                                                <th>{{ __('Paid Amount') }}</th>
                                                {{-- <th>{{ __("Created By") }}</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="11" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                                <th id="total_amount" class="text-white"></th>
                                                {{-- <th></th> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="delete_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addOrEditPaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        // Show session message by toster alert.
        var paymentTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('payments.index') }}",
                "data": function(d) {
                    d.branch_id = $('#f_branch_id').val();
                    d.debit_account_id = $('#f_debit_account_id').val();
                    d.from_date = $('#f_from_date').val();
                    d.to_date = $('#f_to_date').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'accountingVoucher.date'
                },
                {
                    data: 'voucher_no',
                    name: 'accountingVoucher.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'accountingVoucher.branch.name'
                },
                {
                    data: 'reference',
                    name: 'accountingVoucher.purchaseRef.invoice_id'
                },
                {
                    data: 'remarks',
                    name: 'accountingVoucher.remarks'
                },
                {
                    data: 'paid_to',
                    name: 'account.name'
                },
                {
                    data: 'paid_from',
                    name: 'accountingVoucher.voucherCreditDescription.account.name'
                },
                {
                    data: 'payment_method',
                    name: 'accountingVoucher.voucherCreditDescription.paymentMethod.name'
                },
                {
                    data: 'transaction_no',
                    name: 'accountingVoucher.voucherCreditDescription.transaction_no'
                },
                {
                    data: 'cheque_no',
                    name: 'accountingVoucher.voucherCreditDescription.cheque_no'
                },
                // {data: 'cheque_serial_no',name: 'accountingVoucher.voucherDebitDescription.cheque_serial_no'},
                {
                    data: 'total_amount',
                    name: 'accountingVoucher.voucherCreditDescription.cheque_serial_no',
                    className: 'text-end fw-bold'
                },
                // {data: 'created_by',name: 'accountingVoucher.createdBy.name'},
            ],
            fnDrawCallback: function() {

                var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
                $('#total_amount').text(bdFormat(total_amount));

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
            paymentTable.ajax.reload();
        });

        $.ajaxSetup({
            cache: false
        });

        $(document).on('click', '#addPayment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                async: false,
                dataType: 'html',
                success: function(data) {

                    // window.history.forward(1);
                    // location.reload(true);
                    $('#addOrEditPaymentModal').empty();
                    $('#addOrEditPaymentModal').html(data);
                    $('#addOrEditPaymentModal').modal('show');

                    setTimeout(function() {

                        $('#payment_date').focus().select();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#editPayment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                async: false,
                dataType: 'html',
                success: function(data) {

                    $('#addOrEditPaymentModal').empty();
                    $('#addOrEditPaymentModal').html(data);
                    $('#addOrEditPaymentModal').modal('show');

                    setTimeout(function() {

                        $('#payment_date').focus().select();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#delete', function(e) {

            e.preventDefault();

            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#delete_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_form', function(e) {
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

                    paymentTable.ajax.reload();
                    toastr.error(data);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('f_from_date'),
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
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('f_to_date'),
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
            format: 'DD-MM-YYYY'
        });

        function getSupplier() {}
    </script>
@endpush
