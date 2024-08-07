@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
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
                                @php
                                    $senderBranchName = '';
                                    if ($transferStock?->senderBranch) {
                                        if ($transferStock?->senderBranch?->parent_branch_id) {
                                            $senderBranchName = $transferStock?->senderBranch?->parentBranch?->name;
                                        } else {
                                            $senderBranchName = $transferStock?->senderBranch?->name;
                                        }
                                    } else {
                                        $senderBranchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $senderBranchName }}
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
                                    {{ $generalSettings['business_or_shop__business_name'] }}
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
                                {{ date($dateFormat, strtotime($transferStock->date)) }}
                            </li>

                            @if ($transferStock->receive_date)
                                <li style="font-size:11px!important;"><strong>{{ __('Received Date') }} : </strong>
                                    {{ date($dateFormat, strtotime($transferStock->receive_date)) }}
                                </li>
                            @endif

                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $transferStock->voucher_no }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @if ($transferStock->branch_id)

                                    @if ($transferStock?->branch?->parentBranch)
                                        {{ $transferStock?->branch?->parentBranch?->name . '(' . $transferStock?->branch?->area_name . ')' . '-(' . $transferStock?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $transferStock?->branch?->name . '(' . $transferStock?->branch?->area_name . ')' . '-(' . $transferStock?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($transferStock?->branch)
                                    {{ $transferStock->branch->phone }}
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
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Stock Value') }} : ({{ $transferStock?->receiverBranch?->branchCurrency?->symbol ?? $generalSettings['base_currency_symbol'] }})</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($transferStock->total_stock_value) }}
                                    </td>
                                </tr>

                                @if ($transferStock->received_stock_value > 0)
                                    <tr>
                                        <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Received Stock Value') }} : ({{ $transferStock?->receiverBranch?->branchCurrency?->symbol ?? $generalSettings['base_currency_symbol'] }})</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($transferStock->received_stock_value) }}
                                        </td>
                                    </tr>
                                @endif
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
                                        <option {{ $generalSettings['print_page_size__transfer_stock_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Transfer Stock') . '__' . $transferStock->voucher_no . '__' . $transferStock->date . '__' . $senderBranchName;
                            @endphp
                            <a href="{{ route('transfer.stocks.edit', [$transferStock->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                            <a href="{{ route('transfer.stocks.print', $transferStock->id) }}" onclick="printTransferStock(this); return false;" class="footer_btn btn btn-sm btn-success" id="printTransferStockBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printTransferStock(event) {

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
