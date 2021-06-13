@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'Product List - ')
@section('content')
    <div class="content body-woaper">
        <div class="container-fluid">
            <!--begin::Container-->
            <div class="container main__content">
                <!--Filter Card-->
                <h3 style="color: #32325d">Products</h3>
                <div class="card card-custom mb-3">

                    <div class="card-body filter_body">
                        <!--Filter Form Area-->
                        <div class="filter_from_area">
                            <form id="product_filter_form" action="" method="get">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <strong>Type</strong>
                                        <select name="product_type" id="product_type"
                                            class="form-control form-control-sm submit_able" autofocus>
                                            <option value="">All</option>
                                            <option value="1">Single</option>
                                            <option value="2">Variant</option>
                                            <option value="3">Combo</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <strong>Category</strong>
                                        <select id="category_id" name="category_id"
                                            class="form-control form-control-sm submit_able">
                                            <option value="">All</option>
                                            @foreach ($categories as $cate)
                                                <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <strong>Unit</strong>
                                        <select id="unit_id" name="unit_id"
                                            class="form-control form-control-sm submit_able">
                                            <option value="">All</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <strong>Tax</strong>
                                        <select id="tax_id" name="tax_id" class="form-control form-control-sm submit_able">
                                            <option value="">All</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->id }}">{{ $tax->tax_name.' ('.$unit->code_name.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <strong>Brand</strong>
                                        <select id="brand_id" name="brand_id"
                                            class="form-control form-control-sm submit_able">
                                            <option value="">All</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <strong>Status</strong>
                                        <select name="status" id="status" class="form-control form-control-sm submit_able">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>

                                    {{-- <div class="col-md-3">
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input type="checkbox"
                                                name="is_for_sale" class="form-control form-control-sm submit_able"
                                                id="is_for_sale" value="">Not For Selling. </h6>
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                        <!--Filter Form Area End-->
                    </div>
                </div>
                <!--Filter Card End-->
                <!--Table Card-->
                <div class="card card-custom">
                    <div class="section-header">
                        <div class="col-md-6">
                            <h6>All Product</h6>
                        </div>
                        @if (auth()->user()->permission->category['category_add'] == '1')
                            <div class="col-md-6">
                                <div class="btn_30_blue float-end">
                                    <a href="{{ route('products.add.view') }}"><i class="fas fa-plus-square"></i> Add Product</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <!--begin: Datatable-->
                        <form id="multiple_action_form" action="{{ route('products.multiple.delete') }}" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="action" id="action">

                            <div class="data_preloader"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
                            <div class="table-responsive" id="data_list">
                                <table class="display table-hover data_tbl data__table">
                                    <thead>
                                        <tr class="bg-navey-blue">
                                            <th data-bSortable="false">
                                                <input class="all" type="checkbox" name="all_checked" />
                                            </th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                            <th>Product</th>
                                            <th>Purchase Cost</th>
                                            <th>Selling Price</th>
                                            <th>Current Stock</th>
                                            <th>Product Type</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Tax</th>
                                            <th>Expire Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="13">
                                                @if (auth()->user()->permission->product['product_delete'])
                                                    <a href="" class="btn btn-sm btn-danger multipla_delete_btn">Delete
                                                        Selected</a>
                                                @endif
                                                <a href="" class="btn btn-sm btn-warning multipla_deactive_btn text-white">Deactivate
                                                    Selected</a>
                                            </th>
                                        </tr>
                                    </tfoot>
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
                <!--end::Card-->
            </div>
            <!--end::Container-->
            <!--end::Entry-->
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        </div>
        <!-- Details Modal End-->

        <!-- Opening stock Modal -->
        <div class="modal fade" id="openingStockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog five-col-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Add opening stock</h6>
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
        <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.min.js"></script>
        <!--Data table js active link-->

        <script>
            $('.loading_button').hide();
            // Filter toggle
            $('.filter_btn').on('click', function(e) {
                e.preventDefault();
                $('.filter_body').toggle(500);
            });

            product_table = $('.data_tbl').DataTable({
                dom: "lBfrtip",
                buttons: [ 
                    {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                    {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                    {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                ],
                "processing": true,
                "serverSide": true,
                aaSorting: [
                    [0, 'asc']
                ],
                "ajax": {
                    "url": "{{ route('products.all.product') }}",
                    "data": function(d) {
                        d.type = $('#product_type').val();
                        d.category_id = $('#category_id').val();
                        d.brand_id = $('#brand_id').val();
                        d.unit_id = $('#unit_id').val();
                        d.tax_id = $('#tax_id').val();
                        d.status = $('#status').val();
                        d.is_for_sale = $('#is_for_sale').val();
                    }
                },
                columnDefs: [{
                    "targets": [0, 1, 2],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    {data: 'multiple_delete',},
                    {data: 'photo',name: 'photo'},
                    {data: 'action',name: 'action'},
                    {data: 'name',name: 'name'},
                    {data: 'product_cost_with_tax',name: 'product_cost_with_tax'},
                    {data: 'product_price',name: 'product_price'},
                    {data: 'quantity',name: 'quantity'},
                    {data: 'type',name: 'type'},
                    {data: 'category',name: 'category'},
                    {data: 'brand_name',name: 'brand_name'},
                    {data: 'tax_name',name: 'tax_name'},
                    {data: 'expire_date',name: 'expire_date'},
                    {data: 'status',name: 'status'},
                ],
            });

            $(document).ready(function() {
                $(document).on('change', '.submit_able',
                    function() {
                        // $('#product_filter_form').submit();
                        console.log('Submit');
                        product_table.ajax.reload();
                    });
            });

            $(document).on('ifChanged', '#is_for_sale', function() {
                product_table.ajax.reload();
            });

            $(document).on('change', '.all', function() {
                console.log('OK');
                if ($(this).is(':CHECKED', true)) {
                    $('.data_id').prop('checked', true);
                } else {
                    $('.data_id').prop('checked', false);
                }
            });

            function productDetails(url) {
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#detailsModal').html(data);
                        $('.data_preloader').hide();
                        $('#detailsModal').modal('show');
                    }
                });
            }

            $(document).on('click', '.details_button', function(e) {
                e.preventDefault();
                var url = $(this).closest('tr').data('href');
                productDetails(url);
            });

            // Show details modal with data by clicking the row
            $(document).on('click', 'tr.clickable_row td:not(:first-child, :nth-child(3))', function(e) {
                e.preventDefault();
                var url = $(this).parent().data('href');
                console.log(url);
                productDetails(url);
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
                            console.log(data);
                        }
                    }
                });
            });

            // Show sweet alert for delete
            // $(document).on('click', '#delete', function(e) {
            //     e.preventDefault();
            //     var url = $(this).attr('href');
            //     $('#deleted_form').attr('action', url);
            //     swal({
            //             title: "Are you sure to delete ?",
            //             icon: "warning",
            //             buttons: true,
            //             dangerMode: true,
            //         })
            //         .then((willDelete) => {
            //             if (willDelete) {
            //                 $('#deleted_form').submit();
            //             }
            //         });
            // });

            $(document).on('click', '#delete',function(e){
                e.preventDefault(); 
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);       
                $.confirm({
                    'title': 'Delete Confirmation',
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
                                // alert('Deleted canceled.')
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
                        product_table.ajax.reload();
                        toastr.error(data);
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                console.log(url);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);
                        product_table.ajax.reload();
                    }
                });
            });

            // Show sweet alert for multiple delete
            // $(document).on('click', '.multipla_delete_btn', function(e) {
            //     e.preventDefault();
            //     $('#action').val('multiple_delete');
            //     swal({
            //             title: "Are you sure to delete selected?",
            //             icon: "warning",
            //             buttons: true,
            //             dangerMode: true,
            //         }).then((willDelete) => {
            //             if (willDelete) {
            //                 $('#multiple_action_form').submit();
            //             }
            //         });
            // });

            $(document).on('click', '.multipla_delete_btn',function(e){
                e.preventDefault(); 
                $('#action').val('multiple_delete');    
                $.confirm({
                    'title': 'Delete Confirmation',
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
                                // alert('Deleted canceled.')
                            } 
                        }
                    }
                });
            });

            // Show sweet alert for multiple deactive
            // $(document).on('click', '.multipla_deactive_btn', function(e) {
            //     e.preventDefault();
            //     $('#action').val('multipla_deactive');
            //     swal({
            //             title: "Are you sure to deactive selected all?",
            //             icon: "warning",
            //             buttons: true,
            //             dangerMode: true,
            //         })
            //         .then((willDelete) => {
            //             if (willDelete) {
            //                 $('#multiple_action_form').submit();
            //             } 
            //         });
            // });

            $(document).on('click', '.multipla_deactive_btn',function(e){
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
                                // alert('Deleted canceled.')
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
            $(document).on('blur', '#unit_cost_exc_tax', function() {
                if ($(this).val() == '') {
                    $(this).val(parseFloat(0).toFixed(2));
                }
            });

            $(document).on('input', '#quantity', function() {
                var qty = $(this).val() ? $(this).val() : 0;
                var tr = $(this).closest('tr');
                var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val() ? tr.find('#unit_cost_exc_tax').val() :
                    0;
                var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            });

            $(document).on('input', '#unit_cost_exc_tax', function() {
                var unit_cost_exc_tax = $(this).val() ? $(this).val() : 0;
                var tr = $(this).closest('tr');
                var qty = tr.find('#quantity').val() ? tr.find('#quantity').val() : 0;
                var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
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
                    loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",
                    removeInline: true,
                    printDelay: 800,
                    header: null,
                });
            });

        </script>
    @endpush
