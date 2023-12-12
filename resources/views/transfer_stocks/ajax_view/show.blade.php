@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Transfer Stock Details') }} || ({{ __('Voucher No') }} : <strong>{{ $transferStock->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Send From') }} : </strong>
                                @if ($transferStock?->senderBranch)

                                    @if ($transferStock?->senderBranch?->parent_branch_id)
                                        {{ $transferStock?->senderBranch?->parentBranch?->name }}
                                    @else
                                        {{ $transferStock?->senderBranch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </li>

                            @if ($transferStock?->senderWarehouse)
                                <li style="font-size:11px!important;"><strong>{{ __('Send At') }} : </strong>
                                    {{ $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse?->warehouse_code . ')' }}
                                </li>
                            @endif

                            <li style="font-size:11px!important;"><strong>{{ __('Send To') }} : </strong>
                                @if ($transferStock?->receiverBranch)

                                    @if ($transferStock?->receiverBranch?->parent_branch_id)
                                        {{ $transferStock?->receiverBranch?->parentBranch?->name }}
                                    @else
                                        {{ $transferStock?->receiverBranch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </li>

                            @if ($transferStock?->receiverWarehouse)
                                <li style="font-size:11px!important;"><strong>{{ __('Receive At') }} : </strong>
                                    {{ $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse?->warehouse_code . ')' }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                                {{ date($generalSettings['business__date_format'], strtotime($transferStock->date)) }}
                            </li>

                            @if ($transferStock->receive_date)
                                <li style="font-size:11px!important;"><strong>{{ __('Received Date') }} : </strong>
                                    {{ date($generalSettings['business__date_format'], strtotime($transferStock->receive_date)) }}
                                </li>
                            @endif

                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $transferStock->voucher_no }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @if ($transferStock->branch_id)

                                    @if ($transferStock?->branch?->parentBranch)
                                        {{ $transferStock?->branch?->parentBranch?->name . '(' . $transferStock?->branch?->area_name . ')' . '-(' . $transferStock?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $transferStock?->branch?->name . '(' . $transferStock?->branch?->area_name . ')' . '-(' . $transferStock?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($transferStock->branch)
                                    {{ $transferStock->branch->phone }}
                                @else
                                    {{ $generalSettings['business__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Send Qty') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Received Qty') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Pending Qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Transfer Note') }} :</p>
                        <p>{{ $transferStock->transfer_note }}</p>
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
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
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Stock Value') }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}
                                    </td>
                                </tr>

                                @if ($transferStock->received_stock_value > 0)
                                    <tr>
                                        <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Received Stock Value') }} : {{ $generalSettings['business__currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($transferStock->received_stock_value) }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('transfer.stocks.edit', [$transferStock->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                            <button type="submit" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __('Print') }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            page-break-after: auto
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
        bottom: 0px;
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
<div class="print_modal_details d-none">
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
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
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
                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($transferStock?->branch)
                        {{ $transferStock->branch->city . ', ' . $transferStock->branch->state . ', ' . $transferStock->branch->zip_code . ', ' . $transferStock->branch->country }}
                    @else
                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($transferStock?->branch)
                        <strong>{{ __('Email') }} : </strong> {{ $transferStock?->branch?->email }},
                        <strong>{{ __('Phone') }} : </strong> {{ $transferStock?->branch?->phone }}
                    @else
                        <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business__email'] }},
                        <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 style="text-transform: uppercase;"><strong>{{ __('Transfer Stock Voucher') }}</strong></h6>
                {{-- <p><strong>{{ __("Shop/Business To Shop/Business") }}</strong></p> --}}
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Send From') }} : </strong>
                        @if ($transferStock?->senderBranch)
                            @if ($transferStock?->senderBranch?->parent_branch_id)
                                {{ $transferStock?->senderBranch?->parentBranch?->name }}
                            @else
                                {{ $transferStock?->senderBranch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </li>

                    @if ($transferStock?->senderWarehouse)
                        <li style="font-size:11px!important;"><strong>{{ __('Send At') }} : </strong>
                            {{ $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse->warehouse_code . ')' }}
                        </li>
                    @endif

                    <li style="font-size:11px!important;"><strong>{{ __('Send To') }} : </strong>
                        @if ($transferStock?->receiverBranch)
                            @if ($transferStock?->receiverBranch?->parent_branch_id)
                                {{ $transferStock?->receiverBranch?->parentBranch?->name }}
                            @else
                                {{ $transferStock?->receiverBranch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </li>

                    @if ($transferStock?->receiverWarehouse)
                        <li style="font-size:11px!important;"><strong>{{ __('Receive At') }} : </strong>
                            {{ $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse->warehouse_code . ')' }}
                        </li>
                    @endif
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                        {{ date($generalSettings['business__date_format'], strtotime($transferStock->date)) }}
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $transferStock->voucher_no }}</li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">

                    @if ($transferStock->date)
                        <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                            {{ date($generalSettings['business__date_format'], strtotime($transferStock->date)) }}
                        </li>
                    @endif

                    <li style="font-size:11px!important;"><strong>{{ __('Receiving Status') }} : </strong>{{ App\Enums\TransferStockReceiveStatus::tryFrom($transferStock->receive_status)->name }}</li>

                    <li style="font-size:11px!important;"><strong>{{ __('Created By') }} : </strong>
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
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transferStockProduct->subtotal) }}</td>
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
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Stock Value') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}
                            </td>
                        </tr>

                        @if ($transferStock->received_stock_value > 0)
                            <tr>
                                <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Received Stock Value') }} : {{ $generalSettings['business__currency'] }}</th>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($transferStock->received_stock_value) }}
                                </td>
                            </tr>
                        @endif
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
                <p>{{ $transferStock->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <strong>SpeedDigit Software Solution.</strong></small>
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
