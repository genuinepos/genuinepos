<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.6;
    }

    .select2-results__option .fa {
        margin-right: 8px;
    }
</style>
<div class="tab_contant job_card_pdf_and_label d-hide">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('Job Card Print/Pdf & Label') }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body">
                    @if (auth()->user()->branch_id)
                        <form id="add_service_settings_pdf_label_form" action="{{ route('branches.settings.pdf.and.label.settings', $ownBranchIdOrParentBranchId) }}" method="post">
                    @else
                        <form id="add_service_settings_pdf_label_form" action="{{ route('settings.pdf.and.label.settings') }}" method="post">
                    @endif

                    @csrf
                    <div class="row">
                        <div class="heading_area">
                            <p class="pt-1 pb-0 text-primary"><b>{{ __('Fields for customer details') }}</b></p>
                            <hr class="p-0 m-0">
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Customer Information') }}</b></label>
                            <select name="show_customer_info" class="form-control" id="service_settings_pdf_label_show_customer_info">
                                @php
                                    $showCustomerInfo = isset($generalSettings['service_settings_pdf_label__show_customer_info']) ? $generalSettings['service_settings_pdf_label__show_customer_info'] : null;
                                @endphp
                                <option value="1">{{ __('Yes') }}</option>
                                <option @selected($showCustomerInfo == '0') value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Customer Lebel Name') }}</b></label>
                            <input type="text" name="customer_label_name" class="form-control" id="service_settings_pdf_label_customer_label_name" value="{{ isset($generalSettings['service_settings_pdf_label__customer_label_name']) ? $generalSettings['service_settings_pdf_label__customer_label_name'] : null }}" placeholder="{{ __('Customer Lebel Name') }}">
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Customer ID') }}</b></label>
                            <select name="show_contact_id" class="form-control" id="service_settings_pdf_label_show_contact_id">
                                @php
                                    $showCustomerId = isset($generalSettings['service_settings_pdf_label__show_contact_id']) ? $generalSettings['service_settings_pdf_label__show_contact_id'] : null;
                                @endphp
                                <option value="1">{{ __('Yes') }}</option>
                                <option @selected($showCustomerId == '0') value="0">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <label><b>{{ __('Customer ID Label Name') }}</b></label>
                            <input type="text" name="customer_id_label_name" class="form-control" id="service_settings_pdf_label_customer_id_label_name" value="{{ isset($generalSettings['service_settings_pdf_label__customer_id_label_name']) ? $generalSettings['service_settings_pdf_label__customer_id_label_name'] : null }}" placeholder="{{ __('Customer ID Label Name') }}">
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Customer Tax No.') }}</b></label>
                            <select name="show_customer_tax_no" class="form-control" id="service_settings_pdf_label_show_customer_tax_no">
                                @php
                                    $showCustomerTaxNo = isset($generalSettings['service_settings_pdf_label__show_customer_tax_no']) ? $generalSettings['service_settings_pdf_label__show_customer_tax_no'] : null;
                                @endphp
                                <option value="1">{{ __('Yes') }}</option>
                                <option @selected($showCustomerTaxNo == '0') value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Customer Tax No. Label Name') }}</b></label>
                            <input type="text" name="customer_tax_no_label_name" class="form-control" id="service_settings_pdf_label_customer_tax_no_label_name" value="{{ isset($generalSettings['service_settings_pdf_label__customer_tax_no_label_name']) ? $generalSettings['service_settings_pdf_label__customer_tax_no_label_name'] : null }}" placeholder="{{ __('Customer ID Label Name') }}">
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <label><b>{{ __('Show Custom Field 1') }}</b></label>
                            <select name="show_custom_field_1" class="form-control" id="service_settings_pdf_label_show_custom_field_1">
                                @php
                                    $showCustomeField1 = isset($generalSettings['service_settings_pdf_label__show_custom_field_1']) ? $generalSettings['service_settings_pdf_label__show_custom_field_1'] : null;
                                @endphp
                                <option value="0">{{ __('No') }}</option>
                                <option @selected($showCustomeField1 == '1') value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Custom Field 2') }}</b></label>
                            <select name="show_custom_field_2" class="form-control" id="service_settings_pdf_label_show_custom_field_2">
                                @php
                                    $showCustomeField2 = isset($generalSettings['service_settings_pdf_label__show_custom_field_2']) ? $generalSettings['service_settings_pdf_label__show_custom_field_2'] : null;
                                @endphp
                                <option value="0">{{ __('No') }}</option>
                                <option @selected($showCustomeField2 == '1') value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Custom Field 3') }}</b></label>
                            <select name="show_custom_field_3" class="form-control" id="service_settings_pdf_label_show_custom_field_3">
                                @php
                                    $showCustomeField3 = isset($generalSettings['service_settings_pdf_label__show_custom_field_3']) ? $generalSettings['service_settings_pdf_label__show_custom_field_3'] : null;
                                @endphp
                                <option value="0">{{ __('No') }}</option>
                                <option @selected($showCustomeField3 == '1') value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <label><b>{{ __('Show Custom Field 4') }}</b></label>
                            <select name="show_custom_field_4" class="form-control" id="service_settings_pdf_label_show_custom_field_4">
                                @php
                                    $showCustomeField4 = isset($generalSettings['service_settings_pdf_label__show_custom_field_4']) ? $generalSettings['service_settings_pdf_label__show_custom_field_4'] : null;
                                @endphp
                                <option value="0">{{ __('No') }}</option>
                                <option @selected($showCustomeField4 == '1') value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Show Custom Field 5') }}</b></label>
                            <select name="show_custom_field_5" class="form-control" id="service_settings_pdf_label_show_custom_field_5">
                                @php
                                    $showCustomeField5 = isset($generalSettings['service_settings_pdf_label__show_custom_field_5']) ? $generalSettings['service_settings_pdf_label__show_custom_field_5'] : null;
                                @endphp
                                <option value="0">{{ __('No') }}</option>
                                <option @selected($showCustomeField5 == '1') value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="heading_area">
                            <p class="pt-1 pb-0 text-primary"><b>{{ __('Job Card Label Size') }}</b></p>
                            <hr class="p-0 m-0">
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Label Width(MM)') }}</b></label>
                            <input type="text" name="label_width" class="form-control" id="service_settings_pdf_label_label_width" value="{{ isset($generalSettings['service_settings_pdf_label__label_width']) ? $generalSettings['service_settings_pdf_label__label_width'] : 75 }}" placeholder="{{ __('Label Width(MM)') }}">
                        </div>

                        <div class="col-md-4">
                            <label><b>{{ __('Label Height(MM)') }}</b></label>
                            <input type="text" name="label_height" class="form-control" id="service_settings_pdf_label_label_height" value="{{ isset($generalSettings['service_settings_pdf_label__label_height']) ? $generalSettings['service_settings_pdf_label__label_height'] : 55 }}" placeholder="{{ __('Label Height(MM)') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="heading_area">
                                <p class="pt-1 pb-0 text-primary"><b>{{ __('Customer Information In Label') }}</b></p>
                                <hr class="p-0 m-0">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>{{ __('Customer name') }}</b></label>
                                    <select name="customer_name_in_label" class="form-control" id="service_settings_pdf_label_customer_name_in_label">
                                        @php
                                            $customerNameInLabel = isset($generalSettings['service_settings_pdf_label__customer_name_in_label']) ? $generalSettings['service_settings_pdf_label__customer_name_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($customerNameInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Customer Address') }}</b></label>
                                    <select name="customer_address_in_label" class="form-control" id="service_settings_pdf_label_customer_address_in_label">
                                        @php
                                            $customerAddressInLabel = isset($generalSettings['service_settings_pdf_label__customer_address_in_label']) ? $generalSettings['service_settings_pdf_label__customer_address_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($customerAddressInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Customer Phone') }}</b></label>
                                    <select name="customer_phone_in_label" class="form-control" id="service_settings_pdf_label_customer_phone_in_label">
                                        @php
                                            $customerPhoneInLabel = isset($generalSettings['service_settings_pdf_label__customer_phone_in_label']) ? $generalSettings['service_settings_pdf_label__customer_phone_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($customerPhoneInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Alternate Phone') }}</b></label>
                                    <select name="customer_alt_phone_in_label" class="form-control" id="service_settings_pdf_label_customer_alt_phone_in_label">
                                        @php
                                            $customerAltPhoneInLabel = isset($generalSettings['service_settings_pdf_label__customer_alt_phone_in_label']) ? $generalSettings['service_settings_pdf_label__customer_alt_phone_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($customerAltPhoneInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Customer Email') }}</b></label>
                                    <select name="customer_email_in_label" class="form-control" id="service_settings_pdf_label_customer_email_in_label">
                                        @php
                                            $customerEmailInLabel = isset($generalSettings['service_settings_pdf_label__customer_email_in_label']) ? $generalSettings['service_settings_pdf_label__customer_email_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($customerEmailInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="heading_area">
                                <p class="pt-1 pb-0 text-primary"><b>{{ __('Label Details') }}</b></p>
                                <hr class="p-0 m-0">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>{{ __('Sales Person') }}</b></label>
                                    <select name="sales_person_in_label" class="form-control" id="service_settings_pdf_label_sales_person_in_label">
                                        @php
                                            $salesPersonInLabel = isset($generalSettings['service_settings_pdf_label__sales_person_in_label']) ? $generalSettings['service_settings_pdf_label__sales_person_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($salesPersonInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Barcode (Job Sheet Number)') }}</b></label>
                                    <select name="barcode_in_label" class="form-control" id="service_settings_pdf_label_barcode_in_label">
                                        @php
                                            $barcodeInLabel = isset($generalSettings['service_settings_pdf_label__barcode_in_label']) ? $generalSettings['service_settings_pdf_label__barcode_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($barcodeInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Status') }}</b></label>
                                    <select name="status_in_label" class="form-control" id="service_settings_pdf_label_status_in_label">
                                        @php
                                            $statusInLabel = isset($generalSettings['service_settings_pdf_label__status_in_label']) ? $generalSettings['service_settings_pdf_label__status_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($statusInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Due Date') }}</b></label>
                                    <select name="due_date_in_label" class="form-control" id="service_settings_pdf_label_due_date_in_label">
                                        @php
                                            $dueDateInLabel = isset($generalSettings['service_settings_pdf_label__due_date_in_label']) ? $generalSettings['service_settings_pdf_label__due_date_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($dueDateInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="heading_area">
                                <p class="pt-1 pb-0 text-primary"><b>{{ __('Label Information') }}</b></p>
                                <hr class="p-0 m-0">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>{{ __('Technician') }}</b></label>
                                    <select name="technician_in_label" class="form-control" id="service_settings_pdf_label_technician_in_label">
                                        @php
                                            $technicianInLabel = isset($generalSettings['service_settings_pdf_label__technician_in_label']) ? $generalSettings['service_settings_pdf_label__technician_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($technicianInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Problems') }}</b></label>
                                    <select name="problems_in_label" class="form-control" id="service_settings_pdf_label_problems_in_label">
                                        @php
                                            $problemsInLabel = isset($generalSettings['service_settings_pdf_label__problems_in_label']) ? $generalSettings['service_settings_pdf_label__problems_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($problemsInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Job Card Number') }}</b></label>
                                    <select name="job_card_no_in_label" class="form-control" id="service_settings_pdf_label_job_card_no_in_label">
                                        @php
                                            $jobCardNoInLabel = isset($generalSettings['service_settings_pdf_label__job_card_no_in_label']) ? $generalSettings['service_settings_pdf_label__job_card_no_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($jobCardNoInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="heading_area">
                                <p class="pt-1 pb-0 text-primary"><b>{{ __('Device Info') }}</b></p>
                                <hr class="p-0 m-0">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>{{ __('IMEI/Serial No.') }}</b></label>
                                    <select name="serial_in_label" class="form-control" id="service_settings_pdf_label_serial_in_label">
                                        @php
                                            $serialInLabel = isset($generalSettings['service_settings_pdf_label__serial_in_label']) ? $generalSettings['service_settings_pdf_label__serial_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($serialInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Brand/Model') }}</b></label>
                                    <select name="model_in_label" class="form-control" id="service_settings_pdf_label_model_in_label">
                                        @php
                                            $modelInLabel = isset($generalSettings['service_settings_pdf_label__model_in_label']) ? $generalSettings['service_settings_pdf_label__model_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($modelInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label><b>{{ __('Password') }}</b></label>
                                    <select name="password_in_label" class="form-control" id="service_settings_pdf_label_password_in_label">
                                        @php
                                            $passwordInLabel = isset($generalSettings['service_settings_pdf_label__password_in_label']) ? $generalSettings['service_settings_pdf_label__password_in_label'] : null;
                                        @endphp
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option @selected($passwordInLabel == '0') value="0">{{ __('No') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button service_settings_pdf_label_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
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

<form id="delete_status_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>
