<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>ASSET :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>Fixed Asset :</em> 
                            </td>
                            <td class="text-end"><b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['fixed_asset_balance']) }}</em></b>  </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>PURCHASE :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Total Purchase :</em>   
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase']) }}</em> </b>
                            </td>
                        </tr> 

                        <tr>
                            <td class="text-start">
                                <em>Total Paid :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_paid']) }}</em> </b>  
                            </td>
                        </tr> 

                        <tr>
                            <td class="text-start">
                                <em>Total Purchase Due :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_due']) }}</em> </b>  
                            </td>
                        </tr> 

                        <tr>
                            <td class="text-start">
                                <em>Total Purchase Return :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_return']) }}</em> </b>  
                            </td>
                        </tr> 

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>SALES :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Sale:</em>  
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale']) }}</em></b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Payment Received :</em>  
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_paid']) }}</em></b>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Sale Due :</em>  
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_due']) }}</em></b>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Sale Return :</em>  
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_return']) }}</em> </b>   
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>EXPENSES :</strong>
                            </th>
                        </tr> 

                        <tr>
                            <td class="text-start">
                                <em>Total Direct Expense :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_direct_expense']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Indirect Expense :</em>
                            </td>
                            
                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_indirect_expense']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>PRODUCTS :</strong>
                            </th>
                        </tr>

                        @if (!$from_date)
                            <tr>
                                <td class="text-start">
                                    <em>Closing Stock (<small>Non-filterable by Date</small>) :</em>
                                </td>

                                <td class="text-end">
                                    <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['closing_stock']) }}</em> </b>  
                                </td>
                            </tr>
                        @endif
                        
                        <tr>
                            <td class="text-start">
                                <em>Total Stock Adjustment :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Stock Adjustment Recovered Amount :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted_recovered']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>PROFIT LOSS :</strong>
                            </th>
                        </tr>

                        {{-- <tr>
                            <td class="text-start">
                                <em>Total Daily Profit :</em>
                            </td>

                            <td class="text-start">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr> --}}

                        <tr>
                            <td class="text-start">
                                <em>Daily Profit :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['daily_profit']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Gross Profit :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['gross_profit']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Net Profit :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['net_profit']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>ACCOUNT BALANCE :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Cash-In-Hand Balance :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['cash_in_hand']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Bank A/C Balance :</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['bank_account']) }}</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>LOAN & LIABILITIES :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Loan Liabilities :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Loan & Liabilities Due Paid :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Payable Loan & Liabilities Due :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <strong>LOAN & ADVANCE :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Loan & Advance :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Total Loan & Advance Due Received :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Receivable Loan & Advance Due :</em>
                            </td>

                            <td class="text-end">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr>

                  
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>