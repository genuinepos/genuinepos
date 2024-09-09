@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('title', 'Supplier List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Suppliers') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end">
                                        <div class="col-xl-6 col-lg-6 col-md-12">
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

                                        <div class="col-xl-2 col-lg-3 col-md-4">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="section-header">
                    <div class="col-md-4">
                        <h6>{{ __('List of Suppliers') }}</h6>
                    </div>

                    <div class="col-md-8 d-flex flex-wrap justify-content-md-end justify-content-center gap-2">
                        @if (auth()->user()->can('supplier_add'))
                            <a href="{{ route('contacts.create', App\Enums\ContactType::Supplier->value) }}" id="addContact" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-square"></i> {{ __('Add') }}
                            </a>
                        @endif

                        @if (auth()->user()->can('supplier_import'))
                            <a href="{{ route('contacts.suppliers.import.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Import Suppliers') }}</a>
                        @endif

                        <a href="{{ route('reports.suppliers.print') }}" class="btn btn-sm btn-primary" id="printReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr class="text-start">
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Supplier ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Opening Balance') }}</th>
                                    <th>{{ __('Total Purchase') }}</th>
                                    <th>{{ __('Total Sale') }}</th>
                                    <th>{{ __('Total Return') }}</th>
                                    <th>{{ __('Total Paid') }}</th>
                                    <th>{{ __('Total Received') }}</th>
                                    <th>{{ __('Curr. Balance') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="4" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                    <th id="opening_balance" class="text-white text-end"></th>
                                    <th id="total_purchase" class="text-white text-end"></th>
                                    <th id="total_sale" class="text-white text-end"></th>
                                    <th id="total_return" class="text-white text-end"></th>
                                    <th id="total_paid" class="text-white text-end"></th>
                                    <th id="total_received" class="text-white text-end"></th>
                                    <th id="current_balance" class="text-white text-end"></th>
                                    <th class="text-white text-start">---</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form id="delete_contact_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    @include('contacts.manage_suppliers.js_partials.index_js')
@endpush
