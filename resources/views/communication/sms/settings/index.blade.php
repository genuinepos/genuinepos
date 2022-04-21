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
    <div class="row mt-5 px-3">
        <div class="border-class">
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="card ms-1">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>SMS Settings</h6>
                            </div>
                        </div>

                        <form id="sms_settings_form" class="setting_form p-3"
                            action="{{ route('communication.sms.settings.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="setting_form_heading">
                                    <h6 class="text-primary">SMS Settings</h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label><strong>SMS URL : </strong></label>
                                    <input type="text" name="SMS_URL" class="form-control"
                                        placeholder="SMS URL" autocomplete="off"
                                        value="{{ env('SMS_URL') }}">
                                </div>

                                <div class="col-md-3">
                                    <label><strong>API KEY : </strong></label>
                                    <input type="text" name="API_KEY" class="form-control"
                                        placeholder="API KEY" autocomplete="off"
                                        value="{{ env('API_KEY') }}">
                                </div>

                                <div class="col-md-3">
                                    <label><strong>SENDER ID : </strong></label>
                                    <input type="text" name="SENDER_ID" class="form-control"
                                        placeholder="SENDER ID" autocomplete="off"
                                        value="{{ env('SENDER_ID') }}">
                                </div>

                                <div class="col-md-3 mt-1">
                                    <div class="row mt-4">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                {{  env('SMS_ACTIVE') == 'true' ? 'CHECKED' : '' }}
                                                name="SMS_ACTIVE" autocomplete="off"> &nbsp; <b>Is Active</b> 
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <button class="btn btn-sm btn-success submit_button float-end">Save Change</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
