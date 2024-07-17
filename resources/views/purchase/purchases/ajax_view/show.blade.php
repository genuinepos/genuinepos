@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $purchase?->supplier;
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
                    {{ __('Purchase Details') }} ({{ __('Invoice ID') }} : <strong>{{ $purchase->invoice_id }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Supplier') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong> {{ $purchase->supplier->name }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong> {{ $purchase->supplier->address }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($dateFormat, strtotime($purchase->date)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('P.Invoice ID') }} : </strong> {{ $purchase->invoice_id }}</li>
                            @if ($purchase?->purchaseOrder)
                                <li style="font-size:11px!important;"><strong>{{ __('P/o ID') }} : </strong> {{ $purchase?->purchaseOrder?->invoice_id }}</li>
                            @endif
                            <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                    <span class="badge bg-success">{{ __('Paid') }} : </span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">{{ __('Partial') }} : </span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">{{ __('Due') }} : </span>
                                @endif
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $purchase?->admin?->prefix . ' ' . $purchase?->admin?->name . ' ' . $purchase?->admin?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($purchase->branch_id) {
                                        if ($purchase?->branch?->parentBranch) {
                                            $branchName = $purchase?->branch?->parentBranch?->name . '(' . $purchase?->branch?->area_name . ')' . '-(' . $purchase?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $purchase?->branch?->name . '(' . $purchase?->branch?->area_name . ')' . '-(' . $purchase?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($purchase->branch)
                                    {{ $purchase->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Stored Location') }} : </strong>
                                @if ($purchase?->warehouse)
                                    {{ $purchase?->warehouse?->warehouse_name . '/' . $purchase?->warehouse?->warehouse_code . '-(WH)' }}
                                @else
                                    @if ($purchase->branch_id)

                                        @if ($purchase?->branch?->parentBranch)
                                            {{ $purchase?->branch?->parentBranch?->name . '(' . $purchase?->branch?->area_name . ')' . '-(' . $purchase?->branch?->branch_code . ')' }}
                                        @else
                                            {{ $purchase?->branch?->name . '(' . $purchase?->branch?->area_name . ')' . '-(' . $purchase?->branch?->branch_code . ')' }}
                                        @endif
                                    @else
                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                    @endif
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
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Product') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost (Before Discount)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Discount') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost (Before Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal (Before Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost (After Tax)') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Line-Total') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Lot No') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Selling Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                                        @if ($purchase?->purchaseOrder && $purchaseProduct->quantity <= 0)
                                            @continue
                                        @endif

                                        <tr>
                                            @php
                                                $variant = $purchaseProduct->variant ? ' - ' . $purchaseProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ $purchaseProduct->product->name . ' ' . $variant }}
                                                @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                                    <small class="d-block text-muted" style="font-size: 9px;">{{ __('Batch No') }} : {{ $purchaseProduct->batch_number }}, {{ __('Expire Date') }} :{{ $purchaseProduct->expire_date ? date($generalSettings['business_or_shop__date_format'], strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                                @endif
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->quantity . '/' . $purchaseProduct?->unit?->code_name }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_exc_tax) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_with_discount) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->subtotal) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ '(' . $purchaseProduct->unit_tax_percent . '%)=' . $purchaseProduct->unit_tax_amount }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }} </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->selling_price) }}</td>
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
                        @include('purchase.purchases.ajax_view.partials.purchase_details_payment_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} :
                                        {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}
                                    </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Purchase Discount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                    <td class="text-end">
                                        {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Purchase Tax') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->purchase_tax_amount . ' (' . $purchase->purchase_tax_percent . '%)' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __('Shipment Charge') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Purchased Amount') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Return') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->purchase_return_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Paid') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due (On Invoice)') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Current Balance') }} : {{ $purchase?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end fw-bold">
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
                            <p style="font-size:11px!important;"><strong>{{ __('Shipping Details') }}</strong></p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $purchase->shipment_details }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Purchase Note') }}</strong></p>
                            <p class="purchase_note" style="font-size:11px!important;">{{ $purchase->purchase_note }}</p>
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
                                        <option @selected($generalSettings['print_page_size__purchase_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                        <div class="btn-box">
                            @php
                                $filename = __('Purchase') . '__' . $purchase->invoice_id . '__' . $purchase->date . '__' . $branchName;
                            @endphp
                            @if (auth()->user()->can('purchase_edit') && $purchase->branch_id == auth()->user()->branch_id)
                                <a href="{{ route('purchases.edit', [$purchase->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                            @endif
                            <a href="{{ route('purchases.print', $purchase->id) }}" onclick="printPurchase(this); return false;" class="footer_btn btn btn-sm btn-success" id="printPurchaseBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printPurchase(event) {

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

                // var tempElement = document.createElement('div');
                // // Set the received data as HTML content of the temporary element
                // tempElement.innerHTML = data;

                // // Find the #title element within the temporary div
                // var titleElement = tempElement.querySelector('#title');

                // if (titleElement) {
                //     var titleHTML = titleElement.innerHTML;
                //     console.log(titleHTML); // Log the HTML content of #title
                // } else {
                //     console.log("#title element not found in the retrieved data.");
                // }

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
</script>
