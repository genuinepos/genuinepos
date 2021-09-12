
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">  
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start"> 
                                    Sold Product Total Unit Cost : 
                                    <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$totalTotalUnitCost, 2, '.', '') }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Total Order Tax : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalOrderTax, 2, '.', '') }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start"> Total Stock Adjustment : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalStockAdjustmentAmount, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> Total Expense : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalExpense, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total transfer shipping charge : </th>
                                <td class="text-start"> 
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalTransferShipmentCost, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Sell Return : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalReturn, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Payroll :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ number_format($totalPayroll, 2, '.', '') }}</td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Production Cost :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body"> 
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    Total Sales : <br>
                                    <small>((Inc.Tax))</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalSale, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Stock Adjustment Recovered : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format($totalStockAdjustmentRecovered, 2, '.', '') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>  

@php
    $netProfit = ($totalSale + $totalStockAdjustmentRecovered) 
                - $totalStockAdjustmentAmount 
                - $totalExpense
                - $totalReturn
                - $totalOrderTax
                - $totalPayroll
                - $totalTotalUnitCost
                - $totalTransferShipmentCost;
@endphp

<div class="profit_area mt-1">
    <div class="card">
        <div class="card-body"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="net_profit_area">
                        <h6 class="text-muted m-0">Net Profit : 
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <span class="{{ $netProfit < 0 ? 'text-danger' : '' }}">{{ number_format((float)$netProfit, 2, '.', '') }}</span></h6>
                        <p class="text-muted m-0">Net Profit (Total Sale + Total Stock Adjustment Recovered)
                            - <br>( Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Payroll + Total Production Cost )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>