<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Cash Flow from operations --}}
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>CASH FLOW FROM OPERATIONS :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Net Profit Before Tax :</em> 
                            </td>

                            <td class="text-start">
                               <em>0.00</em> 
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Customer Balance : </em>  
                            </td>

                            <td class="text-start">
                                 <em>- 0.00</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Current Stock Value : </em> 
                            </td>

                            <td class="text-start">
                                <em>0.00</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Current Asset :</em>  
                            </td>

                            <td class="text-start">
                                 <em>0.00</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Current Liability :</em>  
                            </td>

                            <td class="text-start">
                                <em>0.00</em>    
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>Tax Payable :</em>  
                            </td>

                            <td class="text-start">
                                <em>0.00</em>     
                            </td>
                        </tr>

                        <tr class="bg-info">
                            <td class="text-start text-white">
                                <b>Total Operations : </b>  
                            </td>

                            <td class="text-start text-white">
                                <b>0.00</b>  
                            </td>
                        </tr>
                    
                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-start" colspan="2">
                                <strong>CASH FLOW FROM INVESTING :</strong>
                            </th>
                        </tr>
                        
                        <tr>
                            <td class="text-start">
                                <em>FIXED ASSET :</em> 
                            </td>
                            <td class="text-start">0.00</td>
                        </tr>

                        <tr class="bg-info">
                            <td class="text-start text-white">
                                <b><em>Total Investing :</em>  </b>  
                            </td>

                            <td class="text-start text-white">
                                <b><em>0.00</em> </b>  
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
                                <em>Capital A/C :</em> 
                            </td>
                            <td class="text-start">0.00</td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>Loan And Advance :</em> 
                            </td>
                            <td class="text-start">0.00</td>
                        </tr>

                        <tr class="bg-info">
                            <td class="text-start text-white">
                                <b><em>Total financing :</em>  </b>  
                            </td>

                            <td class="text-start text-white">
                                <b><em>0.00</em> </b>  
                            </td>
                        </tr> 
                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary">
                            <th class="text-start text-white"><strong>Total Cash Flow : ({{ json_decode($generalSettings->business, true)['currency'] }} )</strong> </th>
                            <th class="text-start text-white">
                                <span class="total_cash_flow">0.00</span>
                            </th>    
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </tbody>
</table>