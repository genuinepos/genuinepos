@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $order?->customer;
    $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
    $branchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
    $__branchId = $account?->group?->sub_sub_group_number == 6 ? $branchId : '';
    $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: null, toDate: null, branchId: $__branchId);
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Sales Order Details') }} ({{ __('Order ID') }} : <strong>{{ $order->order_id }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Customer') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong><span>{{ $order->customer->name }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong><span>{{ $order->customer->address }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong><span>{{ $order->customer->phone }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Order ID') }} : </strong> {{ $order->order_id }}</li>
                            @if ($order->quotation_id)
                                <li style="font-size:11px!important;"><strong>{{ __('Quotation ID') }} : </strong> {{ $order->quotation_id }}</li>
                            @endif

                            <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $receivable = $order->total_invoice_amount;
                                @endphp
                                @if ($order->due <= 0)
                                    <span>{{ __('Paid') }}</span>
                                @elseif($order->due > 0 && $order->due < $receivable)
                                    <span>{{ __('Partial') }}</span>
                                @elseif($receivable == $order->due)
                                    <span>{{ __('Due') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Shipment Status') }} : </strong>
                                @if ($order->shipment_status == App\Enums\ShipmentStatus::NoStatus->value)
                                    <span>{{ __('Not-Available') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Ordered->value)
                                    <span>{{ __('Ordered') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Packed->value)
                                    <span>{{ __('Packed') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Shipped->value)
                                    <span>{{ __('Shipped') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Delivered->value)
                                    <span>{{ __('Delivered') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Cancelled->value)
                                    <span>{{ __('Cancelled') }}</span>
                                @elseif($order->shipment_status == App\Enums\ShipmentStatus::Completed->value)
                                    <span>{{ __('Completed') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $order?->createdBy?->prefix . ' ' . $order?->createdBy?->name . ' ' . $order?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($order->branch_id) {
                                        if ($order?->branch?->parentBranch) {
                                            $branchName = $order?->branch?->parentBranch?->name . '(' . $order?->branch?->area_name . ')' . '-(' . $order?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $order?->branch?->name . '(' . $order?->branch?->area_name . ')' . '-(' . $order?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Email') }} : </strong>
                                @if ($order->branch)
                                    {{ $order->branch->email }}
                                @else
                                    {{ $generalSettings['business_or_shop__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($order->branch)
                                    {{ $order->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
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
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Product') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Ordered Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Delivered Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Left Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Price Exc. Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Discount') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Price Exc. Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($order->saleProducts as $orderProduct)
                                        <tr>
                                            @php
                                                $variant = $orderProduct->variant ? ' - ' . $orderProduct->variant->variant_name : '';
                                                $productCode = $orderProduct?->variant ? $orderProduct?->variant?->variant_code : $orderProduct?->product?->product_code;
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $orderProduct->product->name . ' ' . $variant }}
                                                <small class=" d-block" style="font-size:9px!important;">{{ __('P/c') }}: {{ $productCode }}</small>
                                                <small class="d-block" style="font-size:9px!important;">{{ $orderProduct->description }}</small>
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->ordered_quantity . '/' . $orderProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->delivered_quantity . '/' . $orderProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->left_quantity . '/' . $orderProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_exc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ '(' . $orderProduct->unit_tax_percent . '%)=' . $orderProduct->unit_tax_amount }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_inc_tax) }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">{{ __('Total') }}:</td>
                                        <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($order->total_ordered_qty) }}</td>
                                        <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($order->total_delivered_qty) }}</td>
                                        <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($order->total_left_qty) }}</td>
                                        <td>--</td>
                                        <td>--</td>
                                        <td>--</td>
                                        <td>--</td>
                                        <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Recipts Against Sales Order') }}</p>
                        @include('sales.add_sale.orders.ajax_views.partials.sales_order_details_receipt_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Order Discount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $order->order_discount_type == 1 ? '(Fixed)=' : '(%)=' }}{{ $order->order_discount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Order Tax') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ '(' . $order->order_tax_percent . '%)=' . $order->order_tax_amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Shipment Charge') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Ordered Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($order->total_invoice_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Advance Received') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($order->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due (On Order)') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        @if ($order->due < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($order->due)) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Current Balance') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Shipment Address') }}</strong></p>
                            <p style="font-size:11px!important;">{{ $order->shipment_address }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Shipment Details') }}</strong></p>
                            <p style="font-size:11px!important;">{{ $order->shipment_details }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Order Note') }}</strong></p>
                            <p style="font-size:11px!important;">{{ $order->note }}</p>
                        </div>
                    </div>
                </div>

                <hr class="m-0 mt-3">

                <div class="row g-0 mt-1">
                    <div class="col-md-6 offset-6">
                        <div class="input-group p-0">
                            <label class="col-4 text-end pe-1 offset-md-6"><b>{{ __('Print') }}</b></label>
                            <div class="col-2">
                                <select id="print_page_size" class="form-control">
                                    @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                        <option {{ $generalSettings['print_page_size__sales_order_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        @php
                            $filename = $order->order_id . '__' . $order->date . '__' . $branchName;
                        @endphp
                        <div class="btn-box">
                            @if (auth()->user()->can('sales_order_to_invoice') && $order->branch_id == auth()->user()->branch_id)
                                <a href="{{ route('sales.order.to.invoice.create', [$order->id]) }}" class="btn btn-sm btn-secondary"> <i class="fas fa-check-double"></i> {{ __('Sales Order To Invoice') }}</a>
                            @endif

                            @if (auth()->user()->can('sales_edit') && $order->branch_id == auth()->user()->branch_id)
                                <a href="{{ route('sale.orders.edit', [$order->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                            @endif

                            <a href="{{ route('sales.helper.related.voucher.print', $order->id) }}" onclick="printSalesRelatedVoucher(this); return false;" class="footer_btn btn btn-sm btn-success" id="printSalesVoucherBtn" data-filename="{{ $filename }}">{{ __('Print Sales Order') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printSalesRelatedVoucher(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
        var print_page_size = $('#print_page_size').val();
        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                print_page_size
            },
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                    footer: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }
</script>
