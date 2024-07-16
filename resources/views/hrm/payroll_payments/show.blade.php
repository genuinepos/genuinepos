@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $previousRouteName = app('router')
        ->getRoutes()
        ->match(app('request')->create(url()->previous()))
        ->getName();
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog col-60-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('Payroll Payment Details') }} ({{ __('Voucher No') }} : <strong>{{ $payment->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                                {{ date($dateFormat, strtotime($payment->date)) }}
                            </li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $payment->voucher_no }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Paid Amount') }} : </strong>{{ App\Utils\Converter::format_in_bdt($payment->total_amount) }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Reference') }} : </strong>
                                @if ($payment?->payrollRef)
                                    {{ __('Payroll') }} :{{ $payment?->payrollRef?->voucher_no }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Created By') }} : </strong>
                                {{ $payment?->createdBy?->prefix . ' ' . $payment?->createdBy?->name . ' ' . $payment?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
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

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
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
                        <div class="table-responsive">
                            <table class="table print-table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Employee') }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->prefix . ' ' . $payment?->payrollRef?->user?->name . ' ' . $payment?->payrollRef?->user?->last_name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Employee ID') }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->emp_id }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Phone') }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->phone }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Address') }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->address }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Paid Amount') }} : {{ $payment?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($payment?->total_amount) }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="col-6">
                        <p class="fw-bold">{{ __('Paid From') }} : </p>
                        @foreach ($payment->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                            <div class="table-responsive">
                                <table class="table print-table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Credit A/c') }} : </th>
                                            <td class="text-end" style="font-size:11px!important;">
                                                {{ $description?->account?->name }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Method/Type') }} : </th>
                                            <td class="text-end" style="font-size:11px!important;">
                                                {{ $description?->paymentMethod?->name }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Transaction No') }} : </th>
                                            <td class="text-end" style="font-size:11px!important;">
                                                {{ $description?->transaction_no }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Cheque No') }} : </th>
                                            <td class="text-end" style="font-size:11px!important;">
                                                {{ $description?->cheque_no }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Cheque Serial No') }} : </th>
                                            <td class="text-end" style="font-size:11px!important;">
                                                {{ $description?->cheque_serial_no }}
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Remarks') }}</strong></p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $payment->remarks }}</p>
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
                                        <option {{ $generalSettings['print_page_size__payroll_payment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Payroll Payment') . '__' . $payment->voucher_no . '__' . $payment->date . '__' . $branchName;
                            @endphp
                            <a href="{{ route('hrm.payroll.payments.print', $payment->id) }}" onclick="printPayrollPayment(this); return false;" class="footer_btn btn btn-sm btn-success" id="printPayrollPaymentBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printPayrollPayment(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
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

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            }, error: function(err) {

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
