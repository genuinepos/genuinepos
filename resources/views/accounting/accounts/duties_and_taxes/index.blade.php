@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Duties And Taxes - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Duties And Taxes') }}</h5>
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
                                    <div class="col-md-4">
                                        <label><strong>{{ __('Account Group') }} </strong></label>
                                        <select name="f_account_group_id" id="f_account_group_id" class="form-control select2">
                                            <option value="">{{ __('All') }}</option>
                                            @foreach ($accountGroups as $group)
                                                @php
                                                    $parentGroup = $group?->parentGroup ? '-(' . $group?->parentGroup?->name . ')' : '';
                                                @endphp
                                                <option value="{{ $group->id }}">{{ $group->name . $parentGroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
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
                        <h6>{{ __('List of Duties And Taxes') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('accounts.create', \App\Enums\AccountCreateAndEditType::DutiesAndTaxes->value) }}" id="addAccountBtn" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Add Accounts') }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Group') }}</th>
                                    <th class="text-start">{{ __('Name') }}</th>
                                    <th class="text-start">{{ __('Opening Balance') }}</th>
                                    <th class="text-start">{{ __('Debit') }}</th>
                                    <th class="text-start">{{ __('Credit') }}</th>
                                    <th class="text-start">{{ __('Closing Balance') }}</th>
                                    <th class="text-start">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="2" class="text-white text-end">{{ __('Current Total') }} :</th>
                                    <th id="total_opening_balance" class="text-white">0.00 Cr.</th>
                                    <th id="total_debit" class="text-white">0.00</th>
                                    <th id="total_credit" class="text-white">0.00</th>
                                    <th id="total_closing_balance" class="text-white text-start">0.00 Cr.</th>
                                    <th class="text-white text-start">---</th>
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

    <!--Add/Edit Account modal-->
    <div class="modal fade" id="accountAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account modal End-->
@endsection
@push('scripts')
    @include('accounting.accounts.duties_and_taxes.js_partial.index_js')
@endpush
