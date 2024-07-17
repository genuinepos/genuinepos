@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog four-col-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('Contra Details') }} ({{ __('Voucher No') }} : <strong>{{ $contra->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                                {{ date($dateFormat, strtotime($contra->date)) }}
                            </li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $contra->voucher_no }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Total Expense Amount') }} : </strong>{{ App\Utils\Converter::format_in_bdt($contra->total_amount) }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Reference') }} : </strong>
                                {{ $contra->reference }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Created By') }} : </strong>
                                {{ $contra?->createdBy?->prefix . ' ' . $contra?->createdBy?->name . ' ' . $contra?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($contra->branch_id) {
                                        if ($contra?->branch?->parentBranch) {
                                            $branchName = $contra?->branch?->parentBranch?->name . '(' . $contra?->branch?->area_name . ')' . '-(' . $contra?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $contra?->branch?->name . '(' . $contra?->branch?->area_name . ')' . '-(' . $contra?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($contra->branch)
                                    {{ $contra->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="p-0 m-1">

                @php
                    $debitDescription = $contra->voucherDebitDescription;
                    $creditDescription = $contra->voucherCreditDescription;
                @endphp

                <div class="row mt-2">
                    <div class="col-6">
                        <p class="fw-bold" style="border-bottom: 1px solid black;font-size:11px!important;">{{ __('Credit A/c Details') }} :</p>
                        <div class="table-responsive">
                            <table class="table print-table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Sender A/c') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            @php
                                                $accountNumber = $creditDescription?->account?->account_number ? ' / ' . $creditDescription?->account?->account_number : '';
                                            @endphp
                                            : {{ $creditDescription?->account?->name . $accountNumber }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Method/Type') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->paymentMethod?->name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Transaction No') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->transaction_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque No') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->cheque_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque Serial No') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->cheque_serial_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Send Amount') }}</th>
                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            : {{ App\Utils\Converter::format_in_bdt($creditDescription?->amount) }} {{ $contra?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="col-6">
                        <p class="fw-bold" style="border-bottom: 1px solid black;font-size:11px!important;">{{ __('Debit A/c Details') }} :</p>
                        <div class="table-responsive">
                            <table class="table print-table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Receiver A/c') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            : {{ $debitDescription?->account?->name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('A/c Number') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            @php
                                                $accountNumber = $debitDescription?->account?->account_number ? ' / ' . $debitDescription?->account?->account_number : '';
                                            @endphp
                                            : {{ $accountNumber }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Bank') }}</th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            @php
                                                $bank = $debitDescription?->account?->bank ? $debitDescription?->account?->bank?->name : '';
                                            @endphp
                                            : {{ $bank }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Received Amount') }} {{ $contra?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            : {{ App\Utils\Converter::format_in_bdt($debitDescription?->amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Remarks') }}</strong></p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $contra->remarks }}</p>
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
                                        <option {{ $generalSettings['print_page_size__sales_order_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Contra') . '__' . $contra->voucher_no . '__' . $contra->date . '__' . $branchName;
                            @endphp
                            <a href="{{ route('contras.print', $contra->id) }}" onclick="printContraVoucher(this); return false;" class="btn btn-sm btn-success" id="printContraVoucherBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printContraVoucher(event) {

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

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    };
</script>
