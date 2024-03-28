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
         <div class="sec-name">
             <div class="name-head">
                 <h6>@lang('Add Advertisement')</h6>
             </div>
             <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                 <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
             </a>
         </div>
     </div>
     <div class="p-3 card mb-3">
         <form action="{{route('advertise.update',$data->id)}}" method="post" enctype="multipart/form-data">
             @csrf
             {{method_field('PATCH')}}
             <div class="row">
                 <div class="col-md-4">
                     <div class="form-group">
                         <label>{{ __('Advertisement Title') }} <span class="text-danger">*</span></label>
                         <input type="text" name="title" value="{{$data->title}}" class="form-control" autocomplete="off" placeholder="Advertisement Title" required>
                     </div>
                 </div>

                 <div class="col-md-4">
                     <div class="form-group">
                         <label>{{ __('Select Type') }} <span class="text-danger">*</span></label>
                         <select class="form-control" name="content_type" id="select_type" disabled required>
                             <option value="">Select</option>
                             <option value="1" @if($data->content_type==1) selected @endif>Image</option>
                             <option value="2" @if($data->content_type==2) selected @endif>Video</option>
                         </select>
                     </div>
                 </div>

                 <!-- <div class="col-md-3">
                    <div class="form-group">
                        <label>{{ __('Upload Logo') }}</label>
                         <input type="file" name="logo" class="form-control dropify" id="photo" accept="" data-allowed-file-extensions="png jpeg jpg gif avif webp">
                    </div>
                </div> -->

                 <div class="col-md-4">
                     <div class="form-group">
                         <label>{{ __('Select Status') }}</label>
                         <select class="form-control" name="status" id="select_type" required>
                             <option value="1" @if($data->status==1) selected @endif>Active</option>
                             <option value="0" @if($data->status==0) selected @endif>Inactive</option>
                         </select>
                     </div>
                 </div>

                 <!-- image div start -->
                 <div id="img_attach" @if($data->content_type != 1) style="display: none;" @endif>
                     @if($data->content_type==1)
                     <div class="row">
                         <div class="col-md-2">
                             <button type="button" id="addImage" class="btn btn-primary mt-2 btn-sm">{{ __('Add More') }}</button>
                         </div>
                     </div>
                     @foreach($data->attachments as $item)
                     <div class="row">
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label>{{ __('Images') }}</label><br>
                                 <img class="mt-3 mb-3" width="100" height="100" src="{{asset('uploads/advertisement/'.tenant('id').'/file'.'/'.$item->image)}}">
                                 <input type="hidden" name="image[]" value="{{$item->image}}">
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label>{{ __('Slider Title') }}</label>
                                 <input type="text" name="content_title[]" value="{{$item->content_title}}" class="form-control mt-2" placeholder="Enter Slider Title">
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                 <label>{{ __('Slider Caption') }}</label>
                                 <input type="text" name="caption[]" value="{{$item->caption}}" class="form-control mt-2" placeholder="Enter Slider Caption">
                             </div>
                         </div>
                     </div>
                     @endforeach
                     @endif
                 </div>

                 <br /> <br /> <br />
                 <div id="imageUploads"></div>
                 <!-- image div end -->


                 <!-- url div start -->
                 <div id="video_attach" @if($data->content_type != 2) style="display: none;" @endif>
                     @if($data->content_type==2)
                     <div id="urlForm" style="margin-top:3px;">
                         <div class="row">
                             <div class="col-md-6">
                                 <div id="urlUploads">
                                     <div class="form-group">
                                         <label>{{ __('Video') }}</label>
                                         <br>
                                         <input type="file" name="video" class="form-control dropify" id="photo" accept="" data-default-file="{{$data->attachments[0]->video}}">
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     @endif
                 </div>
                 <!-- url div end -->



                 <div class="form-group mt-3">
                     <button type="submit" style="margin:-1px 10px" class="btn btn-sm btn-success float-end mr-2">Save</button>
                     <button style="margin:-1px 10px" type="reset" id="add" class="btn btn-sm  btn-danger float-end">Reset</button>
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