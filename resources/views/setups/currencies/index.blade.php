@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Currencies - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Currencies') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __('List of Currencies') }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('currencies.create') }}" class="btn btn-sm btn-success" id="addCurrency"><i class="fas fa-plus-square"></i> {{ __('Add Currency') }}</a>
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
                                            <th>{{ __('Country Name') }}</th>
                                            <th>{{ __('Currency Name') }}</th>
                                            <th>{{ __('Currency Code') }}</th>
                                            <th>{{ __('Currency Symbol') }}</th>
                                            @if ($generalSettings['subscription__has_business'] == \App\Enums\BooleanType::True->value)
                                                <th>{{ __('Current Rate(As Per Base Currency)') }}</th>
                                            @endif
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

    <div class="modal fade" id="currencyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    <div class="modal fade" id="currencyRateAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('setups.currencies.js_partials.index_js')
@endpush
