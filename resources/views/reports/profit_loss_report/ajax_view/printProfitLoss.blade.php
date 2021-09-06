@php
    $total_opening_stock = 0;
    $total_purchase = 0;
    $total_purchase_shipping_charge = 0;
    $total_transfer_shipping_charge = 0;
    $total_adjustment = 0;
    $total_adjustment_recovered = 0;
    $total_expanse = 0;
    $total_sale_discount = 0;
    $total_sale_return = 0;
    $closing_stock_by_unit_cost = 0;
    $closing_stock_by_selling_price = 0;
    $total_sale = 0;
    $total_sale_shipping_charge = 0;
    $total_stock_recovered = 0;
    $total_purchase_return = 0;
    $total_purchase_discount = 0;
    $total_payrole = 0;

    foreach ($opening_stocks as $opening_stock) {
        $total_opening_stock += $opening_stock->subtotal;
    }

    foreach ($purchases as $purchase) {
        $total_purchase += $purchase->total_purchase_amount - $purchase->shipment_charge - $purchase->order_discount_amount - $purchase->purchase_tax_amount;
        $total_purchase_shipping_charge += $purchase->shipment_charge;
        $total_purchase_return += $purchase->purchase_return_amount;
        $total_purchase_discount += $purchase->order_discount_amount;
    }

    foreach ($stock_adjustments as $stock_adjustment) {
        $total_adjustment += $stock_adjustment->net_total_amount;
        $total_adjustment_recovered += $stock_adjustment->recovered_amount;
    }

    foreach ($expanses as $expanse) {
        $total_expanse += $expanse->net_total_amount;
    }

    foreach ($transfer_to_branchs as $transfer_to_branch) {
        $total_transfer_shipping_charge += $transfer_to_branch->shipping_charge;
    }

    foreach ($transfer_to_warehouses as $transfer_to_warehouses) {
        $total_transfer_shipping_charge += $transfer_to_warehouses->shipping_charge;
    }

    foreach ($sales as $sale) {
        $total_sale_discount += $sale->order_discount_amount;
        $total_sale_return += $sale->sale_return_amount;
        $total_sale += $sale->total_payable_amount - $sale->shipment_charge - $sale->order_tax_amount - $sale->order_discount_amount;
        $total_sale_shipping_charge += $sale->shipment_charge;
    }

    foreach ($products as $product) {
        $closing_stock_by_unit_cost += $product->quantity * $product->product_cost_with_tax;
        $tax = $product->tax_percent ? $product->tax_percent : 0;
        $closing_stock_by_selling_price += ($product->product_price / 100 * $tax) + $product->product_price;
    }

    $gross_profit = $total_sale - $total_purchase;
    $net_profit = $gross_profit + ($total_sale_shipping_charge + $total_adjustment_recovered + $total_purchase_discount) - ($total_adjustment + $total_expanse + $total_purchase_shipping_charge +  $total_transfer_shipping_charge + $total_sale_discount + $total_payrole);
@endphp


<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 text-center">
            @if ($branch_id == '')
                <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p><b>All Business Location.</b></p> 
            @elseif ($branch_id == 'NULL')
                <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
            @else 
                @php
                    $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code')->first();
                @endphp
                {{ $branch->name.' '.$branch->branch_code }}
            @endif
            <p><b>Date :</b> {{ $fromDate }} <b>To</b> {{ $toDate }} </p> 
            <p><b>Profit / Loss Report </b></p> 
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">  
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    Opening Stock : <br>
                                    <small class="text-muted">(By purchase price) </small>
                                </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_opening_stock, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">
                                    Total purchase : <br>
                                    <small class="text-muted">(Exc. tax, Discount) </small>
                                </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_purchase, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> Total Stock Adjustment : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_adjustment, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> Total Expense : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_expanse, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> Total purchase shipping charge : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_purchase_shipping_charge, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total transfer shipping charge : </th>
                                <td class="text-start"> 
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_transfer_shipping_charge, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Sell discount : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_sale_discount, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> Total customer reward : </th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Sell Return : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_sale_return, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Payroll :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Production Cost :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body"> 
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    Closing stock <br>
                                    <small>(By purchase price)</small>
                                </th>
                                <td class="text-start"> 
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$closing_stock_by_unit_cost, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">
                                    Closing stock : <br>
                                    <small>(By sale price)</small>
                                </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$closing_stock_by_selling_price, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">
                                    Total Sales : <br>
                                    <small>((Exc. tax, Discount))</small>
                                </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_sale, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total sell shipping charge : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}  
                                    {{ number_format((float)$total_sale_shipping_charge, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Stock Recovered : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_adjustment_recovered, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Purchase Return : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_purchase_return, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total Purchase discount : </th>
                                <td class="text-start"> 
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ number_format((float)$total_purchase_discount, 2, '.', '') }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">Total sell round off : </th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>  
<br>
<div class="profit_area mt-1">
    <div class="card">
        <div class="card-body"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="gross_profit_area">
                        <h6 class="text-muted m-0">Gross Profit : 
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <span class="{{ $gross_profit < 0 ? 'text-danger' : '' }}">
                                {{ number_format((float)$gross_profit, 2, '.', '') }}
                            </span>
                            </h6>
                        <p class="text-muted">(Total sell price - Total purchase price)</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="net_profit_area">
                        <h6 class="text-muted m-0">Net Profit : 
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <span class="{{ $net_profit < 0 ? 'text-danger' : '' }}">{{ number_format((float)$net_profit, 2, '.', '') }}</span></h6>
                        <p class="text-muted m-0">Gross Profit + (Total sell shipping charge + Total Stock Recovered + Total Purchase discount + Total sell round off )
                            - <br>( Total Stock Adjustment + Total Expense + Total purchase shipping charge + Total transfer shipping charge + Total Sell discount + Total customer reward + Total Payroll + Total Production Cost )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>