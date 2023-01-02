@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Pos Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cog"></span>
                    <h6>@lang('menu.pos_settings')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <div class="card">

                <form id="pos_settings_form" class="setting_form p-3"
                action="{{ route('sales.pos.settings.store') }}" method="post">
                    @csrf

                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="row ">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_multiple_pay'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enabled_multiple_pay"> &nbsp; <b>@lang('menu.enable_multiple_pay')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_draft'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enabled_draft"> &nbsp; <b>@lang('menu.enable_draft')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox" {{ $generalSettings['pos']['is_enabled_quotation'] == '1' ? 'CHECKED' : '' }} name="is_enabled_quotation"> &nbsp; <b>@lang('menu.enable_quotation')</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="row ">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_suspend'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enabled_suspend"> &nbsp; <b>@lang('menu.enable_suspend')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox" {{ $generalSettings['pos']['is_enabled_discount'] == '1' ? 'CHECKED' : '' }} name="is_enabled_discount"> &nbsp; <b>@lang('menu.enable_order_discount')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_order_tax'] == '1' ? 'CHECKED' : '' }} name="is_enabled_order_tax"> &nbsp; <b>@lang('menu.enable_order_tax')</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="row ">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_show_recent_transactions'] == '1' ? 'CHECKED' : '' }} name="is_show_recent_transactions" autocomplete="off"> &nbsp; <b>@lang('menu.show_recent_transactions')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_credit_full_sale'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enabled_credit_full_sale"> &nbsp; <b>@lang('menu.enable_full_credit_sale') </b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['pos']['is_enabled_hold_invoice'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enabled_hold_invoice"> &nbsp; <b>@lang('menu.enable_hold_invoice')</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn loading_button d-hide"><i
                                class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')...</b></button>
                            <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#pos_settings_form').on('submit', function(e) {
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
                }
            });
        });
    </script>
@endpush
