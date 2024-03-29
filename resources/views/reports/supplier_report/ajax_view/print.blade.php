<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 4%;
        margin-right: 4%;
    }

    .header,
    .header-space,
    .footer,
    .footer-space {
        height: 20px;
    }

    .header {
        position: fixed;
        top: 0;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }

    .noBorder {
        border: 0px !important;
    }

    tr.noBorder td {
        border: 0px !important;
    }

    tr.noBorder {
        border: 0px !important;
        border-left: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
</style>
@php
    $allTotalPurchase = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</h5>
        <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
        <h6 style="margin-top: 10px;"><b>@lang('menu.supplier_report')</b></h6>
    </div>
</div>
<br />
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.supplier')</th>
                    <th class="text-end">@lang('menu.total_purchase')</th>
                    <th class="text-end">@lang('menu.total_paid')</th>
                    <th class="text-end">@lang('menu.opening_balance')</th>
                    <th class="text-end">@lang('menu.total_due')</th>
                    <th class="text-end">@lang('menu.total_return_due')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($supplierReports as $report)
                    @php
                        $allTotalPurchase += $report->total_purchase;
                        $allTotalPaid += $report->total_paid;
                        $allTotalOpDue += $report->opening_balance;
                        $allTotalDue += $report->total_purchase_due;
                        $allTotalReturnDue += $report->total_purchase_return_due;
                    @endphp
                    <tr>
                        <td class="text-end">{{ $report->name . ' (ID: ' . $report->contact_id . ')' }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_purchase) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_paid) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->opening_balance) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_purchase_due) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_purchase_return_due) }}</td>
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
            <tbody>
                <tr>
                    <th class="text-end">@lang('menu.opening_balance') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalOpDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchase') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPurchase) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_paid') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchase_due') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">{{ __('Total Returnable/Refundable Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturnDue) }}</td>
                </tr>
            </tbody>
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
