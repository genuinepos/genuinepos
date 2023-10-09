@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('title', 'Process - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-dumpster-fire"></span>
                    <h6>{{ __("Process") }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __("List Of Processes") }}</h6>
                    </div>

                    @if (auth()->user()->can('process_add'))
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('manufacturing.process.select.product.modal') }}" class="btn btn-sm btn-primary" id="getProcessSelectProductModal"><i class="fas fa-plus-square"></i> {{ __("Add New") }}</a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <form id="update_product_cost_form" action="">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-black">{{ __("Action") }}</th>
                                        <th class="text-black">{{ __("Product Name") }}</th>
                                        <th class="text-black">{{ __("Created From") }}</th>
                                        <th class="text-black">{{ __("Category") }}</th>
                                        <th class="text-black">{{ __("Subcategory") }}</th>
                                        <th class="text-black">{{ __("Wastage") }}</th>
                                        <th class="text-black">{{ __("Output Quantity") }}</th>
                                        <th class="text-black">{{ __('Total Ingredient Cost') }}</th>
                                        <th class="text-black">{{ __("Production Cost") }}</th>
                                        <th class="text-black">{{ __("Net Cost") }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </form>
                    </div>
                </div>

                @if (auth()->user()->can('process_delete'))
                    <form id="deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->can('process_add'))
        <div class="modal fade" id="processSelectProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true"> 
        </div>
    @endif

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content" id="view-modal-content">

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        // $('.select2').select2();

        // var table = $('.data_tbl').DataTable({
        //     dom: "lBfrtip",
        //     buttons: [
        //         {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
        //         {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
        //         {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
        //     ],
        //     "processing": true,
        //     "serverSide": true,
        //     aaSorting: [[0, 'asc']],
        //     "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        //     "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        //     ajax: "{{ route('manufacturing.process.index') }}",
        //     columns: [
        //         {data: 'action', name: 'action'},
        //         {data: 'product', name: 'product'},
        //         {data: 'branch', name: 'branches.name'},
        //         {data: 'cate_name', name: 'cate_name'},
        //         {data: 'sub_cate_name', name: 'sub_cate_name'},
        //         {data: 'wastage_percent', name: 'wastage_percent'},
        //         {data: 'total_output_qty', name: 'total_output_qty'},
        //         {data: 'total_ingredient_cost', name: 'total_ingredient_cost'},
        //         {data: 'production_cost', name: 'production_cost'},
        //         {data: 'net_cost', name: 'net_cost'},
        //     ],
        // });

        $(document).on('click', '#getProcessSelectProductModal', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#processSelectProductModal').html(data);
                    $('#processSelectProductModal').modal('show');

                    setTimeout(function() {

                        $('#product_id').focus();
                    }, 500);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
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
                'content': 'Are you sure to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        // $(document).on('click', '.print_btn',function (e) {
        //    e.preventDefault();
        //     var body = $('.transfer_print_template').html();
        //     var header = $('.heading_area').html();
        //     $(body).printThis({
        //         debug: false,
        //         importCSS: true,
        //         importStyle: true,
        //         loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
        //         removeInline: false,
        //         printDelay: 1000,
        //         header: null,
        //     });
        // });
    </script>
@endpush
