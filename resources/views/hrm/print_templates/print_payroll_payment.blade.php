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
            line-height: 12px !important;
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
    <!-- Payment print templete-->
    <div class="payroll_payment_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($payment->branch)

                        @if ($payment?->branch?->parent_branch_id)

                            @if ($payment->branch?->parentBranch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ file_link('branchLogo', $payment->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payment->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($payment->branch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ file_link('branchLogo', $payment->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payment->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @if ($payment?->branch)
                            @if ($payment?->branch?->parent_branch_id)
                                {{ $payment?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $payment?->branch?->parentBranch?->name . '(' . $payment?->branch?->area_name . ')';
                                @endphp
                            @else
                                {{ $payment?->branch?->name }}
                                @php
                                    $branchName = $payment?->branch?->name . '(' . $payment?->branch?->area_name . ')';
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
                        @if ($payment?->branch)
                            {{ $payment->branch->city . ', ' . $payment->branch->state . ', ' . $payment->branch->zip_code . ', ' . $payment->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($payment?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $payment?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $payment?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Payroll Payment Voucher') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($payment->date)) }}
                        </li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $payment->voucher_no }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Paid Amount') }} : </span>{{ App\Utils\Converter::format_in_bdt($payment->total_amount) }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Reference') }} : </span>
                            @if ($payment?->payrollRef)
                                {{ __('Payroll') }} : {{ $payment?->payrollRef?->voucher_no }}
                            @endif
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Expense A/c') }} : </span>
                            @if ($payment?->payrollRef?->expenseAccount)
                                {{ __('Payroll') }} : {{ $payment?->payrollRef?->expenseAccount?->name }}
                            @endif
                        </li>

                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $payment?->createdBy?->prefix . ' ' . $payment?->createdBy?->name . ' ' . $payment?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <p class="fw-bold">{{ __('Paid To') }} :</p>
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
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Paid Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($payment?->total_amount) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="col-6">
                    <p class="fw-bold">{{ __('Paid From') }} : </p>
                    @foreach ($payment->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                        <table class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Credit A/c') }} : </th>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $description?->account?->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Method/Type') }} : </th>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $description?->paymentMethod?->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Transaction No') }} : </th>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $description?->transaction_no }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque No') }} : </th>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $description?->cheque_no }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Cheque Serial No') }} : </th>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $description?->cheque_serial_no }}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    @endforeach
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $payment->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('speeddigit.show_app_info_in_print') == true)
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
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
    @php
        $filename = __('Payroll Payment') . '__' . $payment->voucher_no . '__' . $payment->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
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
            line-height: 12px !important;
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
    <!-- Payment print templete-->
    <div class="payroll_payment_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($payment->branch)

                        @if ($payment?->branch?->parent_branch_id)

                            @if ($payment->branch?->parentBranch?->logo)
                                <img style="height: 40px; width:200px;" src="{{ file_link('branchLogo', $payment->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payment->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($payment->branch?->logo)
                                <img style="height: 40px; width:200px;" src="{{ file_link('branchLogo', $payment->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payment->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:200px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase; font-size:9px;" class="p-0 m-0 fw-bold">
                        @if ($payment?->branch)
                            @if ($payment?->branch?->parent_branch_id)
                                {{ $payment?->branch?->parentBranch?->name }}
                                @php
                                    $branchName = $payment?->branch?->parentBranch?->name . '(' . $payment?->branch?->area_name . ')';
                                @endphp
                            @else
                                {{ $payment?->branch?->name }}
                                @php
                                    $branchName = $payment?->branch?->name . '(' . $payment?->branch?->area_name . ')';
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
                        @if ($payment?->branch)
                            {{ $payment->branch->city . ', ' . $payment->branch->state . ', ' . $payment->branch->zip_code . ', ' . $payment->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px;">
                        @if ($payment?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $payment?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $payment?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 class="fw-bold" style="text-transform: uppercase;">{{ __('Payroll Payment Voucher') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }} : </span>
                            {{ date($dateFormat, strtotime($payment->date)) }}
                        </li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Voucher No') }} : </span>{{ $payment->voucher_no }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Paid Amount') }} : </span>{{ App\Utils\Converter::format_in_bdt($payment->total_amount) }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Reference') }} : </span>
                            @if ($payment?->payrollRef)
                                {{ __('Payroll') }} : {{ $payment?->payrollRef?->voucher_no }}
                            @endif
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Expense A/c') }} : </span>
                            @if ($payment?->payrollRef?->expenseAccount)
                                {{ __('Payroll') }} : {{ $payment?->payrollRef?->expenseAccount?->name }}
                            @endif
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $payment?->createdBy?->prefix . ' ' . $payment?->createdBy?->name . ' ' . $payment?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <p style="font-size:9px;" class="fw-bold">{{ __('Paid To') }} :</p>
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Employee') }} : </th>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $payment?->payrollRef?->user?->prefix . ' ' . $payment?->payrollRef?->user?->name . ' ' . $payment?->payrollRef?->user?->last_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Employee ID') }} : </th>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $payment?->payrollRef?->user?->emp_id }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Phone') }} : </th>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $payment?->payrollRef?->user?->phone }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Address') }} : </th>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $payment?->payrollRef?->user?->address }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Paid Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-start fw-bold" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($payment?->total_amount) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="col-6">
                    <p style="font-size:9px;" class="fw-bold">{{ __('Paid From') }} : </p>
                    @foreach ($payment->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                        <table class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Credit A/c') }} : </th>
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $description?->account?->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Method/Type') }} : </th>
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $description?->paymentMethod?->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Transaction No') }} : </th>
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $description?->transaction_no }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Cheque No') }} : </th>
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $description?->cheque_no }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Cheque Serial No') }} : </th>
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $description?->cheque_serial_no }}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    @endforeach
                </div>
            </div>

            <br /><br />
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px;font-size:10px;">
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $payment->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('speeddigit.show_app_info_in_print') == true)
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
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

    @php
        $filename = __('Payroll Payment') . '__' . $payment->voucher_no . '__' . $payment->date . '__' . $branchName;
    @endphp
    <span id="title" class="d-none">{{ $filename }}</span>
@endif
