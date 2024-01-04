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
            page-break-after: auto, font-size:9px !important;
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

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
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
                        {{ $generalSettings['business__business_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Sales Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business__business_name'];
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

        <div class="col-6">
            <p><strong>{{ __('Customer') }} : </strong> {{ $filteredCustomerName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
        $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $TotalQty = 0;
        $TotalNetTotal = 0;
        $TotalOrderDiscount = 0;
        $TotalShipmentCharge = 0;
        $TotalOrderTax = 0;
        $TotalSoldAmount = 0;
        $TotalReceived = 0;
        $TotalReturn = 0;
        $TotalDue = 0;
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Invoice ID') }}</th>
                        <th class="text-start">{{ __('Shop/Business') }}</th>
                        <th class="text-start">{{ __('Customer') }}</th>
                        <th class="text-end">{{ __('Total Qty') }}</th>
                        <th class="text-end">{{ __('Net Total Amt.') }}</th>
                        <th class="text-end">{{ __('Sale Discount') }}</th>
                        <th class="text-end">{{ __('Shipment Charge') }}</th>
                        <th class="text-end">{{ __('Sale Tax') }}</th>
                        <th class="text-end">{{ __('Total Invoice Amt.') }}</th>
                        <th class="text-end">{{ __('Received') }}</th>
                        <th class="text-end">{{ __('Return') }}</th>
                        <th class="text-end">{{ __('Due') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                    @endphp

                    @foreach ($sales as $sale)
                        @if ($previousDate != $sale->date)
                            @php
                                $previousDate = $sale->date;
                            @endphp

                            <tr>
                                <th class="text-start" colspan="12">{{ date($__date_format, strtotime($sale->date)) }}</th>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">{{ $sale->invoice_id }}</td>
                            <td class="text-start">
                                @if ($sale->branch_id)
                                    @if ($sale->parent_branch_name)
                                        {{ $sale->parent_branch_name . '(' . $sale->branch_area_name . ')' }}
                                    @else
                                        {{ $sale->branch_name . '(' . $sale->branch_area_name . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </td>

                            <td class="text-start">
                                {{ $sale->customer_name }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->total_qty) }}
                                @php
                                    $TotalQty += $sale->total_qty;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                @php
                                    $TotalNetTotal += $sale->net_total_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                @php
                                    $TotalOrderDiscount += $sale->order_discount_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                @php
                                    $TotalShipmentCharge += $sale->shipment_charge;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ '(' . $sale->order_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                @php
                                    $TotalOrderTax += $sale->order_tax_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                @php
                                    $TotalSoldAmount += $sale->total_invoice_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->received_amount) }}
                                @php
                                    $TotalReceived += $sale->received_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                                @php
                                    $TotalReturn += $sale->sale_return_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                @php
                                    $TotalDue += $sale->due;
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
    <div class="row">
        {{-- <div class="col-6"></div> --}}
        <div class="col-6 offset-6">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Total Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalQty) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Net Amount') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Sale Discount') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Shipment Charge') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Sale Tax') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Sold Amount') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalSoldAmount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Received') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalReceived) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Return') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalReturn) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Due') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalDue) }}
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($__date_format) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small>{{ __('Powered By') }} <strong>{{ __('Speed Digit Software Solution') }}.</strong></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>
