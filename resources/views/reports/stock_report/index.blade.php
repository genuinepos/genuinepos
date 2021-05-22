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
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Brand :</strong></label>
                                                    <select id="brand_id" name="brand_id" class="form-control common_submitable">
                                                         <option value="">All</option>
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Unit :</strong></label>
                                                    <select id="unit_id" name="unit_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                    </select>
                                                 </div>
            
                                                 <div class="col-md-3">
                                                    <label><strong>Tax :</strong></label>
                                                    <select id="tax_id" name="tax_id" class="form-control common_submitable">
                                                        <option value="">All</option>
                                                    </select>
                                                 </div>
                                            </div>

                                            <hr class="m-0 p-0 mt-1">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label><strong>Only Branch Wise Stock :</strong></label>
                                                    <select id="branch_id" name="branch_id" class="form-control">
                                                        <option value="">All</option>
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

    // Get all parent category
    function setParentCategory(){
        $.ajax({
            url:"{{ route('reports.stock.all.parent.categories') }}",
            async: true,
            type:'get',
            dataType: 'json',
            success:function(categories){
                $.each(categories, function(key, val){
                    $('#category_id').append('<option value="'+val.id+'">'+ val.name +'</option>');
                });
            }
        });
    }
    setParentCategory();

    // Set brnads in brnad form field
    function setBrands(){
        $.ajax({
            url:"{{route('products.add.get.all.form.brand')}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(brands){
                $.each(brands, function(key, val){
                    $('#brand_id').append('<option value="'+val.id+'">'+ val.name +'</option>');
                });
            }
        });
    }
    setBrands();

    // Set all vats in form units field
    function setVats(){
        $.ajax({
            url:"{{route('products.add.get.all.form.taxes')}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(taxes){
                $.each(taxes, function(key, val){
                    $('#tax_id').append('<option value="'+val.id+'">'+ val.tax_name +'</option>');
                });
            }
        });
    }
    setVats();

    // Set all units in form units field
    function setAllUnitsInFormUnitsField(){
        $.ajax({
            url:"{{route('products.add.get.all.form.units')}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(units){
                $.each(units, function(key, val){
                    $('#unit_id').append('<option value="'+val.id+'">'+ val.name +'</option>');
                });
            }
        });
    }
    setAllUnitsInFormUnitsField();

    function setBranches(){
        $.ajax({
            url:"{{route('sales.get.all.branches')}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(branches){
                $.each(branches, function(key, val){
                    var branch_code = ' - '+val.branch_code;
                    $('#branch_id').append('<option value="'+val.id+'">'+val.name+branch_code+'</option>');
                });
            }
        });
    }
    setBranches();

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
