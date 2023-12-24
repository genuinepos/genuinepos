@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business__date_format'];
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $previousRouteName = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Payroll Details') }} ({{ __('Voucher No') }} : <strong>{{ $payroll->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Employee') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong> {{  $payroll?->user?->prefix . '  ' . $payroll?->user?->name . '  ' . $payroll?->user?->last_name }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong> {{ $payroll->user->phone }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong> {{ $payroll->user->current_address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Month') }} : </strong> {{ $payroll->month.'-'.$payroll->year }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Generated On') }} : </strong> {{ date($dateFormat, strtotime($payroll->date_ts)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Payroll Voucher No') }} : </strong> {{ $payroll->voucher_no }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $payable = $payroll->gross_amount;
                                @endphp
                                @if ($payroll->due <= 0)
                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                @elseif($payroll->due > 0 && $payroll->due < $payable)
                                    <span class="badge bg-primary text-white">{{ __('Partial') }}</span>
                                @elseif($payable == $payroll->due)
                                    <span class="badge bg-danger text-white">{{ __('Due') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $payroll?->createdBy?->prefix . ' ' . $payroll?->createdBy?->name . ' ' . $payroll?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Shop/Business') }} : </strong>
                                @if ($payroll->branch_id)

                                    @if ($payroll?->branch?->parentBranch)
                                        {{ $payroll?->branch?->parentBranch?->name . '(' . $payroll?->branch?->area_name . ')' . '-(' . $payroll?->branch?->branch_code . ')' }}
                                    @else
                                        {{ $payroll?->branch?->name . '(' . $payroll?->branch?->area_name . ')' . '-(' . $payroll?->branch?->branch_code . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business__business_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($payroll->branch)
                                    {{ $payroll->branch->phone }}
                                @else
                                    {{ $generalSettings['business__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <p class="fw-bold">{{ __("Allowances") }}</p>
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
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
                                        <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __("Total Allowance") }} : </td>
                                        <td colspan="2" class="fw-bold" style="font-size:11px!important;">   {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <p class="fw-bold">{{ __("Deductions") }}</p>
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
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
                                        <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __("Total Deduction") }} : </td>
                                        <td colspan="2" class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Payments') }}</p>
                        @include('hrm.payrolls.ajax_view.partials.payroll_details_payment_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Total Amount') }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Allowance') }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Deduction') }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Gross Amount') }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Paid') }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due') }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->due) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            @if (auth()->user()->branch_id == $payroll->branch_id)
                                @can('payrolls_edit')

                                    <a href="{{ route('hrm.payrolls.edit', $payroll->id) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                                @endcan
                            @endif
                            <button type="submit" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn" filename="{{ 'Payroll - ' . $payroll->voucher_no . ' - ' . $payroll->month.'-'.$payroll->year }}">{{ __('Print') }}</button>
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
        table { page-break-after: auto; }

        tr { page-break-inside: avoid; page-break-after: auto; }

        td { page-break-inside: avoid; page-break-after: auto; }

        thead { display: table-header-group; }

        tfoot { display: table-footer-group; }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 5px;
        margin-right: 5px;
    }

    div#footer { position: fixed; bottom: 0px; left: 0px; width: 100%; height: 0%; color: #CCC; background: #333; padding: 0; margin: 0; }
</style>

<!-- Pay Slip print templete-->
<div class="print_modal_details d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($payroll->branch)

                    @if ($payroll?->branch?->parent_branch_id)

                        @if ($payroll->branch?->parentBranch?->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $payroll->branch?->parentBranch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $payroll->branch?->parentBranch?->name }}</span>
                        @endif
                    @else
                        @if ($payroll->branch?->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $payroll->branch?->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $payroll->branch?->name }}</span>
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
                <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                    @if ($payroll?->branch)
                        @if ($payroll?->branch?->parent_branch_id)
                            {{ $payroll?->branch?->parentBranch?->name }}
                        @else
                            {{ $payroll?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business__business_name'] }}
                    @endif
                </p>

                <p style="font-size:12px!important;">
                    @if ($payroll?->branch)
                        {{ $payroll->branch->city . ', ' . $payroll->branch->state . ', ' . $payroll->branch->zip_code . ', ' . $payroll->branch->country }}
                    @else
                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p style="font-size:12px!important;">
                    @if ($payroll?->branch)
                        <strong>{{ __("Email") }} : </strong> {{ $payroll?->branch?->email }},
                        <strong>{{ __("Phone") }} : </strong> {{ $payroll?->branch?->phone }}
                    @else
                        <strong>{{ __("Email") }} : </strong> {{ $generalSettings['business__email'] }},
                        <strong>{{ __("Phone") }} : </strong> {{ $generalSettings['business__phone'] }}
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
                    <li style="font-size:11px!important;"><strong>{{ __('Employee') }} : - </strong></li>
                    <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong> {{ $payroll?->user?->prefix . '  ' . $payroll?->user?->name . '  ' . $payroll?->user?->last_name }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong> {{ $payroll->user->phone }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong> {{ $payroll->user->current_address }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Month') }} : </strong> {{ $payroll->month.'-'.$payroll->year }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Generated On') }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($payroll->date_ts)) }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Payroll Voucher No') }} : </strong> {{ $payroll->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
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
                        <strong>{{ __('Created By') }} : </strong>
                        {{ $payroll?->createdBy?->prefix . ' ' . $payroll?->createdBy?->name . ' ' . $payroll?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <p class="fw-bold">{{ __("Allowances") }}</p>
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
                                <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __("Total") }} : </td>
                                <td class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="col-6">
                <p class="fw-bold">{{ __("Deductions") }}</p>
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
                                <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __("Total") }} : </td>
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
                            <th class="text-end">{{ __('Total Amount') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">{{ __('Total Allowance') }} : {{ $generalSettings['business__currency'] }} </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end">{{ __('Total Deduction') }} : {{ $generalSettings['business__currency'] }} </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end">{{ __('Gross Amount') }} : {{ $generalSettings['business__currency'] }} </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end">{{ __('Paid') }} : {{ $generalSettings['business__currency'] }} </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payroll->paid) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end">{{ __('Due') }} : {{ $generalSettings['business__currency'] }} </th>
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
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __('Powered By') }} <strong>{{ __("SpeedDigit Software Solution.") }}</strong></small>
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
