<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
    <div class="col-4 align-items-center">
        @if ($branch_id == '')
            @if ($generalSettings['business__business_logo'] != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h4>
            @endif
        @elseif($branch_id == 'NULL')
            @if ($generalSettings['business__business_logo'] != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h4>
            @endif
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'logo', 'email', 'phone', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp

            @if ($branch->logo != null)

                <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . $branch->logo) }}" class="logo__img">
            @else

                <h4 class="text-uppercase fw-bold">{{ $branch->name }}</h4>
            @endif
        @endif
    </div>

    <div class="col-8 text-end">
        @if ($branch_id == '')

            <h5 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h5>
            <p class="text-uppercase fw-bold">@lang('menu.all_business_location')</p>
            <p>{{ $generalSettings['business__address'] }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
        @elseif ($branch_id == 'NULL')

            <h5 class="text-uppercase">{{ $generalSettings['business__shop_name'] }}</h5>
            <p>{{ $generalSettings['business__address'] }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
        @else

            <h5 class="text-uppercase fw-bold">{{ $branch->name }}</h5>
            <p>{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
            <p><strong>@lang('menu.email') : </strong>{{ $branch->email }}</p>
            <p><strong>@lang('menu.phone') : </strong>{{ $branch->phone }}</p>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.daily_profit_loss_report') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to')</strong> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="sale_and_purchase_amount_area">
    {{-- <div class="row">
        <div class="col-md-12 text-center">
            @if ($branch_id == '')
                <h6>{{ $generalSettings['business__shop_name'] }}</h6>
                <p><b>@lang('menu.all_business_location').</b></p>
                <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            @elseif ($branch_id == 'NULL')
                <h6>{{ $generalSettings['business__shop_name'] }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            @else
                @php
                    $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')->first();
                @endphp
                <h6>{{ $branch->name.' '.$branch->branch_code }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
            @endif

            @if ($fromDate && $toDate)
                <p><b>@lang('menu.date') </b> {{date($generalSettings['business__date_format'] ,strtotime($fromDate)) }} <b>@lang('menu.to')</b> {{ date($generalSettings['business__date_format'] ,strtotime($toDate)) }} </p>
            @endif
            <h6 style="margin-top: 10px;"><b>@lang('menu.daily_profit_loss_report')</b></h6>
        </div>
    </div> --}}

    <div class="row g-3 mt-2">
        <div class="col-8 offset-2">
            <div class="card">
                <div class="card-body">
                    <table class="display table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong> @lang('menu.total_sale') <small>({{__('Inc. Tax')}})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                </td>

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end">
                                    <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{__('Inc. Tax')}})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                </td>

                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }})
                                </td>
                            </tr>

                            @php
                                $grossProfit = ($totalSale + $totalStockAdjustmentRecovered) - $totalTotalUnitCost
                            @endphp

                            <tr>
                                <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end fw-bold">
                                    {{ App\Utils\Converter::format_in_bdt($grossProfit) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Order Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalOrderTax) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_stock_adjustment') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_expense') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalExpense) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_transfer_shipping_charge') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_sell_return') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalSaleReturn) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_payroll') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end">
                                    ({{ App\Utils\Converter::format_in_bdt($totalPayroll) }})
                                </td>
                            </tr>

                            {{--<tr>
                                <td class="text-end"><strong>@lang('menu.total_production_cost') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end"> 0.00 (P)</td>
                            </tr> --}}

                            @php
                                $netProfit = $grossProfit
                                            - $totalStockAdjustmentAmount
                                            - $totalExpense
                                            - $totalSaleReturn
                                            - $totalOrderTax
                                            - $totalPayroll
                                            - $totalTransferShipmentCost;
                            @endphp

                            <tr>
                                <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end fw-bold">
                                    {{ App\Utils\Converter::format_in_bdt($netProfit) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif
