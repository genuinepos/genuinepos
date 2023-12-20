@extends('layout.master')
@push('stylesheets')
    <style>
        b { font-weight: 500; font-family: Arial, Helvetica, sans-serif; }
    </style>
@endpush
@section('title', 'Add Payroll - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Add Payroll Of') }} <strong>{{ $month . '/' . $year }}</strong> -- (<strong>{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</strong>)</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <form id="add_payroll_form" action="{{ route('hrm.payrolls.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
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
                                                    <option value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_expense_account_id"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Total work duration') }}</b> <span class="text-danger">*</span> </label>
                                            <input type="number" step="any" name="duration_time" id="duration_time" class="form-control fw-bold" placeholder="{{ __('Total work duration') }}" autofocus value="1">
                                            <span class="error error_duration_time"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Unit') }} ({{ __('Pay Type') }})</b> <span class="text-danger">* </span> </label>
                                            <select required name="duration_unit" id="duration_unit" class="form-control">
                                                <option value="Hourly">{{ __('Hourly') }}</option>
                                                <option value="Monthly">{{ __('Monthly') }}</option>
                                                <option value="Yearly">{{ __('Yearly') }}</option>
                                                <option value="Daliy">{{ __('Daily') }}</option>
                                            </select>
                                            <span class="error error_duration_unit"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Amount per unit duration') }}</b> <span class="text-danger">*</span></label>
                                            <input required type="number" step="any" name="amount_per_unit" id="amount_per_unit" class="form-control fw-bold" placeholder="{{ __('Amount per unit duration') }}" value="">
                                            <span class="error error_amount_per_unit"></span>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <label><b>{{ __('Total') }}</b> <span class="text-danger">*</span></label>
                                            <input readonly type="total" step="any" name="total_amount" id="total_amount" class="form-control fw-bold" placeholder="total" value="0.00">
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
                                                            <th>{{ __('Allowance') }}</th>
                                                            <th>{{ __('Amount Type') }}</th>
                                                            <th>{{ __('Amount') }}</th>
                                                            <th><i class="fas fa-trash-alt text-dark"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="allowance_body">
                                                        @php $index = 0; @endphp
                                                        @if (count($allowances) > 0)
                                                            @foreach ($allowances as $allowance)
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                        <input type="hidden" name="allowance_ids[]" id="allowance_id" value="{{ $allowance->id }}">
                                                                        <input type="text" name="allowance_names[]" class="form-control" id="allowance_name" placeholder="{{ __('Allowance Name') }}" value="{{ $allowance->name }}" autocomplete="off">
                                                                    </td>

                                                                    <td>
                                                                        <div class="input-group">
                                                                            <select class="form-control" name="allowance_amount_types[]" id="allowance_amount_type">
                                                                                <option {{ $allowance->amount_type == 1 ? 'SELECTED' : '' }} value="1">{{ __('Fixed') }}</option>
                                                                                <option {{ $allowance->amount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Percentage') }}</option>
                                                                            </select>

                                                                            <div class="input-group allowance_percent_field {{ $allowance->amount_type == 1 ? 'd-hide' : '' }} ">

                                                                                <input type="number" step="any" name="allowance_percents[]" class="form-control fw-bold" autocomplete="off" value="{{ $allowance->amount_type == 2 ? $allowance->amount : 0.0 }}" id="allowance_percent">

                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="basic-addon1">
                                                                                        <i class="fas fa-percentage input_i"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="allowance_amounts[]" class="form-control fw-bold" id="allowance_amount" value="{{ $allowance->amount }}" placeholder="{{ __('Amount') }}" autocomplete="off">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                                @php $index++; @endphp
                                                            @endforeach
                                                        @endif

                                                        <tr>
                                                            <td>
                                                                <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                <input type="hidden" name="allowance_ids[]" id="allowance_id">
                                                                <input type="text" name="allowance_names[]" class="form-control" id="allowance_name" placeholder="{{ __('Allowance Name') }}" autocomplete="off">
                                                            </td>

                                                            <td>
                                                                <select class="form-control" name="allowance_amount_types[]" id="allowance_amount_type">
                                                                    <option value="1">{{ __('Fixed') }}</option>
                                                                    <option value="2">{{ __('Percentage') }}</option>
                                                                </select>

                                                                <div class="input-group allowance_percent_field d-hide">
                                                                    <input type="number" step="any" name="allowance_percents[]" class="form-control fw-bold" id="allowance_percent" value="0.00" autocomplete="off">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1">
                                                                            <i class="fas fa-percentage input_i"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <input type="number" step="any" name="allowance_amounts[]" class="form-control fw-bold" id="allowance_amount" value="0.00" placeholder="{{ __('Amount') }}" autocomplete="off">
                                                            </td>

                                                            <td class="text-right">
                                                                <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot style="position: absolute;width: 100%;left: 0;bottom: 0;border: 1px solid #ddd;border-width: 1px 0 1px 0px;">
                                                        <tr>
                                                            <td colspan="2" class="text-end fw-bold" style="border: none;">{{ __('Total Allowance') }} : </td>
                                                            <td colspan="2" class="text-start fw-bold" style="border: none;">
                                                                <span id="span_total_allowance">0.00</span>
                                                                <input name="total_allowance" type="hidden" id="total_allowance" value="0.00">
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
                                                        @if (count($deductions) > 0)
                                                            @foreach ($deductions as $deduction)
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                        <input type="hidden" name="deduction_ids[]" id="deduction_id" value="{{ $deduction->id }}">

                                                                        <input type="text" name="deduction_names[]" id="deduction_name" class="form-control" placeholder="{{ __("Deduction Name") }}" value="{{ $deduction->name }}" autocomplete="off">
                                                                    </td>

                                                                    <td>
                                                                        <select class="form-control" name="deduction_amount_types[]" id="deduction_amount_type">
                                                                            <option {{ $deduction->amount_type == 1 ? 'SELECTED' : '' }} value="1">{{ __("Fixed") }}</option>
                                                                            <option {{ $deduction->amount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __("Percentage") }}</option>
                                                                        </select>

                                                                        <div class="input-group deduction_percent_field {{ $deduction->amount_type == 1 ? 'd-hide' : '' }} ">
                                                                            <input type="number" step="any" name="deduction_percents[]" class="form-control fw-bold" id="deduction_percent" value="{{ $deduction->amount_type == 2 ? $deduction->amount : 0 }}" autocomplete="off">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-percentage input_i"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="deduction_amounts[]" id="deduction_amount" class="form-control fw-bold" value="{{ $deduction->amount }}" placeholder="{{ __("Amount") }}" autocomplete="off">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                                @php $index2++; @endphp
                                                            @endforeach
                                                        @endif

                                                        <tr>
                                                            <td>
                                                                <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                <input type="hidden" name="deduction_ids[]" id="deduction_id">
                                                                <input type="text" name="deduction_names[]" id="deduction_name" class="form-control" placeholder="{{ __('Deduction Name') }}" autocomplete="off">
                                                            </td>

                                                            <td>
                                                                <select class="form-control" name="deduction_amount_types[]" id="deduction_amount_type">
                                                                    <option value="1">{{ __('Fixed') }}</option>
                                                                    <option value="2">{{ __('Percentage') }}</option>
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
                                                    </tbody>
                                                    <tfoot style="position: absolute;width: 100%;left: 0;bottom: 0;border: 1px solid #ddd;border-width: 1px 0 1px 0px;">
                                                        <tr>
                                                            <td colspan="2" class="text-end fw-bold" style="border: none;">{{ __('Total Duduction') }} : </td>
                                                            <td colspan="2" class="text-start fw-bold" style="border: none;">
                                                                <span id="span_total_deduction">0.00</span>
                                                                <input name="total_deduction" type="hidden" id="total_deduction" value="0.00">
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
                                    <label><h5>{{ __('Gross Amount') }} : <span id="span_gross_amount">0.00</span></h5> </label>
                                    <input type="hidden" name="gross_amount" id="gross_amount">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button payroll_loading_btn d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __('Loading') }}...</b></button>
                                <button type="submit" class="btn btn-success submit_button float-end payroll_submit_button">{{ __('Generate') }}</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('hrm.payrolls.js_partials.add_js')
@endpush
