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
    <!-- Stock Issue print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($stockIssue->branch)

                        @if ($stockIssue?->branch?->parent_branch_id)

                            @if ($stockIssue->branch?->parentBranch?->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $stockIssue?->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $stockIssue?->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($stockIssue->branch?->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $stockIssue?->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $stockIssue?->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @php
                            $branchName = '';
                        @endphp
                        @if ($stockIssue?->branch)
                            @if ($stockIssue?->branch?->parent_branch_id)

                                {{ $stockIssue?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $stockIssue?->branch?->parentBranch?->name . '(' . $stockIssue?->branch?->area_name . ')';
                                @endphp
                            @else

                                {{ $stockIssue?->branch?->name }}
                                @php
                                    $branchName = $stockIssue?->branch?->name . '(' . $stockIssue?->branch?->area_name . ')';
                                @endphp
                            @endif
                        @else

                            {{ $generalSettings['business_or_shop__business_name'] }}
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                    </p>

                    <p>
                        @if ($stockIssue?->branch)

                            {{ $stockIssue->branch->city . ', ' . $stockIssue->branch->state . ', ' . $stockIssue->branch->zip_code . ', ' . $stockIssue->branch->country }}
                        @else

                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($stockIssue?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $stockIssue?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $stockIssue?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;" class="fw-bold">{{ __('Stock Issue Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($stockIssue->date)) }}
                        </li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $stockIssue->voucher_no }}</li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Department') }} : </span>{{ $stockIssue?->department?->name }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Reported By') }} : </span>{{ $stockIssue?->reportedBy?->prefix . ' ' . $stockIssue?->reportedBy?->name . ' ' . $stockIssue?->reportedBy?->last_name }}</li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $stockIssue?->createdBy?->prefix . ' ' . $stockIssue?->createdBy?->name . ' ' . $stockIssue?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Stock Location') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        @foreach ($stockIssue->stockIssuedProducts as $issuedProduct)
                            <tr>
                                @php
                                    $variant = $issuedProduct?->variant ? ' - ' . $issuedProduct->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $loop->index + 1 }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $issuedProduct->product->name . ' ' . $variant }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    @if ($issuedProduct?->warehouse)
                                        {{ $issuedProduct?->warehouse?->warehouse_name.'/'.$issuedProduct?->warehouse?->warehouse_code }}
                                    @else
                                        @if ($stockIssue?->branch)

                                            @if ($stockIssue?->branch?->parent_branch_id)

                                                {{ $stockIssue?->branch?->parentBranch?->name }}
                                            @else

                                                {{ $stockIssue?->branch?->name }}
                                            @endif
                                        @else

                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                        @endif
                                    @endif
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($issuedProduct->quantity) }}/{{ $issuedProduct?->unit?->code_name }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($issuedProduct->unit_cost_inc_tax) }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($issuedProduct->subtotal) }}
                                </td>
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
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Item') }} : </th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $stockIssue->total_item }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Qty') }} : </th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($stockIssue->total_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($stockIssue->net_total_amount) }}
                                </td>
                            </tr>
                        </thead>
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($stockIssue->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $stockIssue->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <strong>{{ __('SpeedDigit Software Solution.') }}</strong></small>
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
        $filename = __('Stock Issue') . '__' . $stockIssue->voucher_no . '__' . $stockIssue->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
    <!-- Stock issue print templete end-->
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
    <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($stockIssue->branch)

                        @if ($stockIssue?->branch?->parent_branch_id)

                            @if ($stockIssue->branch?->parentBranch?->logo != 'default.png')
                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $stockIssue?->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $stockIssue?->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($stockIssue->branch?->logo != 'default.png')
                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $stockIssue?->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $stockIssue?->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
                        @php
                            $branchName = '';
                        @endphp
                        @if ($stockIssue?->branch)
                            @if ($stockIssue?->branch?->parent_branch_id)
                                {{ $stockIssue?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $stockIssue?->branch?->parentBranch?->name . '(' . $stockIssue?->branch?->area_name . ')';
                                @endphp
                            @else
                                {{ $stockIssue?->branch?->name }}
                                @php
                                    $branchName = $stockIssue?->branch?->name . '(' . $stockIssue?->branch?->area_name . ')';
                                @endphp
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($stockIssue?->branch)
                            {{ $stockIssue->branch->city . ', ' . $stockIssue->branch->state . ', ' . $stockIssue->branch->zip_code . ', ' . $stockIssue->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($stockIssue?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $stockIssue?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $stockIssue?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;" class="fw-bold">{{ __('Stock Issue Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Date') }} : </span>{{ date($dateFormat, strtotime($stockIssue->date)) }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Reported By') }} : </span>{{ $stockIssue?->reportedBy?->prefix . ' ' . $stockIssue?->reportedBy?->name . ' ' . $stockIssue?->reportedBy?->last_name }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Department') }} : </span>{{ $stockIssue?->department?->name }}
                        </li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Reported By') }} : </span>{{ $stockIssue?->reportedBy?->prefix . ' ' . $stockIssue?->reportedBy?->name . ' ' . $stockIssue?->reportedBy?->last_name }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $stockIssue?->createdBy?->prefix . ' ' . $stockIssue?->createdBy?->name . ' ' . $stockIssue?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1">
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
                    <tbody>
                        @foreach ($stockIssue->stockIssuedProducts as $issuedProduct)
                            <tr>
                                @php
                                    $variant = $issuedProduct->variant ? ' - ' . $issuedProduct->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">
                                    <p>{{ $loop->index + 1 }}</p>
                                </td>

                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $issuedProduct->product->name . ' ' . $variant }}
                                </td>

                                <td class="text-start" style="font-size:9px!important;">
                                    @if ($issuedProduct?->stockWarehouse)

                                        {{ $issuedProduct?->stockWarehouse?->warehouse_name.'/'.$issuedProduct?->stockWarehouse?->warehouse_code }}
                                    @else

                                        @if ($stockIssue?->branch)

                                            @if ($stockIssue?->branch?->parent_branch_id)

                                                {{ $stockIssue?->branch?->parentBranch?->name . '(' . $stockIssue?->branch?->area_name . ')' }}
                                            @else

                                                {{ $stockIssue?->branch?->name . '(' . $stockIssue?->branch?->area_name . ')' }}
                                            @endif
                                        @else

                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                        @endif
                                    @endif
                                </td>

                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($issuedProduct->quantity) }}/{{ $issuedProduct?->unit?->code_name }}
                                </td>

                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($issuedProduct->unit_cost_int_tax) }}
                                </td>

                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($issuedProduct->subtotal) }}</td>
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
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Item') }} : </th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($stockIssue->total_item) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Item') }} : </th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($stockIssue->total_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($stockIssue->net_total_amount) }}
                                </td>
                            </tr>
                        </thead>
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($stockIssue->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $stockIssue->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <strong>{{ __("SpeedDigit Software Solution.") }}</strong></small>
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
        $filename = __('Stock Issue') . '__' . $stockIssue->voucher_no . '__' . $stockIssue->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
    <!-- Stock Issue print templete end-->
@endif
