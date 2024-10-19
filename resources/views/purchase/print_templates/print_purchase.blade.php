@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $purchase?->supplier;
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
                line-height: 1 !important;
                padding: 0px !important;
                margin: 0px !important;
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
    <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($purchase->branch)

                        @if ($purchase?->branch?->parent_branch_id)

                            @if ($purchase->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $purchase?->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $purchase?->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($purchase->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $purchase?->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $purchase?->branch?->name }}</span>
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
                        @if ($purchase?->branch)
                            @if ($purchase?->branch?->parent_branch_id)
                                {{ $purchase?->branch?->parentBranch?->name }}
                            @else
                                {{ $purchase?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($purchase?->branch)
                            {{ $purchase->branch->address . ', ' . $purchase->branch->city . ', ' . $purchase->branch->state . ', ' . $purchase->branch->zip_code . ', ' . $purchase->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:10px!important;">
                        @if ($purchase?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $purchase?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $purchase?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;" class="fw-bold">{{ __('Purchase Invoice') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Supplier') }} : </span>{{ $purchase->supplier->name }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Address') }} : </span>{{ $purchase->supplier->address }}</li>
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Phone') }} : </span>{{ $purchase->supplier->phone }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($purchase->date)) }}
                        </li>

                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Invoice ID') }} : </span>{{ $purchase->invoice_id }}</li>

                        @if ($purchase?->purchaseOrder)
                            <li style="font-size:10px!important;"><span class="fw-bold">{{ __('P/o ID') }} : </span>{{ $purchase?->purchaseOrder?->invoice_id }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                            @endphp
                            @if ($purchase->due <= 0)
                                {{ __('Paid') }}
                            @elseif($purchase->due > 0 && $purchase->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $purchase->due)
                                {{ __('Due') }}
                            @endif
                        </li>

                        <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $purchase?->admin?->prefix . ' ' . $purchase?->admin?->name . ' ' . $purchase?->admin?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1 pb-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Description') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Unit Cost (Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Unit Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Net Unit Cost') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Lot No.') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchaseProducts as $purchaseProduct)
                            @if ($purchase?->purchaseOrder && $purchaseProduct->quantity <= 0)
                                @continue
                            @endif
                            <tr>
                                @php
                                    $variant = $purchaseProduct->variant ? ' - ' . $purchaseProduct->variant->variant_name : '';
                                    $productCode = $purchaseProduct?->variant ? $purchaseProduct?->variant?->variant_code : $purchaseProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:10px!important;">{{ $loop->index + 1 }}</td>

                                <td class="text-start" style="font-size:10px!important;">
                                    <p>{{ $purchaseProduct->product->name . ' ' . $variant }}</p>
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' !!}
                                    @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                        <small class="d-block text-muted" style="font-size: 8px!important;">{{ __('Batch No') }} : {{ $purchaseProduct->batch_number }}, {{ __('Expire Date') }} : {{ $purchaseProduct->expire_date ? date($dateFormat, strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                    @endif
                                    <small class="d-block text-muted" style="font-size: 8px!important;">{!! $purchaseProduct->description ? $purchaseProduct->description : '' !!}</small>
                                </td>
                                <td class="text-start" style="font-size:10px!important;">{{ $purchaseProduct->quantity }}/{{ $purchaseProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_exc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }} </td>
                                <td class="text-start" style="font-size:10px!important;">{{ '(' . $purchaseProduct->unit_tax_percent . '%)=' . $purchaseProduct->unit_tax_amount }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
                                <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
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
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Total Item & Qty') }} :</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    ({{ $purchase->total_item }}) / ({{ App\Utils\Converter::format_in_bdt($purchase->total_qty) }}/{{ __("Nos") }})
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Net Total Amount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Purchase Discount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    @if ($purchase->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}
                                    @else
                                        ({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Purchase Vat/Tax') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ '(' . $purchase->purchase_tax_percent . '%)=' . $purchase->purchase_tax_amount }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Shipment Charge') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Total Purchased Amount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Paid') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt(isset($payingAmount) ? $payingAmount : $purchase->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Due (On Invoice)') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:10px!important;">
                                    @if ($purchase->due < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($purchase->due)) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Current Balance') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end fw-bold" style="font-size:10px!important;">
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
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:10px!important;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:10px!important;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;font-size:10px!important;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $purchase->invoice_id }}</p>
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
                line-height: 1 !important;
                padding: 0px !important;
                margin: 0px !important;
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
    <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($purchase->branch)

                        @if ($purchase?->branch?->parent_branch_id)

                            @if ($purchase->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $purchase?->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $purchase?->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($purchase->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $purchase?->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $purchase?->branch?->name }}</span>
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
                        @if ($purchase?->branch)
                            @if ($purchase?->branch?->parent_branch_id)
                                {{ $purchase?->branch?->parentBranch?->name }}
                            @else
                                {{ $purchase?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($purchase?->branch)
                            {{ $purchase->branch->address . ', ' . $purchase->branch->city . ', ' . $purchase->branch->state . ', ' . $purchase->branch->zip_code . ', ' . $purchase->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($purchase?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $purchase?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $purchase?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;" class="fw-bold">{{ __('Purchase Invoice') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Supplier') }} : </span>{{ $purchase->supplier->name }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span>{{ $purchase->supplier->address }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }} : </span>{{ $purchase->supplier->phone }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($purchase->date)) }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Invoice ID') }} : </span>{{ $purchase->invoice_id }}</li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                            @endphp
                            @if ($purchase->due <= 0)
                                {{ __('Paid') }}
                            @elseif($purchase->due > 0 && $purchase->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $purchase->due)
                                {{ __('Due') }}
                            @endif
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $purchase?->admin?->prefix . ' ' . $purchase?->admin?->name . ' ' . $purchase?->admin?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-1">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Description') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Cost (Exc. Tax)') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Unit Discount') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Net Unit Cost') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Lot Number') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchaseProducts as $purchaseProduct)
                            <tr>
                                @php
                                    $variant = $purchaseProduct->variant ? ' - ' . $purchaseProduct->variant->variant_name : '';
                                    $productCode = $purchaseProduct?->variant ? $purchaseProduct?->variant?->variant_code : $purchaseProduct?->product?->product_code;
                                @endphp

                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>

                                <td class="text-start" style="font-size:9px!important;">
                                    <p>{{ $purchaseProduct->product->name . ' ' . $variant }}</p>
                                    {!! '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' !!}
                                    <small class="d-block text-muted" style="font-size: 8px!important;">{!! $purchaseProduct->description ? $purchaseProduct->description : '' !!}</small>
                                    @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                        <small class="d-block text-muted" style="font-size: 8px!important;">{{ __('Batch No') }} : {{ $purchaseProduct->batch_number }}, {{ __('Expire Date') }} : {{ $purchaseProduct->expire_date ? date($dateFormat, strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                    @endif
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ $purchaseProduct->quantity }}/{{ $purchaseProduct?->unit?->code_name }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_exc_tax) }}
                                </td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }} </td>
                                <td class="text-start" style="font-size:9px!important;">{{ '(' . $purchaseProduct->unit_tax_percent . '%)=' . $purchaseProduct->unit_tax_amount }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
                                <td class="text-start" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
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
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Purchase Discount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($purchase->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}
                                    @else
                                        ({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Purchase Vat/Tax') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ '(' . $purchase->purchase_tax_percent . '%)=' . $purchase->purchase_tax_amount }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Shipment Charge') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Purchased Amount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Paid') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt(isset($payingAmount) ? $payingAmount : $purchase->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Invoice)') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $purchase->invoice_id }}</p>
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
    <span id="title" class="d-none">OK</span>
    <!-- Purchase print templete end-->
@endif
