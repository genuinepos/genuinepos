@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Job Cards - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Job Cards') }}</h5>
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
                                                    <div class="col-md-2">
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
                                                    <label><strong>{{ __('Service Type') }}</strong></label>
                                                    <select name="service_type" id="service_type" class="form-control">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach (\App\Enums\ServiceType::cases() as $item)
                                                            <option value="{{ $item->value }}">{{ str($item->name)->headline() }}</option>
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
                                                    <label><strong>{{ __('Brand.') }}</strong></label>
                                                    <select name="brand_id" class="form-control select2" id="brand_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($brands as $brand)
                                                            <option data-brand_name="{{ $brand->name }}" value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Device') }}</strong></label>
                                                    <select name="device_id" class="form-control select2" id="device_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($devices as $device)
                                                            <option data-device_name="{{ $device->name }}" value="{{ $device->id }}">{{ $device->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Device Model') }}</strong></label>
                                                    <select name="device_model_id" class="form-control select2" id="device_model_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($deviceModels as $deviceModel)
                                                            <option data-device_name="{{ $deviceModel->name }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Status') }}</strong></label>
                                                    <select name="status_id" class="form-control select2" id="status_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($status as $status)
                                                            <option value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
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
                                    <h6>{{ __('List of Job Cards') }}</h6>
                                </div>

                                @if (auth()->user()->can('job_cards_create'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('services.job.cards.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Add Job Card') }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table id="job-cards-table" class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Job No.') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Delivery Date') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Quotation ID') }}</th>
                                                <th>{{ __('Invoice ID') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Device') }}</th>
                                                <th>{{ __('Model') }}</th>
                                                <th>{{ __('Serial No.') }}</th>
                                                <th>{{ __('Total Cost') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="14" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                <th id="total_cost" class="text-white text-end"></th>
                                                <th class="text-white text-end">---</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            @if (auth()->user()->can('job_cards_delete'))
                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->can('job_cards_change_status'))
        <div class="modal fade" id="changeStatusModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    @include('services.job_cards.js_partials.index_js')
@endpush
