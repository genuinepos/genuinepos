<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add') }} {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_device_form" action="{{ route('services.settings.devices.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-12">
                        <label>
                            <b>{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] . ' ' . __('Name') : __('Device Name') }}
                            </b>
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" id="device_name" data-next="device_short_description" placeholder="{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] . ' ' . __('Name') : __('Device Name') }}" />
                        <span class="error error_device_name"></span>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-12">
                        <label><b>{{ __('Short Description') }}</b></label>
                        <input type="text" name="short_description" class="form-control" id="device_short_description" data-next="device_save" placeholder="{{ __('Short Description') }}" autocomplete="off" />
                        <span class="error error_short_description"></span>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button device_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="device_save" class="btn btn-sm btn-success device_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('services.settings.ajax_views.devices.js_partials.create_js')
