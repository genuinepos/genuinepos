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
@section('title', 'Supplier Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-users"></span>
                                <h5>Supplier Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_tax_report_form" action="" method="get">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-md-5 offset-md-7">
                                                    <label><strong>Supplier :</strong></label>
                                                    <select name="supplier_id" class="form-control submit_able" id="supplier_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">{{ $supplier->name.' ('.$supplier->phone.')' }}</option>
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
                                                    <div class="table-responsive" >
                                                        <table class="display data_tbl data__table">
                                                            <thead>
                                                                <tr class="text-start">
                                                                    <th>Supplier</th>
                                                                    <th>Total Purchase</th>
                                                                    <th>Total Paid</th>
                                                                    <th>Opening Balance Due</th>
                                                                    <th>Total Due</th>
                                                                    <th>Total Return Due</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-end">Total</th>
                                                                    <th id="total_purchase">0.00</th>
                                                                    <th id="total_paid">0.00</th>
                                                                    <th id="total_op_blc_due">0.00</th>
                                                                    <th id="total_purchase_due">0.00</th>
                                                                    <th id="total_return_due">0.00</th>
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
        "lengthMenu" : [50, 100, 500, 1000, 2000],
        "ajax": {
            "url": "{{ route('reports.supplier.index') }}",
            "data": function(d) {
                d.supplier_id = $('#supplier_id').val();
            }
        },
        columnDefs: [{
            "targets": [0],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'name', name: 'name'},
            { data: 'total_purchase', name: 'total_purchase'},
            { data: 'total_paid', name: 'total_paid'},
            { data: 'opening_balance', name: 'opening_balance'},
            { data: 'total_purchase_due', name: 'total_purchase_due'},
            { data: 'total_purchase_return_due', name: 'total_purchase_return_due'},
        ],
        fnDrawCallback: function() {
            var totalPurchase = sum_table_col($('.data_tbl'), 'total_purchase');
            $('#total_purchase').text("{{ json_decode($generalSettings->business, true)['currency'] }} "+parseFloat(totalPurchase).toFixed(2));
            var totalPaid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text("{{ json_decode($generalSettings->business, true)['currency'] }} "+parseFloat(totalPaid).toFixed(2));
            var totalOpeningBalance = sum_table_col($('.data_tbl'), 'opening_balance');
            $('#total_op_blc_due').text("{{ json_decode($generalSettings->business, true)['currency'] }} "+parseFloat(totalOpeningBalance).toFixed(2));
            var totalDue = sum_table_col($('.data_tbl'), 'total_purchase_due');
            $('#total_purchase_due').text("{{ json_decode($generalSettings->business, true)['currency'] }} "+parseFloat(totalDue).toFixed(2));
            var totalReturnDue = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
            $('#total_return_due').text("{{ json_decode($generalSettings->business, true)['currency'] }} "+parseFloat(totalReturnDue).toFixed(2));
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
</script>
@endpush
