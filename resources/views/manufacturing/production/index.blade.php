@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/backend/asset/css/select2.min.css"/>
@endpush
@section('title', 'All Process - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        <li>
                                            <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire"></i> <b>Process</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('manufacturing.productions.index') }}" class="text-white"><i class="fas fa-shapes text-primary"></i> <b>Production</b></a>
                                        </li>
                                     
                                        <li>
                                            <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h"></i> <b>Settings</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-file-alt"></i> <b>Manufacturing Report</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-name">
                                <div class="col-md-12">
                                    <form action="" method="get" class="px-2">
                                        <div class="form-group row">
                                            @if ($addons->branches == 1)
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-2">
                                                        <label><strong>Business Location :</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control submit_able" id="branch_id" autofocus>
                                                            <option value="">All</option>
                                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="col-md-2">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <label><strong>Warehouse :</strong></label>
                                                    <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                        <option value="">Select Business Location First</option>
                                                    </select>
                                                @else 
                                                    @php
                                                        $wh = DB::table('warehouses')
                                                        ->where('branch_id', auth()->user()->branch_id)
                                                        ->get(['id', 'warehouse_name', 'warehouse_code']);
                                                    @endphp

                                                    <label><strong>Warehouse :</strong></label>
                                                    <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($wh as $row)
                                                            <option value="{{ $row->id }}">{{ $row->warehouse_name.'/'.$row->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <label><strong>From Date :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_i"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="from_date" id="datepicker"
                                                        class="form-control from_date"
                                                        autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong>To Date :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_i"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong></strong></label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-start">
                                                        <i class="fas fa-funnel-dollar"></i> Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6"><h6>Productions</h6></div>

                                    @if (auth()->user()->permission->manufacturing['menuf_add'] == '1') 
                                        <div class="col-md-6">
                                            <div class="btn_30_blue float-end">
                                                <a href="{{ route('manufacturing.productions.create') }}"><i class="fas fa-plus-square"></i> Add</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <form id="update_product_cost_form" action="">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-black">Actions</th>
                                                        <th class="text-black">Date</th>
                                                        <th class="text-black">Voucher No</th>
                                                        <th class="text-black">Business Location</th>
                                                        <th class="text-black">Product</th>
                                                        <th class="text-black">Status</th>
                                                        <th class="text-black">Per Unit Cost(Inc.Tax)</th>
                                                        <th class="text-black">Selling Price(Exc.Tax)</th>
                                                        <th class="text-black">Final Qty</th>
                                                        <th class="text-black">Total Ingredient Cost</th>
                                                        <th class="text-black">Production Cost</th>
                                                        <th class="text-black">Total Cost</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="8" class="text-white text-end">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                        <th id="total_final_quantity" class="text-white text-end"></th>
                                                        <th id="total_ingredient_cost" class="text-white text-end"></th>
                                                        <th id="production_cost" class="text-white text-end"></th>
                                                        <th id="total_cost" class="text-white text-end"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>
                                </div>
    
                                @if (auth()->user()->permission->manufacturing['menuf_delete'] == '1')
                                    <form id="deleted_form" action="" method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
   <script>
        var production_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10]}},
            ],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('manufacturing.productions.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0, 7],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'from', name: 'branches.name'},
                {data: 'product', name: 'product.name'},
                {data: 'status', name: 'productions.is_final'},
                {data: 'unit_cost_inc_tax', name: 'unit_cost_inc_tax', className: 'text-end'},
                {data: 'price_exc_tax', name: 'price_exc_tax', className: 'text-end'},
                {data: 'total_final_quantity', name: 'total_final_quantity', className: 'text-end'},
                {data: 'total_ingredient_cost', name: 'total_ingredient_cost', className: 'text-end'},
                {data: 'production_cost', name: 'production_cost', className: 'text-end'},
                {data: 'total_cost', name: 'total_cost', className: 'text-end'},
            ],fnDrawCallback: function() {
                var total_final_quantity = sum_table_col($('.data_tbl'), 'total_final_quantity');
                $('#total_final_quantity').text(bdFormat(total_final_quantity));
                var total_ingredient_cost = sum_table_col($('.data_tbl'), 'total_ingredient_cost');
                $('#total_ingredient_cost').text(bdFormat(total_ingredient_cost));
                var production_cost = sum_table_col($('.data_tbl'), 'production_cost');
                $('#production_cost').text(bdFormat(production_cost));
                var total_cost = sum_table_col($('.data_tbl'), 'total_cost');
                $('#total_cost').text(bdFormat(total_cost));
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
   </script>
@endpush