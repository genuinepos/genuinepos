@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ __('Production Details') }} ({{ __('Voucher No') }} : <strong>{{ $production->voucher_no }}</strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong> {{ $production->voucher_no }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>{{ date($dateFormat, strtotime($production->date)) }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Stored Location') }} : </strong>
                                @if ($production->storeWarehouse)

                                    {{ $production->storeWarehouse->warehouse_name . '/' . $production->storeWarehouse->warehouse_code }}<b>({{ __('WH') }})</b>
                                @else
                                    @if ($production->branch_id)

                                        @if ($production?->branch?->parentBranch)
                                            {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                        @else
                                            {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                        @endif
                                    @else
                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                    @endif
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Ingredients Stock Location') }} : </strong>
                                @if ($production->stockWarehouse)

                                    {{ $production->stockWarehouse->warehouse_name . '/' . $production->stockWarehouse->warehouse_code }}<b>({{ __('WH)') }})</b>
                                @else
                                    @if ($production->branch_id)

                                        @if ($production?->branch?->parentBranch)
                                            {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                        @else
                                            {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                        @endif
                                    @else
                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                    @endif
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Mfd. Product') }} : </strong>
                                {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Status') }} : </strong>
                                {{ \App\Enums\ProductionStatus::tryFrom($production->status)->name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @if ($production->branch_id)

                                    @if ($production?->branch?->parentBranch)
                                        {{ $production?->branch?->parentBranch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $production?->branch?->name . '(' . $production?->branch?->area_name . ')' . '-(' . $production?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($production->branch)
                                    {{ $production->branch->phone }}
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
                        <p style="font-size:11px!important;"><strong>{{ __('Ingredients List') }}</strong></p>
                        <div class="table-responsive">
                            <table class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Ingredient Name') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Input Qty') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_print_product_list">
                                    @foreach ($production->ingredients as $ingredient)
                                        <tr>
                                            @php
                                                $variant = $ingredient->variant_id ? ' -' . $ingredient->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ Str::limit($ingredient->product->name, 40) . ' ' . $variant }}</td>
                                            <td class="text-start" style="font-size:11px!important;">{{ $ingredient->final_qty . '/' . $ingredient?->unit?->code_name }}</td>
                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                            <td class="text-start" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_tax_amount) }}</td>
                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p style="font-size:11px!important;"><strong>{{ __('Production Quantity And Net Cost') }}</strong></p>
                        <table class="table modal-table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Output Qty') }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ $production->total_output_quantity . '/' . $production?->unit?->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Wasted Qty') }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ $production->total_wasted_quantity . '/' . $production?->unit?->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Final Output Qty') }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ $production->total_final_output_quantity . '/' . $production?->unit?->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Additional Production Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($production->additional_production_cost) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Cost') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($production->net_cost) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 text-end">
                        <p style="font-size:11px!important;"><strong>{{ __('Product Costing And Pricing') }}</strong></p>
                        <table class="table modal-table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Per Unit Cost Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_exc_tax) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ '(' . $production->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($production->unit_tax_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Per Unit Cost Inc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($production->per_unit_cost_inc_tax) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Profit Margin') }}(%)</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ $production->profit_margin }}%
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Selling Price Exc. Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($production->per_unit_price_exc_tax) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                        <option @selected($generalSettings['print_page_size__bom_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end gap-2">
                        @if (auth()->user()->can('production_edit'))

                            <a href="{{ route('manufacturing.productions.edit', $production->id) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                        @endif
                        <a href="{{ route('manufacturing.productions.print', $production->id) }}" onclick="printProduction(this); return false;" class="btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __('Print') }}</a>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<script>
    function printProduction(event) {

        var url = event.getAttribute('href');
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

                var tempElement = document.createElement('div');
                tempElement.innerHTML = data;
                var filename = tempElement.querySelector('#title');

                document.title = filename.innerHTML;

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
