@extends('layout.master')
@push('stylesheets')
    <style>
        .dropify-wrapper {
            height: 100px !important;
        }
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Store List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h5>{{ location_label('branch') }}
                        <span>({{ __('Limit') }} -<span class="text-danger">{{ $currentCreatedBranchCount }}</span>/{{ $generalSettings['subscription']->current_shop_count }})</span>
                    </h5>
                </div>

                <div class="col-md-4 text-start">
                    <p class="fw-bold"></p>
                </div>
                <div class="col-md-4">

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>{{ __('Store List') }}</h6>
                    </div>

                    @if (auth()->user()->can('branches_create') && $currentCreatedBranchCount < $generalSettings['subscription']->current_shop_count)
                        <div class="col-md-6 d-flex justify-content-end">
                            <a id="addBtn" href="{{ route('branches.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-square"></i> {{ __('Add New Store') }}
                            </a>
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
                                    <th>{{ __('Store Name') }}</th>
                                    <th>{{ __('Store Id') }}</th>
                                    <th>{{ __('Parent Store') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Store Logo') }}</th>
                                    <th>{{ __('Expire Date') }}</th>
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

                <form id="delete_branch_logo_form" action="">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="branchAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('setups.branches.js_partials.index_js')
@endpush
