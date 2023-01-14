<div class="modal-header bg-dark">
    <h6 class="modal-title" id="exampleModalLabel">Payroll Of
        <b>{{ $payroll->employee->prefix . ' ' . $payroll->employee->name . ' ' . $payroll->employee->last_name }}</b>
        for <b>{{ $payroll->month . ' ' . $payroll->year }}</b>
    </h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <div class="payroll_print_area">
        <div class="header_area">
            <div class="row">
                <div class="col-md-12">
                    <div class="company_name text-center">
                        @if ($payroll->employee->branch)
                            {{ $payroll->employee->branch->name . '/' . $payroll->employee->branch->branch_code }} <br>
                            {{ $payroll->employee->branch->city == 1 ? $payroll->employee->branch->city : '' }},
                            {{ $payroll->employee->branch->state == 1 ? $payroll->employee->branch->state : '' }},
                            {{ $payroll->employee->branch->zip_code == 1 ? $payroll->employee->branch->zip_code : '' }},
                            {{ $payroll->employee->branch->country == 1 ? $payroll->employee->branch->country : '' }}.
                        @else
                            <h6>{{$generalSettings['business__shop_name']}}  (<b>@lang('menu.head_office')</b>)</h6>
                            <p>{{$generalSettings['business__address']}} </p>
                            <p><b>@lang('menu.phone') </b>  {{$generalSettings['business__phone']}} </p>
                        @endif
                        <h6 class="modal-title" id="exampleModalLabel">Payroll Of
                            <b>{{ $payroll->employee->prefix . ' ' . $payroll->employee->name . ' ' . $payroll->employee->last_name }}</b>
                            for <b>{{ $payroll->month . ' ' . $payroll->year }}</b>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="reference_area pt-2">
            <h6 class="text-dark"><b>@lang('menu.title') </b>@lang('menu.employee_salary')</h6>
            <h6 class="text-dark"><b>@lang('menu.month') </b> {{ $payroll->month }}/{{ $payroll->year }} </h6>
            <h6 class="text-dark"><b>@lang('menu.reference_no') </b> {{ $payroll->reference_no }}</h6>
            <h6 class="text-dark"><b>@lang('menu.created_by') </b> {{ $payroll->admin->prefix.' '.$payroll->admin->name.' '.$payroll->admin->last_name }} </h6>
        </div>

        <div class="total_amount_table_area pt-4">
            <div class="table-responsive">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">{{ __('Total work duration') }} </th>
                            <td width="50%" class="text-start">
                                {{ $payroll->duration_time . ' ' . $payroll->duration_unit }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">{{ __('Amount per unit duration') }} </th>
                            <td width="50%" class="text-start">{{ $payroll->amount_per_unit }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.total') : ({{ $payroll->duration_time }} * {{  $payroll->amount_per_unit }})</th>
                            <th width="50%" class="text-start">{{ $generalSettings['business__currency'] }} {{ $payroll->total_amount }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="allowance_table_area pt-2">
            <div class="heading_area">
                <h6 class="text-start"><b>{{ __('Allowances') }} </b></h6>
            </div>

            <div class="table-responsive">
                <table class="table modal-table table-sm">
                    <tbody>
                        @if (count($payroll->allowances) > 0)
                            @foreach ($payroll->allowances as $allowance)
                                <tr>
                                    <th width="50%" class="text-start">{{ $allowance->allowance_name ? $allowance->allowance_name : 'Unknown' }} </th>
                                    <td width="50%" class="text-start">{{ $allowance->allowance_amount .' '.($allowance->amount_type == 2 ? '(' . $allowance->allowance_percent . '%)' : '') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th colspan="2" width="100%"  class="text-start">@lang('menu.none')</th>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.total') </th>
                            <th width="50%" class="text-start">{{ $generalSettings['business__currency'] }} {{ $payroll->total_allowance_amount }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="deduction_table_area pt-2">
            <div class="heading_area">
                <h6><b>{{ __('Deductions') }} </b> </h6>
            </div>

            <div class="table-responsive">
                <table class="table modal-table table-sm">
                    <tbody>
                        @if (count($payroll->deductions) > 0)
                            @foreach ($payroll->deductions as $deduction)
                                <tr>
                                    <th width="50%" class="text-start">{{ $deduction->deduction_name ? $deduction->deduction_name : 'Unknown' }} </th>
                                    <td width="50%" class="text-start">
                                        {{ $deduction->deduction_amount .' '. ($deduction->amount_type == 2 ? '(' . $deduction->deduction_percent . '%)' : '') }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th colspan="2">@lang('menu.none')</th>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.total') </th>
                            <th class="text-start text-danger" width="50%">{{ $generalSettings['business__currency'] }} {{ $payroll->total_deduction_amount }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="gross_amount_area pt-2">
            <div class="table-responsive">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">
                                {{ __('Gross Amount') }} : <br>
                                ({{ $payroll->total_amount }} + {{ $payroll->total_allowance_amount }} -
                                {{ $payroll->total_deduction_amount }})
                            </th>
                            <td width="50%" class="text-start"><b>{{ $generalSettings['business__currency'] }} {{ $payroll->gross_amount }}</b> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="paid_amount_area pt-2">
            <div class="table-responsive">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.paid') </th>
                            <td width="50%" class="text-start"><b>{{ $generalSettings['business__currency'] }} {{ $payroll->paid }}</b> </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('menu.due') </th>
                            <td width="50%" class="text-start"><b>{{ $generalSettings['business__currency'] }} {{ $payroll->due }}</b> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="signature_area pt-5 mt-5 d-hide">
            <br><br>
            <table class="w-100 pt-5">
                <tbody>
                    <tr>
                        <th width="50%" class="text-dark"><h6 style="border-top:1px solid black;display:inline;">@lang('menu.signature_of_receiver')</h6></th>
                        <th width="50%" class="text-dark text-end"><h6 style="border-top:1px solid black;display:inline;">@lang('menu.signature_of_authority')</h6>  </th>
                    </tr>

                    @if (env('PRINT_SD_OTHERS') == true)
                        <tr>
                            <td colspan="2" class="text-dark text-center">@lang('menu.software_by_speedDigit_pvt_ltd') </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="form-group text-end">
        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
        <button type="submit" class="btn btn-sm btn-success print_payroll">@lang('menu.print')</button>
    </div>
</div>
