@extends('layout.master')
@push('stylesheets')
    <style>
        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }

        .dropify-wrapper {
            height: 100px !important;
        }

        tags.tagify {
            min-width: 100%;
        }

        .tagify__input {
            min-width: 100%;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/css-toggle-switch@latest/dist/toggle-switch.css" />
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
@endpush

@section('title', __('Add Job Card - '))

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-1">
                <div class="col-md-6">
                    <h6>{{ __('Add Job Card') }}</h6>
                </div>

                <div class="col-md-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>
        <form id="add_job_card_form" action="#" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="action" id="action" value="">
            <section class="p-lg-1 p-1">
                <div class="row g-1">
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Customer') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="status">
                                                            @foreach ($customerAccounts as $customerAccount)
                                                                <option data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('customer_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('customer_add') ? 'addContact' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_customer_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Job Card No.') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="job_card_no" class="form-control fw-bold" id="name" data-next="code" placeholder="{{ __('Job Card No.') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control" id="date" data-next="code" placeholder="{{ __('Date') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Service Type') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="service_type" class="form-control" id="service_type" data-next="status">
                                                        <option value="">Carry In</option>
                                                    </select>
                                                </div>
                                                <span class="error error_customer_account_id"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Pick Up/On site address') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="address" class="form-control" id="address" data-next="code" placeholder="{{ __('Pick up/On site address') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Delivery Date') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="delivery_date" class="form-control" id="delivery_date" data-next="code" placeholder="{{ __('Delivery Date') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Brand.') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="brand_id" class="form-control select2" id="brand_id" data-next="status">
                                                            <option value="">{{ __('Select Brand') }}</option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button {{ !auth()->user()->can('product_brand_add') ? 'disabled_element' : '' }}" id="{{ auth()->user()->can('product_brand_add') ? 'addBrand' : '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Device') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_id" class="form-control select2" id="device_id" data-next="status">
                                                            <option value="">{{ __('Select Device') }}</option>
                                                            @foreach ($devices as $device)
                                                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addDevice"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Device Model') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_model_id" class="form-control select2" id="device_model_id" data-next="status">
                                                            <option value="">{{ __('Select Device Model') }}</option>
                                                            @foreach ($deviceModels as $deviceModel)
                                                                <option data-checklist="{{ $deviceModel->service_checklist }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addDeviceModel"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-12">
                                            <p><span class="fw-bold">{{ __('Pre Repair Checklist: ') }}</span> <small>N/A = Not Applicable</small></p>
                                        </div>

                                        <hr>
                                        <div class="row gx-2 gy-1" id="check_list_area">
                                            <div class="col-md-2">
                                                <p class="fw-bold text-primary">{{ __('Display') }}</p>
                                                <div class="switch-toggle switch-candy">
                                                    <input id="0_yes" name="checklist[display]" type="radio" value="yes">
                                                    <label for="0_yes">✔</label>

                                                    <input id="0_no" name="checklist[display]" type="radio" value="no">
                                                    <label for="0_no">❌</label>

                                                    <input id="0_na" name="checklist[display]" type="radio" checked value="no">
                                                    <label for="0_na">N/A</label>
                                                    <a></a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Serial Number') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="serial_number" class="form-control" id="serial_number" data-next="code" placeholder="{{ __('Serial Number') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Password') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="password" class="form-control" id="password" data-next="code" placeholder="{{ __('Password') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Configuration') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_configuration" class="tags-look" id="product_configuration" placeholder="{{ __('Product Configuration') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Problem Reports') }}</b></label>
                                                <div class="col-8">
                                                    <input name="problems_report" class="tags-look" id="problems_report" placeholder="{{ __('Problem Reported By The Customer') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Condition') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_condition" class="tags-look" id="product_condition" placeholder="{{ __('Condition Of The Product') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body py-0">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-12">
                                            <p class="pt-2"><span class="fw-bold">{{ __('Add Related Service And Parts') }}</span></p>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="searching_area" style="position: relative;">
                                                <label class="fw-bold">{{ __('Search Product') }}</label>
                                                <div class="input-group">
                                                    <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">
                                                    @if (auth()->user()->can('product_add'))
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('product_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('product_add') ? 'addProduct' : '' }}"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="select_area">
                                                    <ul id="list" class="variant_list_area"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden_fields d-none">
                                            <input type="hidden" id="e_unique_id">
                                            <input type="hidden" id="e_unit_cost_inc_tax">
                                            <input type="hidden" id="e_item_name">
                                            <input type="hidden" id="e_product_id">
                                            <input type="hidden" id="e_variant_id">
                                            <input type="hidden" id="e_tax_amount">
                                            <input type="hidden" id="e_is_show_emi_on_pos">
                                            <input type="hidden" id="e_price_inc_tax">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Quantity') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold w-60" id="e_quantity" placeholder="{{ __('Quantity') }}" value="0.00">
                                                <select id="e_unit_id" class="form-control w-40">
                                                    <option value="">{{ __('Unit') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Unit Price (Exc. Tax)') }}</label>
                                            <input {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Discount') }}</label>
                                            <div class="input-group">
                                                <input {{ auth()->user()->can('edit_discount_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00">

                                                <select id="e_discount_type" class="form-control">
                                                    <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                    <option value="2">{{ __('Percentage') }}(%)</option>
                                                </select>

                                                <input type="hidden" id="e_discount_amount">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                            <div class="input-group">
                                                <select id="e_tax_ac_id" class="form-control w-50">
                                                    <option data-product_tax_percent="0.00" value="">{{ __('NoTax') }}</option>
                                                    {{-- @foreach ($taxAccounts as $taxAccount)
                                                        <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>

                                                <select id="e_tax_type" class="form-control w-50" tabindex="-1">
                                                    <option value="1">{{ __('Exclusive') }}</option>
                                                    <option value="2">{{ __('Inclusive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 align-items-end">
                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Subtotal') }}</label>
                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                            <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger" value="{{ __('Reset') }}">
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Product') }}</th>
                                                                <th class="text-start">{{ __('Quantity') }}</th>
                                                                <th class="text-start">{{ __('Unit') }}</th>
                                                                <th class="text-start">{{ __('Price Inc. Tax') }}</th>
                                                                <th class="text-start">{{ __('Subtotal') }}</th>
                                                                <th class="text-start"><i class="fas fa-minus text-dark"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="sale_product_list"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Technician Comment') }}</b></label>
                                                <input type="text" name="techicial_comment" class="form-control" id="techicial_comment" data-next="code" placeholder="{{ __('Technician Comment') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Status') }}</b></label>
                                                <div class="col-8">
                                                    <select name="status_id" class="form-control select2" id="status_id" data-next="status">
                                                        <option value="">{{ __('Select Status') }}</option>
                                                        @foreach ($status as $status)
                                                            @php
                                                                $defaultStatus = isset($generalSettings['service_settings__default_status_id']) ? $generalSettings['service_settings__default_status_id'] : null;
                                                            @endphp
                                                            <option @selected($defaultStatus == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Total Cost') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_cost" class="form-control fw-bold" id="total_cost" data-next="code" placeholder="{{ __('0.00') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Document') }}</b></label>
                                                <div class="col-8">
                                                    <input type="file" name="document" class="form-control" id="document" data-next="code" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Due Date') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="due_date" class="form-control" id="due_date" data-next="code" placeholder="{{ __('Due Date') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Send Notification') }}</b></label>
                                                <div class="col-8">
                                                    <select name="send_notification" class="form-control" id="send_notification" data-next="status">
                                                        <option value="">{{ __('None') }}</option>
                                                        <option value="email">{{ __('Email') }}</option>
                                                        <option value="sms">{{ __('Sms') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Custom Field 1') }}</b></label>
                                                <input type="text" name="custom_field_1" class="form-control" id="custom_field_1" data-next="code" placeholder="{{ __('Custom Field 1') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Custom Field 2') }}</b></label>
                                                <input type="text" name="custom_field_2" class="form-control" id="custom_field_2" data-next="code" placeholder="{{ __('Custom Field 2') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Custom Field 3') }}</b></label>
                                                <input type="text" name="custom_field_3" class="form-control" id="custom_field_3" data-next="code" placeholder="{{ __('Custom Field 3') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Custom Field 4') }}</b></label>
                                                <input type="text" name="custom_field_4" class="form-control" id="custom_field_4" data-next="code" placeholder="{{ __('Custom Field 4') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Custom Field 5') }}</b></label>
                                                <input type="text" name="custom_field_5" class="form-control" id="custom_field_5" data-next="code" placeholder="{{ __('Custom Field 5') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button job_card_loading_btn d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>

                                <button type="submit" name="action" value="save_and_print" class="btn btn-success job_card_submit_button p-1" id="save_and_print">{{ __('Save And Print') }}</button>
                                <button type="submit" name="action" value="save" class="btn btn-success job_card_ubmit_button p-1" id="save">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <!-- Add Brand Modal -->
    <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    <!-- Add Brand Modal End -->

    <div class="modal fade" id="deviceAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    <div class="modal fade" id="deviceModelAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('services.job_cards.js_partials.create_js')
@endpush
