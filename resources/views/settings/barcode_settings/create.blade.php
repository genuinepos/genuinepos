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
                    <h5>Add barcode sticker setting</h5>
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
                                        <label class="col-5"><b>Setting Name :</b> <span class="text-danger">*
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
                                        <label class="col-5"><b>Setting Description :</b> </label>

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
                                        <b>Continous feed or rolls</b>
                                    </p>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Top Margin (Inc) :  <span class="text-danger">*
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
                                        <label class="col-5"> <b>Left Margin (Inc) : <span class="text-danger">*
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
                                        <label class="col-5"> <b>Sticker Width (Inc) : <span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_width" id="sticker_width" placeholder="Sticker Width">
                                            </div>
                                            <span class="error error_sticker_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Sticker Height (Inc) :<span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_height" id="sticker_height" placeholder="Sticker Height">
                                            </div>
                                            <span class="error error_sticker_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Paper Width (Inc) : <span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_width" id="paper_width" placeholder="Paper Width">
                                            </div>
                                            <span class="error error_paper_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Paper Height (Inc) : <span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_height" id="paper_height" placeholder="Paper Height">
                                            </div>
                                            <span class="error error_paper_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Row Distance (Inc) :<span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrows-alt-v input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="row_distance" id="row_distance" placeholder="Row Distance" value="0">
                                            </div>
                                            <span class="error error_row_distance"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>Col Distance (Inc) : <span class="text-danger">*
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
                                        <label class="col-5"> <b>Stickers In a Row :<span class="text-danger">*
                                        </span> </b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_a_row" id="stickers_in_a_row" placeholder="Stickers In a Row">
                                            </div>
                                            <span class="error error_stickers_in_a_row"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-5"> <b>No. of Stickers per sheet : <span class="text-danger">*
                                        </span></b></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-braille input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_one_sheet" id="stickers_in_one_sheet" placeholder="No. of Stickers per sheet">
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
                                        <b>Set As Default</b>
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
