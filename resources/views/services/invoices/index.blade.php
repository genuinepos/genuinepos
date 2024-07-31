@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Invoice List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Invoice List') }}</h5>
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
                                            <div class="form-group row">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-3">
                                                        <label><strong>{{ location_label() }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
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
                                                    <label><strong></strong></label>
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
                                    <h6>{{ __('List of Invoices') }}</h6>
                                </div>

                                @if (auth()->user()->can('create_add_sale'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> {{ __('Add Invoice') }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table id="invoices-table" class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Delivery Date') }}</th>
                                                <th>{{ __('Job No.') }}</th>
                                                <th>{{ __('Device') }}</th>
                                                <th>{{ __('Model') }}</th>
                                                <th>{{ __('Serial No.') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Invoice ID') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Payment Status') }}</th>
                                                <th>{{ __('Invoice Amount') }}</th>
                                                <th>{{ __('Received') }}</th>
                                                <th>{{ __('Return') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="12" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                <th id="total_invoice_amount" class="text-white text-end"></th>
                                                <th id="received_amount" class="text-white text-end"></th>
                                                <th id="sale_return_amount" class="text-white text-end"></th>
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

    @if (auth()->user()->can('shipment_access') && $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        <div class="modal fade" id="editShipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    @include('services.invoices.js_partials.index_js')
@endpush
