<style>
    @page {/* size:21cm 29.7cm; */ margin:1cm 1cm 1cm 1cm; *//* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
@php
    $total_purchase = 0;
    $total_purchase_inc_tax = 0;
    $total_purchase_due = 0;
    $total_purchase_return = 0;

    $total_sale = 0;
    $total_sale_inc_tax = 0;
    $total_sale_due = 0;
    $total_sale_return = 0;

    foreach ($purchases as $purchase) {
        $total_purchase += $purchase->total_purchase_amount - $purchase->purchase_tax_amount;
        $total_purchase_inc_tax += $purchase->total_purchase_amount;
        $total_purchase_due += $purchase->due;
        $total_purchase_return += $purchase->purchase_return_amount;
    }

    foreach ($sales as $sale) {
        $total_sale += $sale->total_payable_amount - $sale->order_tax_amount;
        $total_sale_inc_tax += $sale->total_payable_amount;
        $total_sale_due += $sale->due > 0 ? $sale->due : 0;
        $total_sale_return += $sale->sale_return_amount > 0 ? $sale->sale_return_amount : 0;
    }

    $saleMinusPurchase = $total_sale_inc_tax - $total_sale_return - $total_purchase_inc_tax - $total_purchase_return;
    $saleDueMinusPurchaseDue = $total_sale_due - $total_purchase_due;
@endphp
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
        
        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif
        <p><b>Sale / Purcahse Report </b></p> 
    </div>
</div>
<br>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">  
                <div class="heading">
                    <h6 class="text-primary"><b>Purchases</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">Total Purchase :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_purchase, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">Purchase Including Tax : </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_purchase_inc_tax, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">Purchase Return Including Tax : </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_purchase_return, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start"> Purchase Due: </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_purchase_due, 2, '.', '') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-body"> 
                <div class="heading">
                    <h6 class="text-primary"><b>Sales</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">Total Sale :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_sale, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">Sale Including Tax : </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_sale_inc_tax, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">Sale Return Including Tax : </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_sale_return, 2, '.', '') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start"> Sale Due: </th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }} 
                                {{ number_format((float)$total_sale_due, 2, '.', '') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  
<div class="row mt-1">
    <div class="sale_purchase_due_compare_area">
        <div class="col-md-12">
            <div class="card-body card-custom"> 
                <div class="heading">
                    <h6 class="text-navy-blue">Overall (Sale - Sale Return - Purchase - Purchase Return)</h6>
                </div>

                <div class="compare_area mt-3">
                    <h5 class="text-muted">Sale - Purchase : 
                        <span class="{{ $saleMinusPurchase < 0 ? 'text-danger' : '' }}">
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            {{ number_format((float)$saleMinusPurchase, 2, '.', '') }}
                        </span>
                    </h5>
                    <h5 class="text-muted">Due amount (Sale Due - Purchase Due) :
                        <span class="{{ $saleDueMinusPurchaseDue < 0 ? 'text-danger' : '' }}">
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            {{ number_format((float)$saleDueMinusPurchaseDue, 2, '.', '') }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>