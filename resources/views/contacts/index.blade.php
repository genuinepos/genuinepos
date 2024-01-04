@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', \App\Enams\ContactType::tryFrom($type)->name . ' List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>
                        @if ($type == \App\Enams\ContactType::Customer->value)
                            {{ __('Customers') }}
                        @else
                            {{ __('Suppliers') }}
                        @endif
                    </h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">

            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="element-body">
                                <form id="filter_form" class="p-2">
                                    <div class="form-group row">
                                        <div class="col-xl-2 col-lg-3 col-md-4">
                                            <label><strong>@lang('menu.business_location') </strong></label>
                                            <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="NULL">{{ $generalSettings['business__business_name'] }} (@lang('menu.head_office'))</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-lg-3 col-md-4">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                            </div>
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
                        <h6>{{ __('All Customer') }}</h6>
                    </div>

                    <div class="col-md-8 d-flex flex-wrap justify-content-md-end justify-content-center gap-2">
                        <a href="{{ route('contacts.create', App\Enums\ContactType::Customer->value) }}" id="addContact" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-square"></i> @lang('menu.add')
                        </a>
                        <a href="{{ route('contacts.customers.import.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.import_customers')</a>
                        <a href="#" class="print_report btn btn-sm btn-primary"><i class="fas fa-print"></i>@lang('menu.print')</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="text-start">
                                    <th>@lang('menu.action')</th>
                                    <th>@lang('menu.customer_id')</th>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.business')</th>
                                    <th>@lang('menu.phone')</th>
                                    <th>@lang('menu.group')</th>
                                    <th>@lang('menu.credit_limit')</th>
                                    <th>@lang('menu.opening_balance')</th>
                                    <th>@lang('menu.total_sale')</th>
                                    <th>@lang('menu.total_paid')</th>
                                    <th>{{ __('Sale Due') }}</th>
                                    <th>@lang('menu.total_return')</th>
                                    <th>@lang('menu.return_due')</th>
                                    <th>@lang('menu.status')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="7" class="text-white text-end">@lang('menu.total') : ({{ $generalSettings['business__currency_symbol'] }})</th>
                                    <th id="opening_balance" class="text-white text-end"></th>
                                    <th id="total_sale" class="text-white text-end"></th>
                                    <th id="total_paid" class="text-white text-end"></th>
                                    <th id="total_sale_due" class="text-white text-end"></th>
                                    <th id="total_return" class="text-white text-end"></th>
                                    <th id="total_sale_return_due" class="text-white text-end"></th>
                                    <th id="total_sale_return_due" class="text-white text-start">---</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>

    <!-- Money Receipt list Modal-->
    <div class="modal fade" id="moneyReceiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_receipt_voucher')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="receipt_voucher_list_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Money Receipt list Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="MoneyReciptModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.generate_money_receipt')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="money_receipt_modal"></div>
            </div>
        </div>
    </div>
    <!--add money receipt Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="changeReciptStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
    <!--add money receipt Modal End-->
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var table = $('.data_tbl').DataTable({
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
                "url": "{{ route('contacts.customer.index') }}",
                "data": function(d) {

                    d.branch_id = $('#branch_id').val();
                }
            },
            columnDefs: [{
                "targets": [0, 7],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'contact_id',
                    name: 'contact_id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'business_name',
                    name: 'business_name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'group_name',
                    name: 'customer_groups.group_name'
                },
                {
                    data: 'credit_limit',
                    name: 'credit_limit'
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance',
                    className: 'text-end'
                },
                {
                    data: 'total_sale',
                    name: 'total_sale',
                    className: 'text-end'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid',
                    className: 'text-end'
                },
                {
                    data: 'total_sale_due',
                    name: 'total_sale_due',
                    className: 'text-end'
                },
                {
                    data: 'total_return',
                    name: 'total_return',
                    className: 'text-end'
                },
                {
                    data: 'total_sale_return_due',
                    name: 'total_sale_return_due',
                    className: 'text-end'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],
            fnDrawCallback: function() {

                var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#opening_balance').text(bdFormat(opening_balance));
                var total_sale = sum_table_col($('.data_tbl'), 'total_sale');
                $('#total_sale').text(bdFormat(total_sale));
                var total_sale_due = sum_table_col($('.data_tbl'), 'total_sale_due');
                $('#total_sale_due').text(bdFormat(total_sale_due));
                var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(total_paid));
                var total_return = sum_table_col($('.data_tbl'), 'total_return');
                $('#total_return').text(bdFormat(total_return));
                var total_sale_return_due = sum_table_col($('.data_tbl'), 'total_sale_return_due');
                $('#total_sale_return_due').text(bdFormat(total_sale_return_due));

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
            table.ajax.reload();
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

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                            return;
                        }
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.get(url, function(data) {

                    $('#edit-modal-form-body').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();
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
                    async: false,
                    data: request,
                    success: function(data) {
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg, 'Attention');
                            return;
                        }
                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
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
                                    type: 'get',
                                    success: function(data) {
                                        toastr.success(data);
                                        table.ajax.reload();
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

            $(document).on('click', '#generate_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#money_receipt_modal').html(data);
                        $('#MoneyReciptModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#money_receipt_list', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#receipt_voucher_list_modal_body').html(data);
                        $('#moneyReceiptListModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#money_receipt_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        toastr.success('Successfully money receipt voucher is generated.');
                        $('#MoneyReciptModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        $('.loading_button').hide();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            header: null,
                        });
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {

                    $('#money_receipt_modal').html(data);
                    $('#MoneyReciptModal').modal('show');
                });
            });

            $(document).on('click', '#print_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'html',
                    success: function(data) {

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            header: null,
                        });
                        $('.print_area').remove();
                        return;
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_receipt_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.receipt_preloader').show();

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#changeReciptStatusModal').html(data);
                        $('#changeReciptStatusModal').modal('show');
                        $('.receipt_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#change_voucher_status_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.vcs_input');
                $('.error').html('');

                var countErrorField = 0;

                $.each(inputs, function(key, val) {

                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();

                    if (idValue == '') {

                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_vcs_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {

                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        toastr.success(data);
                        $('#changeReciptStatusModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        table.ajax.reload();
                    }
                });
            });

            $(document).on('click', '#delete_receipt', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var tr = $(this).closest('tr');

                $('#receipt_deleted_form').attr('action', url);

                $.confirm({
                    'title': 'Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {

                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#receipt_deleted_form').submit();
                                tr.remove();
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
            $(document).on('submit', '#receipt_deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {

                        toastr.error(data);
                        $('#receipt_deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('change', '#is_header_less', function() {

                if ($(this).is(':CHECKED', true)) {

                    $('.gap-from-top-add').show();
                } else {

                    $('.gap-from-top-add').hide();
                }
            });

            // Print single payment details
            $('#print_payment').on('click', function(e) {
                e.preventDefault();
                var body = $('.sale_payment_print_area').html();
                var header = $('.header_area').html();
                var footer = $('.signature_area').html();
                $(body).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                    removeInline: true,
                    printDelay: 500,
                    header: header,
                    footer: footer
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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('date_of_birth'),
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
            format: 'YYYY-MM-DD',
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
