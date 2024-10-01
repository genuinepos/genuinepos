@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('Stock Issue Details') }} ({{ __('Voucher No') }} : <strong>{{ $stockIssue->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Date') }} : </strong> {{ date($dateFormat, strtotime($stockIssue->date)) }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Voucher No') }} : </strong>{{ $stockIssue->voucher_no }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Department') }} : </strong>{{ $stockIssue?->department?->name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Reported By') }} : </strong>
                                {{ $stockIssue?->reportedBy?->prefix . ' ' . $stockIssue?->reportedBy?->name . ' ' . $stockIssue?->reportedBy?->last_name }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $stockIssue?->createdBy?->prefix . ' ' . $stockIssue?->createdBy?->name . ' ' . $stockIssue?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($stockIssue->branch_id) {
                                        if ($stockIssue?->branch?->parentBranch) {
                                            $branchName = $stockIssue?->branch?->parentBranch?->name . '(' . $stockIssue?->branch?->area_name . ')' . '-(' . $stockIssue?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $stockIssue?->branch?->name . '(' . $stockIssue?->branch?->area_name . ')' . '-(' . $stockIssue?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($stockIssue->branch)
                                    {{ $stockIssue->branch->phone }}
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
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Stock Location') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost (Inc. Tax)') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($stockIssue->stockIssuedProducts as $issuedProduct)
                                        <tr>
                                            @php
                                                $variant = $issuedProduct?->variant ? ' - ' . $issuedProduct->variant->variant_name : '';
                                                $productCode = $issuedProduct?->variant ? $issuedProduct?->variant?->variant_code : $issuedProduct?->product?->product_code;
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $loop->index + 1 }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $issuedProduct->product->name . ' ' . $variant }}
                                                <small class="d-block" style="font-size:9px!important;">{{ __("P/c") }}: {{ $productCode }}</small>
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                @if ($issuedProduct?->stockWarehouse)
                                                    {{ $issuedProduct?->stockWarehouse?->warehouse_name . '/' . $issuedProduct?->stockWarehouse?->warehouse_code }}
                                                @else
                                                    @if ($stockIssue?->branch)
                                                        @if ($stockIssue?->branch?->parent_branch_id)
                                                            {{ $stockIssue?->branch?->parentBranch?->name }}
                                                        @else
                                                            {{ $stockIssue?->branch?->name }}
                                                        @endif
                                                    @else
                                                        {{ $generalSettings['business_or_shop__business_name'] }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($issuedProduct->quantity) }}/{{ $issuedProduct?->unit?->code_name }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($issuedProduct->unit_cost_inc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($issuedProduct->subtotal) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 offset-md-7">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Total Item') }} : </th>
                                    <td class="text-end">
                                        {{ $stockIssue->total_item }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Qty') }} : </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($stockIssue->total_qty) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($stockIssue->net_total_amount) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Remarks') }} : </strong></p>
                            <p style="font-size:11px!important;">{{ $stockIssue->remarks }}</p>
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
                                        <option value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                            @if (auth()->user()->can('stock_issues_edit') && $stockIssue->branch_id == auth()->user()->branch_id)
                                <a href="{{ route('stock.issues.edit', [$stockIssue->id]) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                            @endif
                            <a href="{{ route('stock.issues.print', $stockIssue->id) }}" onclick="printStockIssue(this); return false;" class="footer_btn btn btn-sm btn-success" id="printPurchaseBtn">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printStockIssue(event) {

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
