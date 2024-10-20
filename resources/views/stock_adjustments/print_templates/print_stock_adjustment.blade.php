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
                line-height: 1!important;
                padding: 0px!important;
                margin: 0px!important;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @page {
            size: a4 portrait landscape;
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

    <!-- Stock Adjustment print templete-->
    <div class="print_modal_details">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($adjustment->branch)

                        @if ($adjustment?->branch?->parent_branch_id)

                            @if ($adjustment->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $adjustment->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $adjustment->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($adjustment->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $adjustment->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $adjustment->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;font-size:10px!important;" class="p-0 m-0 fw-bold">
                        @if ($adjustment?->branch)

                            @if ($adjustment?->branch?->parent_branch_id)
                                {{ $adjustment?->branch?->parentBranch?->name }}
                            @else
                                {{ $adjustment?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($adjustment?->branch)
                            {{ $adjustment->branch->city . ', ' . $adjustment->branch->state . ', ' . $adjustment->branch->zip_code . ', ' . $adjustment->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($adjustment?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $adjustment?->branch?->email }},
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $adjustment?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;">{{ __('Stock Adjustment Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($adjustment->date)) }}
                        </li>

                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Voucehr No') }} : </span>{{ $adjustment->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Type') }} : </span>{{ App\Enums\StockAdjustmentType::tryFrom($adjustment->type)->name }}</li>

                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $adjustment?->createdBy?->prefix . ' ' . $adjustment?->createdBy?->name . ' ' . $adjustment?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Stock Location') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($adjustment->adjustmentProducts as $adjustmentProduct)
                            <tr>
                                @php
                                    $variant = $adjustmentProduct->variant ? ' - ' . $adjustmentProduct->variant->variant_name : '';
                                    $productCode = $adjustmentProduct?->variant ? $adjustmentProduct?->variant?->variant_code : $adjustmentProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:10px!important;">{{ $loop->index + 1 }}</td>

                                <td class="text-start" style="font-size:10px!important;">
                                    {{ $adjustmentProduct->product->name . ' ' . $variant }}
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' .__('P/c') . ': ' . $productCode . '</span>' !!}
                                </td>

                                <td class="text-start" style="font-size:10px!important;">
                                    @if ($adjustmentProduct?->warehouse)
                                        {{ $adjustmentProduct?->warehouse?->warehouse_name . '/' . $adjustmentProduct?->warehouse?->warehouse_code . '-(WH)' }}
                                    @else
                                        @if ($adjustmentProduct->branch_id)
                                            @if ($adjustmentProduct?->branch?->parentBranch)
                                                {{ $adjustmentProduct?->branch?->parentBranch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                            @else
                                                {{ $adjustmentProduct?->branch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                            @endif
                                        @else
                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                        @endif
                                    @endif
                                </td>

                                <td class="text-start" style="font-size:10px!important;">{{ $adjustmentProduct->quantity . '/' . $adjustmentProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax) }}
                                </td>

                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Net Total Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustment->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Recovered Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustment->recovered_amount) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:11px!important;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:11px!important;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:11px!important;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($adjustment->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $adjustment->voucher_no }}</p>
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
    <!-- Stock Adjustment print templete end-->
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
                line-height: 1!important;
                padding: 0px!important;
                margin: 0px!important;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @page {
            size: 5.8in 8.3in portrait landscape;
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

    <!-- Stock Adjustment print templete-->
    <div class="print_modal_details">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($adjustment->branch)

                        @if ($adjustment?->branch?->parent_branch_id)

                            @if ($adjustment->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $adjustment->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $adjustment->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($adjustment->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $adjustment->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $adjustment->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase; font-size:9px;" class="p-0 m-0 fw-bold">
                        @if ($adjustment?->branch)

                            @if ($adjustment?->branch?->parent_branch_id)
                                {{ $adjustment?->branch?->parentBranch?->name }}
                            @else
                                {{ $adjustment?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($adjustment?->branch)
                            {{ $adjustment->branch->city . ', ' . $adjustment->branch->state . ', ' . $adjustment->branch->zip_code . ', ' . $adjustment->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($adjustment?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $adjustment?->branch?->email }},
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $adjustment?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;">{{ __('Stock Adjustment Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($adjustment->date)) }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Voucehr No') }} : </span>{{ $adjustment->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Type') }} : </span>{{ App\Enums\StockAdjustmentType::tryFrom($adjustment->type)->name }}</li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $adjustment?->createdBy?->prefix . ' ' . $adjustment?->createdBy?->name . ' ' . $adjustment?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Stock Location') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($adjustment->adjustmentProducts as $adjustmentProduct)
                            <tr>
                                @php
                                    $variant = $adjustmentProduct->variant ? ' - ' . $adjustmentProduct->variant->variant_name : '';
                                    $productCode = $adjustmentProduct?->variant ? $adjustmentProduct?->variant?->variant_code : $adjustmentProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>

                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $adjustmentProduct->product->name . ' ' . $variant }}
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' .__('P/c') . ': ' . $productCode . '</span>' !!}
                                </td>

                                <td class="text-start" style="font-size:9px!important;">
                                    @if ($adjustmentProduct?->warehouse)
                                        {{ $adjustmentProduct?->warehouse?->warehouse_name . '/' . $adjustmentProduct?->warehouse?->warehouse_code . '-(WH)' }}
                                    @else
                                        @if ($adjustmentProduct->branch_id)
                                            @if ($adjustmentProduct?->branch?->parentBranch)
                                                {{ $adjustmentProduct?->branch?->parentBranch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                            @else
                                                {{ $adjustmentProduct?->branch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                            @endif
                                        @else
                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                        @endif
                                    @endif
                                </td>

                                <td class="text-start" style="font-size:9px!important;">{{ $adjustmentProduct->quantity . '/' . $adjustmentProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax) }}
                                </td>

                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Net Total Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustment->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Recovered Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($adjustment->recovered_amount) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:9px;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:9px;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:9px;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($adjustment->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $adjustment->voucher_no }}</p>
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
    <!-- Stock Adjustment print templete end-->
@endif
