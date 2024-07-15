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
    <!-- Transfer Stock print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($transferStock->branch)

                        @if ($transferStock?->branch?->parent_branch_id)

                            @if ($transferStock->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $transferStock->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $transferStock->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($transferStock->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $transferStock->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $transferStock->branch?->name }}</span>
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
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @if ($transferStock?->branch)
                            @if ($transferStock?->branch?->parent_branch_id)
                                {{ $transferStock?->branch?->parentBranch?->name }}
                            @else
                                {{ $transferStock?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p>
                        @if ($transferStock?->branch)
                            {{ $transferStock->branch->city . ', ' . $transferStock->branch->state . ', ' . $transferStock->branch->zip_code . ', ' . $transferStock->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($transferStock?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $transferStock?->branch?->email }}
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $transferStock?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;">{{ __('Transfer Stock Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Send From') }} : </span>
                            @if ($transferStock?->senderBranch)
                                @if ($transferStock?->senderBranch?->parent_branch_id)
                                    {{ $transferStock?->senderBranch?->parentBranch?->name }}
                                @else
                                    {{ $transferStock?->senderBranch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </li>

                        @if ($transferStock?->senderWarehouse)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Send At') }} : </span>
                                {{ $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse->warehouse_code . ')' }}
                            </li>
                        @endif

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Send To') }} : </span>
                            @if ($transferStock?->receiverBranch)
                                @if ($transferStock?->receiverBranch?->parent_branch_id)
                                    {{ $transferStock?->receiverBranch?->parentBranch?->name }}
                                @else
                                    {{ $transferStock?->receiverBranch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </li>

                        @if ($transferStock?->receiverWarehouse)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Receive At') }} : </span>
                                {{ $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse->warehouse_code . ')' }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($transferStock->date)) }}
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $transferStock->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Receiving Status') }} : </span>{{ App\Enums\TransferStockReceiveStatus::tryFrom($transferStock->receive_status)->name }}</li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $transferStock?->sendBy?->prefix . ' ' . $transferStock?->sendBy?->name . ' ' . $transferStock?->sendBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Send Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Received Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Pending Qty') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                            <tr>
                                @php
                                    $variant = $transferStockProduct->variant ? ' - ' . $transferStockProduct->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:11px!important;">
                                    <p>{{ Str::limit($transferStockProduct->product->name, 25) . ' ' . $variant }}</p>
                                </td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->send_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStockProduct->unit_cost_inc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->subtotal) }} </td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->received_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->pending_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
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
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Send Qty') }} : </th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_send_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Received Qty') }} : </th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_received_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Pending Qty') }} : </th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_pending_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Stock Value') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transferStock->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p><b>{{ $transferStock->voucher_no }}</b></p>
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
    <!-- Transfer Stock print templete end-->
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
    <!-- Transfer Stock print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($transferStock->branch)

                        @if ($transferStock?->branch?->parent_branch_id)

                            @if ($transferStock->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $transferStock->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $transferStock->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($transferStock->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $transferStock->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $transferStock->branch?->name }}</span>
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
                    <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
                        @if ($transferStock?->branch)
                            @if ($transferStock?->branch?->parent_branch_id)
                                {{ $transferStock?->branch?->parentBranch?->name }}
                            @else
                                {{ $transferStock?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($transferStock?->branch)
                            {{ $transferStock->branch->city . ', ' . $transferStock->branch->state . ', ' . $transferStock->branch->zip_code . ', ' . $transferStock->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($transferStock?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $transferStock?->branch?->email }}
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $transferStock?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;">{{ __('Transfer Stock Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Send From') }} : </span>
                            <b>
                                @if ($transferStock?->senderBranch)
                                    @if ($transferStock?->senderBranch?->parent_branch_id)
                                        {{ $transferStock?->senderBranch?->parentBranch?->name }}
                                    @else
                                        {{ $transferStock?->senderBranch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </b>
                        </li>

                        @if ($transferStock?->senderWarehouse)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Send At') }} : </span>
                                {{ $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse->warehouse_code . ')' }}
                            </li>
                        @endif

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Send To') }} : </span>
                            @if ($transferStock?->receiverBranch)
                                @if ($transferStock?->receiverBranch?->parent_branch_id)
                                    {{ $transferStock?->receiverBranch?->parentBranch?->name }}
                                @else
                                    {{ $transferStock?->receiverBranch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </li>

                        @if ($transferStock?->receiverWarehouse)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Receive At') }} : </span>
                                {{ $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse?->warehouse_code . ')' }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($transferStock->date)) }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $transferStock->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Receiving Status') }} : </span>{{ App\Enums\TransferStockReceiveStatus::tryFrom($transferStock->receive_status)->name }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $transferStock?->sendBy?->prefix . ' ' . $transferStock?->sendBy?->name . ' ' . $transferStock?->sendBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Product') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Send Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Received Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Pending Qty') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                            <tr>
                                @php
                                    $variant = $transferStockProduct->variant ? ' - ' . $transferStockProduct->variant->variant_name : '';
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">
                                    <p>{{ Str::limit($transferStockProduct->product->name, 25) . ' ' . $variant }}</p>
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->send_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStockProduct->unit_cost_inc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->subtotal) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->received_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->pending_qty) . '/' . $transferStockProduct?->unit?->code_name }}</td>
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
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Send Qty') }} : </th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_send_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Received Qty') }} : </th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_received_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Pending Qty') }} : </th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_pending_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Stock Value') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transferStock->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $transferStock->voucher_no }}</p>
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
    <!-- Transfer Stock print templete end-->
@endif
