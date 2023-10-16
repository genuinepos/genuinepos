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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:0px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
 <!-- Transfer Stock print templete-->
<div class="purchase_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($transferStock->branch)

                    @if ($transferStock?->branch?->parent_branch_id)

                        @if ($transferStock->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $transferStock->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $transferStock->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($transferStock->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $transferStock->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $transferStock->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business__business_logo'] != null)

                        <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                    @endif
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>
                        @if ($transferStock?->branch)
                            @if ($transferStock?->branch?->parent_branch_id)

                                {{ $transferStock?->branch?->parentBranch?->name }}
                            @else

                                {{ $transferStock?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__shop_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($transferStock?->branch)

                        {{ $transferStock->branch->city . ', ' . $transferStock->branch->state. ', ' . $transferStock->branch->zip_code. ', ' . $transferStock->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($transferStock?->branch)

                        <strong>{{ __("Email") }} : </strong> <b>{{ $transferStock?->branch?->email }}</b>,
                        <strong>{{ __("Phone") }} : </strong> <b>{{ $transferStock?->branch?->phone }}</b>
                    @else

                        <strong>{{ __("Email") }} : </strong> <b>{{ $generalSettings['business__email'] }}</b>,
                        <strong>{{ __("Phone") }} : </strong> <b>{{ $generalSettings['business__phone'] }}</b>
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 style="text-transform: uppercase;"><strong>{{ __("Transfer Stock Voucher") }}</strong></h6>
                <p><strong>{{ __("Warehouse To Shop/Business") }}</strong></p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Send From") }} : </strong><b>{{ $transferStock?->senderWarehouse?->warehouse_name.'-('.$transferStock?->senderWarehouse->warehouse_code.')' }}</b></li>
                    <li style="font-size:11px!important;"><strong>{{ __("Send To") }} : </strong>
                        <b>
                            @if ($transferStock?->receiverBranch)
                                @if ($transferStock?->receiverBranch?->parent_branch_id)

                                    {{ $transferStock?->receiverBranch?->parentBranch?->name }}
                                @else

                                    {{ $transferStock?->receiverBranch?->name }}
                                @endif
                            @else

                                {{ $generalSettings['business__shop_name'] }}
                            @endif
                        </b>
                    </li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        <b>{{ date($generalSettings['business__date_format'], strtotime($transferStock->date)) }}</b>
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Voucher No") }} : </strong><b>{{ $transferStock->voucher_no }}</b></li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Receiving Status") }} : </strong>{{ __("Pending") }}</li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        <b>{{ $transferStock?->sendBy?->prefix.' '.$transferStock?->sendBy?->name.' '.$transferStock?->sendBy?->last_name }}</b>
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table print-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Product") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Send Qty") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost (Inc. Tax)") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Received Qty") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Pending Qty') }}</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                        <tr>
                            @php
                                $variant = $transferStockProduct->variant ? ' - '.$transferStockProduct->variant->variant_name : '';
                            @endphp

                            <td class="text-start" style="font-size:11px!important;">
                                <p>{{ Str::limit($transferStockProduct->product->name, 25).' '. $variant }}</p>
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->send_qty).'/'.$transferStockProduct?->unit?->code_name }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transferStockProduct->unit_cost_inc_tax) }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->subtotal) }} </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->received_qty).'/'.$transferStockProduct?->unit?->code_name }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->pending_qty).'/'.$transferStockProduct?->unit?->code_name }}</td>
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
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Total Send Qty") }} : </th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($transferStock->total_send_qty) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Total Received Qty") }} : </th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($transferStock->total_received_qty) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Total Pending Qty") }} : </th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($transferStock->total_pending_qty) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Total Stock Value") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}</b>
                            </td>
                        </tr>
                    </thead>
                </table>
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
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transferStock->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p><b>{{ $transferStock->voucher_no }}</b></p>
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
 <!-- Transfer Stock print templete end-->
