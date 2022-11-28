@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'Email Settings - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <span class="fas fa-sliders-h"></span>
                <h6>Email Settings</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
            </a>
        </div>
    </div>
    <div class="p-3">
        <div class="card ms-1">

            <form id="email_settings_form" class="setting_form p-3" action="{{ route('communication.email.settings.store') }}" method="post">
                @csrf

                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>MAIL MAILER : </strong></label>
                        <input type="text" name="MAIL_MAILER" class="form-control es_input"
                            placeholder="MAIL MAILER" autocomplete="off"
                            value="{{ $emailSetting['MAIL_MAILER'] }}">
                    </div>

                    <div class="col-md-4">
                        <label><strong>MAIL HOST :</strong></label>
                        <input type="text" name="MAIL_HOST" class="form-control es_input"
                            placeholder="MAIL HOST" autocomplete="off"
                            value="{{ $emailSetting['MAIL_HOST'] }}">
                    </div>

                    <div class="col-md-4">
                        <label><strong>MAIL PORT :</strong></label>
                        <input type="text" name="MAIL_PORT" class="form-control  es_input"
                            placeholder="MAIL PORT" autocomplete="off"
                            value="{{ $emailSetting['MAIL_PORT'] }}">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>MAIL_USERNAME :</strong></label>
                        <input type="text" name="MAIL_USERNAME" class="form-control es_input"
                            placeholder="MAIL USERNAME" autocomplete="off"
                            value="{{ $emailSetting['MAIL_USERNAME'] }}">
                    </div>

                    <div class="col-md-4">
                        <label><strong>MAIL PASSWORD :</strong></label>
                        <input type="text" name="MAIL_PASSWORD" class="form-control es_input"
                            placeholder="MAIL PASSWORD" autocomplete="off"
                            value="{{ $emailSetting['MAIL_PASSWORD'] }}">
                    </div>

                    <div class="col-md-4">
                        <label><strong>MAIL ENCRYPTION :</strong></label>
                        <input type="text" name="MAIL_ENCRYPTION" class="form-control  es_input"
                            placeholder="MAIL ENCRYPTION" autocomplete="off"
                            value="{{ $emailSetting['MAIL_ENCRYPTION'] }}">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>MAIL FROM ADDRESS :</strong></label>
                        <input type="text" name="MAIL_FROM_ADDRESS" class="form-control es_input"
                            placeholder="MAIL FROM ADDRESS" autocomplete="off"
                            value="{{ $emailSetting['MAIL_FROM_ADDRESS'] }}">
                    </div>

                    <div class="col-md-4">
                        <label><strong>MAIL FROM NAME :</strong></label>
                        <input type="text" name="MAIL_FROM_NAME" class="form-control es_input"
                            placeholder="MAIL FROM NAME" autocomplete="off"
                            value="{{ $emailSetting['MAIL_FROM_NAME'] }}">
                    </div>

                    <div class="col-md-4 mt-1">
                        <div class="row mt-4">
                            <p class="checkbox_input_wrap">
                                <input type="checkbox"
                                    {{  $emailSetting['MAIL_ACTIVE'] ? 'checked' : '' }}
                                    name="MAIL_ACTIVE" autocomplete="off"> &nbsp; <b>@lang('menu.is_active')</b>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-1">

                </div>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> Loading...</span></button>
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
         $('#email_settings_form').on('submit', function(e) {
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
