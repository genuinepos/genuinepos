@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business__date_format'];
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $previousRouteName = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
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

                                    {{ __("Payroll") }} :{{ $payment?->payrollRef?->voucher_no }}
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
                                @if ($payment->branch_id)

                                    @if ($payment?->branch?->parentBranch)
                                        {{ $payment?->branch?->parentBranch?->name . '(' . $payment?->branch?->area_name . ')' . '-(' . $payment?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $payment?->branch?->name . '(' . $payment?->branch?->area_name . ')' . '-(' . $payment?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($payment->branch)
                                    {{ $payment->branch->phone }}
                                @else
                                    {{ $generalSettings['business__phone'] }}
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
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Employee") }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->prefix .' '. $payment?->payrollRef?->user?->name .' '. $payment?->payrollRef?->user?->last_name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Employee ID") }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->emp_id }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Phone") }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->phone }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Address") }} : </th>
                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $payment?->payrollRef?->user?->address }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Paid Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                        <td class="text-start fw-bold" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt( $payment?->total_amount) }}
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
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <button type="submit" class="footer_btn btn btn-sm btn-success" id="{{ $previousRouteName == 'hrm.payrolls.index' ? 'modalExtraDetailsPrintBtn' : 'modalDetailsPrintBtn' }}" filename="{{ 'Payroll Payment - ' . $payment->voucher_no . ' - ' . $payment->date }}">{{ __('Print') }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    .print_table th { font-size: 11px !important; font-weight: 550 !important; line-height: 12px !important; }

    .print_table tr td { color: black; font-size: 10px !important; line-height: 12px !important; }

    @page { size: a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px; margin-right: 5px; }

    div#footer { position: fixed; bottom: 0px; left: 0px; width: 100%; height: 0%; color: #CCC; background: #333; padding: 0; margin: 0; }
</style>

<!-- Payroll print templete-->
<div class="print_modal_details d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($payment->branch)

                    @if ($payment?->branch?->parent_branch_id)

                        @if ($payment->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $payment->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $payment->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($payment->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $payment->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $payment->branch?->name }}</span>
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
                        @if ($payment?->branch)
                            @if ($payment?->branch?->parent_branch_id)

                                {{ $payment?->branch?->parentBranch?->name }}
                            @else

                                {{ $payment?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__business_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($payment?->branch)

                        {{ $payment->branch->city . ', ' . $payment->branch->state. ', ' . $payment->branch->zip_code. ', ' . $payment->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($payment?->branch)

                        <strong>{{ __("Email") }} : </strong> {{ $payment?->branch?->email }},
                        <strong>{{ __("Phone") }} : </strong> {{ $payment?->branch?->phone }}
                    @else

                        <strong>{{ __("Email") }} : </strong> {{ $generalSettings['business__email'] }},
                        <strong>{{ __("Phone") }} : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 class="fw-bold" style="text-transform: uppercase;">{{ __("Payroll Payment Voucher") }}</h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        {{ date($dateFormat, strtotime($payment->date)) }}
                    </li>
                    <li style="font-size:11px!important;"><strong>{{ __("Voucher No") }} : </strong>{{ $payment->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Paid Amount") }} : </strong>{{ App\Utils\Converter::format_in_bdt($payment->total_amount) }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Reference") }} : </strong>
                        @if ($payment?->payrollRef)
                            {{ __("Payroll") }} : {{ $payment?->payrollRef?->voucher_no }}
                        @endif
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Expense A/c") }} : </strong>
                        @if ($payment?->payrollRef?->expenseAccount)
                            {{ __("Payroll") }} : {{ $payment?->payrollRef?->expenseAccount?->name }}
                        @endif
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        {{ $payment?->createdBy?->prefix.' '.$payment?->createdBy?->name.' '.$payment?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <p class="fw-bold">{{ __("Paid To") }} :</p>
                <table class="table print-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Employee") }} : </th>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $payment?->payrollRef?->user?->prefix .' '. $payment?->payrollRef?->user?->name .' '. $payment?->payrollRef?->user?->last_name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Employee ID") }} : </th>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $payment?->payrollRef?->user?->emp_id }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Phone") }} : </th>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $payment?->payrollRef?->user?->phone }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Address") }} : </th>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $payment?->payrollRef?->user?->address }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Paid Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt( $payment?->total_amount) }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="col-6">
                <p class="fw-bold">{{ __("Paid From") }} : </p>
                @foreach ($payment->voucherDescriptions()->where('amount_type', 'cr')->get() as $description)
                    <table class="table print-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Credit A/c") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->account?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Method/Type") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->paymentMethod?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Transaction No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->transaction_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->cheque_no }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start fw-bold" style="font-size:11px!important;">{{ __("Cheque Serial No") }} : </th>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $description?->cheque_serial_no }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                @endforeach
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
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $payment->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($dateFormat) }}</small>
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
<!-- Purchase print templete end-->