@extends('layout.master')
@push('stylesheets')

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
                                <h5>Balance Sheet</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <div class="data_preloader mt-5 pt-5"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
                                        <div class="balance_sheet_area">
                                            <div class="print_header d-none">
                                                <div class="text-center pb-3">
                                                    <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                                    <h6><strong>BALANCE SHEET</h6>
                                                </div>
                                            </div>
                                            <table class="table modal-table table-sm table-bordered table-striped">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th class="liability text-white">Liability</th>
                                                        <th class="assets text-white">Assets</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="aiability_area">
                                                            <table class="table table-sm">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Supplier Due :</strong></td>
                                                                        <td class=" text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="supplier_due"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Customer Return Due :</strong></td>
                                                                        <td class="text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="customer_return_due"></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td class="asset_area">
                                                            <table class="table table-sm">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Customer Due :</strong></td>
                                                                        <td class="text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="customer_due"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Supplier Return Due :</strong></td>
                                                                        <td class="text-end"> 
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="supplier_return_due"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Closing Stock :</strong></td>
                                                                        <td class=" text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="closing_stock"></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start" colspan="2"><strong>Account Balance :</strong></td>
                                                                    </tr>
                                                                    <tr class="account_balance_list_area">
                                                                        <td colspan="2">
                                                                            <table class="table table-sm">
                                                                                <tbody class="account_balance_list">
                                                                                    <tr>
                                                                                        <td class="text-start">Sale Account </td>
                                                                                        <td class="text-end">0.00 </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-primary">
                                                        <td class="total_liability_area"> 
                                                            <table class="table table-sm">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Total Liability :</strong> </td>
                                                                        <td class="text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="total_liability"></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </th>
                                                        <td class="total_asset_area">
                                                            <table class="table table-sm">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start"><strong>Total Asset :</strong></td>
                                                                        <td class="text-end">
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                                                            <span class="total_asset"></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        
                                        <div class="print_btn_area">
                                            <a id="print_btn" href="#" class="btn btn-sm btn-primary float-end"><i class="fas fa-print"></i> Print</a>
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
<script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
<script>
    // Set accounts in payment and payment edit form
    function getBalanceAmounts(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('accounting.balance.sheet.amounts')}}",
            success:function(amounts){
                console.log(amounts);
                $('.supplier_due').html(parseFloat(amounts.totalSupplierDue).toFixed(2));
                $('.customer_return_due').html(parseFloat(amounts.totalCustomerReturnDue).toFixed(2));
                $('.customer_due').html(parseFloat(amounts.totalCustomerDue).toFixed(2));
                $('.supplier_return_due').html(parseFloat(amounts.totalSupplierReturnDue).toFixed(2));
                $('.total_liability').html(parseFloat(amounts.totalLiLiability).toFixed(2));
                $('.closing_stock').html(parseFloat(amounts.closingStock).toFixed(2));
                
                if(amounts.totalAsset >= 0){
                    $('.total_asset').html(parseFloat(amounts.totalAsset).toFixed(2));
                }else{
                    $('.total_asset').html('<b><span class="text-danger">' + parseFloat(amounts.totalAsset).toFixed(2)+'</span><b>');
                }

                var tr = '';
                $.each(amounts.accounts, function (key, account) {
                    tr += '<tr>';
                    tr += '<td class="text-start">'+account.name+'</td>';
                    tr += '<td class="text-end">'+"{{ json_decode($generalSettings->business, true)['currency'] }} "+account.balance+'</td>';
                    tr += '</tr>';
                });

                $('.account_balance_list').empty();
                $('.account_balance_list').html(tr); 
                $('.data_preloader').hide();
            }
        });
    }
    getBalanceAmounts();

    // Print single payment details
    $('#print_btn').on('click', function (e) {
        e.preventDefault(); 
        var body = $('.balance_sheet_area').html();
        var header = $('.print_header').html();
        var footer = $('.print_footer').html();
        $(body).printThis({
            debug: false,                   
            importCSS: true,                
            importStyle: true,          
            loadCSS: "{{asset('public/assets/css/print/balance.sheet.print.css')}}",                      
            removeInline: false, 
            printDelay: 600, 
            header: header,  
            footer: footer
        });
    });
</script>
@endpush
