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
                    {{ __('Payment Details') }} ({{ __('Voucher No') }} : <strong>{{ $payment->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Date') }} : </strong>
                                {{ date($dateFormat, strtotime($payment->date)) }}
                            </li>
                            <li style="font-size:10px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $payment->voucher_no }}</li>
                            <li style="font-size:10px!important;"><strong>{{ __('Paid Amount') }} : </strong>{{ App\Utils\Converter::format_in_bdt($payment->total_amount) }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ __('Reference') }} : </strong>
                                @if ($payment?->purchaseRef)

                                    @if ($payment?->purchaseRef->purchase_status == \App\Enums\PurchaseStatus::Purchase->value)
                                        {{ __('Purchase') }} : {{ $payment?->purchaseRef?->invoice_id }}
                                    @elseif ($payment?->purchaseRef->purchase_status == \App\Enums\PurchaseStatus::PurchaseOrder->value)
                                        {{ __('P/o') }} : {{ $payment?->purchaseRef?->invoice_id }}
                                    @endif
                                @endif

                                @if ($payment?->salesReturnRef)
                                    {{ __('Purchase Return') }} : {{ $payment?->salesReturnRef->voucher_no }}
                                @endif
                            </li>

                            <li style="font-size:10px!important;"><strong>{{ __('Created By') }} : </strong>
                                {{ $payment?->createdBy?->prefix . ' ' . $payment?->createdBy?->name . ' ' . $payment?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:10px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($payment->branch_id) {
                                        if ($payment?->branch?->parentBranch) {
                                            $branchName = $payment?->branch?->parentBranch?->name . '(' . $payment?->branch?->area_name . ')' . '-(' . $payment?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $payment?->branch?->name . '(' . $payment?->branch?->area_name . ')' . '-(' . $payment?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:10px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($payment->branch)
                                    {{ $payment->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row mt-2">
                    <div class="col-6">
                        <p class="fw-bold">{{ __('Paid To') }} :</p>
                        @foreach ($payment->voucherDescriptions()->where('amount_type', 'dr')->get() as $description)
                            <div class="table-responsive">
                                <table class="table print-table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Credit A/c') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->account?->name }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Address') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->account?->address }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Phone') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->account?->phone }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Paid Amount') }} : {{ $payment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                            <td class="text-end fw-bold" style="font-size:10px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($description?->amount) }}
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-6">
                        <p class="fw-bold">{{ __('Paid From') }} : </p>
                        @foreach ($payment->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                            <div class="table-responsive">
                                <table class="table print-table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Credit A/c') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->account?->name }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Method/Type') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->paymentMethod?->name }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Transaction No') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->transaction_no }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Cheque No') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->cheque_no }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:10px!important;">{{ __('Cheque Serial No') }} : </th>
                                            <td class="text-end" style="font-size:10px!important;">
                                                {{ $description?->cheque_serial_no }}
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                @php
                    $debtiDescription = $payment->voucherDescriptions()->where('amount_type', 'dr')->first();
                @endphp

                <div class="purchase_product_table mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('Paid Against Vouchers') }}</p>
                            <div class="table-responsive">
                                <table class="display table modal-table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Date') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Voucher No') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Voucher Type') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="purchase_print_product_list">
                                        @php
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach ($debtiDescription->references as $reference)

                                            @php
                                                $isOrder = 0;
                                                if ($reference?->purchase?->purchase_status == App\Enums\PurchaseStatus::PurchaseOrder->value) {
                                                    $isOrder = 1;
                                                }
                                            @endphp

                                            @if ($isOrder == 0)
                                                <tr>
                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            {{ $reference?->purchase->date }}
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ $reference?->salesReturn->date }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            @if ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::Purchase->value)
                                                                {{ $reference?->purchase->invoice_id }}
                                                            @elseif ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::PurchaseOrder->value)
                                                                {{ $reference?->purchase->invoice_id }}
                                                            @endif
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ $reference?->salesReturn->voucher_no }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            @if ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::Purchase->value)
                                                                {{ __('Purchase') }}
                                                            @elseif ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::PurchaseOrder->value)
                                                                {{ __('P/o') }}
                                                            @endif
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ __('Sales Return') }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($reference->amount) }}</td>
                                                    @php
                                                        $totalAmount += $reference->amount;
                                                    @endphp
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">{{ __('Total') }} : </th>
                                            <th class="text-start">{{ App\Utils\Converter::format_in_bdt($totalAmount) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('Paid Against Order Vouchers') }}</p>
                            <div class="table-responsive">
                                <table class="display table modal-table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Date') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Voucher No') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Voucher Type') }}</th>
                                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="purchase_print_product_list">
                                        @php
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach ($debtiDescription->references as $reference)

                                            @php
                                                $isOrder = 0;
                                                if ($reference?->purchase?->purchase_status == App\Enums\PurchaseStatus::PurchaseOrder->value) {
                                                    $isOrder = 1;
                                                }
                                            @endphp

                                            @if ($isOrder == 1)
                                                <tr>
                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            {{ $reference?->purchase->date }}
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ $reference?->salesReturn->date }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            @if ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::Purchase->value)
                                                                {{ $reference?->purchase->invoice_id }}
                                                            @elseif ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::PurchaseOrder->value)
                                                                {{ $reference?->purchase->invoice_id }}
                                                            @endif
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ $reference?->salesReturn->voucher_no }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">
                                                        @if ($reference?->purchase)
                                                            @if ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::Purchase->value)
                                                                {{ __('Purchase') }}
                                                            @elseif ($reference?->purchase->purchase_status == \App\Enums\PurchaseStatus::PurchaseOrder->value)
                                                                {{ __('P/o') }}
                                                            @endif
                                                        @endif

                                                        @if ($reference?->salesReturn)
                                                            {{ __('Sales Return') }}
                                                        @endif
                                                    </td>

                                                    <td class="text-start" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($reference->amount) }}</td>
                                                    @php
                                                        $totalAmount += $reference->amount;
                                                    @endphp
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">{{ __('Total') }} : </th>
                                            <th class="text-start">{{ App\Utils\Converter::format_in_bdt($totalAmount) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:10px!important;"><strong>{{ __('Remarks') }}</strong></p>
                            <p class="shipping_details" style="font-size:10px!important;">{{ $payment->remarks }}</p>
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
                                        <option {{ $generalSettings['print_page_size__payment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Payment') . '__' . $payment->voucher_no . '__' . $payment->date . '__' . $branchName;
                            @endphp
                            {{-- <button type="submit" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __('Print') }}</button> --}}
                            <a href="{{ route('payments.print', $payment->id) }}" onclick="printPaymentVoucher(this); return false;" class="btn btn-sm btn-success" id="printPaymentVoucherBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printPaymentVoucher(event) {

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
