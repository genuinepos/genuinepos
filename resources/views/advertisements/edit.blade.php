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
 @section('title', 'Edit Advertisement - ')
 @section('content')
     <div class="body-woaper">
         <div class="main__content">
             <div class="sec-name">
                 <div class="name-head">
                     <h6>@lang('Edit Advertisement')</h6>
                 </div>
                 <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                     <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                 </a>
             </div>
         </div>
         <div class="p-3 card mb-3">
             <form id="edit_advertisement_form" action="{{ route('advertisements.update', $advertisement->id) }}" method="post" enctype="multipart/form-data">
                 @csrf
                 <input type="hidden" name="content_type" value="{{ $advertisement->content_type }}">
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>{{ __('Advertisement Title') }} <span class="text-danger">*</span></label>
                             <input type="text" name="title" value="{{ $advertisement->title }}" class="form-control" autocomplete="off" placeholder="Advertisement Title" required>
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="form-group">
                             <label>{{ __('Select Status') }}</label>
                             <select class="form-control" name="status" required>
                                 <option value="1" @if ($advertisement->status == 1) selected @endif>{{ __('Active') }}</option>
                                 <option value="0" @if ($advertisement->status == 0) selected @endif>{{ __('Inactive') }}</option>
                             </select>
                         </div>
                     </div>

                     @if ($advertisement->content_type == 1)
                         <div class="row">
                             <div class="col-md-12">
                                 <button type="button" id="addImage" class="btn btn-primary mt-2 btn-sm float-end">{{ __('Add More') }}</button>
                             </div>

                             <div class="col-md-12">
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
                                         @foreach ($advertisement->attachments as $attachment)
                                             <tr>
                                                 <td>
                                                     <input type="hidden" name="attachment_ids[]" value="{{ $attachment->id }}">
                                                     <input type="file" name="images[]" class="form-control dropify" id="photo" data-default-file="{{ file_link('advertisementAttachment', $attachment->image) }}" data-allowed-file-extensions="png jpeg jpg gif avif webp">
                                                 </td>

                                                 <td>
                                                     <input type="text" name="content_titles[]" value="{{ $attachment->content_title }}" class="form-control" placeholder="{{ __('Enter Slider Title') }}">
                                                 </td>

                                                 <td>
                                                     <input type="text" name="captions[]" value="{{ $attachment->caption }}" class="form-control" placeholder="{{ __('Enter Slider Caption') }}">
                                                 </td>

                                                 <td>
                                                     <button type="button" class="btn btn-danger btn-sm" id="remove_image"><i class="fa fa-trash"></i></button>
                                                 </td>
                                             </tr>
                                         @endforeach
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     @else
                         <div class="row">
                             <div class="col-md-12">
                                 <table class="table">
                                     <thead>
                                         <tr>
                                             <th scope="col">{{ __('Video') }}</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <tr>
                                             <td>
                                                 <video style="width: 200px;height:150px;" controls autoplay>
                                                     <source src="{{ file_link('advertisementAttachment', $advertisement->attachments[0]->video) }}" type="video/mp4">
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
                                                     <label>{{ __('Upload Video') }} (<small class="text-danger">{{ __('Previous One Will Be Replesed') }}</small>)</label>
                                                     <input type="hidden" name="video_attachment_id" value="{{ $advertisement->attachments[0]->id }}">
                                                     <input type="file" name="video" class="form-control dropify" id="video">
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     @endif

                     <div class="row mt-2 mb-1">
                         <div class="col-md-12 d-flex justify-content-end">
                             <div class="btn-loading">
                                 <button type="button" class="btn loading_button advertisement_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                 <button type="submit" id="advertisement_save_changes" class="btn btn-success advertisement_submit_button">{{ __('Save Changes') }}</button>
                             </div>
                         </div>
                     </div>
                 </div>
             </form>
         </div>
     </div>
 @endsection

 @push('scripts')
     @include('advertisements.js_partials.edit_js')
 @endpush
