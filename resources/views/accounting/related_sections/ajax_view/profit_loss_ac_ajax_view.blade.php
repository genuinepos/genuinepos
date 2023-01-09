<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="display table table-sm">
                    <tbody>
                        {{-- Cash Flow from operations --}}
                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.total_sale') :</em> 
                            </td>

                            <td class="text-start">
                            <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale']) }}</em> 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.purchase_return') :</em> 
                            </td>

                            <td class="text-start">
                            <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase_return']) }}</em> 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.total_purchase') : </em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.sale_return') : </em> 
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_return']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.direct_expense') :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_direct_expense']) }})</em>     
                            </td>
                        </tr>

                        @if ($generalSettings['addons__manufacturing'] == 1)
                            <tr>
                                <td class="text-start">
                                <em>@lang('menu.total_production_cost') :</em>  
                                </td>

                                <td class="text-start">
                                    <em>(0.00)</em>     
                                </td>
                            </tr>
                        @endif
                        
                        {{-- <tr>
                            <td class="text-start">
                            <em>@lang('menu.opening_stock') :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['opening_stock']) }})</em>     
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.closing_stock') :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['closing_stock']) }}</em>     
                            </td>
                        </tr> --}}

                        <tr>
                            <th class="text-end">
                                <em>@lang('menu.gross_profit') :</em>   
                            </th>

                            <td class="text-start">
                                <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em></b>  
                            </td>
                        </tr>
                    
                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>@lang('menu.net_profit_loss_information') :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.gross_profit') :</em> 
                            </td>
                            <td class="text-start"><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em> </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_stock_adjustment') :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_adjustment_recovered') :</em>  
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted_recovered']) }}</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale_order_tax') :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_order_tax']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.item_sold_individual_tax') :</em>  
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['individual_product_sale_tax']) }})</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.indirect_expense') :</em>   
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_indirect_expense']) }})</em> 
                            </td>
                        </tr> 
                        
                        <tr>
                            <th class="text-end">
                                <em>@lang('menu.net_profit') :</em>
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