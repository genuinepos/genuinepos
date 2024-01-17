@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $sale?->customer;
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
                    {{ __('Sale Details') }} ({{ __('Invoice ID') }} : <strong>{{ $sale->invoice_id }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Customer') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong><span>{{ $sale->customer->name }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong><span>{{ $sale->customer->address }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong><span>{{ $sale->customer->phone }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Invoice ID') }} : </strong> {{ $sale->invoice_id }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $receivable = $sale->total_invoice_amount - $sale->sale_return_amount;
                                @endphp
                                @if ($sale->due <= 0)
                                    <span>{{ __('Paid') }}</span>
                                @elseif($sale->due > 0 && $sale->due < $receivable)
                                    <span>{{ __('Partial') }}</span>
                                @elseif($receivable == $sale->due)
                                    <span>{{ __('Due') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Shipment Status') }} : </strong>
                                @if ($sale->shipment_status == App\Enums\ShipmentStatus::NoStatus->value)
                                    <span>{{ __('Not-Available') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Ordered->value)
                                    <span>{{ __('Ordered') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Packed->value)
                                    <span>{{ __('Packed') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Shipped->value)
                                    <span>{{ __('Shipped') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Delivered->value)
                                    <span>{{ __('Delivered') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Cancelled->value)
                                    <span>{{ __('Cancelled') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Completed->value)
                                    <span>{{ __('Cancelled') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($sale->branch_id) {
                                        if ($sale?->branch?->parentBranch) {
                                            $branchName = $sale?->branch?->parentBranch?->name . '(' . $sale?->branch?->area_name . ')' . '-(' . $sale?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $sale?->branch?->name . '(' . $sale?->branch?->area_name . ')' . '-(' . $sale?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp

                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Email') }} : </strong>
                                @if ($sale->branch)
                                    {{ $sale->branch->email }}
                                @else
                                    {{ $generalSettings['business_or_shop__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($sale->branch)
                                    {{ $sale->branch->phone }}
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
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Stock Location') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Price Exc. Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Discount') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Price Exc. Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($sale->saleProducts as $saleProduct)
                                        <tr>
                                            @php
                                                $variant = $saleProduct->variant ? ' - ' . $saleProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $saleProduct->product->name . ' ' . $variant }}
                                                <small>{{ $saleProduct->description }}</small>
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                @if ($saleProduct?->warehouse)
                                                    {{ $saleProduct?->warehouse?->warehouse_name . '/' . $saleProduct?->warehouse?->warehouse_code . '-(WH)' }}
                                                @else
                                                    @if ($saleProduct->branch_id)
                                                        @if ($saleProduct?->branch?->parentBranch)
                                                            {{ $saleProduct?->branch?->parentBranch?->name . '(' . $saleProduct?->branch?->area_name . ')' . '-(' . $saleProduct?->branch?->branch_code . ')' }}
                                                        @else
                                                            {{ $saleProduct?->branch?->name . '(' . $saleProduct?->branch?->area_name . ')' . '-(' . $saleProduct?->branch?->branch_code . ')' }}
                                                        @endif
                                                    @else
                                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $saleProduct->quantity . '/' . $saleProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ '(' . $saleProduct->unit_tax_percent . '%)=' . $saleProduct->unit_tax_amount }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Recipts Against Sale') }}</p>
                        @include('sales.add_sale.ajax_views.partials.sale_details_receipt_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                    <td class="text-end">
                                        {{ $sale->order_discount_type == 1 ? '(Fixed)=' : '(%)=' }}{{ $sale->order_discount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ '(' . $sale->order_tax_percent . '%)=' . $sale->order_tax_amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Invoice Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Received Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due (On Invoice)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $amounts['closing_balance_in_flat_amount_string'] }}
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
                            <p style="font-size:11px!important;" class="fw-bold">{{ __('Shipment Details') }}</p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $sale->shipment_details }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;" class="fw-bold">{{ __('Sale Note') }}</p>
                            <p class="purchase_note" style="font-size:11px!important;">{{ $sale->note }}</p>
                        </div>
                    </div>
                </div>
                <hr class="m-0 mt-3">

                <div class="row g-0 mt-1">
                    <div class="col-md-6 offset-6">
                        <div class="input-group p-0">
                            <label class="col-3 text-end pe-1"><b>{{ __('Print') }}</b></label>
                            <div class="col-9">
                                <select id="print_page_size" class="form-control">
                                    @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                        <option {{ $generalSettings['print_page_size__add_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-box">
                    @if ($sale->sale_screen == App\Enums\SaleScreenType::AddSale->value)

                        @if (auth()->user()->can('edit_add_sale') && $sale->branch_id == auth()->user()->branch_id)

                            <a href="{{ route('sales.edit', [$sale->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                        @endif
                    @elseif($sale->sale_screen == App\Enums\SaleScreenType::PosSale->value)

                        @if (auth()->user()->can('pos_edit') && $sale->branch_id == auth()->user()->branch_id)
                        
                            <a href="{{ route('sales.pos.edit', [$sale->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                        @endif
                    @endif

                    @php
                        $filename = $sale->invoice_id . '__' . $sale->date . '__' . $branchName;
                    @endphp

                    <a href="{{ route('sales.helper.print.packing.slip', [$sale->id]) }}" onclick="printPackingSlip(this); return false;" class="footer_btn btn btn-sm btn-success" id="printPackingSlipBtn" data-filename="{{ __('Packing Slip') . '_' . $filename }}">{{ __('Print Packing Slip') }}</a>

                    <a href="{{ route('sales.helper.print.challan', [$sale->id]) }}" onclick="printChallan(this); return false;" class="footer_btn btn btn-sm btn-success" id="PrintChallanBtn" data-filename="{{ __('Challan') . '_' . $filename }}">{{ __('Print Challan') }}</a>

                    <a href="{{ route('sales.helper.related.voucher.print', $sale->id) }}" onclick="printSalesRelatedVoucher(this); return false;" class="footer_btn btn btn-sm btn-success" id="printSalesVoucherBtn" data-filename="{{ $filename }}">{{ __('Print Invoice') }}</a>

                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
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

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    function printChallan(event) {

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
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    };

    // Print Packing slip
    function printPackingSlip(event) {

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
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    };
</script>
