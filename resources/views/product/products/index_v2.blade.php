@extends('layout.master')
@push('stylesheets')
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
                                <span class="fas fa-shopping-cart"></span>
                                <h6>@lang('menu.products')</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-lg-3 p-1">
                        <div class="form_element rounded mt-0 mb-lg-3 mb-1">
                            <div class="element-body">
                                <form action="" method="get" class="p-2">
                                    <div class="form-group row">
                                        @if ($addons->branches == 1)
                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-lg-2 col-md-3">
                                                    <label><b>@lang('menu.business_location') :</b> </label>
                                                    <select class="form-control submit_able select2" name="branch_id" id="branch_id">
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="NULL">
                                                            {{ json_decode($generalSettings->business, true)['shop_name'] . '(HO)' }}
                                                        </option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">
                                                                {{ $branch->name.'/'.$branch->branch_code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.type') :</b></label>
                                            <select name="product_type" id="product_type"
                                                class="form-control submit_able select2" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.single')</option>
                                                <option value="2">@lang('menu.variant')</option>
                                                <option value="3">@lang('menu.combo')</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.category') :</b></label>
                                            <select id="category_id" name="category_id"
                                                class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.unit') :</b></label>
                                            <select id="unit_id" name="unit_id"
                                                class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.tax') :</b></label>
                                            <select id="tax_id" name="tax_id" class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->id }}">{{ $tax->tax_name.' ('.$unit->code_name.')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.status') : </b></label>
                                            <select name="status" id="status" class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">@lang('menu.active')</option>
                                                <option value="0">{{ __('In-Active') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <label><b>@lang('menu.brand'):</b></label>
                                            <select id="brand_id" name="brand_id"
                                                class="form-control submit_able select2">
                                                <option value="">@lang('menu.all')</option>
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
                                    <h6>{{ __('All Product') }}</h6>
                                </div>

                                @if(auth()->user()->can('product_add'))

                                    <div class="col-md-8 d-flex flex-wrap justify-content-end gap-2">
                                        <a href="{{ route('products.add.view') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> @lang('menu.add_product') (Ctrl+Enter)</a>

                                        <a href="" class="btn btn-sm btn-secondary multipla_deactive_btn">@lang('menu.deactivate_selected')</a>

                                        @if (auth()->user()->can('product_delete'))

                                            <a href="" class="btn btn-sm btn-danger multipla_delete_btn">@lang('menu.delete_selected')</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <!--begin: Datatable-->
                                <form id="multiple_action_form" action="{{ route('products.multiple.delete') }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="action" id="action">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6></div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display table-hover data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th data-bSortable="false">
                                                        <input class="all" type="checkbox" name="all_checked"/>
                                                    </th>
                                                    <th>@lang('menu.image')</th>
                                                    <th>@lang('menu.action')</th>
                                                    <th>@lang('menu.product')</th>
                                                    <th>@lang('menu.access_location')</th>
                                                    <th>@lang('menu.purchase_cost')</th>
                                                    <th>@lang('menu.selling_price')</th>
                                                    <th>@lang('menu.current_stock')</th>
                                                    <th>{{ __('Product Type') }}</th>
                                                    <th>@lang('menu.category')</th>
                                                    <th>@lang('menu.brand')</th>
                                                    <th>@lang('menu.tax')</th>
                                                    <th>@lang('menu.status')</th>
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

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
    <!-- Details Modal End-->

    <!-- Opening stock Modal -->
    <div class="modal fade" id="openingStockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Add opening stock') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="opening_stock_view">

                </div>
            </div>
        </div>
    </div>
    <!-- Opening stock Modal-->
@endsection
@push('scripts')
<!--Data table js active link-->
<script>
    $('.loading_button').hide();
    // Filter toggle
    $('.filter_btn').on('click', function(e) {
        e.preventDefault();

        $('.filter_body').toggle(500);
    });

    var product_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('products.all.product') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.type = $('#product_type').val();
                d.category_id = $('#category_id').val();
                d.brand_id = $('#brand_id').val();
                d.unit_id = $('#unit_id').val();
                d.tax_id = $('#tax_id').val();
                d.status = $('#status').val();
                d.is_for_sale = $('#is_for_sale').val();
            }
        },
        columns: [
            {data: 'multiple_delete', name: 'products.name'},
            {data: 'photo', name: 'products.name'},
            {data: 'action', name: 'products.name'},
            {data: 'name', name: 'products.name'},
            {data: 'access_locations', name: 'products.name'},
            {data: 'product_cost_with_tax', name: 'products.product_cost_with_tax'},
            {data: 'product_price', name: 'products.product_price'},
            {data: 'quantity', name: 'products.product_price'},
            {data: 'type', name: 'products.type'},
            {data: 'cate_name', name: 'categories.name'},
            {data: 'brand_name', name: 'brands.name'},
            {data: 'tax_name', name: 'taxes.tax_name'},
            {data: 'status', name: 'products.status'},
        ],
    });

    $(document).ready(function() {

        $(document).on('change', '.submit_able',
        function() {

            product_table.ajax.reload();
        });
    });

    $(document).on('ifChanged', '#is_for_sale', function() {

        product_table.ajax.reload();
    });

    $(document).on('change', '.all', function() {

        if ($(this).is(':CHECKED', true)) {

            $('.data_id').prop('checked', true);
        } else {

            $('.data_id').prop('checked', false);
        }
    });

    $(document).on('click', '.details_button', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $('.data_preloader').show();
        $.get(url, function (data){

            $('#detailsModal').html(data);
            $('.data_preloader').hide();
            $('#detailsModal').modal('show');
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

    $(document).on('click', '#delete',function(e){
        e.preventDefault();

        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);

        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
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

                product_table.ajax.reload();
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
                            type: 'get',
                            success: function(data) {

                                if (!$.isEmptyObject(data.errorMsg)) {
                                    toastr.error(data.errorMsg);
                                    return;
                                }
                                toastr.success(data);
                                product_table.ajax.reload();
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

        // $.ajax({
        //     url: url,
        //     type: 'get',
        //     success: function(data) {

        //         toastr.success(data);
        //         product_table.ajax.reload();
        //     }
        // });
    });

    $(document).on('click', '.multipla_delete_btn',function(e){
        e.preventDefault();

        $('#action').val('multiple_delete');

        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#multiple_action_form').submit();}},
                'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    $(document).on('click', '.multipla_deactive_btn',function(e){
        e.preventDefault();

        $('#action').val('multipla_deactive');

        $.confirm({
            'title': 'Deactive Confirmation',
            'content': 'Are you sure to deactive selected all?',
            'buttons': {
                'Yes': {'class': 'yes btn-danger','action': function() {$('#multiple_action_form').submit();}},
                'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
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

                    product_table.ajax.reload();
                    toastr.success(data, 'Attention');
                }
            }
        });
    });

    // Show opening stock modal with data
    $(document).on('click', '#opening_stock', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#opening_stock_view').html(data);
                $('#openingStockModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    //Update product opening stock request by ajax
    $(document).on('submit', '#update_opening_stock_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                product_table.ajax.reload();
                $('.loading_button').hide();
                $('#openingStockModal').modal('hide');
            }
        });
    });

    // Reduce empty opening stock qty field
    $(document).on('blur', '#quantity', function() {

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Reduce empty opening stock unit cost field
    $(document).on('blur', '#unit_cost_inc_tax', function() {
        if ($(this).val() == '') {
            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    $(document).on('input', '#quantity', function() {

        var qty = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val() ? tr.find('#unit_cost_inc_tax').val() :
            0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_inc_tax);
        tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $(document).on('input', '#unit_cost_inc_tax', function() {

        var unit_cost_inc_tax = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var qty = tr.find('#quantity').val() ? tr.find('#quantity').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_inc_tax);
        tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    // Make print
    $(document).on('click', '.print_btn', function(e) {
        e.preventDefault();
        var body = $('.modal-body').html();
        var header = $('.heading_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
            removeInline: true,
            printDelay: 800,
            header: null,
        });
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.ctrlKey && e.which == 13) {

            // $('#add_btn').click();
            window.location = $('#add_btn').attr('href');
            return false;
        }
    }
</script>
@endpush
