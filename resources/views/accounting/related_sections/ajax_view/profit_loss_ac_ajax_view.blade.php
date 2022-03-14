<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Cash Flow from operations --}}
                        <tr>
                            <td class="text-start">
                            <em>Total Sale :</em> 
                            </td>

                            <td class="text-start">
                            <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale']) }}</em> 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Purchase Return :</em> 
                            </td>

                            <td class="text-start">
                            <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase_return']) }}</em> 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Total Purchase : </em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Sale Retun : </em> 
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_return']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Direct Expense :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_direct_expense']) }})</em>     
                            </td>
                        </tr>

                        @if ($addons->manufacturing == 1)
                            <tr>
                                <td class="text-start">
                                <em>Total Production Cost :</em>  
                                </td>

                                <td class="text-start">
                                    <em>(0.00)</em>     
                                </td>
                            </tr>
                        @endif
                        
                        <tr>
                            <td class="text-start">
                            <em>Opening Stock :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['opening_stock']) }})</em>     
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Closing Stock :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['closing_stock']) }}</em>     
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">
                                <em>Gross Profit :</em>   
                            </th>

                            <td class="text-start">
                                <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em></b>  
                            </td>
                        </tr>
                    
                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>NET PROFIT LOSS INFORNATION :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>Gross Profit :</em> 
                            </td>
                            <td class="text-start"><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em> </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Stock Adjustment :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Adjustment Recovered :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted_recovered']) }}</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Sale Order Tax :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_order_tax']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Item Sold Individual Tax :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['individual_product_sale_tax']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Indirect Expense :</em>   
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_indirect_expense']) }})</em> 
                            </td>
                        </tr> 
                        
                        <tr>
                            <th class="text-end">
                                <em>Net Profit :</em>
                            </th>

                            <td class="text-start">
                                <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['net_profit']) }}</em> </b>  
                            </td>
                        </tr> 
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>