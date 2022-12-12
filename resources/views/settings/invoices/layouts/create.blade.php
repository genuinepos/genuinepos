@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-plus-circle"></span>
                    <h5>Add Invoice Layout</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_layout_form" action="{{ route('invoices.layouts.store') }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><span class="text-danger">*</span> <b>Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="name" class="form-control" placeholder="Layout Name" autofocus>
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Design :</b></label>

                                        <div class="col-8">
                                            <select name="design" id="design" class="form-control">
                                                <option value="1">Classic (For normal printer)</option>
                                                <option value="2">Slim (For POS printer)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap bordered">
                                                <input type="checkbox" checked name="show_shop_logo"> &nbsp; Show Business/Shop Logo</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" checked name="show_seller_info"> &nbsp; Show Seller Info</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="col-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" checked name="show_total_in_word"> &nbsp; Show Total In Word</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Header Option</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-md-3">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="is_header_less" id="is_header_less"> &nbsp;<b>Is Headerless ?</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you check this option then print header info will not come in the print preview. Use case, When the print page is pre-generated Like Pad.Where header info previously exists." class="fas fa-info-circle tp"></i>
                                    </p>
                                </div>

                                <div class="col-md-9 hideable_field d-hide">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><span class="text-danger">*</span>  <b>@lang('menu.gap_from_top') (inc) : </b> </label>
                                        <div class="col-8">
                                            <input type="number" name="gap_from_top" id="gap_from_top" class="form-control" placeholder="@lang('menu.gap_from_top')">
                                            <span class="error error_gap_from_top"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-2 mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Sub Heading 1 : </b> </label>
                                        <div class="col-8">
                                            <input type="text" name="sub_heading_1" id="sub_heading_1" class="form-control" placeholder="Sub Heading Line 1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Sub Heading 2 : </b> </label>
                                        <div class="col-8">
                                            <input type="text" name="sub_heading_2" id="sub_heading_2" class="form-control" placeholder="Sub Heading Line 2">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-lg-2 col-4"><b>Header Text : </b> </label>
                                        <div class="col-lg-10 col-8">
                                            <input type="text" name="header_text" class="form-control form-control-sm"  placeholder="Header text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Invoice Heading</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-lg-4 col-5"><span class="text-danger">*</span> <b>Invoice Heading :</b> </label>
                                        <div class="col-lg-8 col-7">
                                            <input type="text" name="invoice_heading" class="form-control" id="invoice_heading" placeholder="Invoice Heading">
                                            <span class="error error_invoice_heading"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-lg-4 col-5"><span class="text-danger">*</span> <b>Quotation Heading :</b> </label>
                                        <div class="col-lg-8 col-7">
                                            <input type="text" name="quotation_heading" id="quotation_heading" class="form-control" placeholder="Quotation Heading">
                                            <span class="error error_quotation_heading"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-2 mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-lg-4 col-5"><span class="text-danger">*</span> <b>Draft Heading : </b> </label>
                                        <div class="col-lg-8 col-7">
                                            <input type="text" name="draft_heading" id="draft_heading" class="form-control" placeholder="Draft Heading">
                                            <span class="error error_draft_heading"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-lg-4 col-5"><span class="text-danger">*</span> <b>Challan Heading : </b> </label>
                                        <div class="col-lg-8 col-7">
                                            <input type="text" name="challan_heading" id="challan_heading" class="form-control" placeholder="Challan Heading">
                                            <span class="error error_challan_heading"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Field For Branch</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="branch_landmark" > &nbsp; <b>Landmark</b> </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" checked name="branch_city"> &nbsp;<b>@lang('menu.city')</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="branch_state"> &nbsp; <b>@lang('menu.state')</b></p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="branch_zipcode"> &nbsp; <b>@lang('menu.zip_code')</b></p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="branch_phone"> &nbsp; <b>@lang('menu.phone')</b></p>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="branch_alternate_number"> &nbsp; <b>@lang('menu.alternative_number')</b></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="branch_email"> &nbsp; <b>@lang('menu.email')</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>@lang('menu.field_for_customer')</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" checked name="customer_name"> &nbsp;<b>@lang('menu.name')</b></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" checked name="customer_tax_no"> &nbsp; <b>@lang('menu.tax_number')</b></p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="customer_address"> &nbsp;<b>@lang('menu.address')</b> </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="customer_phone"> &nbsp;<b>@lang('menu.phone')</b></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Field For Product</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" checked name="product_w_type"> &nbsp;<b>Product Warranty Type</b></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="product_w_duration"> &nbsp; <b>Product Warranty Duration</b></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="product_discount"> &nbsp; <b>Product Discount</b></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" checked name="product_tax" > &nbsp; <b>@lang('menu.product_tax')</b></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" name="product_imei"><b>&nbsp; Show sale description</b></p>
                                    </div>
                                    <small class="text-muted">(Product IMEI or Serial Number)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>@lang('menu.bank_details')</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>@lang('menu.account_no') :</b></label>
                                        <div class="col-8">
                                            <input type="text" name="account_no" class="form-control" placeholder="Account Number">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>@lang('menu.account_name'):</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="account_name" class="form-control" placeholder="@lang('menu.account_name')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Bank Name :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Bank Branch :</b></label>

                                        <div class="col-8">
                                            <input type="text" name="bank_branch" class="form-control" placeholder="Bank Branch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Footer Text</b></p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Invoice Notice :</b></label>
                                        <div class="col-8">
                                            <textarea name="invoice_notice" class="form-control" cols="10" rows="3" placeholder="Invoice Notice"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Footer Text :</b> </label>
                                        <div class="col-8">
                                            <textarea name="footer_text" class="form-control" cols="10" rows="3" placeholder="Footer text"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-area d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                            <button class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Add Invoice layout by ajax
    $(document).on('submit', '#add_layout_form', function(e) {
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
                 window.location = "{{ route('invoices.layouts.index') }}";
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#is_header_less').on('change', function () {
       if ($(this).is(':CHECKED', true)) {
           $('.hideable_field').show();
       } else{
        $('.hideable_field').hide();
       }
    });
</script>
@endpush
