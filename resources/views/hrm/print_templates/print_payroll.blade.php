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

        @page {
            size: a4;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- Pay Slip print templete-->
    <div class="print_payroll_templete">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($payroll->branch)

                        @if ($payroll?->branch?->parent_branch_id)

                            @if ($payroll->branch?->parentBranch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $payroll->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payroll->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($payroll->branch?->logo)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $payroll->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payroll->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                        @if ($payroll?->branch)
                            @if ($payroll?->branch?->parent_branch_id)
                                {{ $payroll?->branch?->parentBranch?->name }}
                            @else
                                {{ $payroll?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p>
                        @if ($payroll?->branch)
                            {{ $payroll->branch->city . ', ' . $payroll->branch->state . ', ' . $payroll->branch->zip_code . ', ' . $payroll->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($payroll?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $payroll?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $payroll?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;" class="fw-bold">{{ __('Pay Slip') }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Employee') }} : - </span></li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Name') }} : </span> {{ $payroll?->user?->prefix . '  ' . $payroll?->user?->name . '  ' . $payroll?->user?->last_name }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Phone') }}: </span> {{ $payroll->user->phone }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Address') }} : </span> {{ $payroll->user->current_address }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Month') }} : </span> {{ $payroll->month . '-' . $payroll->year }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Generated On') }} : </span> {{ date($dateFormat, strtotime($payroll->date_ts)) }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Payroll Voucher No') }} : </span> {{ $payroll->voucher_no }}</li>
                        <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $payroll->gross_amount;
                            @endphp
                            @if ($payroll->due <= 0)
                                {{ __('Paid') }}
                            @elseif($payroll->due > 0 && $payroll->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $payroll->due)
                                {{ __('Due') }}
                            @endif
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $payroll?->createdBy?->prefix . ' ' . $payroll?->createdBy?->name . ' ' . $payroll?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <p class="fw-bold">{{ __('Allowances') }}</p>
                    <div class="table-responsive">
                        <table id="" class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payroll->allowances as $allowance)
                                    <tr>
                                        @php
                                            $name = $allowance?->allowance ? $allowance?->allowance?->name : $allowance->allowance_name;
                                        @endphp

                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:11px!important;">{{ $name }}</td>

                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            @php
                                                $allowanceAmountType = $allowance->amount_type == 2 ? '(' . $allowance->allowance_percent . '%)=' : '';
                                            @endphp
                                            {{ $allowanceAmountType . App\Utils\Converter::format_in_bdt($allowance->allowance_amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __('Total') }} : </td>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-6">
                    <p class="fw-bold">{{ __('Deductions') }}</p>
                    <div class="table-responsive">
                        <table id="" class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                    <th class="text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payroll->deductions as $deduction)
                                    <tr>
                                        @php
                                            $name = $deduction?->deduction ? $deduction?->deduction?->name : $deduction->deduction_name;
                                        @endphp

                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:11px!important;">{{ $name }}</td>

                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            @php
                                                $deductionAmountType = $deduction->amount_type == 2 ? '(' . $deduction->deduction_percent . '%)=' : '';
                                            @endphp
                                            {{ $deductionAmountType . App\Utils\Converter::format_in_bdt($deduction->deduction_amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __('Total') }} : </td>
                                    <td class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __('Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __('Total Allowance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __('Total Deduction') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __('Gross Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->paid) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __('Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->due) }}</td>
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
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payroll->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $payroll->voucher_no }}</p>
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
    <!-- Pay Slip print templete end-->
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

        @page {
            size: 5.8in 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- Pay Slip print templete-->
    <div class="print_payroll_templete">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($payroll->branch)

                        @if ($payroll?->branch?->parent_branch_id)

                            @if ($payroll->branch?->parentBranch?->logo)
                                <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $payroll->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payroll->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($payroll->branch?->logo)
                                <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $payroll->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $payroll->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase; font-size:9px!important" class="p-0 m-0 fw-bold">
                        @if ($payroll?->branch)
                            @if ($payroll?->branch?->parent_branch_id)
                                {{ $payroll?->branch?->parentBranch?->name }}
                            @else
                                {{ $payroll?->branch?->name }}
                            @endif
                        @else
                            {{ $generalSettings['business_or_shop__business_name'] }}
                        @endif
                    </p>

                    <p style="font-size:9px!important;">
                        @if ($payroll?->branch)
                            {{ $payroll->branch->city . ', ' . $payroll->branch->state . ', ' . $payroll->branch->zip_code . ', ' . $payroll->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:9px!important;">
                        @if ($payroll?->branch)
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $payroll?->branch?->email }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $payroll?->branch?->phone }}
                        @else
                            <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                            <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;" class="fw-bold">{{ __('Pay Slip') }}</h6>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Employee') }} : - </span></li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Name') }} : </span> {{ $payroll?->user?->prefix . '  ' . $payroll?->user?->name . '  ' . $payroll?->user?->last_name }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }}: </span> {{ $payroll->user->phone }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span> {{ $payroll->user->current_address }}</li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Month') }} : </span> {{ $payroll->month . '-' . $payroll->year }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Generated On') }} : </span> {{ date($dateFormat, strtotime($payroll->date_ts)) }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Payroll Voucher No') }} : </span> {{ $payroll->voucher_no }}</li>
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Payment Status') }} : </span>
                            @php
                                $payable = $payroll->gross_amount;
                            @endphp
                            @if ($payroll->due <= 0)
                                {{ __('Paid') }}
                            @elseif($payroll->due > 0 && $payroll->due < $payable)
                                {{ __('Partial') }}
                            @elseif($payable == $payroll->due)
                                {{ __('Due') }}
                            @endif
                        </li>

                        <li style="font-size:9px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span>
                            {{ $payroll?->createdBy?->prefix . ' ' . $payroll?->createdBy?->name . ' ' . $payroll?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <p class="fw-bold" style="font-size:9px!important;">{{ __('Allowances') }}</p>
                    <div class="table-responsive">
                        <table id="" class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('S/L') }}</th>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Name') }}</th>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payroll->allowances as $allowance)
                                    <tr>
                                        @php
                                            $name = $allowance?->allowance ? $allowance?->allowance?->name : $allowance->allowance_name;
                                        @endphp

                                        <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:9px!important;">{{ $name }}</td>

                                        <td class="text-start fw-bold" style="font-size:9px!important;">
                                            @php
                                                $allowanceAmountType = $allowance->amount_type == 2 ? '(' . $allowance->allowance_percent . '%)=' : '';
                                            @endphp
                                            {{ $allowanceAmountType . App\Utils\Converter::format_in_bdt($allowance->allowance_amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="fw-bold text-end" style="font-size:9px!important;">{{ __('Total') }} : </td>
                                    <td class="fw-bold" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-6">
                    <p class="fw-bold" style="font-size:9px!important;">{{ __('Deductions') }}</p>
                    <div class="table-responsive">
                        <table id="" class="table print-table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('S/L') }}</th>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Name') }}</th>
                                    <th class="text-start fw-bold" style="font-size:9px!important;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payroll->deductions as $deduction)
                                    <tr>
                                        @php
                                            $name = $deduction?->deduction ? $deduction?->deduction?->name : $deduction->deduction_name;
                                        @endphp

                                        <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:9px!important;">{{ $name }}</td>

                                        <td class="text-start fw-bold" style="font-size:9px!important;">
                                            @php
                                                $deductionAmountType = $deduction->amount_type == 2 ? '(' . $deduction->deduction_percent . '%)=' : '';
                                            @endphp
                                            {{ $deductionAmountType . App\Utils\Converter::format_in_bdt($deduction->deduction_amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="fw-bold text-end" style="font-size:9px!important;">{{ __('Total') }} : </td>
                                    <td class="fw-bold" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 offset-6">
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Total Allowance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Total Deduction') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Gross Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->paid) }}</td>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px!important;">{{ __('Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->due) }}</td>
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
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px;">
                        {{ __('Checked By') }}
                    </p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase fw-bold" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-size:10px;">
                        {{ __('Authorized By') }}
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payroll->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $payroll->voucher_no }}</p>
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
    <!-- Pay Slip print templete end-->
@endif
