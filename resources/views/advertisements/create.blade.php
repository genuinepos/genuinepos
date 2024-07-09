@extends('layout.master')
@push('stylesheets')
    <style>
        .dropify-wrapper {
            height: 100px !important;
        }

        .base_unit_name {
            font-size: 10px;
        }
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Add Advertisement - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-0">
                <div class="col-md-6">
                    <div class="name-head">
                        <h6>{{ __('Add Advertisement') }}</h6>
                    </div>
                </div>

                <div class="col-md-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>

        <div class="p-1">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body p-1">
                    <form id="add_advertisement_form" action="{{ route('advertisements.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Advertisement Title') }} <span class="text-danger">*</span></label>
                                    <input required type="text" name="title" class="form-control" autocomplete="off" placeholder="{{ __('Advertisement Title') }}">
                                    <span class="error error_title"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>{{ __('Content Type') }} <span class="text-danger">*</span></label>
                                <select required class="form-control" name="content_type" id="content_type">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="1">{{ __('Image') }}</option>
                                    <option value="2">{{ __('Video') }}</option>
                                </select>
                                <span class="error error_content_type"></span>
                            </div>

                            <div class="col-md-4">
                                <label>{{ __('Status') }}</label>
                                <select required class="form-control" name="status" id="status" required>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                                <span class="error error_status"></span>
                            </div>
                        </div>

                        <div id="image_upload_area" class="d-hide">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="addImage" class="btn btn-primary mt-2 btn-sm float-end">{{ __('Add More') }}</button>
                                </div>
                            </div>

                            <table id="image-table" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Images') }}</th>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Caption') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="advertisement_images">
                                    <tr>
                                        <td>
                                            <input type="file" name="images[]" class="form-control dropify" id="image" data-allowed-file-extensions="png jpeg jpg gif avif webp">
                                        </td>

                                        <td>
                                            <input type="text" name="content_titles[]" class="form-control" placeholder="{{ __('Enter Slider Title') }}">
                                        </td>

                                        <td>
                                            <input type="text" name="captions[]" class="form-control" placeholder="{{ __('Enter Slider Caption') }}">
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" id="remove_image"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="video_upload_area" class="d-hide">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="urlUploads">
                                        <div class="form-group">
                                            <label>{{ __('Video') }}</label>
                                            <input type="file" data-max-file-size="100M" name="video" class="form-control dropify" id="video">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 mb-1">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button advertisement_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                    <button type="submit" id="advertisement_save" class="btn btn-success advertisement_submit_button px-5">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('advertisements.js_partials.create_js')
@endpush
