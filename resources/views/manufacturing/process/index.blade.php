@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('title', 'All Process - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-dumpster-fire"></span>
                                <h6>Process</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>Process</h6>
                                </div>

                                @if (auth()->user()->permission->manufacturing['process_add'] == '1')
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> Add</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <form id="update_product_cost_form" action="">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr class="bg-navey-blue">
                                                    <th data-bSortable="false">
                                                        <input class="all" type="checkbox" name="all_checked"/>
                                                    </th>
                                                    <th class="text-black">Actions</th>
                                                    <th class="text-black">Product Name</th>
                                                    <th class="text-black">Category</th>
                                                    <th class="text-black">SubCategory</th>
                                                    <th class="text-black">Wastage</th>
                                                    <th class="text-black">Output Quantity</th>
                                                    <th class="text-black">Total Ingrediant Cost</th>
                                                    <th class="text-black">Production Cost</th>
                                                    <th class="text-black">Total Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            @if (auth()->user()->permission->manufacturing['process_delete'] == '1')
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

    @if (!auth()->user()->can('process_add'))
        <div class="modal fade" id="addModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog double-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Choose Product</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form action="{{ route('manufacturing.process.create') }}" method="GET">
                            <div class="form-group">
                                <label><b>Select Product</b> : <span class="text-danger">*</span></label>
                                <select required name="product_id" class="form-control select2">
                                    @foreach ($products as $product)
                                        @php
                                            $variant_name = $product->variant_name ? $product->variant_name : '';
                                            $product_code = $product->variant_code ? $product->variant_code : $product->product_code;
                                        @endphp
                                        <option value="{{ $product->id.'-'.($product->v_id ? $product->v_id : 'NULL') }}">{{ $product->name.' '.$variant_name.' ('.$product_code.')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button d-none">
                                            <i class="fas fa-spinner"></i><span> Loading...</span>
                                        </button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                                        <button type="submit" class="btn btn-sm btn-success submit_button">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
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
            ajax: "{{ route('manufacturing.process.index') }}",
            columnDefs: [{"targets": [0],"orderable": false,"searchable": false}],
            columns: [
                {data: 'multiple_update',name: 'multiple_update'},
                {data: 'action',name: 'action'},
                {data: 'product',name: 'product'},
                {data: 'cate_name',name: 'cate_name'},
                {data: 'sub_cate_name',name: 'sub_cate_name'},
                {data: 'wastage_percent',name: 'wastage_percent'},
                {data: 'total_output_qty',name: 'total_output_qty'},
                {data: 'total_ingredient_cost',name: 'total_ingredient_cost'},
                {data: 'production_cost',name: 'production_cost'},
                {data: 'total_cost',name: 'total_cost'},
            ],
        });

        //Show process view modal with data
        $(document).on('click', '#view', function (e) {
           e.preventDefault();

           var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#view-modal-content').html(data);
                $('#viewModal').modal('show');
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

        $(document).on('change', '.all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.data_id').prop('checked', true);
            } else {

                $('.data_id').prop('checked', false);
            }
        });
    </script>
@endpush
