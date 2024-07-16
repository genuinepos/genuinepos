@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog four-col-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Expense Details') }} ({{ __('Voucher No') }} : <strong>{{ $expense->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                                {{ date($dateFormat, strtotime($expense->date)) }}
                            </li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $expense->voucher_no }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Total Expense Amount') }} : </strong>{{ App\Utils\Converter::format_in_bdt($expense->total_amount) }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Reference') }} : </strong>
                                {{ $expense->reference }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Created By') }} : </strong>
                                {{ $expense?->createdBy?->prefix . ' ' . $expense?->createdBy?->name . ' ' . $expense?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($expense->branch_id) {

                                        if ($expense?->branch?->parentBranch) {

                                            $branchName = $expense?->branch?->parentBranch?->name . '(' . $expense?->branch?->area_name . ')' . '-(' . $expense?->branch?->branch_code . ')';
                                        } else {

                                            $branchName = $expense?->branch?->name . '(' . $expense?->branch?->area_name . ')' . '-(' . $expense?->branch?->branch_code . ')';
                                        }
                                    } else {

                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($expense->branch)
                                    {{ $expense->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="p-0 m-1">

                @php
                    $creditDescription = $expense
                        ->voucherDescriptions()
                        ->where('amount_type', 'cr')
                        ->first();
                    $debitDescriptions = $expense
                        ->voucherDescriptions()
                        ->where('amount_type', 'dr')
                        ->get();
                @endphp

                <div class="row mt-2">
                    <div class="col-12">
                        <p class="fw-bold" style="border-bottom: 1px solid black;font-size:11px!important;">{{ __('Credit A/c Details') }} :</p>
                        <div class="table-responsive">
                            <table class="table print-table table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Credit A/c') }}</th>
                                        <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                            @php
                                                $accountNumber = $creditDescription?->account?->account_number ? ' / ' . $creditDescription?->account?->account_number : '';
                                            @endphp
                                            : {{ $creditDescription?->account?->name . $accountNumber }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Type/Method') }}</th>
                                        <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->paymentMethod?->name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Transaction No') }}</th>
                                        <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->tanasaction_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque No') }}</th>
                                        <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->cheque_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque Serial No') }}</th>
                                        <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                            : {{ $creditDescription?->cheque_serial_no }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Total Expense Paid') }}</th>
                                        <td style="width: 70%;" class="text-start fw-bold" style="font-size:11px!important;">
                                            : {{ App\Utils\Converter::format_in_bdt($expense?->total_amount) }} {{ $expense?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p class="fw-bold" style="border-bottom: 1px solid black;font-size:11px!important;">{{ __('Expesne Descriptions') }} : </p>
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <thead>
                                    <tr>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Serial No') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Expense Ledger Name') }}</th>
                                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($debitDescriptions as $debitDescription)
                                        <tr>
                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $loop->index + 1 }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $debitDescription?->account?->name }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($debitDescription?->amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">{{ __('Total') }} : ({{ $expense?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($expense?->total_amount) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>{{ __('Remarks') }}</strong></p>
                            <p class="shipping_details" style="font-size:11px!important;">{{ $expense->remarks }}</p>
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
                                        <option {{ $generalSettings['print_page_size__expense_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
                                $filename = __('Expense') . '__' . $expense->voucher_no . '__' . $expense->date . '__' . $branchName;
                            @endphp
                            <a href="{{ route('expenses.print', $expense->id) }}" onclick="printExpenseVoucher(this); return false;" class="btn btn-sm btn-success" id="printExpenseVoucherBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printExpenseVoucher(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
        var print_page_size = $('#print_page_size').val();
        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: { print_page_size },
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
