@php
    $totalCashFlow = 0;
@endphp
<style>
    .modal-table tbody tr {
         background: #fff;
    }
</style>
<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Cash Flow from operations --}}
                        @php
                            $oparationTotal = 0;
                        @endphp
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>@lang('menu.cash_flow_from_operations') :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.net_profit_before_tax') :</em> 
                            </td>

                            <td class="text-start">
                               <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['net_profit_before_tax']) }}</em> 
                               @php
                                 $oparationTotal += $netProfitLossAccount['net_profit_before_tax'];  
                               @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.customer_balance') : </em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt($customerReceivable->sum('total_due')) }})</em> 
                                @php
                                    $oparationTotal -= $customerReceivable->sum('total_due');  
                                @endphp   
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.supplier_balance') : </em>  
                            </td>

                            <td class="text-start">
                                 <em>{{ App\Utils\Converter::format_in_bdt($supplierPayable->sum('total_due')) }}</em>
                                @php
                                    $oparationTotal += $supplierPayable->sum('total_due');  
                                @endphp    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.current_stock_value') : </em> 
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['closing_stock']) }})</em>   
                                @php
                                    $oparationTotal -= $netProfitLossAccount['closing_stock'];  
                                @endphp  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.current_asset') :</em>  
                            </td>

                            <td class="text-start">
                                 <em>{{ App\Utils\Converter::format_in_bdt($currentAssets->sum('total_current_asset')) }}</em>   
                                @php
                                    $oparationTotal += $currentAssets->sum('total_current_asset');  
                                @endphp 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.current_liability') :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt($currentLiability->sum('current_liability')) }}</em>  
                                @php
                                    $oparationTotal += $currentLiability->sum('current_liability');  
                                @endphp   
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.tax_payable') :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['tax_payable']) }}</em>     
                                @php
                                    $oparationTotal += $netProfitLossAccount['tax_payable'];  
                                @endphp 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end">
                                <b>
                                    <em>@lang('menu.total_operations') : 
                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                    </em> 
                                </b>  
                            </td>

                            <td class="text-start">
                                <b>{{ $oparationTotal < 0 ? '('. App\Utils\Converter::format_in_bdt($oparationTotal).')' : App\Utils\Converter::format_in_bdt($oparationTotal) }}</b>  
                                @php
                                    $totalCashFlow += $oparationTotal;
                                @endphp
                            </td>
                        </tr>

                        {{-- <tr class="bg-info">
                            <td class="text-start text-white">
                                <b>@lang('menu.total_operations') : </b>  
                            </td>

                            <td class="text-start text-white">
                                <b>{{ $oparationTotal < 0 ? '('. App\Utils\Converter::format_in_bdt($oparationTotal).')' : App\Utils\Converter::format_in_bdt($oparationTotal) }}</b>  
                                @php
                                    $totalCashFlow += $oparationTotal;
                                @endphp
                            </td>
                        </tr> --}}
                    
                        {{-- Cash Flow from investing --}}
                       
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>@lang('menu.cash_flow_from_investing') :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>FIXED ASSET :</em> 
                            </td>
                            <td class="text-start">
                                <em>
                                    ({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})
                                </em> 
                            </td>
                            @php
                                $totalCashFlow -= $fixedAssets->sum('total_fixed_asset');
                            @endphp
                        </tr>

                        {{-- <tr class="bg-info">
                            <td class="text-start text-white">
                                <b><em>Total Investing :</em>  </b>  
                            </td>

                            <td class="text-start text-white">
                                <b><em>({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})</em> </b>  
                            </td>
                        </tr>  --}}

                        <tr>
                            <td class="text-end">
                                <b>
                                    <em>Total Investing : 
                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                    </em>  
                                </b>  
                            </td>

                            <td class="text-start">
                                <b><em>({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})</em> </b>  
                            </td>
                        </tr> 

                        {{-- Cash Flow from financing --}}
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>CASH FLOW FROM FINANCING :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.capital_ac') :</em> 
                            </td>
                            <td class="text-start">0.00</td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Loan And Advance :</em> 
                            </td>
                            <td class="text-start">({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</td>
                        </tr>

                        <tr>
                            <td class="text-end">
                                <b>
                                    <em>Total financing : 
                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                    </em>
                                </b>  
                            </td>

                            <td class="text-start">
                                <b>
                                    <em>({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</em> 
                                </b>  
                                @php
                                    $totalCashFlow -= $loanAndAdvance->sum('current_loan_receivable');
                                @endphp
                            </td>
                        </tr> 

                        {{-- <tr class="bg-info">
                            <td class="text-start text-white">
                                <b><em>Total financing :</em>  </b>  
                            </td>

                            <td class="text-start text-white">
                                <b><em>({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</em> </b>  
                                @php
                                    $totalCashFlow -= $loanAndAdvance->sum('current_loan_receivable');
                                @endphp
                            </td>
                        </tr>  --}}
                    </tbody>
                    
                    <tfoot>
                        <tr class="bg-secondary">
                            <td class="text-end text-white">
                                <b>
                                    <em>Total Cash Flow : ({{ json_decode($generalSettings->business, true)['currency'] }})</em> 
                                </b> 
                            </td>
                            <td class="text-start text-white">
                                <b class="total_cash_flow">
                                    <em>
                                        {{ $totalCashFlow < 0 ? '('.App\Utils\Converter::format_in_bdt($totalCashFlow).')' : App\Utils\Converter::format_in_bdt($totalCashFlow) }}
                                    </em> 
                                </b>
                            </th>    
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </tbody>
</table>