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
        bottom: 0px;
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
        line-height: 12px !important;
    }

    .print_table tr td {
        color: black;
        font-size: 10px !important;
        line-height: 12px !important;
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

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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
                    {{ auth()->user()?->branch?->address . ', ' . auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Sales Order Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($generalSettings['business_or_shop__date_format'], strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($toDate)) }}
                </p>
            @endif
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

        <div class="col-6">
            <p><strong>{{ __('Customer') }} : </strong> {{ $filteredCustomerName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $TotalQty = 0;
        $TotalNetTotal = 0;
        $TotalOrderDiscount = 0;
        $TotalShipmentCharge = 0;
        $TotalOrderTax = 0;
        $totalOrderdAmount = 0;
        $TotalReceived = 0;
        $TotalReturn = 0;
        $TotalDue = 0;
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Order ID') }}</th>
                        <th class="text-start">{{ __('Shop/Business') }}</th>
                        <th class="text-start">{{ __('Customer') }}</th>
                        <th class="text-end">{{ __('Total Qty') }}</th>
                        <th class="text-end">{{ __('Net Total Amt.') }}</th>
                        <th class="text-end">{{ __('Order Discount') }}</th>
                        <th class="text-end">{{ __('Shipment Charge') }}</th>
                        <th class="text-end">{{ __('Order Tax') }}</th>
                        <th class="text-end">{{ __('Total Ordered Amt.') }}</th>
                        <th class="text-end">{{ __('Received') }}</th>
                        <th class="text-end">{{ __('Due') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                    @endphp

                    @foreach ($orders as $order)
                        @if ($previousDate != $order->date)
                            @php
                                $previousDate = $order->date;
                            @endphp

                            <tr>
                                <th class="text-start" colspan="12">{{ date($__date_format, strtotime($order->date)) }}</th>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">{{ $order->order_id }}</td>
                            <td class="text-start">
                                @if ($order->branch_id)
                                    @if ($order->parent_branch_name)
                                        {{ $order->parent_branch_name . '(' . $order->branch_area_name . ')' }}
                                    @else
                                        {{ $order->branch_name . '(' . $order->branch_area_name . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </td>

                            <td class="text-start">
                                {{ $order->customer_name }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->total_qty) }}
                                @php
                                    $TotalQty += $order->total_qty;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                @php
                                    $TotalNetTotal += $order->net_total_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                @php
                                    $TotalOrderDiscount += $order->order_discount_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                @php
                                    $TotalShipmentCharge += $order->shipment_charge;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ '(' . $order->order_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($order->order_tax_amount) }}
                                @php
                                    $TotalOrderTax += $order->order_tax_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->total_invoice_amount) }}
                                @php
                                    $totalOrderdAmount += $order->total_invoice_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->received_amount) }}
                                @php
                                    $TotalReceived += $order->received_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                @php
                                    $TotalDue += $order->due;
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
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
                        <th class="text-end">{{ __('Total Net Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Order Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Order Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Ordered Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalOrderdAmount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Received') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalReceived) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
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
