@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Customer List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Customers') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end">
                                        <div class="col-xl-6 col-lg-6 col-md-12">
                                            <label><strong>{{ __('Shop') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="NULL">{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->name;
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-lg-3 col-md-4">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="section-header">
                    <div class="col-md-4">
                        <h6>{{ __('List Of Customers') }}</h6>
                    </div>

                    <div class="col-md-8 d-flex flex-wrap justify-content-md-end justify-content-center gap-2">
                        @if (auth()->user()->can('customer_add'))
                            <a href="{{ route('contacts.create', App\Enums\ContactType::Customer->value) }}" id="addContact" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __('Add Customer') }}</a>
                        @endif

                        @if (auth()->user()->can('customer_import'))
                            <a href="{{ route('contacts.customers.import.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __('Import Customer') }}</a>
                        @endif

                        <a href="#" class="print_report btn btn-sm btn-primary"><i class="fas fa-print"></i> {{ __('Print') }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="text-start">
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    {{-- <th>{{ __("Group") }}</th> --}}
                                    <th>{{ __('Credit Limit') }}</th>
                                    <th>{{ __('Opening Balance') }}</th>
                                    <th>{{ __('Total Sale') }}</th>
                                    <th>{{ __('Total Purchase') }}</th>
                                    <th>{{ __('Total Return') }}</th>
                                    <th>{{ __('Total Received') }}</th>
                                    <th>{{ __('Total Paid') }}</th>
                                    <th>{{ __('Curr. Balance') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="5" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business__currency'] }})</th>
                                    <th id="opening_balance" class="text-white text-end"></th>
                                    <th id="total_sale" class="text-white text-end"></th>
                                    <th id="total_purchase" class="text-white text-end"></th>
                                    <th id="total_return" class="text-white text-end"></th>
                                    <th id="total_received" class="text-white text-end"></th>
                                    <th id="total_paid" class="text-white text-end"></th>
                                    <th id="current_balance" class="text-white text-end"></th>
                                    <th class="text-white text-start">---</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form id="delete_contact_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>

                <form id="delete_money_receipt_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>

    <!-- Money Receipt list Modal-->
    <div class="modal fade" id="moneyReceiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Money Receipt list Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="moneyReciptAddOrEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!--add money receipt Modal End-->
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var contactTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [0, 'asc']
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('contacts.manage.customer.index', \App\Enums\ContactType::Customer->value) }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
            columns: [{
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'contact_id',
                    name: 'contacts.contact_id'
                },
                {
                    data: 'name',
                    name: 'contacts.name'
                },
                {
                    data: 'phone',
                    name: 'contacts.phone'
                },
                // {data: 'group_name', name: 'customer_groups.group_name'},
                {
                    data: 'credit_limit',
                    name: 'contacts.credit_limit'
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_sale',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_purchase',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_return',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_received',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_paid',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'current_balance',
                    name: 'contacts.business_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'status',
                    name: 'status',
                    name: 'contacts.business_name',
                },
            ],
            fnDrawCallback: function() {

                var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#opening_balance').text(bdFormat(opening_balance));

                var total_sale = sum_table_col($('.data_tbl'), 'total_sale');
                $('#total_sale').text(bdFormat(total_sale));

                var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
                $('#total_purchase').text(bdFormat(total_purchase));

                var total_return = sum_table_col($('.data_tbl'), 'total_return');
                $('#total_return').text(bdFormat(total_return));

                var total_received = sum_table_col($('.data_tbl'), 'total_received');
                $('#total_received').text(bdFormat(total_received));

                var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(total_paid));

                var current_balance = sum_table_col($('.data_tbl'), 'current_balance');
                $('#current_balance').text(bdFormat(current_balance));

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
            contactTable.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add category by ajax
            $('#addContact').on('click', function(e) {

                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#addOrEditContactModal').html(data);
                        $('#addOrEditContactModal').modal('show');

                        setTimeout(function() {

                            $('#contact_name').focus();
                        }, 500);

                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
                            return;
                        }
                    }
                });
            });

            $(document).on('click', '#editContact', function(e) {

                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#addOrEditContactModal').html(data);
                        $('#addOrEditContactModal').modal('show');

                        setTimeout(function() {

                            $('#contact_name').focus().select();
                        }, 500);

                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
                            return;
                        }
                    }
                });
            });

            $(document).on('click', '#deleteContact', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#delete_contact_form').attr('action', url);
                $.confirm({
                    'title': 'Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#delete_contact_form').submit();
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
            $(document).on('submit', '#delete_contact_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'Attention');
                            return;
                        }

                        contactTable.ajax.reload();
                        toastr.error(data);
                        $('#delete_contact_form')[0].reset();
                    }
                });
            });

            $(document).on('click', '#money_receipts', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#moneyReceiptListModal').html(data);
                        $('#moneyReceiptListModal').modal('show');
                        $('.data_preloader').hide();
                    },
                    error: function(err) {

                        $('.data_preloader').hide();
                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
                            return;
                        }
                    }
                });
            });

            //data delete by ajax
            $(document).on('submit', '#delete_money_receipt_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.error(data);
                        $('#delete_money_receipt_form')[0].reset();

                        if (deleteAbleMoneryReceiptVoucherTr) {

                            deleteAbleMoneryReceiptVoucherTr.remove();
                        }
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                            return;
                        }
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_status', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $.confirm({
                    'title': 'Changes Status Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'post',
                                    success: function(data) {
                                        toastr.success(data);
                                        contactTable.ajax.reload();
                                    }
                                });
                            }
                        },

                        'No': {
                            'class': 'no btn-modal-primary',
                            'action': function() {
                                console.log('Confirmation canceled.');
                            }
                        }
                    }
                });
            });
        });

        //Print supplier report
        $(document).on('click', '.print_report', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = "{{ route('reports.customer.print') }}";
            var customer_id = $('#customer_id').val();
            console.log(customer_id);
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_id
                },
                success: function(data) {

                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#addModal').modal('show');
                setTimeout(function() {

                    $('#name').focus();
                }, 500);
                //return false;
            }
        }
    </script>
@endpush
