@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Supplier List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>@lang('menu.supplier')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">

            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3">
                            <form id="filter_form" class="p-2">
                                <div class="form-group row">
                                    <div class="col-xl-2 col-lg-3 col-md-4">
                                        <label><strong>@lang('menu.business_location') </strong></label>
                                        <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</option>
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
            @endif

            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>All Supplier</h6>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end gap-2">
                        <a href="{{ route('contacts.create', App\Enums\ContactType::Supplier->value) }}" id="addContact" class="btn btn-sm btn-primary"> <i class="fas fa-plus-square"></i> @lang('menu.add')
                        </a>

                        <a href="{{ route('contacts.suppliers.import.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> @lang('menu.import_suppliers')</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6>
                            <i class="fas fa-spinner"></i> @lang('menu.processing')...
                        </h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="text-start">
                                    <th>@lang('menu.action')</th>
                                    <th>@lang('menu.supplier_id')</th>
                                    <th>@lang('menu.prefix')</th>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.business')</th>
                                    <th>@lang('menu.phone')</th>
                                    <th>@lang('menu.opening_balance')</th>
                                    <th>@lang('menu.total_purchase')</th>
                                    <th>@lang('menu.total_paid')</th>
                                    <th>@lang('menu.purchase_due')</th>
                                    <th>@lang('menu.total_return')</th>
                                    <th>@lang('menu.return_due')</th>
                                    <th>@lang('menu.status')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="6" class="text-white text-end">@lang('menu.total') : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                    <th id="opening_balance" class="text-white text-end"></th>
                                    <th id="total_purchase" class="text-white text-end"></th>
                                    <th id="total_paid" class="text-white text-end"></th>
                                    <th id="total_purchase_due" class="text-white text-end"></th>
                                    <th id="total_return" class="text-white text-end"></th>
                                    <th id="total_purchase_return_due" class="text-white text-end"></th>
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
    </div>

    <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_supplier')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Edit Modal End-->
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
            ajax: "{{ route('contacts.supplier.index') }}",
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('contacts.supplier.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
            columnDefs: [{
                "targets": [0, 12],
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
                    data: 'prefix',
                    name: 'prefix'
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
                    data: 'opening_balance',
                    name: 'opening_balance',
                    className: 'text-end'
                },
                {
                    data: 'total_purchase',
                    name: 'total_purchase',
                    className: 'text-end'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid',
                    className: 'text-end'
                },
                {
                    data: 'total_purchase_due',
                    name: 'total_purchase_due',
                    className: 'text-end'
                },
                {
                    data: 'total_return',
                    name: 'total_return',
                    className: 'text-end'
                },
                {
                    data: 'total_purchase_return_due',
                    name: 'total_purchase_return_due',
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
                var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
                $('#total_purchase').text(bdFormat(total_purchase));
                var total_purchase_due = sum_table_col($('.data_tbl'), 'total_purchase_due');
                $('#total_purchase_due').text(bdFormat(total_purchase_due));
                var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(total_paid));
                var total_return = sum_table_col($('.data_tbl'), 'total_return');
                $('#total_return').text(bdFormat(total_return));
                var total_purchase_return_due = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
                $('#total_purchase_return_due').text(bdFormat(total_purchase_return_due));
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
            // Add Supplier by ajax
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
                            toastr.error(data.errorMsg);
                            return;
                        }

                        contactTable.ajax.reload();
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
                    'title': 'Changes Status',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'Yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'GET',
                                    success: function(data) {

                                        if (!$.isEmptyObject(data.errorMsg)) {
                                            toastr.error(data.errorMsg);
                                            return;
                                        }
                                        toastr.success(data);
                                        table.ajax.reload();
                                    }
                                });
                            }
                        },
                        'No': {
                            'class': 'no btn-modal-primary',
                            'action': function() {
                                // console.log('Confirmation canceled.');
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
