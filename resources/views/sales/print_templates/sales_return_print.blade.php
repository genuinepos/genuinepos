@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $return?->customer;
    $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
    $branchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
    $__branchId = $account?->group?->sub_sub_group_number == 6 ? $branchId : '';
    $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: null, toDate: null, branchId: $__branchId);
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
            bottom: 25px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>

    <!-- Sales Return Voucher print templete-->
    <div class="sales_return_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($return->branch)

                        @if ($return?->branch?->parent_branch_id)

                            @if ($return->branch?->parentBranch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($return->branch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->name }}</span>
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
                        @if ($return?->branch)
                            @if ($return?->branch?->parent_branch_id)
                                {{ $return?->branch?->parentBranch?->name }}
                            @else
                                {{ $return?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p>
                        @if ($return?->branch)
                            {{ $return->branch->city . ', ' . $return->branch->state . ', ' . $return->branch->zip_code . ', ' . $return->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($return?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $return?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $return?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Sales Return Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>{{ $return->date }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $return->voucher_no }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Customer') }} : </span> {{ $return?->customer?->name }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled float-right">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Sale Invoice Details') }} : </span></li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Invoice ID') }} : </span>{{ $return->sale ? $return->sale->invoice_id : 'N/A' }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Sale Date') }} : </span>{{ $return->sale ? $return->sale->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Return Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Price(Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Price(Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="purchase_return_print_product_list">
                        @foreach ($return->saleReturnProducts as $saleReturnProduct)
                            @if ($saleReturnProduct->return_qty > 0)
                                <tr>
                                    @php
                                        $variant = $saleReturnProduct->variant ? ' - ' . $saleReturnProduct->variant->variant_name : '';
                                    @endphp
                                    <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $saleReturnProduct->product->name . $variant }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->return_qty) }}/{{ $saleReturnProduct?->unit?->code_name }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_exc_tax) }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_discount_amount) }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ '(' . $saleReturnProduct->unit_tax_percent . ')=' . App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_tax_amount) }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_inc_tax) }}
                                    </td>

                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $saleReturnProduct->return_subtotal }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    <b>{{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}</b>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Return Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($return->return_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($return->return_discount) }}
                                    @else
                                        ({{ App\Utils\Converter::format_in_bdt($return->return_discount) }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($return->return_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Return Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ '(' . $return->return_tax_percent . '%)=' . $return->return_tax_amount }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Returned Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid (Against Return)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($paidAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due (On Return Voucher)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($return->due) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $amounts['closing_balance_in_flat_amount_string'] }}
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $return->voucher_no }}</p>
                </div>
            </div>


            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <span>{{ __('SpeedDigit Software Solution') }}.</span></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    <!-- Sales Return Voucher print templete-->
    <div class="sales_return_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($return->branch)

                        @if ($return?->branch?->parent_branch_id)

                            @if ($return->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($return->branch?->logo)
                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->name }}</span>
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
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @if ($return?->branch)
                            @if ($return?->branch?->parent_branch_id)
                                {{ $return?->branch?->parentBranch?->name }}
                            @else
                                {{ $return?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size: 9px;">
                        @if ($return?->branch)
                            {{ $return->branch->city . ', ' . $return->branch->state . ', ' . $return->branch->zip_code . ', ' . $return->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size: 9px;">
                        @if ($return?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $return?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $return?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 class="fw-bold" style="text-transform: uppercase;">{{ __('Sales Return Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>{{ $return->date }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $return->voucher_no }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Customer') }} : </span> {{ $return?->customer?->name }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled float-right">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Sale Invoice Details') }} : </span></li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Invoice ID') }} : </span> {{ $return->sale ? $return->sale->invoice_id : 'N/A' }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Sale Date') }} : </span>{{ $return->sale ? $return->sale->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Return Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Price(Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Price(Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="purchase_return_print_product_list">
                        @foreach ($return->saleReturnProducts as $saleReturnProduct)
                            @if ($saleReturnProduct->return_qty > 0)
                                <tr>
                                    @php
                                        $variant = $saleReturnProduct->variant ? ' - ' . $saleReturnProduct->variant->variant_name : '';
                                    @endphp
                                    <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $saleReturnProduct->product->name . $variant }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->return_qty) }}/{{ $saleReturnProduct?->unit?->code_name }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_exc_tax) }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_discount_amount) }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ '(' . $saleReturnProduct->unit_tax_percent . ')=' . App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_tax_amount) }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_inc_tax) }}
                                    </td>

                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $saleReturnProduct->return_subtotal }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    <b>{{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}</b>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Return Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($return->return_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($return->return_discount) }}
                                    @else
                                        ({{ App\Utils\Converter::format_in_bdt($return->return_discount) }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($return->return_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Return Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ '(' . $return->return_tax_percent . '%)=' . $return->return_tax_amount }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Returned Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Paid (Against Return)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($paidAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Return Voucher)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($return->due) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $amounts['closing_balance_in_flat_amount_string'] }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px!important">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px!important">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px!important">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $return->voucher_no }}</p>
                </div>
            </div>


            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <span>{{ __('SpeedDigit Software Solution') }}.</span></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
