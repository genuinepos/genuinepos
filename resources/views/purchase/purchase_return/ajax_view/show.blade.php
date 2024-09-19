@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $account = $return?->supplier;
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
                    {{ __('Purchase Return Details') }} | ({{ __('Voucher No') }} : <strong>{{ $return->voucher_no }}</strong>)
                </h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Supplier') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong> <span>{{ $return->supplier->name }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong> <span class="supplier_address">{{ $return->supplier->address }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong> <span>{{ $return->supplier->phone }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($return->date)) . ' ' . date($timeFormat, strtotime($return->time)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong> {{ $return->voucher_no }}</li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $return?->createdBy?->prefix . ' ' . $return?->createdBy?->name . ' ' . $return?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($return->branch_id) {
                                        if ($return?->branch?->parentBranch) {
                                            $branchName = $order?->branch?->parentBranch?->name . '(' . $return?->branch?->area_name . ')' . '-(' . $return?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $return?->branch?->name . '(' . $return?->branch?->area_name . ')' . '-(' . $return?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($return?->branch)
                                    {{ $return?->branch?->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Product') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Stock Location') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Return Qty') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost(Exc. Tax)') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Discount') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost(Inc. Tax)') }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody class="purchase_return_product_list">
                                @foreach ($return->purchaseReturnProducts as $purchaseReturnProduct)
                                    <tr>
                                        @php
                                            $variant = $purchaseReturnProduct->variant ? ' - ' . $purchaseReturnProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $purchaseReturnProduct->product->name . $variant }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($purchaseReturnProduct?->warehouse)
                                                {{ $purchaseReturnProduct?->warehouse?->warehouse_name . '/' . $purchaseReturnProduct?->warehouse?->warehouse_code . '-(WH)' }}
                                            @else
                                                @if ($purchaseReturnProduct->branch_id)
                                                    @if ($purchaseReturnProduct?->branch?->parentBranch)
                                                        {{ $purchaseReturnProduct?->branch?->parentBranch?->name . '(' . $purchaseReturnProduct?->branch?->area_name . ')' . '-(' . $purchaseReturnProduct?->branch?->branch_code . ')' }}
                                                    @else
                                                        {{ $purchaseReturnProduct?->branch?->name . '(' . $purchaseReturnProduct?->branch?->area_name . ')' . '-(' . $purchaseReturnProduct?->branch?->branch_code . ')' }}
                                                    @endif
                                                @else
                                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                                @endif
                                            @endif
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($purchaseReturnProduct->return_qty) }}/{{ $purchaseReturnProduct?->unit?->code_name }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($purchaseReturnProduct->unit_cost_exc_tax) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($purchaseReturnProduct->unit_discount_amount) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ '(' . $purchaseReturnProduct->unit_tax_percent . ')=' . App\Utils\Converter::format_in_bdt($purchaseReturnProduct->unit_tax_amount) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($purchaseReturnProduct->unit_cost_inc_tax) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($purchaseReturnProduct->return_subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Payments Against Purchase') }}</p>
                        @include('purchase.purchase_return.ajax_view.partials.purchase_return_details_payment_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Net Total Amount') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Return Discount') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ $return->return_discount_type == 1 ? '(Fixed)=' : '%=' }} {{ $return->return_discount_amount }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Return Tax') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ '(' . $return->return_tax_percent . '%)=' . $return->return_tax_amount }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Returned Amount') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Received Amount') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->received_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due (On Return Voucher)') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        @if ($return->due < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($return->due)) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($return->due) }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Current Balance') }} : {{ $return?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
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

                <hr class="m-0 mt-3">

                <div class="row g-0 mt-1">
                    <div class="col-md-6 offset-md-6">
                        <div class="input-group p-0">
                            <label class="col-4 text-end pe-1 offset-md-6"><b>{{ __('Print') }}</b></label>
                            <div class="col-2">
                                <select id="print_page_size" class="form-control">
                                    @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                        <option {{ $generalSettings['print_page_size__purchase_return_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @php
                    $filename = __('Purchase Return') . '__' . $return->voucher_no . '__' . $return->date . '__' . $branchName;
                @endphp

                @if (auth()->user()->can('purchase_return_edit') && $return->branch_id == auth()->user()->branch_id)
                    <a href="{{ route('purchase.returns.edit', $return->id) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                @endif

                <a href="{{ route('purchase.returns.print', $return->id) }}" onclick="printPurchaseReturn(this); return false;" class="btn btn-sm btn-success" id="printPurchaseReturnBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<script>
    function printPurchaseReturn(event) {

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
