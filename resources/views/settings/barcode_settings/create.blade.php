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
                    <h5>@lang('menu.add_barcode_sticker_setting')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_barcode_settings_form" action="{{ route('settings.barcode.store') }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"><b>@lang('menu.setting_name') :</b> <span class="text-danger">*
                                        </span></label>
                                        <div class="col-7">
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="Sticker Sheet setting Name" autofocus>
                                                <span class="error error_name"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"><b>@lang('menu.setting_description') :</b> </label>

                                        <div class="col-7">
                                            <textarea class="form-control" name="description" id="" cols="10" rows="3" placeholder="Sticker Sheet setting Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="is_continuous" id="is_continuous">
                                        <b>{{ __('Continous feed or rolls') }}</b>
                                    </p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.top_margin') (Inc) :  <span class="text-danger">*
                                        </b></span></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrow-up input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="top_margin" id="top_margin" placeholder="Additional Top Margin" value="0">
                                            </div>
                                            <span class="error error_top_margin"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.left_margin') (Inc) : <span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrow-left input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="left_margin" id="left_margin" placeholder="Additional Left Margin" value="0">
                                            </div>
                                            <span class="error error_top_margin"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>{{ __('Sticker Width') }} (Inc) : <span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_width" id="sticker_width" placeholder="@lang('menu.sticker_width')">
                                            </div>
                                            <span class="error error_sticker_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.sticker_height') (Inc) :<span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_height" id="sticker_height" placeholder="@lang('menu.sticker_height')">
                                            </div>
                                            <span class="error error_sticker_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.paper_width') (Inc) : <span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_width" id="paper_width" placeholder="@lang('menu.paper_width')">
                                            </div>
                                            <span class="error error_paper_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.paper_height') (Inc) : <span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_height" id="paper_height" placeholder="@lang('menu.paper_height')">
                                            </div>
                                            <span class="error error_paper_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.row_distance') (Inc) :<span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrows-alt-v input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="row_distance" id="row_distance" placeholder="@lang('menu.row_distance')" value="0">
                                            </div>
                                            <span class="error error_row_distance"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.col_distance') (Inc) : <span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrows-alt-h input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="column_distance" id="column_distance" placeholder="Colunmns Distance" value="0">
                                            </div>
                                            <span class="error error_column_distance"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.stickers_in_Row') :<span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_a_row" id="stickers_in_a_row" placeholder="@lang('menu.stickers_in_Row')">
                                            </div>
                                            <span class="error error_stickers_in_a_row"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>@lang('menu.no_of_stickers_per_sheet') : <span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-braille input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_one_sheet" id="stickers_in_one_sheet" placeholder="@lang('menu.no_of_stickers_per_sheet')">
                                            </div>
                                            <span class="error error_stickers_in_one_sheet"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="set_as_default" id="set_as_default">
                                        <b>@lang('menu.set_as_default')</b>
                                    </p>
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
    // Add user by ajax
    $(document).on('submit', '#add_barcode_settings_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        $('.submit_button').prop('type', 'button');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.submit_button').prop('type', 'submit');
                toastr.success(data);
                $('.loading_button').hide();
                window.location = "{{ route('settings.barcode.index') }}";
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
@endpush
