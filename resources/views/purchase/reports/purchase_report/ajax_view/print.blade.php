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
        margin-left: 5px;
        margin-right: 5px;
    }

    div#footer {
        position: fixed;
        bottom: 24px;
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Purchase Report') }}</strong></h6>
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
            <p><strong>{{ __('Supplier') }} : </strong> {{ $filteredSupplierName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $TotalItem = 0;
        $TotalQty = 0;
        $TotalNetTotal = 0;
        $TotalOrderDiscount = 0;
        $TotalOrderTax = 0;
        $TotalPurchaseAmount = 0;
        $TotalPaid = 0;
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
                        <th class="text-start">{{ __('Supplier') }}</th>
                        <th class="text-end">{{ __('Total Item') }}</th>
                        <th class="text-end">{{ __('Total Qty') }}</th>
                        <th class="text-end">{{ __('Net Total Amt.') }}</th>
                        <th class="text-end">{{ __('Purchase Discount') }}</th>
                        <th class="text-end">{{ __('Purchase Tax') }}</th>
                        <th class="text-end">{{ __('Total Purchased Amt.') }}</th>
                        <th class="text-end">{{ __('Paid') }}</th>
                        <th class="text-end">{{ __('Return') }}</th>
                        <th class="text-end">{{ __('Due') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                    @endphp

                    @foreach ($purchases as $purchase)
                        @if ($previousDate != $purchase->date)
                            @php
                                $previousDate = $purchase->date;
                            @endphp

                            <tr>
                                <th class="text-start" colspan="12">{{ date($__date_format, strtotime($purchase->date)) }}</th>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">{{ $purchase->invoice_id }}</td>
                            <td class="text-start">
                                @if ($purchase->branch_id)
                                    @if ($purchase->parent_branch_name)
                                        {{ $purchase->parent_branch_name . '(' . $purchase->branch_area_name . ')' }}
                                    @else
                                        {{ $purchase->branch_name . '(' . $purchase->branch_area_name . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </td>

                            <td class="text-start">
                                {{ $purchase->supplier_name }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_item) }}
                                @php
                                    $TotalItem += $purchase->total_item;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_qty) }}
                                @php
                                    $TotalQty += $purchase->total_qty;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                @php
                                    $TotalNetTotal += $purchase->net_total_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                                @php
                                    $TotalOrderDiscount += $purchase->order_discount_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ '(' . $purchase->purchase_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($purchase->purchase_tax_amount) }}
                                @php
                                    $TotalOrderTax += $purchase->purchase_tax_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                @php
                                    $TotalPurchaseAmount += $purchase->total_purchase_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                @php
                                    $TotalPaid += $purchase->paid;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->purchase_return_amount) }}
                                @php
                                    $TotalReturn += $purchase->purchase_return_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                @php
                                    $TotalDue += $purchase->due;
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
                        <th class="text-end">{{ __('Total Item') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalItem) }}
                        </td>
                    </tr>

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
                        <th class="text-end">{{ __('Total Purchase Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Purchase Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Purchased Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalPurchaseAmount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalPaid) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Return') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($TotalReturn) }}
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
