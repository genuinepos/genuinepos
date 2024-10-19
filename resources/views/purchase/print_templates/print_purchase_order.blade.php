@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $order?->supplier;
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
    <!-- Purchase Order print templete-->
    <div class="purchase_order_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($order->branch)

                        @if ($order?->branch?->parent_branch_id)

                            @if ($order->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($order->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->name }}</span>
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
                    <p style="text-transform: uppercase;font-size:10px!important;" class="p-0 m-0">
                        <strong>
                            @if ($order?->branch)
                                @if ($order?->branch?->parent_branch_id)
                                    {{ $order?->branch?->parentBranch?->name }}
                                @else
                                    {{ $order?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($order?->branch)
                            {{ $order->branch->city . ', ' . $order->branch->state . ', ' . $order->branch->zip_code . ', ' . $order->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($order?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $order?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $order?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;">{{ __('Purchase Order') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Supplier') }} : </span>{{ $order?->supplier?->name }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Address') }} : </span>{{ $order?->supplier?->address }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Phone') }} : </span>{{ $order?->supplier?->phone }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('P/o ID') }} : </span> {{ $order->invoice_id }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('P/o Date') }} : </span>{{ date($dateFormat, strtotime($order->date)) }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $order?->admin?->prefix . ' ' . $order?->admin?->name . ' ' . $order?->admin?->last_name }}
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Delivery Date') }} : </span>{{ $order->delivery_date ? date($dateFormat, strtotime($order->delivery_date)) : '' }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Receiving Status') }} : </span>{{ $order->po_receiving_status }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $order->total_purchase_amount - $order->total_return_amount;
                            @endphp
                            @if ($order->due <= 0)
                                {{ __('Paid') }}
                            @elseif($order->due > 0 && $order->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $order->due)
                                {{ __('Due') }}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Description') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Ordered Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Received Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Pending Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Unit Cost(Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Unit Cost(Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($order->purchaseOrderProducts as $orderProduct)
                            <tr>
                                @php
                                    $variant = $orderProduct?->variant ? ' - ' . $orderProduct?->variant?->variant_name : '';
                                    $productCode = $orderProduct?->variant ? $orderProduct?->variant?->variant_code : $orderProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:10px!important;">
                                    {{ $orderProduct->product->name . ' ' . $variant }}
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' .__('P/c') . ': ' . $productCode . '</span>' !!}
                                    {!! $orderProduct->description1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">'. $orderProduct->description . '</span>' : '' !!}
                                </td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->ordered_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->received_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->pending_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_cost_exc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount) }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ '(' . $orderProduct->unit_tax_percent . '%)=' . $orderProduct->unit_tax_amount }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->net_unit_cost) }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->line_total) }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6">
                    <p style="font-size:10px!important;"><strong>{{ __('Order Note') }} : </strong> </p>
                    <p style="font-size:10px!important;">{{ $order->purchase_note }}</p><br>
                    <p style="font-size:10px!important;"><strong>{{ __('Shipment Details') }} : </strong> </p>
                    <p style="font-size:10px!important;">{{ $order->shipment_details }}</p>
                </div>

                <div class="col-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Total Item & Ordered Qty') }} :</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    ({{ $order->total_item }}) / ({{ App\Utils\Converter::format_in_bdt($order->po_qty) }}/{{ __("Nos") }})
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Net Total Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Order Discount') }} :
                                    {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}
                                </th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->order_discount) }} {{ $order->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Order Vat/Tax') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ '(' . $order->purchase_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($order->purchase_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Shipment Charge') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Total Ordered Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Paid') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt(isset($payingAmount) ? $payingAmount : $order->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Due (On Order)') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:10px!important;">
                                    @if ($order->due < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($order->due)) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Current Balance') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                    @endif
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">{{ __('Prepared By') }}</p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">{{ __('Checked By') }}</p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">{{ __('Authorized By') }}</p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $order->invoice_id }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
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
    <!-- Purchase print templete end-->
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
            size: a4 portrait landscape;
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
    <!-- Purchase Order print templete-->
    <div class="purchase_order_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($order?->branch)

                        @if ($order?->branch?->parent_branch_id)

                            @if ($order?->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order?->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order?->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($order?->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order?->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order?->branch?->name }}</span>
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
                        <strong>
                            @if ($order?->branch)
                                @if ($order?->branch?->parent_branch_id)
                                    {{ $order?->branch?->parentBranch?->name }}
                                @else
                                    {{ $order?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p style="font-size:9px;">
                        @if ($order?->branch)
                            {{ $order?->branch?->address . ', ' . $order?->branch?->city . ', ' . $order?->branch?->state . ', ' . $order?->branch?->zip_code . ', ' . $order?->branch?->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($order?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $order?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $order?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;">{{ __('Purchase Order') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Supplier') }} : </span>{{ $order?->supplier?->name }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span>{{ $order?->supplier?->address }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }} : </span>{{ $order?->supplier?->phone }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('P/o ID') }} : </span> {{ $order->invoice_id }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('P/o Date') }} : </span>{{ date($dateFormat, strtotime($order->date)) }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $order?->admin?->prefix . ' ' . $order?->admin?->name . ' ' . $order?->admin?->last_name }}
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Delivery Date') }} : </span>{{ $order->delivery_date ? date($dateFormat, strtotime($order->delivery_date)) : '' }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Receiving Status') }} : </span>{{ $order->po_receiving_status }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $order->total_purchase_amount - $order->total_return_amount;
                            @endphp
                            @if ($order->due <= 0)
                                {{ __('Paid') }}
                            @elseif($order->due > 0 && $order->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $order->due)
                                {{ __('Due') }}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Description') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Ordered Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Received Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Pending Qty') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost(Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost(Inc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($order->purchaseOrderProducts as $orderProduct)
                            <tr>
                                @php
                                    $variant = $orderProduct?->variant ? ' - ' . $orderProduct?->variant?->variant_name : '';
                                    $productCode = $orderProduct?->variant ? $orderProduct?->variant?->variant_code : $orderProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">
                                    {{ Str::limit($orderProduct->product->name, 25) . ' ' . $variant }}
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' .__('P/c') . ': ' . $productCode . '</span>' !!}
                                    {!! $orderProduct->description1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">'. $orderProduct->description . '</span>' : '' !!}
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->ordered_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->received_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->pending_quantity) }}/{{ $orderProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_cost_exc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ '(' . $orderProduct->unit_tax_percent . '%)=' . $orderProduct->unit_tax_amount }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->net_unit_cost) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->line_total) }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6">
                    <p style="font-size:9px!important;"><strong>{{ __('Order Note') }} : </strong> </p>
                    <p style="font-size:9px!important;">{{ $order->purchase_note }}</p><br>
                    <p style="font-size:9px!important;"><strong>{{ __('Shipment Details') }} : </strong> </p>
                    <p style="font-size:9px!important;">{{ $order->shipment_details }}</p>
                </div>

                <div class="col-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Item & Ordered Qty') }} :</th>
                                <td class="text-end" style="font-size:9px!important;">
                                    ({{ $order->total_item }}) / ({{ App\Utils\Converter::format_in_bdt($order->po_qty) }}/{{ __("Nos") }})
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Order Discount') }} :
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                </th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->order_discount) }} {{ $order->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Order Vat/Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ '(' . $order->purchase_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($order->purchase_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Ordered Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt(isset($payingAmount) ? $payingAmount : $order->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Order)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td colspan="2" class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($order->due < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($order->due)) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                    @endif
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
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $order->invoice_id }}</p>
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
    <!-- Purchase print templete end-->
@endif
