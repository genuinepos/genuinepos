@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="view-modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('Process/Bill Of Materials') }}</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Product Name') }} : </strong>
                                {{ $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')' }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Created From') }} : </strong>
                                @if ($process->branch_id)

                                    @if ($process?->branch?->parentBranch)
                                        {{ $process?->branch?->parentBranch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $process?->branch?->name . '(' . $process?->branch?->area_name . ')' . '-(' . $process?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] . '('.__('Company').')' }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Ingredient') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($process->ingredients as $ingredient)
                                <tr>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $ingredient->product->name . ' ' . $ingredient?->variant?->variant_name }}
                                    </td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->name }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="display data_tbl data__table">
                            <tr>
                                <th colspan="5" class="text-end">{{ __('Total Ingredients') }} : {{ $process?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-start">{{ App\Utils\Converter::format_in_bdt($process->total_ingredient_cost) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <th class="text-end">{{ __('Total Output Qty') }} : </th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->total_output_qty) . '/' . $process?->unit?->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">{{ __('Instructions') }} :</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <th class="text-end">{{ __('Addl. Production Cost') }} : {{ $process?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->additional_production_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">{{ __('Net Cost') }} : {{ $process?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->net_cost) }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                    <div class="col-md-12">
                        @if (auth()->user()->can('process_edit'))
                            <a href="{{ route('manufacturing.process.edit', [$process->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                        @endif

                        <a href="{{ route('manufacturing.process.print', $process->id) }}" onclick="printProcess(this); return false;" class="btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __('Print') }}</a>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printProcess(event) {

        var url = event.getAttribute('href');
        var print_page_size = $('#print_page_size').val();

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: { print_page_size },
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
