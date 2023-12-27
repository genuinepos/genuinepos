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
                                <h6>{{ __("Expired Products") }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-lg-1 p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-4">
                                                        <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="NULL">{{ $generalSettings['business__business_name'] }}({{ __("Business") }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                        $branchCode = '-' . $branch->branch_code;
                                                                    @endphp
                                                                    {{  $branchName.$areaName.$branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-4">
                                                    <label><strong>{{ __("Supplier") }}</strong></label>
                                                    <select name="supplier_account_id" class="form-control select2" id="supplier_account_id" autofocus>
                                                        <option value="">{{ __("All") }}</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option data-supplier_account_name="{{ $supplierAccount->name.'/'.$supplierAccount->phone }}" value="{{ $supplierAccount->id }}">{{ $supplierAccount->name.'/'.$supplierAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                            <i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}
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
                                <div class="col-md-4">
                                    <h6>{{ __('List Of Expired Products') }}</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                                </div>
                                <div class="table-responsive" id="data_list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr>
                                                {{-- <th data-bSortable="false">
                                                    <input class="all" type="checkbox" name="all_checked"/>
                                                </th> --}}
                                                <th>{{ __("Product") }}</th>
                                                <th>{{ __("Purchase Invoice ID") }}</th>
                                                <th>{{ __("Supplier") }}</th>
                                                <th>{{ __("Unit Cost Inc. Tax") }}</th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
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

    var expiredProductsTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary'},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('expired.products.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.supplier_account_id = $('#supplier_account_id').val();
            }
        },
        columns: [
            // {data: 'multiple_check', name: 'products.name'},
            {data: 'name', name: 'products.name'},
            {data: 'invoice_id', name: 'purchases.invoice_id', className: 'fw-bold'},
            {data: 'supplier_name', name: 'suppliers.name'},
            {data: 'net_unit_cost', name: 'purchase_products.net_unit_cost', className: 'fw-bold'},
            {data: 'batch_number', name: 'purchase_products.batch_number'},
            {data: 'expire_date', name: 'purchase_products.expire_date', className: 'fw-bold text-danger'},
        ],fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

     //Submit filter form by select input changing
     $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();

        $('.data_preloader').show();
        expiredProductsTable.ajax.reload();
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
            },error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                }else if (err.status == 500) {

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
            loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
            removeInline: false,
            printDelay: 500,
            header: null,
        });
    });

    // $(document).on('change', '.all', function() {

    //     if ($(this).is(':CHECKED', true)) {

    //         $('.data_id').prop('checked', true);
    //     } else {

    //         $('.data_id').prop('checked', false);
    //     }
    // });
</script>
@endpush
