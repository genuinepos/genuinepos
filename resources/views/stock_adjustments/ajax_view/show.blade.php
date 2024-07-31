@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Stock Adjusmtent Details') }} ({{ __('Voucher No') }} : <strong>{{ $adjustment->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($dateFormat, strtotime($adjustment->date)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong> {{ $adjustment->voucher_no }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Type') }} : </strong>{{ App\Enums\StockAdjustmentType::tryFrom($adjustment->type)->name }}</li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $adjustment?->createdBy?->prefix . ' ' . $adjustment?->createdBy?->name . ' ' . $adjustment?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($adjustment->branch_id) {
                                        if ($adjustment?->branch?->parentBranch) {
                                            $branchName = $adjustment?->branch?->parentBranch?->name . '(' . $adjustment?->branch?->area_name . ')' . '-(' . $adjustment?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $adjustment?->branch?->name . '(' . $adjustment?->branch?->area_name . ')' . '-(' . $adjustment?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Email') }} : </strong>
                                @if ($adjustment->branch)
                                    {{ $adjustment->branch->email }}
                                @else
                                    {{ $generalSettings['business_or_shop__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($adjustment->branch)
                                    {{ $adjustment->branch->phone }}
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
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($adjustment->adjustmentProducts as $adjustmentProduct)
                                        <tr>
                                            @php
                                                $variant = $adjustmentProduct->variant ? ' - ' . $adjustmentProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $adjustmentProduct->product->name . ' ' . $variant }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                @if ($adjustmentProduct?->warehouse)
                                                    {{ $adjustmentProduct?->warehouse?->warehouse_name . '/' . $adjustmentProduct?->warehouse?->warehouse_code . '-(WH)' }}
                                                @else
                                                    @if ($adjustmentProduct->branch_id)
                                                        @if ($adjustmentProduct?->branch?->parentBranch)
                                                            {{ $adjustmentProduct?->branch?->parentBranch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                                        @else
                                                            {{ $adjustmentProduct?->branch?->name . '(' . $adjustmentProduct?->branch?->area_name . ')' . '-(' . $adjustmentProduct?->branch?->branch_code . ')' }}
                                                        @endif
                                                    @else
                                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $adjustmentProduct->quantity . '/' . $adjustmentProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        {{-- <p class="fw-bold">{{ __("Stock Adjusmtent Recovered Against Voucher.") }}</p> --}}
                        @include('stock_adjustments.ajax_view.partials.stock_adjustment_details_receipt_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Total Qty') }} : </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($adjustment->total_qty) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($adjustment->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Recovered Amount') }} : {{ $adjustment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($adjustment->recovered_amount) }}
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
                            <p style="font-size:11px!important;"><strong>{{ __('Reason') }} : </strong></p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $adjustment->reason }}</p>
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
                                        <option {{ $generalSettings['print_page_size__stock_adjustment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Stock Adjustment') . '__' . $adjustment->voucher_no . '__' . $adjustment->date . '__' . $branchName;
                            @endphp
                            <a href="{{ route('stock.adjustments.print', $adjustment->id) }}" onclick="printStockAdjustment(this); return false;" class="footer_btn btn btn-sm btn-success" id="printStockAdjustmentBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printStockAdjustment(event) {

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
