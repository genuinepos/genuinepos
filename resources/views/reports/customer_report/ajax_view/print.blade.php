<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:9px!important; font-weight: 550!important;}
    .print_table tr td{font-size: 9px!important;}
</style>
@php
    $allTotalSale = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
    $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
    <div class="col-4" style="border-right: 1px solid black;!important;">
        @if ($generalSettings['business__business_logo'] != null)

            <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
        @else

            <h4 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h4>
        @endif
    </div>

    <div class="col-8 text-end">
        <h5 class="text-uppercase fw-bold">{{ $generalSettings['business__shop_name'] }}</h5>
        <p class="text-uppercase fw-bold">@lang('menu.all_business_location')</p>
        <p>{{ $generalSettings['business__address'] }}</p>
        <p><strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }}</p>
        <p><strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}</p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.customer_report') </strong></h6>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.opening_balance')</th>
                    <th class="text-end">@lang('menu.total_sale')</th>
                    <th class="text-end">@lang('menu.total_paid')</th>
                    <th class="text-end">@lang('menu.total_due')</th>
                    <th class="text-end">@lang('menu.total_return_due')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($customerReports as $report)
                    @php
                        $openingBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['opening_balance'];
                        $totalSale = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['total_sale'];
                        $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['total_paid'];
                        $totalSaleDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['total_sale_due'];
                        $totalReturn = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['total_return'];
                        $totalSaleReturnDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($report->id)['total_sale_return_due'];
                        $allTotalSale += $totalSale;
                        $allTotalPaid += $totalPaid;
                        $allTotalOpDue += $openingBalance;
                        $allTotalDue += $totalSaleDue;
                        $allTotalReturnDue += $totalReturn;
                    @endphp

                    <tr>
                        <td class="text-start">{!! $report->name.'(<b>'.$report->phone.'</b>)' !!}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($openingBalance) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($totalSale) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($totalPaid) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($totalSaleDue) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($totalSaleReturnDue) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered print_table">
            <tbody>
                <tr>
                    <th class="text-end">@lang('menu.opening_balance') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalOpDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalSale) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_paid') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale_due') : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Returnable Due') }} : {{ $generalSettings['business__currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturnDue) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date($generalSettings['business__date_format']) }}</small>
        </div>

        @if (env('PRINT_SD_OTHERS') == 'true')
            <div class="col-4 text-center">
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
            </div>
        @endif

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
