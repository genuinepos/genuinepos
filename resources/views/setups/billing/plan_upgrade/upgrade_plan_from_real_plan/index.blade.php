@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@section('title', 'Upgrade Plan - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-4">
                    <h6>{{ __('Upgrade Plan') }}</h6>
                </div>

                <div class="col-md-4">
                    @if (Session::has('trialExpireDate'))
                        <p class="text-danger fw-bold">{{ session('trialExpireDate') }}</p>
                    @endif
                </div>

                <div class="col-md-4">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>

            <div class="p-1">
                @include('setups.billing.plan_upgrade.upgrade_plan_from_real_plan.partials.index_partials.price_plan')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
