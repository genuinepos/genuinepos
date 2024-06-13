@extends('layout.master')
@push('stylesheets')
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Holidays - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Holidays') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                    <div class="element-body">
                        <form id="filter_form" action="" method="get">
                            <div class="form-group row align-items-end">

                                <div class="col-md-4">
                                    <label><strong>{{ __('Shop Acccess') }}</strong></label>
                                    <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                        <option value="">{{ __('All') }}</option>
                                        <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List of Holidays') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        @if (auth()->user()->can('holidays_create'))
                            <a href="{{ route('hrm.holidays.create') }}" class="btn btn-sm btn-primary" id="addHoliday"><i class="fas fa-plus-square"></i> {{ __('Add Holiday') }}</a>
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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Allowed Shop/Business') }}</th>
                                    <th>{{ __('Note') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

    <div class="modal fade" id="holidayAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('hrm.holidays.js_partial.index_js')
@endpush
