<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 20px;margin-right: 20px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
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

        <h6 style="margin-top: 10px;"><b>Business Location Stock Report </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">P.Code</th>
                    <th class="text-start">Product</th>
                    <th class="text-start">Business Location</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Current Stock</th>
                    <th class="text-end">Stock Value <b><small>(By Unit Cost)</small></b></th>
                    <th class="text-end">Total Sold</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($branch_stock as $row)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $row->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}</td>
                            <td class="text-end">{{ $row->variant_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($row->variant_price) }}</td>
                            <td class="text-end">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-end">{{ $row->v_total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @else 
                        <tr>
                            <td class="text-start">{{ $row->product_code }}</td>
                            <td class="text-start">{{ $row->name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}</td>
                            <td class="text-end">{{ $row->product_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($row->product_price) }}</td>
                            <td class="text-end">
                                @php
                                    $currentStockValue = $row->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td class="text-end">
                            <td>{{ $row->total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
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
