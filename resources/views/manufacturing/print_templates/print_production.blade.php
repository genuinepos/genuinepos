@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value)
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
                page-break-after: auto;
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
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>

    <div class="production_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($production->branch)

                        @if ($production?->branch?->parent_branch_id)

                            @if ($production->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $production->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $production->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($production->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $production->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $production->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0">
                        @php
                            $branchName = '';
                        @endphp
                        @if ($production?->branch)
                            @if ($production?->branch?->parent_branch_id)
                                @php
                                    $branchName = $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')';
                                @endphp
                                <span class="fw-bold">{{ $production?->branch?->parentBranch?->name }}</span>
                            @else
                                @php
                                    $branchName = $production?->branch?->name . '(' . $production?->branch?->area_name . ')';
                                @endphp
                                <span class="fw-bold">{{ $production?->branch?->name }}</span>
                            @endif
                        @else
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                            <span class="fw-bold">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    </p>

                    <p>
                        @if ($production?->branch)
                            {{ $production->branch->address . ', ' . $production->branch->city . ', ' . $production->branch->state . ', ' . $production->branch->zip_code . ', ' . $production->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($production?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $production?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $production?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;"><span class="fw-bold">{{ __('Production Voucher') }}</span></h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Stored Location') }} : </span>
                            @if ($production->storeWarehouse)

                                {{ $production->storeWarehouse->warehouse_name . '/' . $production->storeWarehouse->warehouse_code }}<b>({{ __('WH') }})</b>
                            @else
                                @if ($production->branch_id)

                                    @if ($production?->branch?->parentBranch)
                                        {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Ingredients Stock Location') }} : </span>
                            @if ($production->stockWarehouse)

                                {{ $production->stockWarehouse->warehouse_name . '/' . $production->stockWarehouse->warehouse_code }}<b>({{ __('WH)') }})</b>
                            @else
                                @if ($production->branch_id)

                                    @if ($production?->branch?->parentBranch)
                                        {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Mfd. Product') }} : </span>
                            {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Status') }} : </span>
                            {{ \App\Enums\ProductionStatus::tryFrom($production->status)->name }}
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No ') }} : </span> {{ $production->voucher_no }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>{{ date($dateFormat . ' ' . $timeFormat, strtotime($production->date_ts)) }}</li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-2 pb-2">
                <p style="font-size:11px!important;"><span class="fw-bold">{{ __('Ingredients List') }}</span></p>
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Ingredient Name') }}</th>
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Input Qty') }}</th>
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php
                            $totalIngredientCost = 0;
                        @endphp
                        @foreach ($production->ingredients as $ingredient)
                            <tr>
                                @php
                                    $variant = $ingredient->variant_id ? ' -' . $ingredient->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:11px!important;">{{ Str::limit($ingredient->product->name, 40) . ' ' . $variant }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ $ingredient->final_qty . '/' . $ingredient?->unit?->code_name }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_tax_amount) }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}
                                    @php
                                        $totalIngredientCost += $ingredient->subtotal;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th colspan="5" class="text-end" style="font-size:11px!important;">{{ __('Total Ingredient Cost') }} ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                        <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($totalIngredientCost) }}</th>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p style="font-size:11px!important;"><span class="fw-bold">{{ __('Production Quantity And Net Cost') }}</span></p>
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Output Qty') }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $production->total_output_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Wasted Qty') }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $production->total_wasted_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Final Output Qty') }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $production->total_final_output_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Additional Production Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->additional_production_cost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->net_cost) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 text-end">
                    <p style="font-size:11px!important;"><span class="fw-bold">{{ __('Product Costing And Pricing') }}</span></p>
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Per Unit Cost Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_exc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ '(' . $production->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($production->unit_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Per Unit Cost Inc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Profit Margin') }}(%)</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $production->profit_margin }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Selling Price Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_price_exc_tax) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($production->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $production->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('speeddigit.show_app_info_in_print') == true)
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $product = $production->product->name . ' ' . ($production->variant_id ? '-' . $production->variant->variant_name : '') . ' (' . ($production->variant_id ? $production->variant->variant_code : $production->product->product_code) . ')';
        $filename = __('Production') . '__' . $production->voucher_no . '__' . $product . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
@else
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
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @page {
            size: 5.8in 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>

    <div class="production_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($production->branch)

                        @if ($production?->branch?->parent_branch_id)

                            @if ($production->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $production->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $production->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($production->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $production->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $production->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
                        @php
                            $branchName = '';
                        @endphp
                        @if ($production?->branch)
                            @if ($production?->branch?->parent_branch_id)
                                @php
                                    $branchName = $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')';
                                @endphp
                                {{ $production?->branch?->parentBranch?->name }}
                            @else
                                @php
                                    $branchName = $production?->branch?->name . '(' . $production?->branch?->area_name . ')';
                                @endphp
                                {{ $production?->branch?->name }}
                            @endif
                        @else
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($production?->branch)
                            {{ $production->branch->address . ', ' . $production->branch->city . ', ' . $production->branch->state . ', ' . $production->branch->zip_code . ', ' . $production->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($production?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $production?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $production?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;"><span class="fw-bold">{{ __('Production Voucher') }}</span></h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Stored Location') }} : </span>
                            @if ($production->storeWarehouse)

                                {{ $production->storeWarehouse->warehouse_name . '/' . $production->storeWarehouse->warehouse_code }}<b>({{ __('WH') }})</b>
                            @else
                                @if ($production->branch_id)

                                    @if ($production?->branch?->parentBranch)
                                        {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Ingredients Stock Location') }} : </span>
                            @if ($production->stockWarehouse)

                                {{ $production->stockWarehouse->warehouse_name . '/' . $production->stockWarehouse->warehouse_code }}<b>({{ __('WH)') }})</b>
                            @else
                                @if ($production->branch_id)

                                    @if ($production?->branch?->parentBranch)
                                        {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Mfd. Product') }} : </span>
                            {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Status') }} : </span>
                            {{ \App\Enums\ProductionStatus::tryFrom($production->status)->name }}
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Voucher No') }} : </span> {{ $production->voucher_no }}
                        </li>
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Date') }} : </span>{{ date($dateFormat . ' ' . $timeFormat, strtotime($production->date_ts)) }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-2 pb-2">
                <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Ingredients List') }}</span></p>
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Ingredient Name') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Input Qty') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php
                            $totalIngredientCost = 0;
                        @endphp
                        @foreach ($production->ingredients as $ingredient)
                            <tr>
                                @php
                                    $variant = $ingredient->variant_id ? ' -' . $ingredient->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">{{ Str::limit($ingredient->product->name, 40) . ' ' . $variant }}</td>
                                <td class="text-end" style="font-size:9px!important;">{{ $ingredient->final_qty . '/' . $ingredient?->unit?->code_name }}</td>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                <td class="text-end" style="font-size:9px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_tax_amount) }}</td>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}
                                    @php
                                        $totalIngredientCost += $ingredient->subtotal;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th colspan="5" class="text-end" style="font-size:9px!important;">{{ __('Total Ingredient Cost') }} ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                        <th class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($totalIngredientCost) }}</th>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Production Quantity And Net Cost') }}</span></p>
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Output Qty') }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $production->total_output_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Wasted Qty') }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $production->total_wasted_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Final Output Qty') }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $production->total_final_output_quantity . '/' . $production?->unit?->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Additional Production Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->additional_production_cost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($production->net_cost) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 text-end">
                    <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Product Costing And Pricing') }}</span></p>
                    <table class="table print-table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Per Unit Cost Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_exc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Vat/Tax') }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ '(' . $production->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($production->unit_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Per Unit Cost Inc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Profit Margin') }}(%)</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $production->profit_margin }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Selling Price Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($production->per_unit_price_exc_tax) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px!important;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($production->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $production->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('speeddigit.show_app_info_in_print') == true)
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $product = $production->product->name . ' ' . ($production->variant_id ? '-' . $production->variant->variant_name : '') . ' (' . ($production->variant_id ? $production->variant->variant_code : $production->product->product_code) . ')';
        $filename = __('Production') . '__' . $production->voucher_no . '__' . $product . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
@endif
