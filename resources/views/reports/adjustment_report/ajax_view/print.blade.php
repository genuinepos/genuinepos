<style>
    @page {/* size:21cm 29.7cm; */ margin:1cm 1cm 1cm 1cm; *//* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
@php
    $totalNormal = 0;
    $totalAbnormal = 0;
    $totalAdjustment = 0;
    $totalRecovered = 0;
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

        <p><b>Stock Adjustment Report </b></p> 
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">Reference No.</th>
                    <th class="text-start">B.Location</th>
                    <th class="text-start">Type</th>
                    <th class="text-start">Total Amount</th>
                    <th class="text-start">Total Recovered Amount</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($adjustments as $ad)
                    @php
                        if($ad->type == 1) {
                            $totalNormal += $ad->net_total_amount;
                        } else {
                            $totalAbnormal += $ad->net_total_amount;
                        }

                        $totalAdjustment += $ad->net_total_amount;
                        $totalRecovered += $ad->recovered_amount;
                    @endphp
                    <tr>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ad->date)) }}</td>
                        <td class="text-start">{{ $ad->invoice_id }}</td>
                        <td class="text-start">
                            @if (!$ad->branch_name && !$ad->warehouse_name) 
                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @else 
                                @if ($ad->branch_name) 
                                    {!! $ad->branch_name . '/' . $ad->branch_code . '(<b>BL</b>)' !!}
                                @else 
                                    {!! $ad->warehouse_name . '/' . $ad->warehouse_code . '(<b>WH</b>)' !!} 
                                @endif
                            @endif   
                        </td>
                        <td class="text-start">
                            {{ $ad->type == 1 ? 'Normal' : 'Abnormal' }}
                        </td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $ad->net_total_amount }}</td>
                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] .' '. $ad->recovered_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="row">
    <div class="col-6">

    </div>

    <div class="col-6">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <th class="text-start">Total Normal : </th>
                    <td class="text-start"> 
                        {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalNormal, 0, 2)  }} 
                    </td>
                </tr>

                <tr>
                    <th class="text-start">Total Abormal :</th>
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalAbnormal, 0, 2)  }} 
                    </td>
                </tr>

                <tr>
                    <th class="text-start">Total Adjustment :</th>
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalAdjustment, 0, 2)  }}
                    </td>
                </tr>

                <tr>
                    <th class="text-start">Total Recovered Amount :</th>
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalRecovered, 0, 2)  }}
                    </td>
                </tr>
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