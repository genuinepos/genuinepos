<div class="payroll_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            @if ($payment->payroll->employee->branch)
                {{ $payment->payroll->employee->branch->name . '/' . $payment->payroll->employee->branch->branch_code }} <br>
                {{ $payment->payroll->employee->branch->city == 1 ? $payment->payroll->employee->branch->city : '' }},
                {{ $payment->payroll->employee->branch->state == 1 ? $payment->payroll->employee->branch->state : '' }},
                {{ $payment->payroll->employee->branch->zip_code == 1 ? $payment->payroll->employee->branch->zip_code : '' }},
                {{ $payment->payroll->employee->branch->country == 1 ? $payment->payroll->employee->branch->country : '' }}.
            @else
                <h6>{{$generalSettings['business__business_name']}}  (<b>@lang('menu.head_office')</b>)</h6>
                <p>{{$generalSettings['business__address']}} </p>
                <p><b>@lang('menu.phone') </b>  {{$generalSettings['business__phone']}} </p>
            @endif
            <h6 class="modal-title" id="exampleModalLabel">Payroll Of
                <b>{{ $payment->payroll->employee->prefix . ' ' . $payment->payroll->employee->name . ' ' . $payment->payroll->employee->last_name }}</b>
                for <b>{{ $payment->payroll->month . ' ' . $payment->payroll->year }}</b>
            </h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6><b>@lang('menu.title') </b> @lang('menu.payroll_payment')</h6>
        <h6><b>@lang('menu.reference_no') </b> {{ $payment->payroll->reference_no }}</h6>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.paid_amount') </th>
                            <td width="50%" class="text-start">
                                {{ $generalSettings['business__currency'] }}
                                {{ $payment->paid }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.due') </th>
                            <td width="50%" class="text-start">
                                {{ $generalSettings['business__currency'] }} {{ $payment->due }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.payment_method') </th>
                            <td width="50%" class="text-start">{{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.voucher_no') </th>
                            <td width="50%" class="text-start">
                                {{ $payment->reference_no }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.paid_on') </th>
                            <td width="50%" class="text-start">
                                {{date($generalSettings['business__date_format'], strtotime($payment->date)) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.payment_note') </th>
                            <td width="50%" class="text-start">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-hide">
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">@lang('menu.signature_of_authority')</th>
                    <th width="50%" class="text-end">@lang('menu.signature_of_receiver')</th>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-navy-blue text-center">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>