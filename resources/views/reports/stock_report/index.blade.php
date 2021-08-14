@extends('layout.master')
@push('stylesheets')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,td {color: #32325d;}
        .report_data_area {position: relative;}
        .data_preloader{top:2.3%}
        .sale_and_purchase_amount_area table tbody tr th{text-align: left;}
        .sale_and_purchase_amount_area table tbody tr td{text-align: left;}
    </style>
@endpush
@section('title', 'Stock Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Stock Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="stock_filter_form" action="{{ route('reports.stock.filter') }}" method="get">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label><strong>Category :</strong></label>
                                                    <select id="category_id" name="category_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                        @foreach ($categories as $c)
                                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Brand :</strong></label>
                                                    <select id="brand_id" name="brand_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                        @foreach ($brands as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                        @endforeach
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Unit :</strong></label>
                                                    <select id="unit_id" name="unit_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                        @foreach ($units as $u)
                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Tax :</strong></label>
                                                    <select id="tax_id" name="tax_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                        @foreach ($taxes as $t)
                                                            <option value="{{ $t->id }}">{{ $t->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                 </div>
                                            </div>

                                            <hr class="m-0 p-0 mt-1">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label><strong>Only Business Location Wise Stock :</strong></label>
                                                    <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                        <option value="">All</option>
                                                        <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">
                                                                {{ $branch->name . '/' . $branch->branch_code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                 </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="report_data_area">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                <div class="report_data">
                                    <div class="card">
                                        <div class="card-body">
                                            <!--begin: Datatable-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive" id="data_list">
                                                        <table class="display data_tbl data__table">
                                                            <thead>
                                                                <tr class="text-start">
                                                                    <th>P.Code</th>
                                                                    <th>Product</th>
                                                                    <th>Unit Price</th>
                                                                    <th>Current Stock</th>
                                                                    <th>Current Stock Value <b><small>(By Unit Cost)</small></b></th>
                                                                    <th>Current Stock Value <b><small>(By Unit Price)</small></b></th>
                                                                    <th>Potential profit</th>
                                                                    <th>Total Sold</th>
                                                                    <th>Total Transfered</th>
                                                                    <th>Total Adjusted</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
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
    $('.loading_button').hide();
    // Filter toggle
    $('.filter_btn').on('click', function (e) {
        e.preventDefault();
        $('.filter_body').toggle(500);
    });

    // Get all product by ajax
    function getAllProduct(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('reports.stock.all.products') }}",
            type:'get',
            success:function(data){
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllProduct();

    $(document).on('change', '.common_submitable', function () {
        $('#branch_id').val('');
        $('#stock_filter_form').submit();
    });

    $(document).on('change', '#branch_id', function () {
        $('.common_submitable').val('');
        $('#stock_filter_form').submit();
    });

    $('#stock_filter_form').on('submit', function (e) {
       e.preventDefault();
       $('.data_preloader').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'get',
            data: request,
            success:function(data){
                console.log(data);
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        }); 
    });
</script>
@endpush
