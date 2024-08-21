<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Status') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_status_form" action="{{ route('services.settings.status.update', $status->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-6">
                        <label><b>{{ __('Status Name') }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="status_name" data-next="status_color_code" value="{{ $status->name }}" placeholder="{{ __('Status Name') }}" />
                        <span class="error error_status_name"></span>
                    </div>

                    <div class="col-6">
                        <label><b>{{ __('Color') }}</b></label>
                        <input type="color" name="color_code" class="form-control" id="status_color_code" data-next="status_sort_order" value="{{ $status->color_code }}" placeholder="{{ __('Status Color') }}" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-6">
                        <label><b>{{ __('Sort Order') }}</b></label>
                        <input type="text" name="sort_order" class="form-control" id="status_sort_order" data-next="status_as_complete" value="{{ $status->sort_order }}" placeholder="{{ __('Status Sort Order') }}" autocomplete="off" />
                    </div>

                    <div class="col-lg-6">
                        <label><b>{{ __('This Status As Complete') }}</b></label>
                        <select name="status_as_complete" class="form-control" id="status_as_complete" data-next="status_save_changes">
                            <option value="0">{{ __('No') }}</option>
                            <option @selected($status->status_as_complete == 1) value="1">{{ __('Yes') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-12">
                        <label><b>{{ __('Sms Template') }}</b></label>
                        <textarea name="sms_template" class="form-control" id="status_sms_template" cols="10" rows="3" placeholder="{{ __('Sms Template') }}" autocomplete="off">{{ $status->sms_template }}</textarea>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-12">
                        <label><b>{{ __('Email Subject') }}</b></label>
                        <input type="text" name="email_subject" class="form-control" data-next="status_sort_order" id="status_email_subject" value="{{ $status->email_subject }}" placeholder="{{ __('Email Subject') }}" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-12">
                        <label><b>{{ __('Email Body') }}</b></label>
                        <textarea name="email_body" class="ckEditor form-control" id="status_email_body" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;" placeholder="{{ __('Email Body') }}" autocomplete="off">{{ $status->email_body }}</textarea>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button status_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="status_save_changes" class="btn btn-sm btn-success status_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('services.settings.ajax_views.status.js_partials.edit_js')
