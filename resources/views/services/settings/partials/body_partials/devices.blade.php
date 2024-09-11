<div class="tab_contant devices d-hide" id="tab_contant">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('List of') }} {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Devices') }}</h6>
        </div>

        <div class="col-6 d-flex justify-content-end">
            @if (auth()->user()->can('devices_create'))
                <a href="{{ route('services.settings.devices.create') }}" class="btn btn-sm btn-success" id="addDevice"><i class="fas fa-plus-square"></i> {{ __('Add') }} {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table_area">
                <div class="table-responsive">
                    <table id="devices-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                {{-- <th>{{ __('S/L') }}</th> --}}
                                <th>{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device Name') }}</th>
                                <th>{{ __('Short Description') }}</th>
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

@if (auth()->user()->can('devices_delete'))
    <form id="delete_device_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endif
