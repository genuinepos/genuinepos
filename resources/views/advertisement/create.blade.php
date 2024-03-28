@extends('layout.master')
@push('stylesheets')
    <style>
        .dropify-wrapper { height: 100px!important;}
        .base_unit_name {font-size: 10px;}
    </style>
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Advertisement - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Advertisement')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
            </a>
        </div>
    </div>
    <div class="p-3 card mb-3">
       <form id="add_data" method="post" enctype="multipart/form-data">
                @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Advertisement Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" autocomplete="off" placeholder="Advertisement Title">
                    </div>
                </div>

               <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Select Type') }} <span class="text-danger">*</span></label>
                        <select class="form-control" name="content_type" id="select_type">
                            <option value="">Select</option>
                            <option value="1">Image</option>
                            <option value="2">Video</option>
                        </select>
                    </div>
                </div>

                 <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Select Status') }}</label>
                        <select class="form-control" name="status" id="select_type" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- image div start -->
                    <div id="titleForm" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Images') }}</label>
                            <input type="file" name="image[]" class="form-control dropify" id="photo" accept="" data-allowed-file-extensions="png jpeg jpg gif avif webp">
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Slider Title') }}</label>
                            <input type="text" name="content_title[]" class="form-control mt-2" placeholder="Enter Slider Title">
                        </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label>{{ __('Slider Caption') }}</label>
                            <input type="text" name="caption[]" class="form-control mt-2" placeholder="Enter Slider Caption">
                        </div>
                        </div>
                    </div>
                    <button type="button" id="addImage" class="btn btn-primary mt-2 btn-sm">{{ __('Add More') }}</button>
                </div>
                <br/>  <br/>  <br/>
                <div id="imageUploads"></div>
               <!-- image div end -->


              <!-- url div start -->
                <div id="urlForm" style="display: none; margin-top:3px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="urlUploads">
                                <div class="form-group">
                                    <label>{{ __('Video') }}</label>
                                    <input type="file" name="video" class="form-control dropify" id="photo">
                                </div>  
                            </div>
                        </div>
                    </div>
                    <!-- <button type="button" id="addUrl" class="btn btn-primary mt-2 btn-sm">{{ __('Add More') }}</button> -->
                </div>
                <!-- url div end -->


               
                <div class="form-group mt-3">
                    <button type="submit" style="margin:-1px 10px"  class="btn btn-sm btn-success float-end mr-2">Save</button>
                    <button style="margin:-1px 10px" type="reset" id="add" class="btn btn-sm  btn-danger float-end">Reset</button>
                </div>
                <br>
          </div>
         </form>
    </div>
@endsection

@push('scripts')
   <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
   @include('advertisement.ajax_view.list_js')
@endpush
