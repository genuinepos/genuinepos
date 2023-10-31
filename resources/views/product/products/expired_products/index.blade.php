@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Expired Products List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h6>{{ __("Expired Products") }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-lg-3 p-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-4">
                                    <h6>{{ __('List Of Expired Products') }}</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="table-responsive" id="data_list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th data-bSortable="false">
                                                    <input class="all" type="checkbox" name="all_checked"/>
                                                </th>
                                                <th>{{ __("Action") }}</th>
                                                <th>{{ __("Product") }}</th>
                                                <th>{{ __("Unit Cost Inc. Tax") }}</th>
                                                <th>{{ __("Selling Price Exc. Tax") }}</th>
                                                <th>{{ __("Supplier") }}</th>
                                                <th>{{ __("Purchase Invoice ID") }}</th>
                                                <th>{{ __("Batch No") }}</th>
                                                <th>{{ __("Expired Date") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('products.expired.products') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        },
        columns: [
            {data: 'multiple_check', name: 'products.name'},
            {data: 'action', name: 'products.name'},
            {data: 'name', name: 'products.name'},
            {data: 'product_cost_with_tax', name: 'products.product_cost_with_tax'},
            {data: 'product_price', name: 'products.product_price'},
            {data: 'supplier_name', name: 'suppliers.name'},
            {data: 'p_invoice_id', name: 'purchases.invoice_id'},
            {data: 'batch_number', name: 'purchase_products.batch_number'},
            {data: 'expire_date', name: 'purchase_products.expire_date', className: 'fw-bold text-danger'},
        ],
    });

    $(document).on('change', '.all', function() {

        if ($(this).is(':CHECKED', true)) {

            $('.data_id').prop('checked', true);
        } else {

            $('.data_id').prop('checked', false);
        }
    });
</script>
@endpush
