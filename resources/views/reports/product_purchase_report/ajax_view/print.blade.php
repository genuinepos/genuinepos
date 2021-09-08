@php
    $totalQty = 0;
    $totalUnitCost = 0;
    $totalSubTotal = 0;
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
        <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        <p><b>Product Purchase Report </b></p> 
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Product</th>
                    <th class="text-start">P.Code(SKU)</th>
                    <th class="text-start">Supplier</th>
                    <th class="text-start">P.Invoice ID</th>
                    <th class="text-start">Date</th>
                    <th class="text-start">Qty</th>
                    <th class="text-start">Unit Cost</th>
                    <th class="text-start">SubTotal</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($purchaseProducts as $pProduct)
                    <tr>
                        <td class="text-start">
                            @php
                                $variant = $pProduct->variant_name ? ' - ' . $pProduct->variant_name : '';
                                $totalQty += $pProduct->quantity;
                                $totalUnitCost += $pProduct->net_unit_cost;
                                $totalSubTotal += $pProduct->line_total;
                            @endphp
                           {{ $pProduct->name . $variant }}
                        </td>
                        <td class="text-start">{{ $pProduct->variant_code ? $pProduct->variant_code : $pProduct->product_code}}</td>
                        <td class="text-start">{{ $pProduct->supplier_name }}</td>
                        <td class="text-start">{{ $pProduct->invoice_id }}</td>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($pProduct->report_date)) }}</td>
                        <td class="text-start">{!! $pProduct->quantity . ' (<span class="qty" data-value="' . $pProduct->quantity . '">' . $pProduct->unit_code . '</span>)' !!}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $pProduct->net_unit_cost }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $pProduct->line_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">Total Quantity :</th>
                    <th class="text-end">{{ bcadd($totalQty, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Price :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'] .' '. bcadd($totalUnitCost, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Net Total Amount :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'] .' '. bcadd($totalSubTotal, 0, 2) }}</th>
                </tr>
           
            </thead>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small> 
        </div>
    </div>
@endif