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
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php
    $totalQty = 0;
    $totalUnitPrice = 0;
    $totalSubTotal = 0;
@endphp
    <div class="row">
        <div class="col-md-12 text-center">
            @if ($branch_id == '')
                <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</h5>
                <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                <p><b>@lang('menu.all_business_location')</b></p>
            @elseif ($branch_id == 'NULL')
                <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</h5>
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
                <p><b>@lang('menu.date') :</b>
                    {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                    <b>@lang('menu.to')</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
                </p>
            @endif
            <h6 style="margin-top: 10px;"><b>{{ __('Product Sale Report') }} </b></h6>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">@lang('menu.date')</th>
                        <th class="text-start">@lang('menu.product')</th>
                        <th class="text-start">@lang('menu.p_code')(SKU)</th>
                        <th class="text-start">@lang('menu.customer')</th>
                        <th class="text-start">@lang('menu.invoice_id')</th>
                        <th class="text-start">@lang('menu.qty')</th>
                        <th class="text-end">@lang('menu.unit_price')({{json_decode($generalSettings->business, true)['currency']}})</th>
                        <th class="text-end">@lang('menu.subtotal')({{json_decode($generalSettings->business, true)['currency']}})</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($saleProducts as $sProduct)
                        <tr>
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sProduct->report_date)) }}</td>
                            <td class="text-start">
                                @php
                                    $variant = $sProduct->variant_name ? ' - ' . $sProduct->variant_name : '';
                                    $totalQty += $sProduct->quantity;
                                    $totalUnitPrice += $sProduct->unit_price_inc_tax;
                                    $totalSubTotal += $sProduct->subtotal;
                                @endphp
                            {{ $sProduct->name . $variant }}
                            </td>
                            <td class="text-start">{{ $sProduct->variant_code ? $sProduct->variant_code : $sProduct->product_code}}</td>
                            <td class="text-start">{{ $sProduct->customer_name ? $sProduct->customer_name : 'Walk-In-Customer' }}</td>
                            <td class="text-start">{{ $sProduct->invoice_id }}</td>

                            <td class="text-start">{!! $sProduct->quantity . ' (<span class="qty" data-value="' . $sProduct->quantity . '">' . $sProduct->unit_code . '</span>)' !!}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($sProduct->unit_price_inc_tax) }}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($sProduct->subtotal) }}</td>
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
                        <th class="text-end">@lang('menu.total_quantity') :</th>
                        <td class="text-end">{{ bcadd($totalQty, 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Price') }} : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalUnitPrice) }}</td>
                    </tr>

                    <tr>
                        <th class="text-end">@lang('menu.net_total_amount') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSubTotal) }}</td>
                    </tr>

                </thead>
            </table>
        </div>
    </div>

    @if (env('PRINT_SD_OTHERS') == 'true')
        <div class="row">
            <div class="col-md-12 text-center">
                <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
            </div>
        </div>
    @endif

    <div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
        <small style="font-size: 5px;" class="text-end">
            @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
        </small>
    </div>
