@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value)
    <style>
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        .print_table th {
            font-size: 11px !important;
            font-weight: 550 !important;
            line-height: 12px !important;
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
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 25px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- Contra print templete-->
    <div class="contra_voucher_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($contra->branch)

                        @if ($contra?->branch?->parent_branch_id)

                            @if ($contra->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $contra->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $contra->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($contra->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $contra->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $contra->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @if ($contra?->branch)
                            @if ($contra?->branch?->parent_branch_id)
                                {{ $contra?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $contra?->branch?->parentBranch?->name . '(' . $contra?->branch?->area_name . ')';
                                @endphp
                            @else
                                {{ $contra?->branch?->name }}
                                @php
                                    $branchName = $contra?->branch?->name . '(' . $contra?->branch?->area_name . ')';
                                @endphp
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                    </p>

                    <p>
                        @if ($contra?->branch)
                            {{ $contra->branch->city . ', ' . $contra->branch->state . ', ' . $contra->branch->zip_code . ', ' . $contra->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($contra?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $contra?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $contra?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Contra Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($contra->date)) }}
                        </li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $contra->voucher_no }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Total Amount') }} : </span>{{ App\Utils\Converter::format_in_bdt($contra->total_amount) }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Reference') }} : </span>
                            {{ $contra->reference }}
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $contra?->createdBy?->prefix . ' ' . $contra?->createdBy?->name . ' ' . $contra?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            @php
                $debitDescription = $contra->voucherDebitDescription;
                $creditDescription = $contra->voucherCreditDescription;
            @endphp

            <div class="row mt-4">
                <div class="col-6">
                    <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:11px!important;">{{ __('Credit A/c Details') }} : </p>
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
                                    : {{ App\Utils\Converter::format_in_bdt($creditDescription?->amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="col-6">
                    <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:11px!important;">{{ __('Debit A/c Details') }} :</p>
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
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Received Amount') }} {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important;">
                                    : {{ App\Utils\Converter::format_in_bdt($debitDescription?->amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                </td>
                            </tr>
                        </thead>
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($contra->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $contra->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <span class="fw-bold">{{ __('SpeedDigit Software Solution.') }}</span></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $filename = __('Contra') . '__' . $contra->voucher_no . '__' . $contra->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
    <!-- Contra print templete end-->
    <!-- Contra print templete end-->
@else
    <style>
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        .print_table th {
            font-size: 11px !important;
            font-weight: 550 !important;
            line-height: 12px !important;
        }

        .print_table tr td {
            color: black;
            font-size: 10px !important;
            line-height: 12px !important
        }

        @page {
            size: 5.8in 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 25px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- Contra print templete-->
    <div class="contra_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($contra->branch)

                        @if ($contra?->branch?->parent_branch_id)

                            @if ($contra->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $contra->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $contra->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($contra->branch?->logo)
                                <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $contra->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $contra->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase; font-size:9px;" class="p-0 m-0 fw-bold">
                        @if ($contra?->branch)
                            @if ($contra?->branch?->parent_branch_id)
                                {{ $contra?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $contra?->branch?->parentBranch?->name . '(' . $contra?->branch?->area_name . ')';
                                @endphp
                            @else
                                {{ $contra?->branch?->name }}
                                @php
                                    $branchName = $contra?->branch?->name . '(' . $contra?->branch?->area_name . ')';
                                @endphp
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                            @php
                                $branchName = $generalSettings['business_or_shop__business_name'];
                            @endphp
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($contra?->branch)
                            {{ $contra->branch->city . ', ' . $contra->branch->state . ', ' . $contra->branch->zip_code . ', ' . $contra->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($contra?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $contra?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $contra?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 class="fw-bold" style="text-transform: uppercase;">{{ __('Contra Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($contra->date)) }}
                        </li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $contra->voucher_no }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Total Amount') }} : </span>{{ App\Utils\Converter::format_in_bdt($contra->total_amount) }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Reference') }} : </span>
                            {{ $contra->reference }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $contra?->createdBy?->prefix . ' ' . $contra?->createdBy?->name . ' ' . $contra?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            @php
                $debitDescription = $contra->voucherDebitDescription;
                $creditDescription = $contra->voucherCreditDescription;
            @endphp

            <div class="row mt-4">
                <div class="col-6">
                    <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:9px!important;">{{ __('Credit A/c Details') }} : </p>
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Sender A/c') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    @php
                                        $accountNumber = $creditDescription?->account?->account_number ? ' / ' . $creditDescription?->account?->account_number : '';
                                    @endphp
                                    : {{ $creditDescription?->account?->name . $accountNumber }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Method/Type') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    : {{ $creditDescription?->paymentMethod?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Transaction No') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    : {{ $creditDescription?->transaction_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Cheque No') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    : {{ $creditDescription?->cheque_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Cheque Serial No') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    : {{ $creditDescription?->cheque_serial_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Send Amount') }}</th>
                                <td class="text-start fw-bold" style="font-size:9px!important;">
                                    : {{ App\Utils\Converter::format_in_bdt($creditDescription?->amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="col-6">
                    <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:9px!important;">{{ __('Debit A/c Details') }} :</p>
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Receiver A/c') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    : {{ $debitDescription?->account?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('A/c Number') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    @php
                                        $accountNumber = $debitDescription?->account?->account_number ? ' / ' . $debitDescription?->account?->account_number : '';
                                    @endphp
                                    : {{ $accountNumber }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Bank') }}</th>
                                <td class="text-start" style="font-size:9px!important;">
                                    @php
                                        $bank = $debitDescription?->account?->bank ? $debitDescription?->account?->bank?->name : '';
                                    @endphp
                                    : {{ $bank }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Received Amount') }} {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-start fw-bold" style="font-size:9px!important;">
                                    : {{ App\Utils\Converter::format_in_bdt($debitDescription?->amount) }} {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px;">
                        {{ __('Prepared By') }}
                    </p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($contra->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $contra->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <span class="fw-bold">{{ __('SpeedDigit Software Solution.') }}</span></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $filename = __('Contra') . '__' . $contra->voucher_no . '__' . $contra->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
    <!-- Contra print templete end-->
@endif
