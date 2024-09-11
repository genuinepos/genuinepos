<div class="tab_contant device_models d-hide" id="tab_contant">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('List of') }} {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Models') }}</h6>
        </div>

        <div class="col-6 d-flex justify-content-end">
            @if (auth()->user()->can('device_models_create'))
                <a href="{{ route('services.settings.device.models.create') }}" class="btn btn-sm btn-success" id="addDeviceModel"><i class="fas fa-plus-square"></i> {{ __('Add') }} {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }}</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table_area">
                <div class="table-responsive">
                    <table id="device-models-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                {{-- <th>{{ __('S/L') }}</th> --}}
                                <th>
                                    {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Model Name') }}
                                </th>
                                <th>{{ __('Brand.') }}</th>
                                <th>{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device Name') }}</th>
                                <th>{{ __('Sevice Checklist') }}</th>
                                <th>{{ __('Created By') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->can('device_models_delete'))
    <form id="delete_device_model_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endif
