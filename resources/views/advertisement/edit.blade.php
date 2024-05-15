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
 @section('title', 'Update Advertisement - ')
 @section('content')
     <div class="body-woaper">
         <div class="main__content">
             <div class="sec-name">
                 <div class="name-head">
                     <h6>@lang('Update Advertisement')</h6>
                 </div>
                 <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                     <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                 </a>
             </div>
         </div>
         <div class="p-3 card mb-3">
             <form id="updateForm" action="{{ route('advertise.update', $data->id) }}" method="post" enctype="multipart/form-data">
                 @csrf
                 {{ method_field('PUT') }}
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>{{ __('Advertisement Title') }} <span class="text-danger">*</span></label>
                             <input type="text" name="title" value="{{ $data->title }}" class="form-control" autocomplete="off" placeholder="Advertisement Title" required>
                         </div>
                     </div>

                     <!-- <div class="col-md-4">
                                     <div class="form-group">
                                         <label>{{ __('Select Type') }} <span class="text-danger">*</span></label>
                                         <select class="form-control" name="content_type" id="select_type" required>
                                             <option value="">Select</option>
                                             <option value="1" @if ($data->content_type == 1) selected @endif>Image</option>
                                             <option value="2" @if ($data->content_type == 2) selected @endif>Video</option>
                                         </select>
                                     </div>
                                 </div> -->

                     <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('Upload Logo') }}</label>
                                         <input type="file" name="logo" class="form-control dropify" id="photo" accept="" data-allowed-file-extensions="png jpeg jpg gif avif webp">
                                    </div>
                                </div> -->

                     <div class="col-md-6">
                         <div class="form-group">
                             <label>{{ __('Select Status') }}</label>
                             <select class="form-control" name="status" required>
                                 <option value="1" @if ($data->status == 1) selected @endif>Active</option>
                                 <option value="0" @if ($data->status == 0) selected @endif>Inactive</option>
                             </select>
                         </div>
                     </div>

                     <!-- image div start -->
                     <div id="img_attach" @if ($data->content_type != 1) style="display: none;" @endif>
                         @if ($data->content_type == 1)

                             <div class="row">
                                 <div class="col-md-2">
                                     <button type="button" id="addImage" class="btn btn-primary mt-2 btn-sm">{{ __('Add More') }}</button>
                                 </div>
                             </div>
                             <br />
                             <div id="imageUploads"></div>
                             <table class="table">
                                 <thead>
                                     <tr>
                                         <th scope="col">{{ __('Images') }}</th>
                                         <th scope="col">{{ __('Title') }}</th>
                                         <th scope="col">{{ __('Caption') }}</th>
                                         <th scope="col">{{ __('Action') }}</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($data->attachments as $item)
                                         <tr>
                                             <input type="hidden" name="attach_id[]" value="{{ $item->id }}">
                                             <td>
                                                 <img class="mt-3 mb-3" width="100" height="100" src="{{ asset('uploads/advertisement/' . $item->image) }}">
                                             </td>
                                             <td>
                                                 <input type="text" name="default_content_title[]" value="{{ $item->content_title }}" class="form-control mt-2" placeholder="{{ __('Enter Slider Title') }}">
                                             </td>
                                             <td>
                                                 <input type="text" name="default_caption[]" value="{{ $item->caption }}" class="form-control mt-2" placeholder="Enter Slider Caption">
                                             </td>
                                             <td>
                                                 <button type="button" class="btn btn-danger btn-sm delete-item" data-id="{{ $item->id }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         @endif
                     </div>
                     <!-- image div end -->

                     <!-- url div start -->
                     <div id="video_attach" @if ($data->content_type != 2) style="display: none;" @endif>
                         @if ($data->content_type == 2)
                             <input type="hidden" name="video_id" value="{{ $data->attachments[0]->id }}">
                             <table class="table">
                                 <thead>
                                     <tr>
                                         <th scope="col">Video</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td>
                                             <video style="width: 200px;height:150px;" controls autoplay>
                                                 <source src="{{ asset('uploads/advertisement/' . $data->attachments[0]->video) }}" type="video/mp4">
                                                 {{ __('Your browser does not support the video tag.') }}
                                             </video>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>

                             <div id="urlForm" style="margin-top:3px;">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div id="urlUploads">
                                             <div class="form-group">
                                                 <label>{{ __('Video') }}</label>
                                                 <br>
                                                 <input type="file" name="video" class="form-control dropify" id="photo" accept="">
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endif
                     </div>
                     <!-- url div end -->

                     <div class="form-group mt-3">
                         <button type="submit" style="margin:-1px 10px" class="btn btn-sm btn-success float-end mr-2">{{ __('Save') }}</button>
                         <button style="margin:-1px 10px" type="reset" id="add" class="btn btn-sm  btn-danger float-end">{{ __('Reset') }}</button>
                     </div>
                     <br>
                 </div>
             </form>
         </div>
     @endsection

     @push('scripts')
         <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
         @include('advertisement.ajax_view.update_js')
     @endpush
