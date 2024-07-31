@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
@endpush
@section('title', 'Payrolls - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Payrolls') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end">
                                    {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="f_branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Employee') }} </strong></label>
                                        <select name="user_id" class="form-control select2" id="f_user_id" autofocus>
                                            <option value="">{{ __('All') }}</option>
                                            @foreach ($users as $row)
                                                <option value="{{ $row->id }}">{{ $row->prefix . ' ' . $row->name . ' ' . $row->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Month & Year') }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                            </div>
                                            <input type="month" name="month_year" class="form-control" id="f_month_year" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List of Payrolls') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        @if (auth()->user()->can('payrolls_create'))
                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> {{ __('Add Payroll') }}</a>
                        @endif
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Month/Year') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Payroll voucher') }}</th>
                                    <th>{{ location_label() }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Payment Status') }}</th>
                                    <th>{{ __('Gross Amount') }}</th>
                                    <th>{{ __('Paid') }}</th>
                                    <th>{{ __('Due') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6"></th>
                                    <th id="gross_amount"></th>
                                    <th id="paid"></th>
                                    <th id="due"></th>
                                    <th>---</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>

                <form id="delete_payroll_payment_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    @if (auth()->user()->can('payrolls_create'))
        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog double-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">{{ __('Select Employee & Month') }}</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('hrm.payrolls.create') }}" method="get">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="fw-bold">{{ __('Department') }}</label>
                                    <select class="form-control employee" id="department_id">
                                        <option value="all"> {{ __('All') }} </option>
                                        @foreach ($departments as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="fw-bold"><b>{{ __('Employee') }} </b></label>
                                    <select required name="user_id" class="form-control" id="user_id">
                                        <option value=""> {{ __('Select Employee') }} </option>
                                        @foreach ($users as $user)
                                            @php
                                                $empId = $user->emp_id ? '(' . $user->emp_id . ')' : '';
                                            @endphp
                                            <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . $empId }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-12">
                                    <label><strong>{{ __('Month & Year') }}</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                        </div>
                                        <input required type="month" name="month_year" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-sm btn-success">{{ __('Create') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Modal End-->
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>

    <div class="modal fade" id="addOrEditPaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('hrm.payrolls.js_partials.index_js')
@endpush
