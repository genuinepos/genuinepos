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
@endpush
@section('title', 'Advertisements - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>{{ __('Advertisements') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription__has_business'] == 1)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form_element rounded mt-0 mb-1">
                                        <div class="element-body">
                                            <form id="filter_form">
                                                <div class="form-group row align-items-end">
                                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                                        <label><strong>{{ location_label() }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option data-branch_name="{{ $branch->name }}" value="{{ $branch->id }}">{{ $branch->name }}</option>
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
                                <div class="col-6">
                                    <h6>{{ __('List of Advertisements') }}</h6>
                                </div>

                                @if (auth()->user()->can('advertisements_create'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('advertisements.create') }}" class="btn btn-sm btn-success" id="add_btn"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr class="bg-navey-blue">
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Attachments') }}</th>
                                                <th>{{ __('Status') }}</th>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('advertisements.js_partials.index_js')
@endpush
