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
@section('title', 'Product Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.product_settings')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-1">
            <div class="card p-3">
                <form id="product_settings_form" class="setting_form" action="{{ route('product.settings.update') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3 col-sm-6">
                            <label><strong>{{ __('Product Code Prefix(SKU)') }} </strong></label>
                            <input type="text" name="product_code_prefix" class="form-control" id="product_code_prefix" data-next="default_unit_id" value="{{ $generalSettings['product__product_code_prefix'] }}" autocomplete="off">
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <label><strong>{{ __("Default Unit") }}</strong></label>
                            <select name="default_unit_id" class="form-control" id="default_unit_id" data-next="is_enable_brands">
                                <option value="null">@lang('menu.none')</option>
                                @foreach ($units as $unit)
                                    <option {{ $generalSettings['product__default_unit_id'] == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-lg-3 col-md-4">
                            <label><strong>{{ __("Enable Brands") }}</strong></label>
                            <select name="is_enable_brands" class="form-control" id="is_enable_brands" data-next="is_enable_categories">
                                <option value="1">{{ __("Yes") }}</option>
                                <option {{ $generalSettings['product__is_enable_brands'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label><strong>{{ __("Enable Categories") }}</strong></label>
                            <select name="is_enable_categories" class="form-control" id="is_enable_categories" data-next="is_enable_sub_categories">
                                <option value="1">{{ __("Yes") }}</option>
                                <option {{ $generalSettings['product__is_enable_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label><strong>{{ __("Enable Subcategories") }}</strong></label>
                            <select name="is_enable_sub_categories" class="form-control" id="is_enable_sub_categories" data-next="is_enable_price_tax">
                                <option value="1">{{ __("Yes") }}</option>
                                <option {{ $generalSettings['product__is_enable_sub_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label><strong>{{ __("Enable Price Vat/Tax") }}</strong></label>
                            <select name="is_enable_price_tax" class="form-control" id="is_enable_price_tax" data-next="is_enable_warranty">
                                <option value="1">{{ __("Yes") }}</option>
                                <option {{ $generalSettings['product__is_enable_price_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-lg-3 col-md-4">
                            <label><strong>{{ __("Enable Product Warranty") }}</strong></label>
                            <select name="is_enable_warranty" class="form-control" id="is_enable_warranty" data-next="save_changes">
                                <option value="1">{{ __("Yes") }}</option>
                                <option {{ $generalSettings['product__is_enable_warranty'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button id="save_changes" class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
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
                    $('#product_code_prefix').focus().select();
                }
            });
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

                $('#' + nextId).focus().select();
            }
        });

        $('#product_code_prefix').focus().select();
    </script>
@endpush
