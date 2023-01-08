@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Product Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-sliders-h"></span>
                    <h6>@lang('menu.product_settings')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-lg-3 p-1">
            <div class="card p-3">

                <form id="product_settings_form" class="setting_form" action="{{ route('products.settings.store') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3 col-sm-6">
                            <label><strong>{{ __('Product Code Prefix') }} (SKU) :</strong></label>
                            <input type="text" name="product_code_prefix" class="form-control"
                                autocomplete="off" value="{{ $generalSettings['product__product_code_prefix'] }}">
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <label><strong>@lang('menu.default_unit') :</strong></label>
                            <select name="default_unit_id" class="form-control" id="default_unit_id">
                                <option value="null">@lang('menu.none')</option>
                                @foreach ($units as $unit)
                                    <option {{ $generalSettings['product__default_unit_id'] == $unit->id ? 'SELECTED' : '' }}
                                        value="{{ $unit->id }}">{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3 col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['product__is_enable_brands'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_brands"> &nbsp; <b>@lang('menu.enable_brands')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['product__is_enable_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_categories"> &nbsp; <b>@lang('menu.enable_categories')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['product__is_enable_sub_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_sub_categories"> &nbsp; <b>@lang('menu.enable_sub_categories')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['product__is_enable_price_tax'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_price_tax"> &nbsp; <b>@lang('menu.enable_price_tax_info')</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ $generalSettings['product__is_enable_warranty'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_warranty"> &nbsp; <b>@lang('menu.enable_warranty')</b>
                                </p>
                            </div>
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
@endsection
@push('scripts')
    <script>
         $('#product_settings_form').on('submit', function(e) {
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
