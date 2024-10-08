@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $order?->supplier;
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
                    {{ __('P/o Details') }} | ({{ __('P/o ID') }} : <strong>{{ $order->invoice_id }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Supplier') }} : </strong> {{ $order?->supplier?->name }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Address') }} : </strong> {{ $order?->supplier?->address }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Tax Number') }} : </strong> {{ $order?->supplier?->tax_number }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Phone') }} : </strong> {{ $order?->supplier?->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('P/o ID') }} : </strong> {{ $order->invoice_id }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('P/o Date') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Delivery Date') }} : </strong> {{ $order->delivery_date ? date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) : '' }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Receiving Status') }} : </strong>
                                @if ($order->po_receiving_status == 'Pending')
                                    <span class="badge bg-danger">{{ __('Pending') }}</span>
                                @elseif ($order->po_receiving_status == 'Completed')
                                    <span class="badge bg-success">{{ __('Completed') }}</span>
                                @else
                                    <span class="badge bg-primary">{{ __('Partial') }}</span>
                                @endif
                            </li>

                            <li style="font-size:10px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $payable = $order->total_purchase_amount;
                                @endphp
                                @if ($order->due <= 0)
                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                @elseif($order->due > 0 && $order->due < $payable)
                                    <span class="badge bg-primary text-white">{{ __('Partial') }}</span>
                                @elseif($payable == $order->due)
                                    <span class="badge bg-danger text-white">{{ __('Due') }}</span>
                                @endif
                            </li>
                            <li style="font-size:10px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $order?->admin?->prefix . ' ' . $order?->admin?->name . ' ' . $order?->admin?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ location_label() }} : </strong>
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

                            <li style="font-size:10px!important;"><strong>{{ __('Phone') }} : </strong>
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
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Product') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Ordered Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Received Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Pending Qty') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Unit Cost (Before Discount)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Discount') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Unit Cost (Before Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Subtotal (Before Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Tax (%)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Unit Cost (After Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:10px!important;">{{ __('Linetotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($order->purchaseOrderProducts as $orderProduct)
                                        <tr>
                                            @php
                                                $variant = $orderProduct?->variant ? '(' . $orderProduct?->variant?->variant_name . ')' : '';
                                                $productCode = $orderProduct?->variant ? $orderProduct?->variant?->variant_code : $orderProduct?->product?->product_code;
                                            @endphp

                                            <td class="text-start" style="font-size:10px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:10px!important;">
                                                {{ $orderProduct?->product->name . ' ' . $variant }}
                                                <small class="d-block" style="font-size:9px!important;">{{ __('P/c') }}: {{ $productCode }}</small>
                                            </td>

                                            <td class="text-start fw-bold" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->ordered_quantity) . '/' . $orderProduct?->unit?->code_name }}</td>
                                            <td class="text-start text-success fw-bold" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->received_quantity) }}</td>
                                            <td class="text-start text-danger fw-bold" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->pending_quantity) }}</td>
                                            <td class="text-start" style="font-size:10px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_cost_exc_tax) }}
                                            </td>
                                            <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount) }} </td>
                                            <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_cost_with_discount) }}</td>
                                            <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->subtotal) }}</td>
                                            <td class="text-start" style="font-size:10px!important;">{{ '(' . $orderProduct->unit_tax_percent . '%)=' . $orderProduct->unit_tax_amount }}</td>
                                            <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->net_unit_cost) }} </td>
                                            <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->line_total) }}</td>

                                            @if (count($orderProduct->purchaseProducts) > 0)
                                        <tr>
                                            <td colspan="3" class="text-center"><strong>{{ __('Receiving Details') }} ➡</strong></td>

                                            <td colspan="8">
                                                <table class="table modal-table table-sm table-striped">
                                                    <thead>
                                                        <tr class="bg-secondary">
                                                            <th class="text-white" style="font-size:10px!important;">{{ __('Date') }}</th>
                                                            <th class="text-white" style="font-size:10px!important;">{{ __('P. Invoice ID.') }}</th>
                                                            <th class="text-white" style="font-size:10px!important;">{{ __('Challan No') }}</th>
                                                            <th class="text-white" style="font-size:10px!important;">{{ __('Lot No') }}</th>
                                                            <th class="text-white" style="font-size:10px!important;">{{ __('Received Qty') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orderProduct->purchaseProducts as $purchaseProduct)
                                                            @if ($purchaseProduct->quantity <= 0)
                                                                @continue
                                                            @endif

                                                            <tr>
                                                                <td style="font-size:10px!important;">{{ date($generalSettings['business_or_shop__date_format'], strtotime($purchaseProduct?->purchase?->date)) }}</td>

                                                                <td style="font-size:10px!important;">{{ $purchaseProduct?->purchase?->invoice_id }}</td>

                                                                <td style="font-size:10px!important;">{{ $purchaseProduct?->purchase?->challan_no }}</td>

                                                                <td style="font-size:10px!important;">{{ $purchaseProduct->lot_number }}</td>

                                                                <td style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . '/' . $purchaseProduct?->unit?->code_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Payments Against Purchase') }}</p>
                        @include('purchase.orders.ajax_view.partials.purchase_order_details_payment_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Total Item & Ordered Qty') }} :</th>
                                    <td class="text-end">
                                        ({{ $order->total_item }}) / ({{ App\Utils\Converter::format_in_bdt($order->po_qty) }}/{{ __('Nos') }})
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Order Discount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $order->order_discount_type == 1 ? '(Fixed)' : '%' }} {{ $order->order_discount }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Order Tax') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $order->purchase_tax_amount . ' (' . $order->purchase_tax_percent . '%)' }}
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
                                        {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Paid') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
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
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:10px!important;"><b>{{ __('Shipment Details') }}</b> </p>
                            <p style="font-size:10px!important;">{{ $order->shipment_details }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:10px!important;"><b>{{ __('Purchase Note') }}</b> </p>
                            <p style="font-size:10px!important;">{{ $order->purchase_note }}</p>
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
                                        <option {{ $generalSettings['print_page_size__purchase_order_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $filename = __('Purchase Order') . '__' . $order->invoice_id . '__' . $order->date . '__' . $branchName;
                        @endphp

                        @if (auth()->user()->can('purchase_order_edit') && $order->branch_id == auth()->user()->branch_id)
                            <a href="{{ route('purchase.orders.edit', [$order->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                        @endif

                        @if (auth()->user()->can('purchase_order_to_invoice') && $order->branch_id == auth()->user()->branch_id)
                            <a href="{{ route('purchase.order.to.invoice.create', [$order->id]) }}" class="btn btn-sm btn-secondary"> <i class="fas fa-check-double"></i> {{ __('P/o To Purchase Invoice') }}</a>
                        @endif

                        <a href="{{ route('purchases.order.print.supplier.copy', $order->id) }}" onclick="printSupplierCopy(this); return false;" class="btn btn-sm btn-info text-white" id="printSupplierCopy" data-filename="{{ $filename }}"> <i class="fas fa-print"></i> {{ __('Print Supplier Copy') }}</a>

                        <a href="{{ route('purchase.orders.print', $order->id) }}" onclick="printPurchaseOrder(this); return false;" class="btn btn-sm btn-success" id="printPurchaseOrderBtn" data-filename="{{ $filename }}">{{ __('Print Order') }}</a>

                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printSupplierCopy(event) {

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

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    };

    function printPurchaseOrder(event) {

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

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    };
</script>
