@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endpush
@section('title', 'Add Invoice Layout')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Add Invoice Layout') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="add_layout_form" action="{{ route('invoices.layouts.store') }}" method="POST">
                @csrf
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Name') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="name" class="form-control" id="name" data-next="page_size" placeholder="Layout Name" autofocus>
                                                    <span class="error error_name"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Show Store Logo') }}</b></label>
                                                <div class="col-8">
                                                    <select name="show_business_shop_logo" id="show_business_shop_logo" class="form-control" data-next="show_total_in_word">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Show Total Inword') }}</b></label>
                                                <div class="col-8">
                                                    <select name="show_total_in_word" id="show_total_in_word" class="form-control" data-next="is_header_less">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Header Option') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Is Header less') }}?</b></label>
                                                <div class="col-8">
                                                    <select name="is_header_less" id="is_header_less" class="form-control" data-next="header_text">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 hideable_field d-hide">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Gap From Top(Inc)') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="number" name="gap_from_top" id="gap_from_top" class="form-control" data-next="header_text" placeholder="{{ __('Gap From Top(Inc)') }}">
                                                    <span class="error error_gap_from_top"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Header Text') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="header_text" id="header_text" class="form-control" data-next="sub_heading_1" placeholder="{{ __('Header Text') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Sub Heading 1') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="sub_heading_1" id="sub_heading_1" class="form-control" data-next="sub_heading_2" placeholder="{{ __('Sub Heading Line 1') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Sub Heading 2') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="sub_heading_2" id="sub_heading_2" class="form-control" data-next="invoice_heading" placeholder="Sub Heading Line 2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Paper Main Heading') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Invoice Heading') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="invoice_heading" class="form-control" id="invoice_heading" data-next="quotation_heading" placeholder="{{ __('Invoice Heading') }}">
                                                    <span class="error error_quotation_heading"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Quotation Heading') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="quotation_heading" id="quotation_heading" class="form-control" data-next="sales_order_heading" placeholder="{{ __('Quotation Heading') }}">
                                                    <span class="error error_quotation_heading"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Sales Order Heading') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="sales_order_heading" id="sales_order_heading" class="form-control" data-next="delivery_note_heading" placeholder="{{ __('Sales Order Heading') }}">
                                                    <span class="error error_sales_order_heading"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><span class="text-danger">*</span> <b>{{ __('Delivery Note Heading') }}</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="delivery_note_heading" id="delivery_note_heading" class="form-control" data-next="branch_city" placeholder="{{ __('Delivery Note Heading') }}">
                                                    <span class="error error_delivery_heading"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Field For Store Address') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('City') }}</b></label>
                                                <div class="col-8">
                                                    <select name="branch_city" id="branch_city" class="form-control" data-next="branch_state">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('State') }}</b></label>
                                                <div class="col-8">
                                                    <select name="branch_state" id="branch_state" class="form-control" data-next="branch_zipcode">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Zip-Code') }}</b></label>
                                                <div class="col-8">
                                                    <select name="branch_zipcode" id="branch_zipcode" class="form-control" data-next="branch_phone">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Phone Number') }}?</b></label>
                                                <div class="col-8">
                                                    <select name="branch_phone" id="branch_phone" class="form-control" data-next="branch_alternate_number">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Alternative Phone') }}</b></label>
                                                <div class="col-8">
                                                    <select name="branch_alternate_number" id="branch_alternate_number" class="form-control" data-next="branch_email">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Store Email Address') }}</b></label>
                                                <div class="col-8">
                                                    <select name="branch_email" id="branch_email" class="form-control" data-next="customer_name">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Field For Customer') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Customer Name') }}</b></label>
                                                <div class="col-8">
                                                    <select name="customer_name" id="customer_name" class="form-control" data-next="customer_tax_no">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Tax Number') }}</b></label>
                                                <div class="col-8">
                                                    <select name="customer_tax_no" id="customer_tax_no" class="form-control" data-next="customer_address">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Address') }}</b></label>
                                                <div class="col-8">
                                                    <select name="customer_address" id="customer_address" class="form-control" data-next="customer_phone">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Phone') }}</b></label>
                                                <div class="col-8">
                                                    <select name="customer_phone" id="customer_phone" class="form-control" data-next="customer_current_balance">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Current Balance') }}</b></label>
                                                <div class="col-8">
                                                    <select name="customer_current_balance" id="customer_current_balance" class="form-control" data-next="product_w_type">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Field For Product') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Warranty Type') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_w_type" id="product_w_type" class="form-control" data-next="product_w_duration">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Warranty Duration') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_w_duration" id="product_w_duration" class="form-control" data-next="product_discount">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Discount') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_discount" id="product_discount" class="form-control" data-next="product_tax">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Tax') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_tax" id="product_tax" class="form-control" data-next="product_price_exc_tax">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Price Exc. Tax') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_price_exc_tax" id="product_price_exc_tax" class="form-control" data-next="product_price_inc_tax">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Price Inc. Tax') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_price_inc_tax" id="product_price_inc_tax" class="form-control" data-next="product_brand">
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Brand.') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_brand" id="product_brand" class="form-control" data-next="product_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Description') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_details" id="product_details" class="form-control" data-next="product_code">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Product Code') }}</b></label>
                                                <div class="col-8">
                                                    <select name="product_code" id="product_code" class="form-control" data-next="account_no">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Bank Details') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Account No') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="account_no" class="form-control" id="account_no" data-next="account_name" placeholder="{{ __('Account Number') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Account Name') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="account_name" class="form-control" id="account_name" data-next="bank_name" placeholder="{{ __('Account Name') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Bank Name') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_name" class="form-control" id="bank_name" data-next="bank_branch" placeholder="{{ __('Bank Name') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Bank Branch') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_branch" class="form-control" id="bank_branch" data-next="invoice_notice" placeholder="{{ __('Bank Branch') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>{{ __('Footer Text') }}</b></p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Invoice Notice') }}</b></label>
                                                <div class="col-8">
                                                    <input name="invoice_notice" class="form-control" id="invoice_notice" data-next="footer_text" placeholder="{{ __('Invoice Notice') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-1"><b>{{ __('Footer Text') }}</b></label>
                                                <div class="col-8">
                                                    <input name="footer_text" class="form-control" id="footer_text" data-next="save_btn" placeholder="{{ __('Footer Text') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-area d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                    <button id="save_btn" class="btn btn-sm btn-success submit_button">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            var action_direction = $(this).val();

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_btn').click();
                return false;
            }
        }

        // Add Invoice layout by ajax
        $('#add_layout_form').on('submit', function(e) {

            e.preventDefault();
            $('.loading_button').removeClass('d-hide');
            var url = $(this).attr('action');

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success(data);
                        $('#add_layout_form')[0].reset();
                        document.getElementById('name').focus();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },
                error: function(err) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if (err.status == 0) {

                        toastr.error('Net Connection Error.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                e.preventDefault();

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                if ($(this).attr('id') == 'is_header_less' && $(this).val() == 1) {

                    $('#gap_from_top').focus().salect();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $('#is_header_less').on('change', function() {

            if ($(this).val() == 1) {

                $('.hideable_field').show();
                $('#gap_from_top').prop('required', true);
            } else {

                $('.hideable_field').hide();
                $('#gap_from_top').prop('required', false);
            }
        });
    </script>
@endpush
