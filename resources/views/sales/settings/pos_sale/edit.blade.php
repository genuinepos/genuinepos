@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
@endpush
@section('title', 'Pos Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Pos Sale Settings') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <form id="pos_settings_form" class="setting_form p-3" action="{{ route('pos.sales.settings.update') }}" method="post">
                    @csrf

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Multiple Payment') }} </strong></label>
                            <select class="form-control" name="is_enabled_multiple_pay" id="is_enabled_multiple_pay" data-next="is_enabled_draft" autofocus>
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_multiple_pay'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Draft') }} </strong></label>
                            <select class="form-control" name="is_enabled_draft" id="is_enabled_draft" data-next="is_enabled_quotation">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_draft'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Quotation') }} </strong></label>
                            <select class="form-control" name="is_enabled_quotation" id="is_enabled_quotation" data-next="is_enabled_suspend">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_quotation'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Suspend') }} </strong></label>
                            <select class="form-control" name="is_enabled_suspend" id="is_enabled_suspend" data-next="is_enabled_discount">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_suspend'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Discount') }} </strong></label>
                            <select class="form-control" name="is_enabled_discount" id="is_enabled_discount" data-next="is_enabled_order_tax">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_discount'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Sale Tax') }} </strong></label>
                            <select class="form-control" name="is_enabled_order_tax" id="is_enabled_order_tax" data-next="is_show_recent_transactions">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_order_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Show Recent Transactions') }} </strong></label>
                            <select class="form-control" name="is_show_recent_transactions" id="is_show_recent_transactions" data-next="is_enabled_credit_full_sale">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_show_recent_transactions'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Full Credit Sale') }} </strong></label>
                            <select class="form-control" name="is_enabled_credit_full_sale" id="is_enabled_credit_full_sale" data-next="is_enabled_hold_invoice">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_credit_full_sale'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong> {{ __('Enable Hold Invoice') }} </strong></label>
                            <select class="form-control" name="is_enabled_hold_invoice" id="is_enabled_hold_invoice" data-next="save_changes">
                                <option value="1">{{ __('Yes') }}</option>
                                <option {{ $generalSettings['pos__is_enabled_hold_invoice'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button pos_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                <button type="button" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        $('#pos_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.pos_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.pos_settings_loading_btn').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data);
                },
                error: function(err) {

                    $('.pos_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });
    </script>
@endpush
