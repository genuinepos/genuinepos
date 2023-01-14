@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'SMS Settings - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-sliders-h"></span>
                <h6>@lang('menu.sms_settings')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
            </a>
        </div>
    </div>

    <div class="p-3">
        <div class="card">

            <form id="sms_settings_form" class="setting_form p-3"
                action="{{ route('communication.sms.settings.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <div class="setting_form_heading">
                        <h6 class="text-primary">@lang('menu.sms_settings')</h6>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label><strong>{{ __('SMS URL') }} </strong></label>
                        <input type="text" name="SMS_URL" class="form-control"
                            placeholder="{{ __('SMS URL') }}" autocomplete="off"
                            value="{{ $generalSettings['sms__SMS_URL'] }}">
                    </div>

                    <div class="col-md-3">
                        <label><strong>{{ __('API KEY') }} </strong></label>
                        <input type="text" name="API_KEY" class="form-control"
                            placeholder="{{ __('API KEY') }}" autocomplete="off"
                            value="{{ $generalSettings['sms__API_KEY'] }}">
                    </div>

                    <div class="col-md-3">
                        <label><strong>{{ __('SENDER ID') }} </strong></label>
                        <input type="text" name="SENDER_ID" class="form-control"
                            placeholder="{{ __('SENDER ID') }}" autocomplete="off"
                            value="{{ $generalSettings['sms__SENDER_ID'] }}">
                    </div>

                    <div class="col-md-3 mt-1">
                        <div class="row mt-4">
                            <p class="checkbox_input_wrap">
                                <input type="checkbox"
                                    {{  $generalSettings['sms__SMS_ACTIVE'] == 'true' ? 'CHECKED' : '' }}
                                    name="SMS_ACTIVE" autocomplete="off"> &nbsp; <b>@lang('menu.is_active')</b>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                            <button class="btn btn-success submit_button float-end">@lang('menu.save_change')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
        $('#sms_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
