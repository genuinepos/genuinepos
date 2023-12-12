@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    .print_table th { font-size:11px!important; font-weight: 550!important; line-height: 12px!important}
    .print_table tr td{color: black; font-size:10px!important; line-height: 12px!important}

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
 <!-- Contra print templete-->
<div class="purchase_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($contra->branch)

                    @if ($contra?->branch?->parent_branch_id)

                        @if ($contra->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $contra->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $contra->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($contra->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $contra->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $contra->branch?->name }}</span>
                        @endif
                    @endif
                @else
                    @if ($generalSettings['business__business_logo'] != null)

                        <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
                    @endif
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>
                        @if ($contra?->branch)
                            @if ($contra?->branch?->parent_branch_id)

                                {{ $contra?->branch?->parentBranch?->name }}
                            @else

                                {{ $contra?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($contra?->branch)

                        {{ $contra->branch->city . ', ' . $contra->branch->state. ', ' . $contra->branch->zip_code. ', ' . $contra->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($contra?->branch)

                        <strong>{{ __("Email") }} : </strong> {{ $contra?->branch?->email }},
                        <strong>{{ __("Phone") }} : </strong> {{ $contra?->branch?->phone }}
                    @else

                        <strong>{{ __("Email") }} : </strong> {{ $generalSettings['business__email'] }},
                        <strong>{{ __("Phone") }} : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 class="fw-bold" style="text-transform: uppercase;">{{ __("Contra Voucher") }}</h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        {{ date($generalSettings['business__date_format'], strtotime($contra->date)) }}
                    </li>
                    <li style="font-size:11px!important;"><strong>{{ __("Voucher No") }} : </strong>{{ $contra->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Total Amount") }} : </strong>{{ App\Utils\Converter::format_in_bdt($contra->total_amount) }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Reference") }} : </strong>
                        {{ $contra->reference }}
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
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
                <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:11px!important;">{{ __("Credit A/c Details") }} : </p>
                <table class="table print-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Sender A/c") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                @php
                                    $accountNumber = $creditDescription?->account?->account_number ? ' / ' . $creditDescription?->account?->account_number : '';
                                @endphp
                                : {{ $creditDescription?->account?->name . $accountNumber }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Method/Type") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                               : {{ $creditDescription?->paymentMethod?->name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Transaction No") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                               : {{ $creditDescription?->transaction_no }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque No") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                               : {{ $creditDescription?->cheque_no }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque Serial No") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                               : {{ $creditDescription?->cheque_serial_no }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Send Amount") }}</th>
                            <td class="text-start fw-bold" style="font-size:11px!important;">
                               : {{ App\Utils\Converter::format_in_bdt($creditDescription?->amount) }} {{ $generalSettings['business__currency'] }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="col-6">
                <p class="fw-bold" style="border-bottom: 1px solid black!important;font-size:11px!important;">{{ __("Debit A/c Details") }} :</p>
                <table class="table print-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Receiver A/c") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                               : {{ $debitDescription?->account?->name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("A/c Number") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                @php
                                    $accountNumber = $debitDescription?->account?->account_number ? ' / ' . $debitDescription?->account?->account_number : '';
                                @endphp
                               : {{ $accountNumber }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Bank") }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                @php
                                    $bank = $debitDescription?->account?->bank ? $debitDescription?->account?->bank?->name : '';
                                @endphp
                               : {{ $bank }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Received Amount") }} {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-start fw-bold" style="font-size:11px!important;">
                               : {{ App\Utils\Converter::format_in_bdt($debitDescription?->amount) }} {{ $generalSettings['business__currency'] }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Prepared By") }}
                </p>
            </div>

            <div class="col-4 text-center">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Checked By") }}
                </p>
            </div>

            <div class="col-4 text-end">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Authorized By") }}
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
                    <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __("Powered By") }} <strong>{{ __("SpeedDigit Software Solution.") }}</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Contra print templete end-->
