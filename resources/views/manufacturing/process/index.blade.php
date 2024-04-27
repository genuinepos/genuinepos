@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Process/Bill Of Materials - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Process/Bill Of Materials') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List Of Processes/BOM') }}</h6>
                    </div>

                    @if (auth()->user()->can('process_add'))
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('manufacturing.process.select.product.modal') }}" class="btn btn-sm btn-primary" id="getProcessSelectProductModal"><i class="fas fa-plus-square"></i> {{ __('Add New') }}</a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <form id="update_product_cost_form" action="">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-black">{{ __('Action') }}</th>
                                        <th class="text-black">{{ __('Product Name') }}</th>
                                        <th class="text-black">{{ __('Created From') }}</th>
                                        <th class="text-black">{{ __('Category') }}</th>
                                        <th class="text-black">{{ __('Subcategory') }}</th>
                                        <th class="text-black">{{ __('Output Quantity') }}</th>
                                        <th class="text-black">{{ __('Total Ingredient Cost') }}</th>
                                        <th class="text-black">{{ __('Addl. Production Cost') }}</th>
                                        <th class="text-black">{{ __('Net Cost') }}</th>
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

    @if (auth()->user()->can('process_add'))
        <div class="modal fade" id="processSelectProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        </div>
    @endif

    <div id="details"></div>
@endsection
@push('scripts')
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
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [
            //     [0, 'asc']
            // ],
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            ajax: "{{ route('manufacturing.process.index') }}",
            columns: [{
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'product',
                    name: 'product',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'cate_name',
                    name: 'categories.name'
                },
                {
                    data: 'sub_cate_name',
                    name: 'categories.name'
                },
                {
                    data: 'total_output_qty',
                    name: 'parentBranch.name',
                    className: 'fw-bold'
                },
                {
                    data: 'total_ingredient_cost',
                    name: 'total_ingredient_cost',
                    className: 'fw-bold'
                },
                {
                    data: 'additional_production_cost',
                    name: 'additional_production_cost',
                    className: 'fw-bold'
                },
                {
                    data: 'net_cost',
                    name: 'net_cost',
                    className: 'fw-bold'
                },
            ],
        });

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

        // Make print
        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure to delete?',
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

                    table.ajax.reload(false, null);
                    toastr.error(data);
                }
            });
        });
    </script>
@endpush
