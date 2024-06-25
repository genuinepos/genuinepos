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
                            @if (auth()->user()->can('status_index'))
                                <a id="tab_btn" data-show="status" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                    <i class="fa-solid fa-check"></i> {{ __('Status') }}
                                </a>
                            @endif

                            @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
                                @if (auth()->user()->can('product_brand_index'))
                                    <a id="tab_btn" data-show="brands" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fa-solid fa-bandage"></i> {{ __('Brands') }}
                                    </a>
                                @endif
                            @endif

                            @if (auth()->user()->can('devices_index'))
                                <a id="tab_btn" data-show="devices" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fa-solid fa-laptop-code"></i> {{ __('Devices') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('device_models_index'))
                                <a id="tab_btn" data-show="device_models" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fa fa-bolt"></i> {{ __('Device Models') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('servicing_settings'))
                                <a id="tab_btn" data-show="service_settings" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> {{ __('Servicing Settings') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('job_card_pdf_print_label_settings'))
                                <a id="tab_btn" data-show="job_card_pdf_and_label" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fa-regular fa-file-pdf"></i> {{ __('Job Card Print/Pdf & Label') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (auth()->user()->can('status_index'))
                        @include('services.settings.partials.body_partials.status')
                    @endif

                    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
                        @if (auth()->user()->can('product_brand_index'))
                            @include('services.settings.partials.body_partials.brands')
                        @endif
                    @endif

                    @if (auth()->user()->can('devices_index'))
                        @include('services.settings.partials.body_partials.devices')
                    @endif

                    @if (auth()->user()->can('device_models_index'))
                        @include('services.settings.partials.body_partials.device_models')
                    @endif

                    @if (auth()->user()->can('servicing_settings'))
                        @include('services.settings.partials.body_partials.service_settings')
                    @endif

                    @if (auth()->user()->can('job_card_pdf_print_label_settings'))
                        @include('services.settings.partials.body_partials.pdf_and_label_settings')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('product_brand_add') || auth()->user()->can('product_brand_edit'))
            <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        @endif
    @endif

    @if (auth()->user()->can('status_create') || auth()->user()->can('status_edit'))
        <div class="modal fade" id="statusAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    @if (auth()->user()->can('devices_create') || auth()->user()->can('devices_create'))
        <div class="modal fade" id="deviceAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    @if (auth()->user()->can('device_models_create') || auth()->user()->can('device_models_create'))
        <div class="modal fade" id="deviceModelAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif
@endsection

@push('scripts')
    @include('services.settings.partials.js_partials.index_js')

    @if (auth()->user()->can('status_index'))
        @include('services.settings.partials.js_partials.status_js')
    @endif

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('product_brand_index'))
            @include('services.settings.partials.js_partials.brand_js')
        @endif
    @endif

    @if (auth()->user()->can('devices_index'))
        @include('services.settings.partials.js_partials.device_js')
    @endif

    @if (auth()->user()->can('device_models_index'))
        @include('services.settings.partials.js_partials.device_model_js')
    @endif

    @if (auth()->user()->can('servicing_settings'))
        @include('services.settings.partials.js_partials.service_settings_js')
    @endif

    @if (auth()->user()->can('job_card_pdf_print_label_settings'))
        @include('services.settings.partials.js_partials.pdf_and_label_settings_js')
    @endif
@endpush
