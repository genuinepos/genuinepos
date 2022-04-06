<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
</style>
@php
    $totalStockInQty = 0;
    $totalStockOutQty = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p><b>All Business Location</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>Date :</b>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>Stock In-Out Report </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Product</th>
                    <th class="text-start">Sale</th>
                    <th class="text-start">Sale Date</th>
                    <th class="text-start">B. Location</th>
                    <th class="text-end">Sold/Out Qty</th>
                    <th class="text-end">Sold Price({{json_decode($generalSettings->business, true)['currency']}})</th>
                
                    <th class="text-start">Customer</th>
                    <th class="text-start">Stock In By</th>
                    <th class="text-start">Stock In Date</th>
                    <th class="text-end">Unit Cost({{json_decode($generalSettings->business, true)['currency']}})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($stockInOuts as $row)
                    @php
                        $totalStockInQty += $row->stock_in_qty;
                        $totalStockOutQty += $row->sold_qty;
                    @endphp
                    <tr>
                        <td class="text-start">
                            @php
                                $variant = $row->variant_name ? '/' . $row->variant_name : '';
                            @endphp
                            {{ Str::limit($row->name, 20, '') . $variant }}
                        </td>
                        <td class="text-start">{{ $row->invoice_id }}</td>
                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->date)) }}
                        </td>
                        <td class="text-start">
                            @if ($row->branch_name) 
                                {{ $row->branch_name }}
                            @else 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            @endif
                        </td>

                        <td class="text-end">{{ $row->sold_qty }}</td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax) }}
                        </td>

                        <td class="text-start">{{ $row->customer_name ? $row->customer_name : 'Walk-In-Customer'; }}</td>
                        
                        <td class="text-start">
                            @if ($row->purchase_inv) 
                                {{ 'Purchase:'. $row->purchase_inv }}  
                            @elseif ($row->production_voucher_no) 
                                {{ 'Production:' . $row->production_voucher_no }}
                            @elseif ($row->pos_id) 
                                {{ 'Opening Stock' }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->stock_in_date)) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->net_unit_cost) }}
                        </td>
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
                    <th class="text-end">Total Stock In Qty : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalStockInQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Stock Out Qty : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalStockOutQty) }}
                    </td>
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

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        Print Date: {{ date('d-m-Y , h:iA') }}
    </small>
</div>
