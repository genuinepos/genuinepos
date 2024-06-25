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
            page-break-after: auto, font-size:9px !important;
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
        margin-left: 20px;
        margin-right: 20px;
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
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $totalStockInQty = 0;
    $totalStockOutQty = 0;
@endphp

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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Stock In-Out Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($dateFormat, strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($dateFormat, strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-4">
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

        <div class="col-4">
            <p><strong>{{ __('Product') }} : </strong> {{ $filteredProductName ? $filteredProductName : __('All') }} </p>
        </div>

        <div class="col-4">
            <p><strong>{{ __('Customer') }} : </strong> {{ $filteredCustomerName }} </p>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Product') }}</th>
                        <th class="text-start">{{ __('Out Date') }}</th>
                        <th class="text-start">{{ __('Out By') }}</th>
                        <th class="text-start">{{ __('Shop/Business') }}</th>
                        <th class="text-end">{{ __('Out Qty') }}</th>
                        <th class="text-end">{{ __('Price/Cost(Inc. Tax)') }}</th>
                        <th class="text-start">{{ __('Customer') }}</th>
                        <th class="text-start">{{ __('Stock In By') }}</th>
                        <th class="text-start">{{ __('Stock In Date') }}</th>
                        <th class="text-end">{{ __('Unit Cost(Inc. Tax)') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($stockInOuts as $row)
                        @php
                            $totalStockInQty += $row->stock_in_qty;
                            $totalStockOutQty += $row->out_qty;
                        @endphp
                        <tr>
                            <td class="text-start">
                                @php
                                    $variant = $row->variant_name ? '/' . $row->variant_name : '';
                                @endphp
                                {{ Str::limit($row->product_name, 20, '') . $variant }}
                            </td>

                            <td class="text-start">
                                {{ date($dateFormat, strtotime($row->stock_out_data_ts)) }}
                            </td>

                            <td class="text-start">
                                @php
                                    $stockOutBy = null;
                                    if ($row->sale_id) {
                                        $stockOutBy = __('Sales') . ': ' . $row->invoice_id;
                                    } elseif ($row->stock_issue_id) {
                                        $stockOutBy = __('Stock Issue') . ': ' . $row->stock_issue_voucher_no;
                                    } elseif ($row->stock_adjustment_id) {
                                        $stockOutBy = __('Stock Adjustment') . ': ' . $row->stock_adjustment_voucher_no;
                                    }
                                @endphp
                                {{ $stockOutBy }}
                            </td>

                            <td class="text-start">
                                @if ($row->branch_id)
                                    @if ($row->parent_branch_name)
                                        {{ $row->parent_branch_name . '(' . $row->branch_area_name . ')-' . $row->branch_code }}
                                    @else
                                        {{ $row->branch_name . '(' . $row->branch_area_name . ')-' . $row->branch_code }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $stockOutUnit = null;
                                    if ($row->sale_id) {
                                        $stockOutUnit = $row->sale_unit;
                                    } elseif ($row->stock_issue_id) {
                                        $stockOutUnit = $row->stock_issue_unit;
                                    } elseif ($row->stock_adjustment_id) {
                                        $stockOutUnit = $row->stock_adjustment_unit;
                                    }
                                @endphp
                                {{ \App\Utils\Converter::format_in_bdt($row->out_qty) . '/' . $stockOutUnit }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $stockOutUnitPriceOrCostIncTax = null;
                                    if ($row->sale_id) {
                                        $stockOutUnitPriceOrCostIncTax = \App\Utils\Converter::format_in_bdt($row->sale_unit_price_inc_tax);
                                    } elseif ($row->stock_issue_id) {
                                        $stockOutUnitPriceOrCostIncTax = \App\Utils\Converter::format_in_bdt($row->stock_issue_unit_cost_inc_tax);
                                    } elseif ($row->stock_adjustment_id) {
                                        $stockOutUnitPriceOrCostIncTax = \App\Utils\Converter::format_in_bdt($row->stock_adjustment_unit_cost_inc_tax);
                                    }
                                @endphp
                                {{ $stockOutUnitPriceOrCostIncTax }}
                            </td>

                            <td class="text-start">{{ $row->customer_name ? $row->customer_name : 'N/A' }}</td>

                            <td class="text-start">
                                @if ($row->purchase_inv)
                                    {{ __('Purchase') . ':' . $row->purchase_inv }}
                                @elseif ($row->production_voucher_no)
                                    {{ __('Production') . ':' . $row->production_voucher_no }}
                                @elseif ($row->product_opening_stock_id)
                                    {{ __('Opening Stock') }}
                                @elseif ($row->sale_return_id)
                                    {{ __('Sales Returned Stock') . ':' . $row->sales_return_voucher_no }}
                                @elseif ($row->transfer_stock_id)
                                    {{ __('Transfer Stock') . ':' . $row->transfer_stock_voucher_no }}
                                @else
                                    {{ __('Non-Manageable-Stock') }}
                                @endif
                            </td>

                            <td class="text-start">
                                @php
                                    $stockInDate = null;
                                    if ($row->stock_in_date_ts) {
                                        $stockInDate = date($dateFormat, strtotime($row->stock_in_date_ts));
                                    } else {
                                        $stockInDate = date($dateFormat, strtotime($row->product_created_at));
                                    }
                                @endphp
                                {{ $stockInDate }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $stockInUnitCostIncTax = null;
                                    if ($row->stock_in_unit_cost_inc_tax) {
                                        $stockInUnitCostIncTax = \App\Utils\Converter::format_in_bdt($row->stock_in_unit_cost_inc_tax);
                                    } else {
                                        if ($row->variant_unit_cost_inc_tax) {
                                            $stockInUnitCostIncTax = \App\Utils\Converter::format_in_bdt($row->variant_unit_cost_inc_tax);
                                        } else {
                                            $stockInUnitCostIncTax = \App\Utils\Converter::format_in_bdt($row->product_unit_cost_inc_tax);
                                        }
                                    }
                                @endphp
                                {{ $stockInUnitCostIncTax }}
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
                        <th class="text-end">{{ __('Total Stock In Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalStockInQty) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Stock Out Qty') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalStockOutQty) }}
                        </td>
                    </tr>
                </thead>
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
