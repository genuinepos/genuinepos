@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Discounts - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Discounts') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('List of Discounts') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ route('sales.discounts.create') }}" class="btn btn-sm btn-primary" id="addBtn"><i class="fas fa-plus-square"></i> {{ __('Add Discount') }}</a>
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
                                                <th>{{ __('Discount Name') }}</th>
                                                <th>{{ __('Created From') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Start At') }}</th>
                                                <th>{{ __('End At') }}</th>
                                                <th>{{ __('Discount Type') }}</th>
                                                <th>{{ __('Discount Amount') }}</th>
                                                <th>{{ __('Priority') }}</th>
                                                <th>{{ __('Brand.') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Applicable Products') }}</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addOrEditModal" role="dialog" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

@endsection
@push('scripts')
    @include('sales.discounts.partials.js_partial.index_js')
@endpush
