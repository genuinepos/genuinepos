<style>
    @media print {
        table {
            page-break-after: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto, font-size: 9px !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 5px;
        margin-right: 5px;
    }

    div#footer {
        position: fixed;
        bottom: 20px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    .print_table th {
        font-size: 11px !important;
        font-weight: 550 !important;
        line-height: 12px !important
    }

    .print_table tr td {
        color: black;
        font-size: 10px !important;
        line-height: 12px !important
    }

    .print_area {
        font-family: Arial, Helvetica, sans-serif;
    }

    .print_area h6 {
        font-size: 14px !important;
    }

    .print_area p {
        font-size: 11px !important;
    }

    .print_area small {
        font-size: 8px !important;
    }
</style>

@php
    $dateFormat = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $currency = $generalSettings['business_or_shop__currency_symbol'];
@endphp

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                @endif
            @endif
        </div>

        <div class="col-8 text-end">
            <p style="text-transform: uppercase;" class="p-0 m-0">
                <strong>
                    @if (auth()->user()?->branch)
                        @if (auth()->user()?->branch?->parent_branch_id)
                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else
                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business_or_shop__business_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business_or_shop__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business_or_shop__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business_or_shop__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Today Summary') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            <p>
                <strong>{{ __('Date') }} : </strong> {{ date($dateFormat) }}
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business_or_shop__business_name'];
                if (auth()->user()?->branch) {
                    if (auth()->user()?->branch->parentBranch) {
                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                    } else {
                        $ownOrParentbranchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                    }
                }
            @endphp
            <p><strong>{{ __('Shop/Business') }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th class="text-start">{{ __('Total Purchased') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchase']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Purchase Shipping Charge') }} </th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseShipmentCharge']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Purchase Return') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseReturn']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Purchase After Return') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseAfterReturn']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Payment') }} </th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPayment']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Purchase Due') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPurchaseDue']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Stock Issue') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockIssue']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Stock Adjustment') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockAdjustment']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Stock Adjustment Recovered') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalStockAdjustmentRecovered']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Expense') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalExpense']) }}</td>
                    </tr>

                    @if ($generalSettings['subscription']->features['hrm'] == 1)
                        <tr>
                            <th class="text-start">{{ __('Total Expense By Payroll') }}</th>
                            <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalPayrollPayment']) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th class="text-start">{{ __('Total Sales') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSales']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Sales Discount') }} </th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSaleDiscount']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Sales Shipment Charge') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSaleShipmentCharge']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Sales Return') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesReturn']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Sales After Return') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesAfterReturn']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Received') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalReceived']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start">{{ __('Total Sales Due') }}</th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['totalSalesDue']) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start"><strong>{{ __('Today Profit/Loss') }}</strong></th>
                        <td class="text-start">: {{ $currency }} {{ App\Utils\Converter::format_in_bdt($todaySummaries['netProfit']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('speeddigit.show_app_info_in_print') == true)
                    <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __("M:") }} {{ config('speeddigit.phone') }}</small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>
