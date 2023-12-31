@extends('layout.master')
@push('stylesheets')
    <style>
        .element-body {
            padding: 1px 7px 6px 6px;
        }
    </style>
@endpush
@section('title', 'Product List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>{{ __('Products') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-lg-1 p-1">
                        <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                            <div class="element-body">
                                <form action="" method="get">
                                    <div class="form-group row">
                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                            <div class="col-md-2">
                                                <label><strong>{{ __('Shop Acccess') }}</strong></label>
                                                <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                    <option value="">{{ __('All') }}</option>
                                                    {{-- <option value="NULL">{{ $generalSettings['business__business_name'] }}({{ __("Business") }})</option> --}}
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Type') }}</b></label>
                                            <select name="product_type" id="product_type" class="form-control submit_able select2" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="1">{{ __('Single') }}</option>
                                                <option value="2">{{ __('Variant') }}</option>
                                                <option value="3">{{ __('Combo') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Category') }}</b></label>
                                            <select id="category_id" name="category_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Unit') }}</b></label>
                                            <select id="unit_id" name="unit_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name . ' (' . $unit->code_name . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Tax') }}</b></label>
                                            <select id="tax_ac_id" name="tax_ac_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($taxAccounts as $tax)
                                                    <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Status') }}</b></label>
                                            <select name="status" id="status" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0">{{ __('In-Active') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>{{ __('Brand.') }}</b></label>
                                            <select id="brand_id" name="brand_id" class="form-control submit_able select2">
                                                <option value="">{{ __('All') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {{-- <div class="col-md-3">
                                        <p class="mt-4"> <input type="checkbox" name="is_for_sale" class="submit_able me-1" id="is_for_sale" value="1"><b>Not For Selling.</b></p>
                                        </div>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-4">
                                    <h6>{{ __('List Of Products') }}</h6>
                                </div>

                                @if (auth()->user()->can('product_add'))

                                    <div class="col-md-8 d-flex flex-wrap justify-content-end gap-2">
                                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> {{ __('Add Product') }}</a>

                                        @if (auth()->user()->can('product_delete'))
                                            <a href="" class="btn btn-sm btn-danger multipla_delete_btn">{{ __('Delete Selected All') }}</a>
                                        @endif
                                    </div>
                                @endif

                            </div>

                            <div class="widget_content">
                                <!--begin: Datatable-->
                                <form id="multiple_action_form" action="#" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="action" id="action">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display table-hover data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th data-bSortable="false">
                                                        <input class="all" type="checkbox" name="all_checked" />
                                                    </th>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Product') }}</th>
                                                    <th>{{ __('Access Shop/Business') }}</th>
                                                    <th>{{ __('Unit Cost(Inc.Tax)') }}</th>
                                                    <th>{{ __('Unit Price(Exc. Tax)') }}</th>
                                                    <th>{{ __('Curr. Stock') }}</th>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Brand.') }}</th>
                                                    <th>{{ __('Default Tax') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </form>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                <!--end: Datatable-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>

    <!-- Opening stock Modal -->
    <div class="modal fade" id="addOrEditOpeningStock" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    <!-- Opening stock Modal-->
@endsection
@push('scripts')
    <!--Data table js active link-->
    <script>
        $('.loading_button').hide();

        var productTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: { columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: { columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: { columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
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
                "url": "{{ route('products.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.type = $('#product_type').val();
                    d.category_id = $('#category_id').val();
                    d.brand_id = $('#brand_id').val();
                    d.unit_id = $('#unit_id').val();
                    d.tax_ac_id = $('#tax_ac_id').val();
                    d.status = $('#status').val();
                    d.is_for_sale = $('#is_for_sale').val();
                }
            },
            columns: [
                { data: 'multiple_delete', name: 'products.name', orderable: false },
                { data: 'photo', name: 'name' },
                { data: 'action', name: 'name' },
                { data: 'name', name: 'name' },
                { data: 'access_branches', name: 'product_code' },
                { data: 'product_cost_with_tax', name: 'product_cost_with_tax', className: 'fw-bold' },
                { data: 'product_price', name: 'product_price', className: 'fw-bold' },
                { data: 'quantity', name: 'product_price', className: 'fw-bold' },
                { data: 'type', name: 'type' },
                { data: 'cate_name', name: 'categories.name' },
                { data: 'brand_name', name: 'brands.name' },
                { data: 'tax_name', name: 'brands.name' },
                { data: 'status', name: 'products.status' },
            ],
        });

        $(document).ready(function() {

            $(document).on('change', '.submit_able', function() {

                productTable.ajax.reload();
            });
        });

        $(document).on('ifChanged', '#is_for_sale', function() {

            productTable.ajax.reload();
        });

        $(document).on('change', '.all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.data_id').prop('checked', true);
            } else {

                $('.data_id').prop('checked', false);
            }
        });

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

        //Check purchase and generate burcode
        $(document).on('click', '#check_pur_and_gan_bar_button', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        window.location = data;
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
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
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

                    productTable.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            // var url = $(this).attr('href');
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
                                    productTable.ajax.reload();
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

        $(document).on('click', '.multipla_delete_btn', function(e) {
            e.preventDefault();

            $('#action').val('multiple_delete');

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#multiple_action_form').submit();
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

        $(document).on('click', '.multipla_deactive_btn', function(e) {
            e.preventDefault();

            $('#action').val('multipla_deactive');

            $.confirm({
                'title': 'Deactive Confirmation',
                'content': 'Are you sure to deactive selected all?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#multiple_action_form').submit();
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
        $(document).on('submit', '#multiple_action_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'Attention');
                    } else {

                        productTable.ajax.reload();
                        toastr.success(data, 'Attention');
                    }
                }
            });
        });

        $(document).on('click', '#openingStock', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addOrEditOpeningStock').html(data);
                    $('#addOrEditOpeningStock').modal('show');

                    // setTimeout(function() {

                    //     $('#brand_name').focus();
                    // }, 500);
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
    </script>
@endpush
