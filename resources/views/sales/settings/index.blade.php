@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Sale Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-sliders-h"></span>
                    <h6>@lang('menu.add_sale_settings')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <form id="sale_settings_form" class="setting_form p-3" action="{{ route('sales.add.sale.settings.store') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.default_sale_discount') :</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_f"></i></span>
                                        </div>
                                        <input type="text" name="default_sale_discount" class="form-control"
                                            autocomplete="off" value="{{ $generalSettings['sale']['default_sale_discount'] }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.default_sale_tax') :</strong></label>
                                    <select name="default_tax_id" class="form-control">
                                        <option value="null">@lang('menu.none')</option>
                                        @foreach ($taxes as $tax)
                                            <option
                                                {{ $generalSettings['sale']['default_tax_id'] == $tax->tax_percent ? 'SELECTED' : '' }}
                                                value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.sales_commission_agent')</strong></label>
                                    <select class="form-control" name="sales_cmsn_agnt">
                                        <option {{ $generalSettings['sale']['sales_cmsn_agnt'] == 'disable' ? 'SELECTED' : '' }}
                                            value="disable">{{ __('Disable') }}
                                        </option>

                                        <option {{ $generalSettings['sale']['sales_cmsn_agnt'] == 'logged_in_user' ? 'SELECTED' : '' }}
                                            value="logged_in_user">@lang('menu.logged_in_user')
                                        </option>

                                        <option {{ $generalSettings['sale']['sales_cmsn_agnt'] == 'user' ? 'SELECTED' : '' }}
                                            value="user">@lang('menu.select_from_user')&#039; {{ __('list') }}
                                        </option>

                                        <option {{ $generalSettings['sale']['sales_cmsn_agnt'] == 'select_form_cmsn_list' ? 'SELECTED' : '' }}
                                            value="select_form_cmsn_list">@lang('menu.select_from_commission_agent')&#039; {{ __('list') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-4">
                                    <label><strong>{{ __('Default Selling Price Group') }} :</strong></label>
                                    <select name="default_price_group_id" class="form-control">
                                        <option value="null">@lang('menu.none')</option>
                                        @foreach ($price_groups as $pg)
                                            <option {{ $generalSettings['sale']['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#sale_settings_form').on('submit', function(e) {
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
