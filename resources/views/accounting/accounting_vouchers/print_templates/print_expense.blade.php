@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    .print_table th {
        font-size: 11px !important;
        font-weight: 550 !important;
        line-height: 12px !important
    }

    .print_table tr td {
        color: black;
        font-size: 10px !important;
        line-height: 12px !important
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    div#footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }
</style>
<!-- Payment print templete-->
<div class="purchase_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($expense->branch)

                    @if ($expense?->branch?->parent_branch_id)

                        @if ($expense->branch?->parentBranch?->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $expense->branch?->parentBranch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $expense->branch?->parentBranch?->name }}</span>
                        @endif
                    @else
                        @if ($expense->branch?->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $expense->branch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $expense->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business_or_shop__business_logo'] != null)
                        <img src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                    @endif
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>
                        @if ($expense?->branch)
                            @if ($expense?->branch?->parent_branch_id)
                                {{ $expense?->branch?->parentBranch?->name }}
                            @else
                                {{ $expense?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($expense?->branch)
                        {{ $expense->branch->city . ', ' . $expense->branch->state . ', ' . $expense->branch->zip_code . ', ' . $expense->branch->country }}
                    @else
                        {{ $generalSettings['business_or_shop__address'] }}
                    @endif
                </p>

                <p>
                    @if ($expense?->branch)
                        <strong>{{ __('Email') }} : </strong> {{ $expense?->branch?->email }},
                        <strong>{{ __('Phone') }} : </strong> {{ $expense?->branch?->phone }}
                    @else
                        <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business_or_shop__email'] }},
                        <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business_or_shop__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 class="fw-bold" style="text-transform: uppercase;">{{ __('Expense Voucher') }}</h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Date') }} : </strong>
                        {{ date($generalSettings['business_or_shop__date_format'], strtotime($expense->date)) }}
                    </li>
                    <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $expense->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Total Expense Amount') }} : </strong>{{ App\Utils\Converter::format_in_bdt($expense->total_amount) }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Reference') }} : </strong>
                        {{ $expense->reference }}
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __('Created By') }} : </strong>
                        {{ $expense?->createdBy?->prefix . ' ' . $expense?->createdBy?->name . ' ' . $expense?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

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
                <table class="table print-table table-sm">
                    <thead>
                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Credit A/c') }} : </th>
                            <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                : {{ $creditDescription?->account?->name }}
                            </td>
                        </tr>

                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Type/Method') }} : </th>
                            <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                : {{ $creditDescription?->paymentMethod?->name }}
                            </td>
                        </tr>

                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Transaction No') }} : </th>
                            <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                : {{ $creditDescription?->tanasaction_no }}
                            </td>
                        </tr>

                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque No') }} : </th>
                            <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                : {{ $creditDescription?->cheque_no }}
                            </td>
                        </tr>

                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque Serial No') }} : </th>
                            <td style="width: 70%;" class="text-start" style="font-size:11px!important;">
                                : {{ $creditDescription?->cheque_serial_no }}
                            </td>
                        </tr>

                        <tr>
                            <th style="width: 30%;" class="text-start fw-bold" style="font-size:11px!important;">{{ __('Total Expense Paid') }} :</th>
                            <td style="width: 70%;" class="text-start fw-bold" style="font-size:11px!important;">
                                : {{ App\Utils\Converter::format_in_bdt($expense?->total_amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <p class="fw-bold" style="border-bottom: 1px solid black;font-size:11px!important;">{{ __('Expesne Descriptions') }} : </p>
                <table class="table report-table table-sm table-bordered print_table mt-1">
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
                            <th colspan="2" class="text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                            <th>{{ App\Utils\Converter::format_in_bdt($expense?->total_amount) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <br /><br />
        <div class="row">
            <div class="col-4 text-start">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __('Prepared By') }}
                </p>
            </div>

            <div class="col-4 text-center">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __('Checked By') }}
                </p>
            </div>

            <div class="col-4 text-end">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __('Authorized By') }}
                </p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($expense->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $expense->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <strong>{{ __('SpeedDigit Software Solution.') }}</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Payment print templete end-->