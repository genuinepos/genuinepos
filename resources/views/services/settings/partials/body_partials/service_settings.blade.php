<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.6;
}

.select2-results__option .fa {
    margin-right: 8px;
}
</style>
<div class="tab_contant service_settings d-hide">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('Service Settings') }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body">
                    @if (auth()->user()->branch_id)
                        <form id="add_service_settings_form" action="{{ route('branches.settings.service.settings', $ownBranchIdOrParentBranchId) }}" method="post">
                    @else
                        <form id="add_service_settings_form" action="{{ route('settings.service.settings') }}" method="post">
                    @endif

                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label><b>{{ __('Default Job Card Status') }}</b></label>
                                <select name="default_status_id" class="form-control" id="service_settings_default_status_id" data-next="service_settings_default_checklist">
                                    <option value="">{{ __('Select Default Job Card Status') }}</option>
                                    @foreach ($status as $status)
                                        @php
                                           $defaultStatus = isset($generalSettings['service_settings__default_status_id']) ? $generalSettings['service_settings__default_status_id'] : null;
                                        @endphp
                                        <option @selected($defaultStatus == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Default Servicing Checklist') }}</b></label>
                                <input type="text" name="default_checklist" class="form-control" id="service_settings_default_checklist" value="{{ isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null }}" placeholder="{{ __('Default Servicing Checklist') }}">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label><b>{{ __('Product Configuration') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Add comma (,) separated multiple product configurations, to be used in job sheet Ex: Item 1, Item 2, Item 3') }}" class="fas fa-info-circle tp"></i></b></label>
                                <input type="text" name="product_configuration" class="form-control" id="service_settings_product_configuration" value="{{ isset($generalSettings['service_settings__product_configuration']) ? $generalSettings['service_settings__product_configuration'] : null }}" placeholder="{{ __('Product Configuration') }}">
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Problem Reported By The Customer') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Add comma (,) separated common problems reported by customer, to be used in job sheet Ex: Item 1, Item 2, Item 3') }}" class="fas fa-info-circle tp"></i></b></label>
                                <input type="text" name="default_problems_report" class="form-control" id="service_settings_default_problems_report" value="{{ isset($generalSettings['service_settings__default_problems_report']) ? $generalSettings['service_settings__default_problems_report'] : null }}" placeholder="{{ __('Problem Reported By The Customer') }}">
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Condition Of The Product') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Add comma (,) separated common product conditions, to be used in job sheet Ex: Item 1, Item 2, Item 3') }}" class="fas fa-info-circle tp"></i></b></label>
                                <input type="text" name="product_condition" class="form-control" id="service_settings_product_condition" value="{{ isset($generalSettings['service_settings__product_condition']) ? $generalSettings['service_settings__product_condition'] : null }}" placeholder="{{ __('Product Configuration') }}">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label><b>{{ __('Servicing terms & conditions') }}</b></label>
                                <textarea name="terms_and_condition" class="ckEditor form-control" id="service_settings_terms_and_condition" cols="30" rows="10" placeholder="{{ __('Servicing terms & conditions') }}">{{ isset($generalSettings['service_settings__terms_and_condition']) ? $generalSettings['service_settings__terms_and_condition'] : null }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label><b>{{ __('Label for job sheet custom field 1') }}</b></label>
                                <input type="text" name="customer_field_1" class="form-control" id="service_settings_customer_field_1" value="{{ isset($generalSettings['service_settings__customer_field_1']) ? $generalSettings['service_settings__customer_field_1'] : null }}" placeholder="{{ __('Label for job sheet custom field 1') }}">
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Label for job sheet custom field 2') }}</b></label>
                                <input type="text" name="customer_field_2" class="form-control" id="service_settings_customer_field_2" value="{{ isset($generalSettings['service_settings__customer_field_2']) ? $generalSettings['service_settings__customer_field_2'] : null }}" placeholder="{{ __('Label for job sheet custom field 2') }}">
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Label for job sheet custom field 3') }}</b></label>
                                <input type="text" name="customer_field_3" class="form-control" id="service_settings_customer_field_3" value="{{ isset($generalSettings['service_settings__customer_field_3']) ? $generalSettings['service_settings__customer_field_3'] : null }}" placeholder="{{ __('Label for job sheet custom field 3') }}">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label><b>{{ __('Label for job sheet custom field 4') }}</b></label>
                                <input type="text" name="customer_field_4" class="form-control" id="service_settings_customer_field_4" value="{{ isset($generalSettings['service_settings__customer_field_4']) ? $generalSettings['service_settings__customer_field_4'] : null }}" placeholder="{{ __('Label for job sheet custom field 4') }}">
                            </div>

                            <div class="col-md-4">
                                <label><b>{{ __('Label for job sheet custom field 5') }}</b></label>
                                <input type="text" name="customer_field_5" class="form-control" id="service_settings_customer_field_5" value="{{ isset($generalSettings['service_settings__customer_field_5']) ? $generalSettings['service_settings__customer_field_5'] : null }}" placeholder="{{ __('Label for job sheet custom field 5') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button service_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                    <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
