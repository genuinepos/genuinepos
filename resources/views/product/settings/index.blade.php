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
                    <h6>Product Settings</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <div class="p-3">
            <div class="card p-3">

                <form id="product_settings_form" class="setting_form" action="{{ route('products.settings.store') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label><strong>Product Code Prefix (SKU) :</strong></label>
                            <input type="text" name="product_code_prefix" class="form-control"
                                autocomplete="off" value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}">
                        </div>

                        <div class="col-md-3">
                            <label><strong>Default Unit :</strong></label>
                            <select name="default_unit_id" class="form-control" id="default_unit_id">
                                <option value="null">None</option>
                                @foreach ($units as $unit)
                                    <option {{ json_decode($generalSettings->product, true)['default_unit_id'] == $unit->id ? 'SELECTED' : '' }}
                                        value="{{ $unit->id }}">{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ json_decode($generalSettings->product, true)['is_enable_brands'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_brands"> &nbsp; <b>Enable Brands</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ json_decode($generalSettings->product, true)['is_enable_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_categories"> &nbsp; <b>Enable Categories</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_sub_categories"> &nbsp; <b>Enable Sub-Categories</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_price_tax"> &nbsp; <b>Enable Price & Tax info</b>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row">
                                <p class="checkbox_input_wrap mt-3">
                                    <input type="checkbox"
                                        {{ json_decode($generalSettings->product, true)['is_enable_warranty'] == '1' ? 'CHECKED' : '' }}
                                        name="is_enable_warranty"> &nbsp; <b>Enable Warranty</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button class="btn btn-sm btn-success submit_button float-end">Save Change</button>
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
