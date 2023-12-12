@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    .print_table th { font-size:11px!important; font-weight: 550!important; line-height: 12px!important}
    .print_table tr td{color: black; font-size:10px!important; line-height: 12px!important}

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
 <!-- Receipt print templete-->
<div class="purchase_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($receipt->branch)

                    @if ($receipt?->branch?->parent_branch_id)

                        @if ($receipt->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $receipt->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $receipt->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($receipt->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $receipt->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $receipt->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business__business_logo'] != null)

                        <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
                    @endif
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>
                        @if ($receipt?->branch)
                            @if ($receipt?->branch?->parent_branch_id)

                                {{ $receipt?->branch?->parentBranch?->name }}
                            @else

                                {{ $receipt?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($receipt?->branch)

                        {{ $receipt->branch->city . ', ' . $receipt->branch->state. ', ' . $receipt->branch->zip_code. ', ' . $receipt->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($receipt?->branch)

                        <strong>@lang('menu.email') : </strong> {{ $receipt?->branch?->email }},
                        <strong>@lang('menu.phone') : </strong> {{ $receipt?->branch?->phone }}
                    @else

                        <strong>@lang('menu.email') : </strong> {{ $generalSettings['business__email'] }},
                        <strong>@lang('menu.phone') : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 class="fw-bold" style="text-transform: uppercase;">{{ __("Receipt Voucher") }}</h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        {{ date($generalSettings['business__date_format'], strtotime($receipt->date)) }}
                    </li>
                    <li style="font-size:11px!important;"><strong>{{ __("Voucher No") }} : </strong>{{ $receipt->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Received Amount") }} : </strong>{{ App\Utils\Converter::format_in_bdt($receipt->total_amount) }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Reference") }} : </strong>
                        @if ($receipt?->saleRef)

                            @if ($receipt?->saleRef->status == \App\Enums\SaleStatus::Final->value)
                                {{ __("Sales") }} : {{ $receipt?->saleRef->invoice_id }}
                            @elseif ($receipt?->saleRef->status == \App\Enums\SaleStatus::Order->value)
                                {{ __("Sales-Order") }} : {{ $receipt?->saleRef->order_id }}
                            @endif
                        @endif

                        @if ($receipt?->purchaseReturnRef)
                            {{ __("Purchase Return") }} : {{ $receipt?->purchaseReturnRef->voucher_no }}
                        @endif

                        @if ($receipt?->stockAdjustmentRef)
                            {{ __("Stock Adjustment") }} : {{ $receipt?->purchaseReturnRef->voucher_no }}
                        @endif
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        {{ $receipt?->createdBy?->prefix.' '.$receipt?->createdBy?->name.' '.$receipt?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <p class="fw-bold">{{ __("Received From") }} :</p>
                @foreach ($receipt->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Credit A/c") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->account?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Address") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->account?->address }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Phone") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->account?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Received Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                <td class="text-end fw-bold" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($description?->amount) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                @endforeach
            </div>

            <div class="col-6">
                <p class="fw-bold">{{ __("Received To") }} : </p>
                @foreach ($receipt->voucherDescriptions()->where('amount_type', 'dr')->get() as $description)
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Debit A/c") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->account?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Method/Type") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->paymentMethod?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Transaction No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->transaction_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->cheque_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque Serial No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->cheque_serial_no }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                @endforeach
            </div>
        </div>

        @php
            $creditDescription = $receipt->voucherDescriptions()->where('amount_type', 'cr')->first();
        @endphp

        <div class="purchase_product_table mt-2">
            <div class="row">
                <div class="col-6">
                    <p class="fw-bold">{{ __("Receipt Against Vouchers") }}</p>
                    <table class="table report-table table-sm table-bordered print_table">
                        <thead>
                            <tr>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Date") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Voucher No") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Voucher Type") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Amount") }}</th>
                            </tr>
                        </thead>
                        <tbody class="purchase_print_product_list">
                            @php
                                $totalAmount = 0;
                            @endphp
                            @foreach ($creditDescription->references as $reference)
                                @php
                                    $isOrder = 0;
                                    if ($reference?->sale?->status == App\Enums\SaleStatus::Order->value) {

                                        $isOrder = 1;
                                    }
                                @endphp
                                @if ($isOrder == 0)
                                    <tr>
                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                {{ $reference?->sale->date }}
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ $reference?->purchaseReturn->date }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ $reference?->stockAdjustment->date }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                @if ($reference?->sale->status == \App\Enums\SaleStatus::Final->value)

                                                    {{ $reference?->sale->invoice_id }}

                                                @elseif ($reference?->sale->status == \App\Enums\SaleStatus::Order->value)

                                                    {{ $reference?->sale->order_id }}
                                                @endif
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ $reference?->purchaseReturn->voucher_no }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ $reference?->stockAdjustment->voucher_no }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                @if ($reference?->sale->status == \App\Enums\SaleStatus::Final->value)

                                                    {{ __("Sales") }}
                                                @elseif ($reference?->sale->status == \App\Enums\SaleStatus::Order->value)

                                                    {{ __("Sales-Order") }}
                                                @endif
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ __("Purchase Return") }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ __("Stock Adjustment") }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($reference->amount) }}</td>
                                        @php
                                            $totalAmount += $reference->amount;
                                        @endphp
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">{{ __("Total") }} : </th>
                                <th class="text-start">{{ App\Utils\Converter::format_in_bdt($totalAmount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="col-6">
                    <p class="fw-bold">{{ __("Receipt Against Order Vouchers") }}</p>
                    <table class="table report-table table-sm table-bordered print_table">
                        <thead>
                            <tr>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Date") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Voucher No") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Voucher Type") }}</th>
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Amount") }}</th>
                            </tr>
                        </thead>
                        <tbody class="purchase_print_product_list">
                            @php
                                $totalAmount = 0;
                            @endphp
                            @foreach ($creditDescription->references as $reference)
                                @php
                                    $isOrder = 0;
                                    if ($reference?->sale?->status == App\Enums\SaleStatus::Order->value) {

                                        $isOrder = 1;
                                    }
                                @endphp
                                @if ($isOrder == 1)
                                    <tr>
                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                {{ $reference?->sale->date }}
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ $reference?->purchaseReturn->date }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ $reference?->stockAdjustment->date }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                @if ($reference?->sale->status == \App\Enums\SaleStatus::Final->value)

                                                    {{ $reference?->sale->invoice_id }}

                                                @elseif ($reference?->sale->status == \App\Enums\SaleStatus::Order->value)

                                                    {{ $reference?->sale->order_id }}
                                                @endif
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ $reference?->purchaseReturn->voucher_no }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ $reference?->purchaseReturn->voucher_no }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($reference?->sale)

                                                @if ($reference?->sale->status == \App\Enums\SaleStatus::Final->value)

                                                    {{ __("Sales") }}
                                                @elseif ($reference?->sale->status == \App\Enums\SaleStatus::Order->value)

                                                    {{ __("Sales-Order") }}
                                                @endif
                                            @endif

                                            @if ($reference?->purchaseReturn)

                                                {{ __("Purchase Return") }}
                                            @endif

                                            @if ($reference?->stockAdjustment)

                                                {{ __("Stock Adjustment") }}
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($reference->amount) }}</td>
                                        @php
                                            $totalAmount += $reference->amount;
                                        @endphp
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">{{ __("Total") }} : </th>
                                <th class="text-start">{{ App\Utils\Converter::format_in_bdt($totalAmount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Prepared By") }}
                </p>
            </div>

            <div class="col-4 text-center">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Checked By") }}
                </p>
            </div>

            <div class="col-4 text-end">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Authorized By") }}
                </p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $receipt->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __("Powered By") }} <strong>SpeedDigit Software Solution.</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Receipt print templete end-->
