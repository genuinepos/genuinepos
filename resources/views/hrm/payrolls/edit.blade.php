@extends('layout.master')
@push('stylesheets')
    <style>
        b { font-weight: 500; font-family: Arial, Helvetica, sans-serif; }
    </style>
@endpush
@section('title', 'Edit Payroll - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Edit Payroll Of') }} <strong>{{ $payroll->month . '/' . $payroll->year }}</strong> -- (<strong>{{ $payroll?->user?->prefix . ' ' . $payroll?->user?->name . ' ' . $payroll?->user?->last_name }}</strong>)</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <form id="edit_payroll_form" action="{{ route('hrm.payrolls.update', $payroll->id) }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $payroll->user_id }}">
                <input type="hidden" name="month" value="{{ $payroll->month }}">
                <input type="hidden" name="year" value="{{ $payroll->year }}">
                <section class="p-1">
                    <div class="row g-1">
                        <div class="card">
                            <div class="card-body">
                                <table class="display table table-sm">
                                    <tr>
                                        <td>
                                            <h4>{{ __('Total Present') }} : {{ $totalPresent }} {{ __('Days') }}</h4>
                                        </td>

                                        <td>
                                            <h4>{{ __('Total Work Duration') }} : {{ App\Utils\Converter::format_in_bdt($totalHours) }} {{ __('Hours') }}</h4>
                                        </td>

                                        <td>
                                            <h4>{{ __('Total Leave') }} : 0 {{ __('Days') }}</h4>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row g-1 mt-1">
                        <div class="card p-1">
                            <div class="row g-2">
                                <div class="col-md-2" style="border-right: 1px solid #c6c2c2;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><b>{{ __('Expense Account') }}</b> <span class="text-danger">*</span> </label>
                                            <select required name="expense_account_id" class="form-control" id="expense_account_id">
                                                <option value="">{{ __('Select Expense Account') }}</option>
                                                @foreach ($expenseAccounts as $expenseAccount)
                                                    <option {{ $expenseAccount->id == $payroll->expense_account_id ? 'SELECTED' : '' }} value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_expense_account_id"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Total work duration') }}</b> <span class="text-danger">*</span> </label>
                                            <input type="number" step="any" name="duration_time" id="duration_time" class="form-control fw-bold" value="{{ $payroll->duration_time }}" placeholder="{{ __('Total work duration') }}" autofocus>
                                            <span class="error error_duration_time"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Unit') }} ({{ __('Pay Type') }})</b> <span class="text-danger">* </span> </label>
                                            <select required name="duration_unit" id="duration_unit" class="form-control">
                                                <option {{ $payroll->duration_unit == 'Hourly' ? 'SELECTED' : '' }} value="Hourly">{{ __('Hourly') }}</option>
                                                <option {{ $payroll->duration_unit == 'Monthly' ? 'SELECTED' : '' }} value="Monthly">{{ __('Monthly') }}</option>
                                                <option {{ $payroll->duration_unit == 'Yearly' ? 'SELECTED' : '' }} value="Yearly">{{ __('Yearly') }}</option>
                                                <option {{ $payroll->duration_unit == 'Daliy' ? 'SELECTED' : '' }} value="Daliy">{{ __('Daily') }}</option>
                                            </select>
                                            <span class="error error_duration_unit"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Amount per unit duration') }}</b> <span class="text-danger">*</span></label>
                                            <input required type="number" step="any" name="amount_per_unit" id="amount_per_unit" class="form-control fw-bold" placeholder="{{ __('Amount per unit duration') }}" value="{{ $payroll->amount_per_unit }}">
                                            <span class="error error_amount_per_unit"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Total') }}</b> <span class="text-danger">*</span></label>
                                            <input readonly type="total" step="any" name="total_amount" id="total_amount" class="form-control fw-bold" placeholder="total" value="{{ $payroll->total_amount }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5" style="position: relative; border-right: 1px solid #c6c2c2;">
                                    <div class="heading_area">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <p class="p-1 fw-bold">{{ __('Allowances') }}</p>
                                            </div>

                                            <div class="col-md-6 col-sm-6 text-end">
                                                <a href="#" class="btn btn-sm btn-success mt-1 me-1" id="add_more_allowance"><i class="fas fa-plus-square"></i> {{ __('Add More') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="table-responsive w-100" style="max-height: 219px;">
                                                <table class="modal-table table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-navy-blue">{{ __('Allowance') }}</th>
                                                            <th class="text-navy-blue">{{ __('Amount Type') }}</th>
                                                            <th class="text-navy-blue">{{ __('Amount') }}</th>
                                                            <th class="text-right"><i class="fas fa-trash-alt text-dark"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="allowance_body">
                                                        @php $index = 0; @endphp
                                                        @if (count($payroll->allowances) > 0)
                                                            @foreach ($payroll->allowances as $allowance)
                                                                @php
                                                                    $allowanceName = $allowance?->allowance ? $allowance?->allowance->name : $allowance?->allowance_name;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="payroll_allowance_ids[]" value="{{ $allowance->id }}">
                                                                        <input type="hidden" name="allowance_ids[]" value="{{ $allowance->allowance_id }}">
                                                                        <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                        <input type="text" name="allowance_names[]" class="form-control" id="allowance_name" value="{{ $allowanceName }}" placeholder="{{ __("Allowance Name") }}" autocomplete="off">
                                                                    </td>

                                                                    <td>
                                                                        <select class="form-control" name="allowance_amount_types[]" id="allowance_amount_type">
                                                                            <option {{ $allowance->amount_type == 1 ? 'SELECTED' : '' }}  value="1">{{ __("Fixed") }}</option>
                                                                            <option {{ $allowance->amount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __("Percentage") }}</option>
                                                                        </select>

                                                                        <div class="input-group allowance_percent_field {{ $allowance->amount_type == 1 ? 'd-hide' : '' }} ">

                                                                            <input type="number" step="any" name="allowance_percents[]" class="form-control fw-bold" id="allowance_percent" value="{{ $allowance->amount_type == 2 ? $allowance->allowance_percent : 0.00 }}" autocomplete="off">

                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="basic-addon1">
                                                                                    <i class="fas fa-percentage input_i"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="allowance_amounts[]" class="form-control fw-bold" id="allowance_amount" placeholder="{{ __("Amount") }}" value="{{ $allowance->allowance_amount }}" autocomplete="off">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                                @php $index++; @endphp
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="payroll_allowance_ids[]">
                                                                    <input type="hidden" name="allowance_ids[]" id="allowance_id">
                                                                    <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                    <input type="text" name="allowance_names[]" class="form-control" id="allowance_name" placeholder="{{ __("Allowance Name") }}" autocomplete="off">
                                                                </td>

                                                                <td>
                                                                    <select class="form-control" name="allowance_amount_types[]" id="allowance_amount_type">
                                                                        <option value="1">{{ __("Fixed") }}</option>
                                                                        <option value="2">{{ __("Percentage") }}</option>
                                                                    </select>

                                                                    <div class="input-group allowance_percent_field d-hide">
                                                                        <input type="number" step="any" name="allowance_percents[]" class="form-control fw-bold" value="0.00" id="allowance_percent" autocomplete="off">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1">
                                                                                <i class="fas fa-percentage input_i"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <input type="number" step="any" name="allowance_amounts[]" class="form-control fw-bold" id="allowance_amount" value="0.00" placeholder="{{ __("Amount") }}" autocomplete="off">
                                                                </td>

                                                                <td class="text-right">
                                                                    <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                    <tfoot style="position: absolute;width: 100%;left: 0;bottom: 0;border: 1px solid #ddd;border-width: 1px 0 1px 0px;">
                                                        <tr>
                                                            <td colspan="2" class="text-end fw-bold" style="border: none;">{{ __('Total Allowance') }} : </td>
                                                            <td colspan="2" class="text-start fw-bold" style="border: none;">
                                                                <span id="span_total_allowance">{{ $payroll->total_allowance }}</span>
                                                                <input name="total_allowance" type="hidden" id="total_allowance" value="{{ $payroll->total_allowance }}">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5" style="position: relative;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="p-1 fw-bold">{{ __('Deductions') }}</p>
                                        </div>

                                        <div class="col-md-6 col-sm-6 text-end">
                                            <a href="#" class="btn btn-sm btn-success mt-1 me-1" id="add_more_deduction"><i class="fas fa-plus-square"></i> {{ __('Add More') }}</a>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="table-responsive w-100" style="max-height: 219px;">
                                                <table class="modal-table table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Deduction') }}</th>
                                                            <th>{{ __('Amount Type') }}</th>
                                                            <th>{{ __('Amount') }}</th>
                                                            <th><i class="fas fa-trash-alt text-dark"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="deduction_body">
                                                        @php $index2 = 0; @endphp
                                                        @if (count($payroll->deductions) > 0)
                                                            @foreach ($payroll->deductions as $deduction)
                                                                @php
                                                                    $deductionName = $deduction?->deduction ? $deduction?->deduction->name : $deduction?->deduction_name;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="payroll_deduction_ids[]" value="{{ $deduction->id }}">
                                                                        <input type="hidden" name="deduction_ids[]" id="deduction_id" value="{{ $deduction->deduction_id }}">
                                                                        <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                        <input type="text" name="deduction_names[]" class="form-control" id="deduction_name" value="{{ $deductionName }}" placeholder="{{ __("Deduction Name") }}" autocomplete="off">
                                                                    </td>

                                                                    <td>
                                                                        <select class="form-control" name="deduction_amount_types[]" id="deduction_amount_type">
                                                                            <option {{ $deduction->amount_type == 1 ? 'SELECTED' : '' }} value="1">{{ __("Fixed") }}</option>
                                                                            <option {{ $deduction->amount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __("Fixed") }}</option>
                                                                        </select>

                                                                        <div class="input-group deduction_percent_field {{ $deduction->amount_type == 1 ? 'd-hide' : '' }} ">

                                                                            <input type="number" step="any" name="deduction_percents[]" class="form-control fw-bold" id="deduction_percent" value="{{ $deduction->amount_type == 2 ? $deduction->deduction_percent : 0.00 }}" autocomplete="off">

                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="basic-addon1">
                                                                                    <i class="fas fa-percentage input_i"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="deduction_amounts[]" class="form-control" id="deduction_amount" value="{{ $deduction->deduction_amount }}" placeholder="{{ __("Amount") }}" autocomplete="off">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                                @php $index2++; @endphp
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="payroll_deduction_id[]" value="noid">
                                                                    <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                    <input type="text"  name="deduction_names[]" id="deduction_name" class="form-control" placeholder="{{ __("Allowance Name") }}" autocomplete="off">
                                                                </td>

                                                                <td>
                                                                    <select class="form-control" name="deduction_amount_types[]" id="deduction_amount_type">
                                                                        <option value="1">{{ __("Fixed") }}</option>
                                                                        <option value="2">{{ __("Percentage") }}</option>
                                                                    </select>

                                                                    <div class="input-group deduction_percent_field d-hide">
                                                                        <input type="number" step="any" name="deduction_percents[]" class="form-control fw-bold" id="deduction_percent" value="0.00" autocomplete="off">

                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1">
                                                                                <i class="fas fa-percentage input_i"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <input type="number" step="any" name="deduction_amounts[]" class="form-control fw-bold" id="deduction_amount" value="0.00" placeholder="{{ __("Amount") }}" autocomplete="off">
                                                                </td>

                                                                <td class="text-right">
                                                                    <a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                    <tfoot style="position: absolute;width: 100%;left: 0;bottom: 0;border: 1px solid #ddd;border-width: 1px 0 1px 0px;">
                                                        <tr>
                                                            <td colspan="2" class="text-end fw-bold" style="border: none;">{{ __('Total Duduction') }} : </td>
                                                            <td colspan="2" class="text-start fw-bold" style="border: none;">
                                                                <span id="span_total_deduction">{{ $payroll->total_deduction }}</span>
                                                                <input name="total_deduction" type="hidden" id="total_deduction" value="{{ $payroll->total_deduction }}">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="p-0 m-0 mt-1 mb-1">

                            <div class="row">
                                <div class="col-md-4 offset-8 text-end">
                                    <label><h5>{{ __('Gross Amount') }} : <span id="span_gross_amount">{{ $payroll->gross_amount }}</span></h5> </label>
                                    <input type="hidden" name="gross_amount" id="gross_amount" value="{{ $payroll->gross_amount }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button payroll_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __('Loading') }}...</b></button>
                                <button type="submit" class="btn btn-success submit_button float-end payroll_submit_button">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('hrm.payrolls.js_partials.edit_js')
@endpush
