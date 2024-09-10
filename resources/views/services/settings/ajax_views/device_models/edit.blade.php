<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit') }} {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_device_model_form" action="{{ route('services.settings.device.models.update', $deviceModel->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>
                            <b>
                                {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] . ' ' .__('Name') : __('Model Name') }}
                            </b>
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" id="device_model_name" data-next="device_model_brand_id" value="{{ $deviceModel->name }}" placeholder="{{ __('Model Name') }}" />
                        <span class="error error_device_model_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-6">
                        <label><b>{{ __('Brand.') }}</b></label>
                        <select name="brand_id" class="form-control" id="device_model_brand_id" data-next="device_model_device_id">
                            <option value="">{{ __('Select Brand') }}</option>
                            @foreach ($brands as $brand)
                                <option @selected($brand->id == $deviceModel->brand_id) value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label><b>{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</b></label>
                        <select name="device_id" class="form-control" id="device_model_device_id" data-next="device_model_service_checklist">
                            <option value="">{{ __('Select') }} {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</option>
                            @foreach ($devices as $device)
                                <option @selected($device->id == $deviceModel->device_id) value="{{ $device->id }}">{{ $device->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <div class="col-12">
                        <label><b>{{ __('Service Checklist') }}</b> <small>{{ __('Add pipe (|) separated multiple checklist Ex: Item 1 | Item 2 | Item 3') }}</small></label>
                        <input type="text" name="service_checklist" class="form-control" data-next="device_model_save_changes" id="device_model_service_checklist" value="{{ $deviceModel->service_checklist }}" placeholder="{{ __('Exp: Display | Buttery | Motherboard') }}" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button device_model_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="device_model_save_changes" class="btn btn-sm btn-success device_model_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('services.settings.ajax_views.device_models.js_partials.edit_js')
