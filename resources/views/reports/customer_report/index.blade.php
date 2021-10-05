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
@section('title', 'Customer Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-people-arrows"></span>
                                <h5>Customer Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-8">
                                        <form id="filter_tax_report_form" action="" method="get">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label></label>
                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                                        </div>
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
                                                    <div class="table-responsive" >
                                                        <table class="display data_tbl data__table">
                                                            <thead>
                                                                <tr class="text-start">
                                                                    <th>Customer</th>
                                                                    <th>Opening Balance Due({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                                                    <th>Total Sale({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                                                    <th>Total Paid({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                                                    <th>Total Due({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                                                    <th>Total Return Due({{ json_decode($generalSettings->business, true)['currency']}})</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="bg-secondary">
                                                                    <th class="text-end text-white">Total : {{ json_decode($generalSettings->business, true)['currency']}}</th>
                                                                    <th id="total_op_blc_due" class="text-white">0.00</th>
                                                                    <th id="total_sale" class="text-white">0.00</th>
                                                                    <th id="total_paid" class="text-white">0.00</th>
                                                                    <th id="total_sale_due" class="text-white">0.00</th>
                                                                    <th id="total_return_due" class="text-white">0.00</th>
                                                                </tr>
                                                            </tfoot>
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
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'},
            {extend: 'print',text: 'Print',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [[3, 'asc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.customer.index') }}",
            "data": function(d) {d.customer_id = $('#customer_id').val();}
        },
        columns: [
            { data: 'name', name: 'name'},
            { data: 'opening_balance', name: 'opening_balance', className: 'text-end'},
            { data: 'total_sale', name: 'total_sale', className: 'text-end'},
            { data: 'total_paid', name: 'total_paid', className: 'text-end'},
            { data: 'total_sale_due', name: 'total_sale_due', className: 'text-end'},
            { data: 'total_sale_return_due', name: 'total_sale_return_due', className: 'text-end'},
        ],
        fnDrawCallback: function() {
            var totalSale = sum_table_col($('.data_tbl'), 'total_sale');
            $('#total_sale').text(parseFloat(totalSale).toFixed(2));
            var totalPaid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(parseFloat(totalPaid).toFixed(2));
            var totalOpeningBalance = sum_table_col($('.data_tbl'), 'opening_balance');
            $('#total_op_blc_due').text(parseFloat(totalOpeningBalance).toFixed(2));
            var totalDue = sum_table_col($('.data_tbl'), 'total_purchase_due');
            $('#total_sale_due').text(parseFloat(totalDue).toFixed(2));
            var totalReturnDue = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
            $('#total_return_due').text(parseFloat(totalReturnDue).toFixed(2));
        },
    });

    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
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

    //Print supplier report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        
        var url = "{{ route('reports.customer.print') }}";
        var customer_id = $('#customer_id').val();
        console.log(customer_id);
        $.ajax({
            url:url,
            type:'get',
            data: {customer_id},
            success:function(data){
                $(data).printThis({
                    debug: false,                   
                    importCSS: true,                
                    importStyle: true,          
                    loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
                    removeInline: false, 
                    printDelay: 700, 
                    header: null,        
                });
            }
        }); 
    });
</script>
@endpush
