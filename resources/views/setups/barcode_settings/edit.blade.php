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
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Barcode Sticker Settings') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-3">
            <form id="edit_barcode_settings_form" action="{{ route('barcode.settings.update', $bs->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Setting Name') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Sticker Sheet setting Name') }}" autofocus value="{{ $bs->name }}">
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Setting Description') }}</b></label>

                                        <div class="col-8">
                                            <textarea class="form-control" name="description" id="" cols="10" rows="3" placeholder="Sticker Sheet setting Description">{{ $bs->description }}</textarea>
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
                                        <input type="checkbox" {{ $bs->is_continuous == 1 ? 'CHECKED' : '' }} name="is_continuous" id="is_continuous">
                                        <b>{{ __('Continuos feed or rolls') }}</b>
                                    </p>
                                </div>
                            </div>


                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Top Margin (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrow-up input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="top_margin" id="top_margin" placeholder="Additional Top Margin" value="{{ $bs->top_margin }}">
                                            </div>
                                            <span class="error error_top_margin"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Left Margin (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrow-left input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="left_margin" id="left_margin" placeholder="{{ __('Additional Left Margin') }}" value="{{ $bs->left_margin }}">
                                            </div>
                                            <span class="error error_top_margin"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Sticker Width (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_width" id="sticker_width" placeholder="{{ __('Sticker Width (Inc)') }}" value="{{ $bs->sticker_width }}">
                                            </div>
                                            <span class="error error_sticker_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Sticker Height (Inc)') }}<span class="text-danger">*
                                                </span></b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="sticker_height" id="sticker_height" placeholder="{{ __('Sticker Height') }}" value="{{ $bs->sticker_height }}">
                                            </div>
                                            <span class="error error_sticker_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Paper Width (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_width" id="paper_width" placeholder="{{ __('Paper Width (Inc)') }}" value="{{ $bs->paper_width }}">
                                            </div>
                                            <span class="error error_paper_width"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Paper Height (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-height input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="paper_height" id="paper_height" placeholder="{{ __('Paper Height (Inc)') }}" value="{{ $bs->paper_height }}">
                                            </div>
                                            <span class="error error_paper_height"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Row Distance (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrows-alt-v input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="row_distance" id="row_distance" placeholder="{{ __('Row Distance (Inc)') }}" value="{{ $bs->row_distance }}">
                                            </div>
                                            <span class="error error_row_distance"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Col Distance (Inc)') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-arrows-alt-h input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="column_distance" id="column_distance" placeholder="{{ __('Colunmns Distance') }}" value="{{ $bs->column_distance }}">
                                            </div>
                                            <span class="error error_column_distance"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Stickers In A Row') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_a_row" id="stickers_in_a_row" placeholder="@lang('menu.stickers_in_Row')" value="{{ $bs->stickers_in_a_row }}">
                                            </div>
                                            <span class="error error_stickers_in_a_row"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('No Of Stickers Per Sheet') }}</b> <span class="text-danger">*
                                            </span></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-braille input_i"></i></span>
                                                </div>
                                                <input type="number" step="any" class="form-control" name="stickers_in_one_sheet" id="stickers_in_one_sheet" placeholder="{{ __('No Of Stickers Per Sheet') }}" value="{{ $bs->stickers_in_one_sheet }}">
                                            </div>
                                            <span class="error error_stickers_in_one_sheet"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="set_as_default" id="set_as_default" @if ($bs->is_default == 1) checked @endif>
                                            <b>{{ __('Set As Default') }}</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-area d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                            <button class="btn btn-sm btn-success submit_button">{{ __('Save Changes') }}</button>
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
        $(document).on('submit', '#edit_barcode_settings_form', function(e) {
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
                    window.location = "{{ route('barcode.settings.index') }}";
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
