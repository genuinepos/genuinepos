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

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 40px; width:100px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Product Ledger') }}</strong></h6>
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
        <div class="col-4">
            <table>
                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Product') }}</th>
                    <td><strong>:</strong> {{ $product->name }}</td>
                </tr>

                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Product Code') }}</td>
                    <td><strong>:</strong> {{ $product->product_code }}</td>
                </tr>
            </table>
        </div>

        <div class="col-4">
            <table>
                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Unit') }}</th>
                    <td><strong>:</strong> {{ $product?->unit?->name }}</td>
                </tr>

                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Brand.') }}</td>
                    <td><strong>:</strong> {{ $product?->brand ? $product?->brand?->name : 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="col-4">
            <table>
                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Category') }}</th>
                    <td><strong>:</strong> {{ $product?->category ? $product?->category?->name : 'N/A' }}</td>
                </tr>

                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Subcategory') }}</td>
                    <td><strong>:</strong> {{ $product?->subcategory ? $product?->subcategory?->name : 'N/A' }}</td>
                </tr>
            </table>
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
            <p><strong>{{ __('Warehouse') }} : </strong> {{ $filteredWarehouseName }} </p>
        </div>

        @if ($product->is_variant == 1)
            <div class="col-4">
                <p><strong>{{ __('Variant') }} : </strong> {{ $filteredVariantName }} </p>
            </div>
        @endif
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $productLedgerService = new App\Services\Products\ProductLedgerService();

        $productStockService = new App\Services\Products\ProductStockService();
        $amounts = $productStockService->productStock(id: $product->id, request: $request);
    @endphp

    <div class="row mt-1">
        <div class="col-12 print_table_area">
            <table class="table report-table table-sm print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Date') }}</th>
                        <th class="text-start">{{ __('Shop/Business') }}</th>
                        <th class="text-start">{{ __('Warehouse') }}</th>
                        <th class="text-start">{{ __('Voucher Type') }}</th>
                        <th class="text-start">{{ __('Voucher No') }}</th>
                        <th class="text-end">{{ __('In') }}</th>
                        <th class="text-end">{{ __('Out') }}</th>
                        <th class="text-end">{{ __('Running Stock') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $previousDate = '';
                        $isEmptyDate = 0;
                    @endphp
                    @foreach ($entries as $row)
                        <tr class="main_tr">
                            <td class="text-start fw-bold main_td" style="border-bottom: 0px solid black!important;">
                                @php
                                    $date = $row->date_ts ? date($__date_format, strtotime($row->date_ts)) : '';
                                @endphp

                                @if ($previousDate != $date)
                                    @php
                                        $previousDate = $date;
                                        $isEmptyDate = 0;
                                    @endphp
                                    {{ $date }}
                                @endif
                            </td>

                            <td>
                                @php
                                    $branchName = null;
                                    $areaName = $row->area_name ? '(' . $row->area_name . ')' : '';
                                    if ($row->branch_id) {
                                        if ($row->parent_branch_name) {
                                            $branchName = $row->parent_branch_name;
                                        } else {
                                            $branchName = $row->branch_name;
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName . $areaName }}
                            </td>

                            <td>
                                @php
                                    $warehouseCode = $row->warehouse_code ? '-(' . $row->warehouse_code . ')' : '';
                                @endphp
                                {{ $row->warehouse_name . $warehouseCode }}
                            </td>

                            <td class="text-start main_td">
                                @php
                                    $type = $productLedgerService->voucherType($row->voucher_type);
                                @endphp
                                {!! '<strong>' . $type['name'] . '</strong>' !!}
                            </td>

                            <td class="text-start main_td">{!! $row->{$type['voucher_no']} !!}</td>
                            <td class="text-end fw-bold main_td">
                                {{ $row->in > 0 ? \App\Utils\Converter::format_in_bdt($row->in) : '' }}
                            </td>

                            <td class="text-end fw-bold main_td">
                                {{ $row->out > 0 ? \App\Utils\Converter::format_in_bdt($row->out) : '' }}
                            </td>

                            <td class="text-end fw-bold main_td">
                                @if ($row->running_stock < 0)
                                    ({{ \App\Utils\Converter::format_in_bdt(abs($row->running_stock)) }})
                                @else
                                    {{ \App\Utils\Converter::format_in_bdt($row->running_stock) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center fw-bold">{{ __('Product Summary') }}</th>
                    </tr>

                    <tr>
                        <th class="text-end"></th>
                        <th class="text-end fw-bold">{{ __('In') }}</th>
                        <th class="text-end fw-bold">{{ __('Out') }}</th>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Opening Stock') }}</td>
                        <td class="text-end fw-bold">
                            @if ($amounts['opening_stock'] >= 0)
                                {{ App\Utils\Converter::format_in_bdt($amounts['opening_stock']) }}
                            @endif
                        </td>
                        <td class="text-end fw-bold">
                            @if ($amounts['opening_stock'] < 0)
                                ({{ App\Utils\Converter::format_in_bdt(abs($amounts['opening_stock'])) }})
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Current Total') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_in'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_in']) : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_out'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_out']) : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Closing Stock') }}</td>
                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($amounts['closing_stock']) }}
                        </td>
                        <td class="text-end fw-bold">
                            @if ($amounts['closing_stock'] < 0)
                                ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_stock'])) }})
                            @endif
                        </td>
                    </tr>
                </tbody>
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

@php
    $fileBranchName = $filteredBranchName ? 'Shop/Business: ' . $filteredBranchName : $ownOrParentbranchName;
    $dateRange = $fromDate && $toDate ? '__' . $fromDate . '_To_' . $toDate : '';
    $filename = __('Product Ledger') . $dateRange . '__' . $fileBranchName;
@endphp
<span id="title" class="d-none">{{ $filename }}</span>
<!-- Stock Issue print templete end-->
