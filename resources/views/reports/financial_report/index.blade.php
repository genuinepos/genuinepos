@extends('layout.master')
@push('stylesheets')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,td {color: #32325d;}
        .sale_purchase_and_profit_area {position: relative;}
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
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-money-bill-wave"></span>
                                <h5>Financial Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label></label>
                                    <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                                </div>
                            </div>
                            <div class="sale_purchase_and_profit_area">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                <div id="data_list">
                                    <div class="sale_and_purchase_amount_area">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">  
                                                        <div class="card-title">
                                                            <h6>Account Summery</h6>
                                                        </div>
                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="text-start"> Total Opening Balance </th>
                                                                
                                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($accounts->sum('total_op_balance'), 0, 2) }}</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Current Balance : </th>
                                                                    @if ($accounts->sum('total_balance') >= 0)
                                                                        <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($accounts->sum('total_balance'), 0, 2) }}</td>
                                                                    @else 
                                                                        <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($accounts->sum('total_balance'), 0, 2).'</span>' !!}</td>
                                                                    @endif
                                                                </tr>
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

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Assets</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Current Asset Value</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($assets->sum('total_asset_value'), 0, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Purchase</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Total Purchase</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_purchase'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Supplier Payment</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_paid'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Supplier Due </th>
                                                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($suppliers->sum('total_purchase_due'), 0, 2).'</span>' !!}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Supplier Return</th>
                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($suppliers->sum('total_return'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Supplier Return Due</th>
                                                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($suppliers->sum('total_return_due'), 0, 2).'</span>' !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Sale</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Total Sale</th>
                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_sale'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Customer Payment</th>
                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_paid'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Customer Due </th>
                                                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($sales->sum('total_due'), 0, 2).'</span>' !!}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Customer Return</th>
                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($sales->sum('total_return'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Customer Return Due</th>
                                                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($sales->sum('total_return_due'), 0, 2).'</span>' !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Product</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Current Stock Value</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalStockValue, 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Adjustment :</th>
                                                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($adjustments->sum('total_adjust_amount'), 0, 2).'</span>' !!}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Adjustment Recovered:</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($adjustments->sum('total_recovered'), 0, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Expense</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Total Expense :</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($expenses->sum('total_amount'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Paid :</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($expenses->sum('total_paid'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Due :</th>
                                                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($expenses->sum('total_due'), 0, 2).'</span>' !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="card-title">
                                            <h6>Loan</h6>
                                        </div>
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Total Pay Loan :</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_pay_loan'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Pay Loan Paid :</th>
                                                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_pay_loan_paid'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Pay Loan payment Due :</th>
                                                    <td class="text-start"> {!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($loans->sum('total_pay_loan_due'), 0, 2).'</span>' !!}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Receive Loan :</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_receive_loan'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Receive Loan Paid :</th>
                                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($loans->sum('total_receive_loan_paid'), 0, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-start">Total Receive Loan payment Due :</th>
                                                    <td class="text-start">{!! json_decode($generalSettings->business, true)['currency'].' <span class="text-danger">'.bcadd($loans->sum('total_receive_loan_due'), 0, 2).'</span>' !!}</td>
                                                </tr>
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
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#print_report', function (e) {
            e.preventDefault();
            var url = "{{ route('reports.financial.print') }}";
            $.ajax({
                url:url,
                type:'get',
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
