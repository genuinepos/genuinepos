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
                                <span class="fas fa-balance-scale-left"></span>
                                <h5>Trial Balance</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <div class="data_preloader mt-5 pt-5"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
                                        <div class="trial_balance_area">
                                            <div class="print_header d-none">
                                                <div class="text-center pb-3">
                                                    <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                                                    <h6><strong>Trial BALANCE</h6>
                                                </div>
                                            </div>

                                            <table class="table modal-table tables-sm table-striped">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th class="trial_balance text-start text-white">Trial Balance</th>
                                                        <th class="credit text-white">Credit</th>
                                                        <th class="debit text-white">Debit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start"><strong>Supplier Due :</strong> </td>
                                                        <td class="sp">&nbsp;</td>
                                                        <td class="sp">
                                                            <span class="supplier_due">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </td>
                                                    </tr>
                
                                                    <tr>
                                                        <td class="text-start"><strong>Supplier Return Due :</strong> </td>
                                                        <td class="sp">
                                                            <span class="supplier_return_due">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </td>
                                                        <td class="sp">&nbsp;</td>
                                                    </tr>
                
                                                    <tr>
                                                        <td class="text-start"><strong>Customer Due :</strong></td>
                                                        <td class="sp">
                                                            <span class="customer_due">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </td>
                                                        <td class="sp">&nbsp;</td>
                                                    </tr>
                
                                                    <tr>
                                                        <td class="text-start"><strong>Customer Return Due :</strong> </td>
                                                        <td class="sp">&nbsp;</td>
                                                        <td class="sp">
                                                            <span class="customer_return_due">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start"><strong>Asset :</strong> </td>
                                                        <td class="sp">
                                                            <span class="total_physical_asset">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </td>
                                                        <td class="sp">&nbsp;</td>
                                                    </tr>
                
                                                    <tr>
                                                        <td class="text-start"><strong>Account Balance :</strong> </td>
                                                        <td class="sp">&nbsp;</td>
                                                        <td class="sp">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                
                                                <tbody class="account_balance_list">
                                                    <tr>
                                                        <td class="text-start">Payment Account</td>
                                                        <td class="sp"><span class="account_balance">0.00</span></td>
                                                        <td class="sp">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-primary">
                                                        <th class="text-white text-start">Total :</th>
                                                        <th class="text-white">
                                                           <span class="total_credit">0.00</span> 
                                                           {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </th>
                                                        <th class="text-white">
                                                            <span class="total_debit">0.00</span> 
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                
                                            <div class="print_footer d-none">
                                                <div class="text-center">
                                                    <small>Software by <b>SpeedDigit Pvt. Ltd.</b></small>
                                                </div>
                                            </div>
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
            url:"{{route('accounting.trial.balance.amounts')}}",
            success:function(amounts){
                $('.supplier_due').html(parseFloat(amounts.totalSupplierDue).toFixed(2));
                $('.customer_return_due').html(parseFloat(amounts.totalCustomerReturnDue).toFixed(2));
                $('.customer_due').html(parseFloat(amounts.totalCustomerDue).toFixed(2));
                $('.supplier_return_due').html(parseFloat(amounts.totalSupplierReturnDue).toFixed(2));
                $('.total_physical_asset').html(parseFloat(amounts.totalPhysicalAsset).toFixed(2));
                $('.total_credit').html(parseFloat(amounts.totalCredit).toFixed(2));
                $('.total_debit').html(parseFloat(amounts.totalDebit).toFixed(2));
                
                var tr = '';
                $.each(amounts.accounts, function (key, account) {
                    tr += '<tr>';
                    tr += '<td class="text-start">'+account.name+'</td>';
                    tr += '<td class="sp">'+account.balance+" {{ json_decode($generalSettings->business, true)['currency'] }}"+'</td>';
                    tr += '<td class="sp">&nbsp;</td>';
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
        var body = $('.trial_balance_area').html();
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
