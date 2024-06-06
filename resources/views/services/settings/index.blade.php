@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
    <link href="{{ asset('backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'Service Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Service Settings') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="status" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fa-solid fa-check"></i> {{ __('Status') }}
                            </a>

                            <a id="tab_btn" data-show="devices" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fa-solid fa-laptop-code"></i> {{ __('Devices') }}
                            </a>

                            <a id="tab_btn" data-show="device_models" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fa fa-bolt"></i> {{ __('Device Models') }}
                            </a>

                            <a id="tab_btn" data-show="service_settings" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fa-solid fa-screwdriver-wrench"></i> {{ __('Servicing Settings') }}
                            </a>

                            <a id="tab_btn" data-show="job_card_pdf_and_label" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fa-regular fa-file-pdf"></i> {{ __('Job Card Print/Pdf & Label') }}
                            </a>
                        </div>
                    </div>

                    @include('services.settings.partials.body_partials.status')
                    @include('services.settings.partials.body_partials.devices')
                    @include('services.settings.partials.body_partials.device_models')
                    @include('services.settings.partials.body_partials.service_settings')
                    @include('services.settings.partials.body_partials.pdf_and_label_settings')
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    <div class="modal fade" id="deviceAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    <div class="modal fade" id="deviceModelAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('services.settings.partials.js_partials.index_js')
    @include('services.settings.partials.js_partials.status_js')
    @include('services.settings.partials.js_partials.device_js')
    @include('services.settings.partials.js_partials.device_model_js')
    @include('services.settings.partials.js_partials.service_settings_js')
    @include('services.settings.partials.js_partials.pdf_and_label_settings_js')
@endpush
