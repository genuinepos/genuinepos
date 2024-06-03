@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Sales Return List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Sales Returns') }}</h5>
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
                                                    <div class="col-md-3">
                                                        <label><strong>{{ __('Shop/Business') }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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
                                                    <label><strong>{{ __('Payment Status') }}</strong></label>
                                                    <select name="payment_status" id="payment_status" class="form-control">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                            <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Customer') }}</strong></label>
                                                    <select name="customer_account_id" class="form-control select2" id="customer_account_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($customerAccounts as $customerAccount)
                                                            <option data-customer_account_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('List Of Sales Returns') }}</h6>
                                </div>

                                @if (auth()->user()->can('create_sales_return'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('sales.returns.create') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Voucher No') }}</th>
                                                <th>{{ __('Parent Sale') }}</th>
                                                <th>{{ __('Shop/Business') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Payment Status') }}</th>
                                                <th>{{ __('Total Qty') }}</th>
                                                <th>{{ __('Net Total Amount') }}</th>
                                                <th>{{ __('Return Discount') }}</th>
                                                <th>{{ __('Return Tax') }}</th>
                                                <th>{{ __('Total Returned Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                <th id="total_qty" class="text-white text-end"></th>
                                                <th id="net_total_amount" class="text-white text-end"></th>
                                                <th id="return_discount_amount" class="text-white text-end"></th>
                                                <th id="return_tax_amount" class="text-white text-end"></th>
                                                <th id="total_return_amount" class="text-white text-end"></th>
                                                <th id="paid" class="text-white text-end"></th>
                                                <th id="due" class="text-white text-end"></th>
                                                <th class="text-white text-end">---</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    @include('sales.sales_return.js_partials.index_js')
@endpush
